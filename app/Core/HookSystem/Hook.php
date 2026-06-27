<?php

namespace App\Core\HookSystem;

use Illuminate\Support\Str;

class Hook
{
    protected static array $filters = [];

    protected static array $actions = [];

    public static function filter(string $hook, callable $callback, int $priority = 10): void
    {
        static::$filters[$hook][$priority][] = $callback;
        ksort(static::$filters[$hook]);
    }

    public static function action(string $hook, callable $callback, int $priority = 10): void
    {
        static::$actions[$hook][$priority][] = $callback;
        ksort(static::$actions[$hook]);
    }

    public static function apply(string $hook, mixed $value, mixed ...$args): mixed
    {
        if (! isset(static::$filters[$hook])) {
            return $value;
        }

        foreach (static::$filters[$hook] as $priority => $callbacks) {
            foreach ($callbacks as $callback) {
                $value = $callback($value, ...$args);
            }
        }

        return $value;
    }

    public static function execute(string $hook, mixed ...$args): void
    {
        if (! isset(static::$actions[$hook])) {
            return;
        }

        foreach (static::$actions[$hook] as $priority => $callbacks) {
            foreach ($callbacks as $callback) {
                $callback(...$args);
            }
        }
    }

    public static function removeFilter(string $hook, ?callable $callback = null, ?int $priority = null): void
    {
        static::removeFromRegistry(static::$filters, $hook, $callback, $priority);
    }

    public static function removeAction(string $hook, ?callable $callback = null, ?int $priority = null): void
    {
        static::removeFromRegistry(static::$actions, $hook, $callback, $priority);
    }

    protected static function removeFromRegistry(array &$registry, string $hook, ?callable $callback, ?int $priority): void
    {
        if (! isset($registry[$hook])) {
            return;
        }

        if ($callback === null && $priority === null) {
            unset($registry[$hook]);
            return;
        }

        if ($priority !== null && isset($registry[$hook][$priority])) {
            if ($callback === null) {
                unset($registry[$hook][$priority]);
            } else {
                $registry[$hook][$priority] = array_filter(
                    $registry[$hook][$priority],
                    fn($cb) => $cb !== $callback
                );
            }
        }
    }
}
