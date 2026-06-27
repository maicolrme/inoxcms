# Module Hooks

## Usage

```php
\App\Core\HookSystem\Hook::register('hook.name', function ($param) {
    // Handle hook
});
```

Hooks execute in registration order. All registered callbacks run synchronously.

## Available Hooks

### `module.activated`
Fired when a module is activated via the module manager.

Parameters: `$name` (string) — module name

```php
Hook::register('module.activated', function ($name) {
    // Module $name was just activated
});
```

### `module.deactivated`
Fired when a module is deactivated.

Parameters: `$name` (string) — module name

```php
Hook::register('module.deactivated', function ($name) {
    // Clean up resources
});
```

### `post.created` (content module)
Fired after a new post is created.

Parameters: `$post` (App\Models\Post)

### `post.updated` (content module)
Fired after a post is updated.

Parameters: `$post` (App\Models\Post)

### `post.deleted` (content module)
Fired after a post is deleted.

Parameters: `$post` (App\Models\Post)

### `media.uploaded` (storage module)
Fired after a file is uploaded.

Parameters: `$media` (Inox\Storage\Models\Media)

### `media.deleted` (storage module)
Fired after a file is deleted.

Parameters: `$media` (Inox\Storage\Models\Media)

## Adding New Hooks

In your code:

```php
\App\Core\HookSystem\Hook::execute('your.hook.name', $data);
```

The second argument is passed to all registered callbacks.
