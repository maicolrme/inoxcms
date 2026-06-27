<?php

namespace App\Core\ThemeEngine;

use App\Core\SettingRegistry\SettingRegistry;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Illuminate\View\View as ViewInstance;

class ThemeEngine
{
    protected array $themes = [];
    protected ?array $activeTheme = null;
    protected ?string $activeVendor = null;
    protected ?array $manifest = null;

    protected SettingRegistry $settings;

    public function __construct()
    {
        $this->settings = app(SettingRegistry::class);
    }

    public function discover(): array
    {
        $this->themes = [];
        $path = config('inox.themes.path', base_path('themes'));

        if (!File::isDirectory($path)) {
            return $this->themes;
        }

        foreach (File::directories($path) as $vendorDir) {
            $vendor = basename($vendorDir);
            foreach (File::directories($vendorDir) as $themeDir) {
                $manifestPath = $themeDir . '/theme.json';
                if (File::exists($manifestPath)) {
                    $manifest = json_decode(File::get($manifestPath), true);
                    if ($manifest) {
                        $manifest['vendor'] = $vendor;
                        $manifest['path'] = $themeDir;
                        $name = $vendor . '/' . $manifest['name'];
                        $this->themes[$name] = $manifest;

                        if (isset($manifest['settings'])) {
                            $moduleName = 'theme.' . str_replace('/', '.', $name);
                            $normalized = [];
                            foreach ($manifest['settings'] as $def) {
                                if (isset($def['key'])) {
                                    $normalized[$def['key']] = $def;
                                }
                            }
                            $this->settings->register($moduleName, $normalized);
                            $this->settings->purgeUnregistered($moduleName, $normalized);
                        }
                    }
                }
            }
        }

        return $this->themes;
    }

    public function all(): array
    {
        return $this->themes ?: $this->discover();
    }

    public function active(): ?array
    {
        if ($this->activeTheme !== null) {
            return $this->activeTheme;
        }

        $active = config('inox.themes.active');

        if (!$active) {
            return null;
        }

        $this->activeTheme = $this->find($active);
        return $this->activeTheme;
    }

    public function find(string $vendor): ?array
    {
        $themes = $this->all();
        return $themes[$vendor] ?? null;
    }

    public function activate(string $vendor): void
    {
        $theme = $this->find($vendor);
        if (!$theme) {
            throw new \RuntimeException("Theme '{$vendor}' not found.");
        }

        $this->activeTheme = $theme;
        $this->activeVendor = $vendor;

        $path = config_path('inox.php');
        $config = File::exists($path) ? File::get($path) : '';

        $escaped = preg_quote($vendor, '/');
        if (preg_match("/'active'\s*=>\s*'[^']*'/", $config)) {
            $config = preg_replace("/'active'\s*=>\s*'[^']*'/", "'active' => '{$vendor}'", $config);
        } else {
            $config = preg_replace(
                "/('themes'\s*=>\s*\[[^]]*'path'\s*=>\s*[^,]+,\s*)/",
                "$1'active' => '{$vendor}',\n        ",
                $config
            );
        }

        File::put($path, $config);
    }

    public function setting(string $key, mixed $default = null): mixed
    {
        $active = $this->active();
        if (!$active) {
            return $default;
        }
        $settingKey = 'theme.' . $active['vendor'] . '.' . $active['name'] . '.' . $key;

        $value = config("settings.{$settingKey}");
        if ($value !== null) {
            return $value;
        }

        return $this->settings->get($settingKey, $default);
    }

    public function saveSettings(array $settings): void
    {
        $active = $this->active();
        if (!$active) {
            return;
        }
        $module = 'theme.' . $active['vendor'] . '.' . $active['name'];
        foreach ($settings as $key => $value) {
            $this->settings->set('theme.' . $active['vendor'] . '.' . $active['name'] . '.' . $key, $value, $module);
        }
    }

    public function path(string $subpath = ''): string
    {
        $active = $this->active();
        if (!$active) {
            return '';
        }
        $base = $active['path'];
        return $subpath ? $base . '/' . ltrim($subpath, '/') : $base;
    }

    public function url(string $subpath = ''): string
    {
        $active = $this->active();
        if (!$active) {
            return '';
        }
        $relative = 'themes/' . $active['vendor'] . '/' . $active['name'] . '/' . ltrim($subpath, '/');
        return asset($relative);
    }

    public function template(string $view, array $data = []): ViewInstance
    {
        if ($themeView = $this->resolveThemeView($view)) {
            return view($themeView, $data);
        }
        return view($view, $data);
    }

    protected function resolveThemeView(string $view): ?string
    {
        $nsView = 'theme::' . $view;
        if (view()->exists($nsView)) {
            return $nsView;
        }
        $parts = explode('.', $view);
        $flat = end($parts);
        $flatView = 'theme::' . $flat;
        if (view()->exists($flatView)) {
            return $flatView;
        }
        return null;
    }

    public function boot(): void
    {
        $active = $this->active();
        if (!$active) {
            return;
        }

        $templatesPath = $active['path'] . '/templates';

        if (File::isDirectory($templatesPath)) {
            View::addNamespace('theme', $templatesPath);
            View::getFinder()->prependLocation($templatesPath);
        }

        $assetsPath = $active['path'] . '/assets';
        if (File::isDirectory($assetsPath)) {
            $this->registerAssets($active);
        }
    }

    protected function registerAssets(array $theme): void
    {
        $cssPath = $theme['path'] . '/assets/css/app.css';
        if (File::exists($cssPath)) {
            $url = $this->url('assets/css/app.css');
            view()->composer('*', function ($view) use ($url) {
                $view->with('theme_css_url', $url);
            });
        }

        $jsPath = $theme['path'] . '/assets/js/app.js';
        if (File::exists($jsPath)) {
            $url = $this->url('assets/js/app.js');
            view()->composer('*', function ($view) use ($url) {
                $view->with('theme_js_url', $url);
            });
        }
    }

    public function getSettings(): array
    {
        $active = $this->active();
        if (!$active || !isset($active['settings'])) {
            return [];
        }
        return $active['settings'];
    }

    public function manifest(): ?array
    {
        $active = $this->active();
        return $active;
    }
}
