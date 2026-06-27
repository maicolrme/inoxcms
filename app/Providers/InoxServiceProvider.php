<?php

namespace App\Providers;

use App\Core\HookSystem\Hook;
use App\Core\ModuleEngine\ModuleEngine;
use App\Core\ThemeEngine\ThemeEngine;
use Illuminate\Support\ServiceProvider;

class InoxServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(config_path('inox.php'), 'inox');

        $this->app->singleton(ModuleEngine::class, function () {
            return new ModuleEngine();
        });
        $this->app->alias(ModuleEngine::class, 'inox.modules');
        $this->app->alias(ModuleEngine::class, 'module.engine');

        $this->app->singleton(ThemeEngine::class, function () {
            return new ThemeEngine();
        });
        $this->app->alias(ThemeEngine::class, 'inox.themes');
    }

    public function boot(): void
    {
        require_once __DIR__ . '/../helpers.php';

        $this->registerConfig();
        $this->registerCommands();
        $this->bootModules();
        $this->bootTheme();

        Hook::action('inox.booted', function () {
            //
        });

        Hook::execute('inox.booted');
    }

    protected function registerConfig(): void
    {
        $this->publishes([
            config_path('inox.php') => config_path('inox.php'),
        ], 'inox-config');
    }

    protected function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                \App\Console\Commands\InoxInstallCommand::class,
                \App\Console\Commands\InoxMakeThemeCommand::class,
                \App\Console\Commands\InoxServeCommand::class,
                \App\Console\Commands\InoxCheckUpdatesCommand::class,
            ]);
        }
    }

    protected function bootModules(): void
    {
        $engine = app(ModuleEngine::class);
        $engine->discover();

        foreach (config('inox.modules.active', []) as $module) {
            if (is_string($module)) {
                $engine->activate($module);
            }
        }

        $engine->registerNav('core', [
            ['label' => 'Roles',  'route' => 'admin.roles',  'active' => 'admin.roles*'],
            ['label' => 'Users',  'route' => 'admin.users',  'active' => 'admin.users*'],
            ['label' => 'Themes', 'route' => 'admin.themes', 'active' => 'admin.themes*', 'subheading' => 'Appearance'],
        ]);

        $engine->registerSettingsComponent('core', 'themes', 'Theme', 'theme-settings');
    }

    protected function bootTheme(): void
    {
        $engine = app(ThemeEngine::class);
        $engine->discover();

        $activeTheme = $engine->active();
        if ($activeTheme) {
            $engine->boot();
        }
    }
}
