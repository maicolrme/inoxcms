# Module Registry

The file `modules/registry.json` powers the Browse tab in the module manager.

## Format

```json
[
    {
        "name": "module-name",
        "vendor": "inox",
        "version": "1.0.0",
        "description": "Short description of the module.",
        "download_url": "https://example.com/modules/module-name.zip",
        "requirements": ["php:>=8.1", "laravel:>=11.0"]
    }
]
```

## Fields

| Field | Required | Description |
|-------|----------|-------------|
| `name` | yes | Module identifier (must match `module.json` name) |
| `vendor` | yes | Vendor/directory name |
| `version` | yes | Semantic version |
| `description` | yes | Human-readable description (shown in Browse tab) |
| `download_url` | no | URL to ZIP file for direct install |
| `requirements` | no | Array of platform/package requirements |

## Adding a Module to the Registry

1. Build your module ZIP (must contain `module.json` at root)
2. Host the ZIP at a public URL
3. Add an entry to `modules/registry.json`
4. The module appears in Browse tab after cache clear

## Install Process

1. **From URL**: Module manager downloads ZIP to temp, extracts to `modules/<vendor>/<name>/`
2. **From upload**: ZIP uploaded via form, extracted to same path
3. PSR-4 autoload entry added to `composer.json` (`Vendor\Name\` → `modules/vendor/name/src/`)
4. `composer dump-autoload` executed
5. Module appears in Installed tab, ready to activate
