<?php

namespace App\Livewire;

use App\Core\ModuleEngine\ModuleEngine;
use Illuminate\Support\Facades\File;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;

#[Layout('layouts.admin')]
class ModulesManager extends Component
{
    use WithFileUploads;

    public string $tab = 'installed';

    public array $modules = [];

    public array $registry = [];

    public array $registryRaw = [];

    public array $updates = [];

    public ?array $selectedModule = null;

    public string $search = '';

    public string $registrySearch = '';

    public $upload;

    public string $installUrl = '';

    public bool $installing = false;

    public string $installStatus = '';

    protected function rules(): array
    {
        return [
            'upload' => 'nullable|file|mimes:zip|max:102400',
            'installUrl' => 'nullable|url|max:500',
        ];
    }

    public function mount(ModuleEngine $engine): void
    {
        $engine->discover();
        $this->loadModules($engine);
        $this->loadRegistry();
        $this->checkForUpdates();
    }

    protected function loadModules(ModuleEngine $engine): void
    {
        $all = $engine->all();
        foreach ($all as $name => &$mod) {
            $mod['active'] = $engine->isActive($name);
            $mod['name_key'] = $name;
            $mod['has_settings'] = $engine->find($name) && File::exists(($mod['path'] ?? '') . '/src/Livewire/Settings.php');
        }
        $this->modules = $all;
    }

    protected function loadRegistry(): void
    {
        $engine = app(ModuleEngine::class);
        $packages = $engine->fetchRegistry();
        $this->registryRaw = $packages ?? [];

        $this->registry = [];
        if ($packages) {
            foreach ($packages as $key => $pkg) {
                $latestVer = $pkg['latest_version'] ?? '0.1.0';
                $latest = $pkg['versions'][$latestVer] ?? [];
                $this->registry[] = [
                    'name' => $pkg['name'] ?? $key,
                    'vendor' => $pkg['vendor'] ?? 'inox',
                    'version' => $latestVer,
                    'latest_version' => $latestVer,
                    'description' => $pkg['description'] ?? '',
                    'download_url' => $latest['download_url'] ?? '',
                    'downloads' => $pkg['downloads'] ?? 0,
                    'rating' => $pkg['rating'] ?? 0,
                    'requirements' => $latest['requires'] ?? [],
                    'versions' => $pkg['versions'] ?? [],
                ];
            }
        }
    }

    public function checkForUpdates(): void
    {
        $engine = app(ModuleEngine::class);
        $this->updates = $engine->checkUpdates($this->registryRaw);
    }

    public function refresh(): void
    {
        $engine = app(ModuleEngine::class);
        $engine->discover();
        $this->loadModules($engine);
        $this->loadRegistry();
    }

    public function showDetails(string $name): void
    {
        $this->selectedModule = $this->modules[$name] ?? null;
    }

    public function closeDetails(): void
    {
        $this->selectedModule = null;
    }

    public function toggle(string $name): void
    {
        $engine = app(ModuleEngine::class);
        $engine->discover();

        if ($engine->isActive($name)) {
            $engine->deactivate($name);
            $this->removeFromActiveConfig($name);
            session()->flash('message', "Module '$name' deactivated.");
        } else {
            $engine->activate($name);
            $this->addToActiveConfig($name);
            session()->flash('message', "Module '$name' activated.");
        }

        $engine->persistActiveConfig();
        $engine->discover();
        $this->loadModules($engine);
    }

    public function activateAll(): void
    {
        $engine = app(ModuleEngine::class);
        $engine->discover();
        $engine->activateAll();
        $engine->persistActiveConfig();
        $engine->discover();
        $this->loadModules($engine);
        session()->flash('message', 'All modules activated.');
    }

    public function deactivateAll(): void
    {
        $engine = app(ModuleEngine::class);
        $engine->discover();
        $engine->deactivateAll();
        $engine->persistActiveConfig();
        $engine->discover();
        $this->loadModules($engine);
        session()->flash('message', 'All modules deactivated.');
    }

    public function installFromUrl(): void
    {
        $this->validate(['installUrl' => 'required|url|max:500']);
        $this->installModule(null, $this->installUrl);
    }

    public function installFromUpload(): void
    {
        $this->validate(['upload' => 'required|file|mimes:zip|max:102400']);
        $this->installModule($this->upload, null);
    }

    public function installFromRegistry(string $moduleName): void
    {
        $entry = collect($this->registry)->firstWhere('name', $moduleName);
        $url = $entry['download_url'] ?? '';
        if (!$url) {
            session()->flash('error', "No download URL available for '$moduleName'.");
            return;
        }
        $this->installModule(null, $url);
    }

    public function installUpdate(string $moduleName): void
    {
        $update = $this->updates[$moduleName] ?? null;
        if (!$update || empty($update['download_url'])) {
            session()->flash('error', "No update URL available for '$moduleName'.");
            return;
        }
        $this->installModule(null, $update['download_url']);
    }

    protected function installModule($upload, ?string $url): void
    {
        $this->installing = true;
        $this->installStatus = '';

        try {
            $tempDir = storage_path('app/module-temp/' . uniqid());
            File::ensureDirectoryExists($tempDir);

            $zipPath = $tempDir . '/module.zip';

            if ($upload) {
                $this->installStatus = 'Processing uploaded file...';
                $upload->storeAs('module-temp', basename($zipPath));
                $zipPath = storage_path('app/module-temp/' . basename($zipPath));
            } elseif ($url) {
                $this->installStatus = 'Downloading module...';
                $zipContent = @file_get_contents($url);
                if ($zipContent === false) {
                    throw new \Exception('Failed to download module from URL.');
                }
                File::put($zipPath, $zipContent);
            }

            $this->installStatus = 'Extracting module...';
            $zip = new \ZipArchive();
            if ($zip->open($zipPath) !== true) {
                throw new \Exception('Invalid ZIP file.');
            }

            $manifestContent = null;
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $name = $zip->getNameIndex($i);
                if (str_ends_with($name, 'module.json')) {
                    $manifestContent = $zip->getFromIndex($i);
                    break;
                }
            }

            if (! $manifestContent) {
                $zip->close();
                throw new \Exception('No module.json found in the package.');
            }

            $manifest = json_decode($manifestContent, true);
            if (! $manifest || ! isset($manifest['name']) || ! isset($manifest['vendor'])) {
                $zip->close();
                throw new \Exception('Invalid module.json: name and vendor are required.');
            }

            $modName = $manifest['name'];
            $vendor = $manifest['vendor'];
            $targetDir = base_path("modules/$vendor/$modName");

            if (File::isDirectory($targetDir)) {
                $zip->close();
                throw new \Exception("Module '$modName' already exists in modules/$vendor/$modName.");
            }

            File::ensureDirectoryExists(dirname($targetDir));

            $this->installStatus = 'Installing module...';
            $zip->extractTo($targetDir);
            $zip->close();

            $this->installStatus = 'Registering autoload...';
            $this->registerAutoload($manifest, $vendor, $modName);

            $this->installStatus = 'Optimizing autoload...';
            $this->runComposerDump();

            $this->installing = false;
            $this->installStatus = '';

            $this->upload = null;
            $this->installUrl = '';

            File::deleteDirectory(dirname($tempDir));

            $engine = app(ModuleEngine::class);
            $engine->discover();
            $this->loadModules($engine);

            $displayName = $manifest['title'] ?? $modName;
            session()->flash('message', "Module '$displayName' installed successfully.");

        } catch (\Exception $e) {
            $this->installing = false;
            $this->installStatus = '';
            session()->flash('error', 'Installation failed: ' . $e->getMessage());
        }
    }

    protected function registerAutoload(array $manifest, string $vendor, string $modName): void
    {
        $composerPath = base_path('composer.json');
        if (! File::exists($composerPath)) return;

        $namespace = ($manifest['namespace'] ?? 'Inox\\' . ucfirst($modName) . '\\');
        $srcPath = "modules/$vendor/$modName/src";

        $composer = json_decode(File::get($composerPath), true);

        if (! isset($composer['autoload']['psr-4'][$namespace])) {
            $composer['autoload']['psr-4'][$namespace] = $srcPath;
            File::put($composerPath, json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . "\n");
        }
    }

    protected function runComposerDump(): void
    {
        $composerPath = base_path('composer.json');
        $start = microtime(true);
        exec('php "' . base_path('composer.phar') . '" dump-autoload 2>&1', $output, $code);
        if ($code !== 0) {
            // Try global composer
            exec('composer dump-autoload 2>&1', $output, $code);
        }
    }

    public function deleteModule(string $name): void
    {
        $engine = app(ModuleEngine::class);
        $engine->discover();

        $module = $engine->find($name);
        if (! $module) {
            session()->flash('error', "Module '$name' not found.");
            return;
        }

        $vendor = $module['vendor'] ?? 'inox';
        $modulePath = $module['path'] ?? base_path("modules/$vendor/$name");

        if ($engine->isActive($name)) {
            $engine->deactivate($name);
            $this->removeFromActiveConfig($name);
            $engine->persistActiveConfig();
        }

        if (File::isDirectory($modulePath)) {
            File::deleteDirectory($modulePath);
        }

        $this->removeAutoload($name);

        session()->flash('message', "Module '$name' deleted.");
        $engine->discover();
        $this->loadModules($engine);
    }

    protected function removeAutoload(string $name): void
    {
        $composerPath = base_path('composer.json');
        if (! File::exists($composerPath)) return;

        $composer = json_decode(File::get($composerPath), true);

        foreach ($composer['autoload']['psr-4'] ?? [] as $namespace => $path) {
            if (str_contains($path, "modules/")) {
                $parts = explode('/', $path);
                $modName = end($parts);
                if ($modName === $name) {
                    unset($composer['autoload']['psr-4'][$namespace]);
                }
            }
        }

        File::put($composerPath, json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . "\n");
    }

    protected function addToActiveConfig(string $name): void
    {
        $active = config('inox.modules.active', []);
        if (! in_array($name, $active)) {
            $active[] = $name;
            config(['inox.modules.active' => $active]);
        }
    }

    protected function removeFromActiveConfig(string $name): void
    {
        $active = config('inox.modules.active', []);
        $active = array_values(array_filter($active, fn($m) => $m !== $name));
        config(['inox.modules.active' => $active]);
    }

    public function getFilteredModulesProperty()
    {
        if (empty($this->search)) {
            return $this->modules;
        }

        $q = strtolower($this->search);
        return array_filter($this->modules, fn($m) =>
            str_contains(strtolower($m['name'] ?? ''), $q) ||
            str_contains(strtolower($m['description'] ?? ''), $q) ||
            str_contains(strtolower($m['vendor'] ?? ''), $q)
        );
    }

    public function getFilteredRegistryProperty()
    {
        if (empty($this->registrySearch)) {
            return $this->registry;
        }

        $q = strtolower($this->registrySearch);
        return array_filter($this->registry, fn($m) =>
            str_contains(strtolower($m['name'] ?? ''), $q) ||
            str_contains(strtolower($m['description'] ?? ''), $q) ||
            str_contains(strtolower($m['vendor'] ?? ''), $q)
        );
    }

    public function isModuleInstalledFromRegistry(string $name): bool
    {
        return isset($this->modules[$name]);
    }

    public function render()
    {
        return view('livewire.modules-manager');
    }
}
