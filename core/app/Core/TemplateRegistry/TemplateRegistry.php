<?php

namespace App\Core\TemplateRegistry;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class TemplateRegistry
{
    protected array $cache = [];

    public function all(): array
    {
        if (! empty($this->cache)) {
            return $this->cache;
        }

        $templates = [];

        $templates = array_merge($templates, $this->scanDirectory(
            resource_path('views/pages'),
            'pages.'
        ));

        $themeTemplates = $this->scanThemeTemplates();
        $templates = array_merge($templates, $themeTemplates);

        $this->cache = $templates;

        return $templates;
    }

    public function exists(string $name): bool
    {
        return view()->exists('pages.' . $name) || view()->exists('theme::' . $name);
    }

    protected function scanDirectory(string $dir, string $prefix): array
    {
        $templates = [];

        if (! File::isDirectory($dir)) {
            return $templates;
        }

        foreach (File::files($dir) as $file) {
            $filename = $file->getFilename();

            if (! str_ends_with($filename, '.blade.php')) {
                continue;
            }

            $name = Str::before($filename, '.blade.php');

            if ($name === 'show') {
                continue;
            }

            $content = $file->getContents();
            $label = $this->parseLabel($content, $name);
            $description = $this->parseDescription($content);

            $templates[] = [
                'value' => $name,
                'label' => $label,
                'description' => $description,
                'source' => 'core',
            ];
        }

        return $templates;
    }

    protected function scanThemeTemplates(): array
    {
        $engine = app('App\Core\ThemeEngine\ThemeEngine');
        $active = $engine ? $engine->active() : null;

        if (! $active) {
            return [];
        }

        $templatesPath = $active['path'] . '/templates';

        if (! File::isDirectory($templatesPath)) {
            return [];
        }

        $templates = [];

        foreach (File::files($templatesPath) as $file) {
            $filename = $file->getFilename();

            if (! str_ends_with($filename, '.blade.php')) {
                continue;
            }

            $name = Str::before($filename, '.blade.php');
            $content = $file->getContents();
            $label = $this->parseLabel($content, $name);
            $description = $this->parseDescription($content);

            $templates[] = [
                'value' => $name,
                'label' => $label,
                'description' => $description,
                'source' => 'theme',
            ];
        }

        return $templates;
    }

    protected function parseLabel(string $content, string $fallback): string
    {
        if (preg_match('/{{\s*--\s*name:\s*(.+?)\s*--\s*}}/', $content, $m)) {
            return trim($m[1]);
        }

        return Str::title(str_replace(['-', '_'], ' ', $fallback));
    }

    protected function parseDescription(string $content): ?string
    {
        if (preg_match('/{{\s*--\s*description:\s*(.+?)\s*--\s*}}/', $content, $m)) {
            return trim($m[1]);
        }

        return null;
    }
}
