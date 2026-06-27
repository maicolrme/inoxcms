<?php

namespace App\Livewire;

use App\Core\ThemeEngine\ThemeEngine;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class ThemeSettings extends Component
{
    public array $settings = [];
    public array $schema = [];
    public ?array $themeInfo = null;
    public string $message = '';

    protected ThemeEngine $engine;

    public function boot(ThemeEngine $engine): void
    {
        $this->engine = $engine;
    }

    public function mount(): void
    {
        $this->themeInfo = $this->engine->manifest();
        $this->schema = $this->engine->getSettings();

        foreach ($this->schema as $field) {
            $key = $field['key'];
            $this->settings[$key] = $this->engine->setting($key, $field['default'] ?? '');
        }
    }

    public function save(): void
    {
        $rules = [];
        foreach ($this->schema as $field) {
            $rules['settings.' . $field['key']] = 'nullable|string|max:5000';
        }
        $this->validate($rules);

        $this->engine->saveSettings($this->settings);
        $this->message = 'Theme settings saved.';
    }

    public function render(): View
    {
        return view('livewire.theme-settings');
    }
}
