<?php

namespace App\Core\SettingRegistry;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingRegistry
{
    protected array $schemas = [];

    protected array $loaded = [];

    protected const CACHE_KEY = 'inox_settings_all';

    protected const CACHE_TTL = 86400;

    public function register(string $module, array $settings): void
    {
        if (!isset($this->schemas[$module])) {
            $this->schemas[$module] = [];
        }
        foreach ($settings as $key => $definition) {
            $definition['key'] = $key;
            $definition['module'] = $module;
            $this->schemas[$module][$key] = $definition;
        }
    }

    public function getSchemas(?string $module = null): array
    {
        if ($module) {
            return $this->schemas[$module] ?? [];
        }
        $all = [];
        foreach ($this->schemas as $moduleSchemas) {
            foreach ($moduleSchemas as $key => $schema) {
                $all[$key] = $schema;
            }
        }
        return $all;
    }

    public function getSchema(string $key): ?array
    {
        foreach ($this->schemas as $moduleSchemas) {
            if (isset($moduleSchemas[$key])) {
                return $moduleSchemas[$key];
            }
        }
        return null;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $cached = config("settings.{$key}");
        if ($cached !== null) {
            return $cached;
        }

        $setting = Setting::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public function set(string $key, mixed $value, string $module = 'core', bool $autoload = true): void
    {
        Setting::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'module' => $module, 'autoload' => $autoload]
        );
    }

    public function purge(string $module): void
    {
        Setting::where('module', $module)->delete();
        $this->flushCache();
    }

    public function purgeUnregistered(string $module, array $currentSettings): void
    {
        $prefix = $module . '.';
        $validKeys = [];
        foreach ($currentSettings as $def) {
            $validKeys[] = $prefix . ($def['key'] ?? $def);
        }
        if (empty($validKeys)) {
            return;
        }

        Setting::where('module', $module)
            ->whereNotIn('key', $validKeys)
            ->delete();
        $this->flushCache();
    }

    public function flushCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    public function autoload(): void
    {
        try {
            $settings = Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
                return Setting::where('autoload', true)->get()->keyBy('key');
            });
        } catch (\Exception $e) {
            return;
        }

        $values = [];
        foreach ($settings as $key => $setting) {
            if (is_object($setting) && isset($setting->value)) {
                $values[$key] = $setting->value;
            }
        }

        config(['settings' => $values]);

        if (isset($values['app_name'])) {
            config(['app.name' => $values['app_name']]);
        }
        if (isset($values['site_description'])) {
            config(['app.description' => $values['site_description']]);
        }
        if (isset($values['project_type'])) {
            config(['inox.project_type' => $values['project_type']]);
        }
        if (isset($values['cache_page_enabled'])) {
            config(['inox.cache.page.enabled' => (bool) $values['cache_page_enabled']]);
        }
        if (isset($values['cache_object_enabled'])) {
            config(['inox.cache.object.enabled' => (bool) $values['cache_object_enabled']]);
        }
        if (isset($values['cache_fragment_enabled'])) {
            config(['inox.cache.fragment.enabled' => (bool) $values['cache_fragment_enabled']]);
        }
        if (isset($values['cache_driver'])) {
            config(['inox.cache.page.driver' => $values['cache_driver']]);
        }
        if (isset($values['feature_realtime'])) {
            config(['inox.features.realtime' => (bool) $values['feature_realtime']]);
        }
        if (isset($values['feature_ai'])) {
            config(['inox.features.ai' => (bool) $values['feature_ai']]);
        }

        $this->loaded = $values;
    }

    public function cacheKey(): string
    {
        return self::CACHE_KEY;
    }
}
