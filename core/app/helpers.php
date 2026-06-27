<?php

use App\Core\SettingRegistry\SettingRegistry;
use App\Core\ThemeEngine\ThemeEngine;

if (!function_exists('theme')) {
    function theme(?string $key = null, mixed $default = null): mixed
    {
        $engine = app(ThemeEngine::class);

        if ($key === null) {
            return $engine;
        }

        return $engine->setting($key, $default);
    }
}

if (!function_exists('setting')) {
    function setting(?string $key = null, mixed $default = null): mixed
    {
        $registry = app(SettingRegistry::class);

        if ($key === null) {
            return $registry;
        }

        $cached = config("settings.{$key}");
        if ($cached !== null) {
            return $cached;
        }

        return $registry->get($key, $default);
    }
}

if (!function_exists('cms_path')) {
    function cms_path(string $path = ''): string
    {
        static $base = null;
        if ($base === null) {
            $base = app()->bound('cms.path') ? app('cms.path') : dirname(__DIR__, 3);
        }
        return $base . ($path ? '/' . ltrim($path, '/') : '');
    }
}
