<?php

namespace App\Models;

use App\Core\SettingRegistry\SettingRegistry;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'inox_settings';

    protected $fillable = [
        'key',
        'value',
        'group',
        'autoload',
        'module',
    ];

    protected $casts = [
        'autoload' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saved(fn() => static::flushRegistryCache());
        static::deleted(fn() => static::flushRegistryCache());
    }

    public function scopeByModule(Builder $query, string $module): Builder
    {
        return $query->where('module', $module);
    }

    public static function getValue(string $key, mixed $default = null): mixed
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function setValue(string $key, mixed $value, string $group = 'general'): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'group' => $group]
        );
    }

    public static function purgeByModule(string $module): int
    {
        return static::where('module', $module)->delete();
    }

    protected static function flushRegistryCache(): void
    {
        if (app()->has(SettingRegistry::class)) {
            app(SettingRegistry::class)->flushCache();
        }
    }
}
