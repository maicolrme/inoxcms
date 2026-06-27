# Module Development Guide

## Structure

```
modules/<vendor>/<name>/
├── module.json
├── config/
│   └── <name>.php
├── database/
│   └── migrations/
├── resources/
│   └── views/
├── routes/
│   ├── web.php
│   └── api.php
├── src/
│   ├── Livewire/
│   ├── Models/
│   ├── Http/
│   │   └── Controllers/
│   │       └── Api/
│   └── Providers/
│       └── ModuleServiceProvider.php
```

## module.json

```json
{
    "name": "my-module",
    "vendor": "inox",
    "version": "1.0.0",
    "description": "Module description",
    "provider": "Vendor\\Name\\Providers\\ModuleServiceProvider",
    "required_modules": [],
    "download_url": ""
}
```

- **name**: Unique identifier (used as key in `config('inox.modules.active')`)
- **vendor**: Must match the directory name under `modules/`
- **provider**: Full namespace of the service provider (activated on module activation)
- **required_modules**: Array of module names that must be active for this module to work

## Service Provider

Extend `Illuminate\Support\ServiceProvider`. Register routes, views, migrations, Livewire components, and hooks in the `boot()` method:

```php
public function boot(): void
{
    $this->loadViewsFrom(__DIR__.'/../../resources/views', 'module-name');
    $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
    $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');

    Livewire::component('module-component', MyComponent::class);

    $this->registerNav();
    $this->registerDashboardWidgets();
    $this->registerSettingsComponents();
}
```

### Navigation

```php
$engine->registerNav('module-name', [
    ['label' => 'My Page', 'route' => 'admin.my-page.index', 'active' => 'admin.my-page.*'],
]);
```

Displayed in the admin sidebar when the module is active.

### Dashboard Widgets

```php
$engine->registerDashboardWidget('module-name', 'widget-key', 'Widget Title', 'md:col-span-2', function () {
    return '<p>Widget content as HTML</p>';
});
```

Widgets appear in the dashboard in a 2-column grid. `grid_class` allows spanning (`md:col-span-2` for full width).

### Settings Components (Livewire)

```php
$engine->registerSettingsComponent('module-name', 'tab-key', 'Tab Label', 'livewire-alias');
```

The Livewire component must be registered via `Livewire::component()` and renders as a tab in the Settings page. Each component has its own form/submit context.

### Settings Tabs (plain HTML)

```php
$engine->registerSettingsTab('module-name', 'tab-key', 'Tab Label', function () {
    return view('module::settings-tab')->render();
});
```

Simpler alternative to settings components — just returns HTML, rendered inline in the settings page.

## Hooks

Register hooks in your service provider:

```php
\App\Core\HookSystem\Hook::register('post.created', function ($post) {
    // Do something when a post is created
});
```

See `docs/module-hooks.md` for available hooks.
