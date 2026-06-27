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
    public array $installedThemes = [];
    public ?string $activeThemeKey = null;

    protected ThemeEngine $engine;

    public function boot(ThemeEngine $engine): void
    {
        $this->engine = $engine;
    }

    public function mount(): void
    {
        $this->loadInstalled();
        $this->loadRegistry();
    }

    protected function loadInstalled(): void
    {
        $this->installedThemes = $this->engine->all();
        $active = $this->engine->active();
        $this->activeThemeKey = $active ? ($active['vendor'] . '/' . $active['name']) : null;
    }

    protected function loadRegistry(): void
    {
        $registryPath = base_path('themes/registry.json');
        if (File::exists($registryPath)) {
            $this->registry = json_decode(File::get($registryPath), true) ?? [];
        }
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
