<?php

namespace App\Core\ModuleEngine;

use App\Core\HookSystem\Hook;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class ModuleEngine
{
    protected array $modules = [];

    protected array $activeModules = [];

    protected array $navItems = [];

    protected array $settingsTabs = [];

    protected array $settingsComponents = [];

    protected array $dashboardWidgets = [];

    public function discover(): void
    {
        $path = config('inox.modules.path');

        if (! File::isDirectory($path)) {
            return;
        }

        foreach (File::directories($path) as $vendorPath) {
            $vendor = basename($vendorPath);

            foreach (File::directories($vendorPath) as $modulePath) {
                $manifest = $modulePath . '/module.json';

                if (! File::exists($manifest)) {
                    continue;
                }

                $config = json_decode(File::get($manifest), true);

                if (! $config || ! isset($config['name'])) {
                    continue;
                }

                $name = $config['name'];
                $this->modules[$name] = array_merge($config, [
                    'path' => $modulePath,
                    'vendor' => $vendor,
                ]);
            }
        }
    }

    public function all(): array
    {
        return $this->modules;
    }

    public function find(string $name): ?array
    {
        return $this->modules[$name] ?? null;
    }

    public function activate(string $name): bool
    {
        if (! isset($this->modules[$name])) {
            return false;
        }

        $this->activeModules[$name] = $this->modules[$name];
        config(["inox.modules.active.$name" => $name]);

        $module = $this->modules[$name];
        $provider = $module['provider'] ?? null;

        if ($provider && class_exists($provider)) {
            app()->register($provider);
        }

        Hook::execute('module.activated', $name);

        return true;
    }

    public function deactivate(string $name): bool
    {
        if (! isset($this->activeModules[$name])) {
            return false;
        }

        unset($this->activeModules[$name]);
        Hook::execute('module.deactivated', $name);

        if (app()->has(\App\Core\SettingRegistry\SettingRegistry::class)) {
            app(\App\Core\SettingRegistry\SettingRegistry::class)->purge($name);
        }

        return true;
    }

    public function activateAll(): void
    {
        foreach ($this->modules as $name => $module) {
            if (! isset($this->activeModules[$name])) {
                $this->activate($name);
            }
        }
    }

    public function deactivateAll(): void
    {
        foreach (array_keys($this->activeModules) as $name) {
            $this->deactivate($name);
        }
    }

    public function isActive(string $name): bool
    {
        return isset($this->activeModules[$name]);
    }

    public function active(): array
    {
        return $this->activeModules;
    }

    public function persistActiveConfig(): void
    {
        $names = array_keys($this->activeModules);
        $path = config_path('inox.php');

        if (! File::exists($path)) return;

        $content = File::get($path);

        $list = empty($names) ? '[]' : "['" . implode("', '", $names) . "']";
        $content = preg_replace(
            "/'active'\s*=>\s*\[[^\]]*\]/m",
            "'active' => $list",
            $content
        );

        File::put($path, $content);
    }

    // ─── Navigation ───────────────────────────────────────────

    public function registerNav(string $module, array $items): void
    {
        if (! isset($this->navItems[$module])) {
            $this->navItems[$module] = [];
        }
        $this->navItems[$module] = array_merge($this->navItems[$module], $items);
    }

    public function getNavItems(): array
    {
        $items = [];
        foreach ($this->navItems as $module => $moduleItems) {
            if ($module === 'core' || $this->isActive($module)) {
                foreach ($moduleItems as $item) {
                    $items[] = $item;
                }
            }
        }
        return $items;
    }

    // ─── Settings Tabs (plain HTML) ───────────────────────────

    public function registerSettingsTab(string $module, string $key, string $label, callable $renderer): void
    {
        $this->settingsTabs[] = [
            'module' => $module,
            'key' => $key,
            'label' => $label,
            'renderer' => $renderer,
        ];
    }

    public function getSettingsTabs(): array
    {
        return array_filter($this->settingsTabs, fn($tab) => $tab['module'] === 'core' || $this->isActive($tab['module']));
    }

    public function renderSettingsTab(string $key): string
    {
        foreach ($this->getSettingsTabs() as $tab) {
            if ($tab['key'] === $key) {
                return (string) call_user_func($tab['renderer']);
            }
        }
        return '';
    }

    // ─── Settings Tabs (Livewire component) ───────────────────

    public function registerSettingsComponent(string $module, string $key, string $label, string $componentAlias): void
    {
        $this->settingsComponents[] = [
            'module' => $module,
            'key' => $key,
            'label' => $label,
            'component' => $componentAlias,
        ];
    }

    public function getSettingsComponents(): array
    {
        return array_filter($this->settingsComponents, fn($tab) => $tab['module'] === 'core' || $this->isActive($tab['module']));
    }

    public function getSettingsComponentForTab(string $key): ?string
    {
        foreach ($this->getSettingsComponents() as $tab) {
            if ($tab['key'] === $key) {
                return $tab['component'];
            }
        }
        return null;
    }

    // ─── Dashboard Widgets ────────────────────────────────────

    public function registerDashboardWidget(string $module, string $key, string $title, string $gridClass, callable $renderer): void
    {
        $this->dashboardWidgets[$key] = [
            'module' => $module,
            'title' => $title,
            'grid_class' => $gridClass,
            'renderer' => $renderer,
        ];
    }

    public function getDashboardWidgets(): array
    {
        $widgets = [];
        foreach ($this->dashboardWidgets as $key => $widget) {
            if ($widget['module'] === 'core' || $this->isActive($widget['module'])) {
                $widgets[] = [
                    'key' => $key,
                    'title' => $widget['title'],
                    'grid_class' => $widget['grid_class'],
                    'content' => (string) call_user_func($widget['renderer']),
                ];
            }
        }
        return $widgets;
    }

    // ─── Marketplace ──────────────────────────────────────────

    public function getMarketplaceUrl(): string
    {
        return config('inox.marketplace.modules_url', 'https://raw.githubusercontent.com/maicolrme/inoxcms-modules/main/registry.json');
    }

    public function fetchRegistry(): ?array
    {
        try {
            $url = $this->getMarketplaceUrl();
            $response = Http::timeout(10)->get($url);
            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['packages'])) {
                    return $data['packages'];
                }
                return $data;
            }
        } catch (\Exception $e) {
            // Fallback to local cache
        }

        $regPath = config('inox.modules.path') . '/registry.json';
        if (File::exists($regPath)) {
            $local = json_decode(File::get($regPath), true) ?? [];
            if (isset($local['packages'])) {
                return $local['packages'];
            }
            return $local;
        }

        return null;
    }

    public function checkUpdates(?array $registry = null): array
    {
        $updates = [];
        $registry = $registry ?? $this->fetchRegistry();

        if (!$registry) {
            return $updates;
        }

        foreach ($this->modules as $name => $module) {
            $regEntry = $registry[$name] ?? null;
            if (!$regEntry || !isset($regEntry['latest_version'])) {
                continue;
            }

            $currentVersion = $module['version'] ?? '0.0.0';
            $latestVersion = $regEntry['latest_version'];

            if (version_compare($latestVersion, $currentVersion, '>')) {
                $updates[$name] = [
                    'name' => $name,
                    'current_version' => $currentVersion,
                    'latest_version' => $latestVersion,
                    'download_url' => $regEntry['versions'][$latestVersion]['download_url'] ?? '',
                ];
            }
        }

        return $updates;
    }

    // ─── Internal ─────────────────────────────────────────────

    protected function bootModule(array $module): void
    {
        $serviceProvider = $module['path'] . '/src/Providers/ModuleServiceProvider.php';

        if (File::exists($serviceProvider)) {
            require_once $serviceProvider;
        }
    }
}

