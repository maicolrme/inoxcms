<?php

namespace App\Livewire;

use App\Core\ThemeEngine\ThemeEngine;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Theme Manager')]
class ThemeManager extends Component
{
    public string $tab = 'installed';
    public string $search = '';
    public array $registry = [];
    public array $registryRaw = [];
    public array $updates = [];
    public array $installedThemes = [];
    public ?string $activeThemeKey = null;
    public bool $installing = false;
    public string $installStatus = '';

    protected ThemeEngine $engine;

    public function boot(ThemeEngine $engine): void
    {
        $this->engine = $engine;
    }

    public function mount(): void
    {
        $this->loadInstalled();
        $this->loadRegistry();
        $this->checkForUpdates();
    }

    protected function loadInstalled(): void
    {
        $this->installedThemes = $this->engine->all();
        $active = $this->engine->active();
        $this->activeThemeKey = $active ? ($active['vendor'] . '/' . $active['name']) : null;
    }

    protected function loadRegistry(): void
    {
        $packages = $this->engine->fetchRegistry();
        $this->registryRaw = $packages ?? [];

        $this->registry = [];
        if ($packages) {
            foreach ($packages as $key => $pkg) {
                $latestVer = $pkg['latest_version'] ?? '0.1.0';
                $latest = $pkg['versions'][$latestVer] ?? [];
                $registryKey = ($pkg['vendor'] ?? 'inox') . '/' . ($pkg['name'] ?? $key);
                $this->registry[$registryKey] = [
                    'name' => $pkg['name'] ?? $key,
                    'vendor' => $pkg['vendor'] ?? 'inox',
                    'version' => $latestVer,
                    'latest_version' => $latestVer,
                    'description' => $pkg['description'] ?? '',
                    'download_url' => $latest['download_url'] ?? '',
                    'screenshot' => $pkg['screenshot'] ?? '',
                    'downloads' => $pkg['downloads'] ?? 0,
                    'rating' => $pkg['rating'] ?? 0,
                    'requires' => $latest['requires'] ?? [],
                ];
            }
        }
    }

    public function checkForUpdates(): void
    {
        $this->updates = $this->engine->checkUpdates($this->registryRaw);
    }

    public function activate(string $vendor): void
    {
        try {
            $this->engine->activate($vendor);
            $this->loadInstalled();
            $this->dispatch('notify', message: "Theme '{$vendor}' activated.");
        } catch (\Exception $e) {
            $this->dispatch('notify', message: $e->getMessage());
        }
    }

    public function delete(string $vendor): void
    {
        $theme = $this->engine->find($vendor);
        if (!$theme) {
            $this->dispatch('notify', message: 'Theme not found.');
            return;
        }

        if ($this->activeThemeKey === $vendor) {
            $this->dispatch('notify', message: 'Cannot delete the active theme.');
            return;
        }

        File::deleteDirectory($theme['path']);
        $this->loadInstalled();
        $this->dispatch('notify', message: "Theme '{$vendor}' deleted.");
    }

    public function installFromMarketplace(string $key): void
    {
        $entry = $this->registry[$key] ?? null;
        if (!$entry || empty($entry['download_url'])) {
            $this->dispatch('notify', message: 'No download URL available.');
            return;
        }

        $this->installing = true;
        $this->installStatus = 'Downloading theme...';

        try {
            $this->engine->installFromUrl($entry['download_url']);
            $this->installing = false;
            $this->engine->discover();
            $this->loadInstalled();
            $this->dispatch('notify', message: "Theme '{$entry['name']}' installed.");
        } catch (\Exception $e) {
            $this->installing = false;
            $this->dispatch('notify', message: 'Installation failed: ' . $e->getMessage());
        }
    }

    public function render(): View
    {
        $this->loadInstalled();

        $registryThemes = collect($this->registry)
            ->filter(fn($t) => !$this->search || str_contains(strtolower($t['name'] ?? ''), strtolower($this->search)) || str_contains(strtolower($t['vendor'] ?? ''), strtolower($this->search)))
            ->toArray();

        return view('livewire.theme-manager', [
            'registryThemes' => $registryThemes,
        ]);
    }
}
