<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class InoxMakeThemeCommand extends Command
{
    protected $signature = 'inox:make-theme
        {name : Theme name (e.g. \"my-theme\")}
        {--vendor=inox : Vendor name}
        {--description= : Theme description}';

    protected $description = 'Scaffold a new theme';

    public function handle(): int
    {
        $name = Str::slug($this->argument('name'));
        $vendor = $this->option('vendor');
        $description = $this->option('description') ?: "{$vendor}/{$name} theme for INOX.";

        $path = base_path("themes/{$vendor}/{$name}");

        if (File::isDirectory($path)) {
            $this->error("Theme \"{$vendor}/{$name}\" already exists.");
            return Command::FAILURE;
        }

        File::makeDirectory($path . '/templates', 0755, true);
        File::makeDirectory($path . '/assets/css', 0755, true);
        File::makeDirectory($path . '/assets/js', 0755, true);
        File::makeDirectory($path . '/screenshots', 0755, true);
        File::makeDirectory($path . '/components', 0755, true);

        $this->writeFile($path . '/theme.json', $this->themeJson($vendor, $name, $description));
        $this->writeFile($path . '/templates/layout.blade.php', $this->layoutTemplate());
        $this->writeFile($path . '/templates/home.blade.php', $this->homeTemplate());
        $this->writeFile($path . '/templates/page.blade.php', $this->pageTemplate());
        $this->writeFile($path . '/templates/post.blade.php', $this->postTemplate());
        $this->writeFile($path . '/assets/css/app.css', "/* {$vendor}/{$name} theme styles */\n");
        $this->writeFile($path . '/screenshots/.gitkeep', '');

        $this->info("Theme \"{$vendor}/{$name}\" created successfully.");
        $this->line("Path: themes/{$vendor}/{$name}");
        $this->line("Activate with: php artisan theme:activate {$vendor}/{$name}");

        return Command::SUCCESS;
    }

    protected function writeFile(string $path, string $content): void
    {
        File::put($path, $content);
        $this->line("  Created: " . str_replace(base_path(), '', $path));
    }

    protected function themeJson(string $vendor, string $name, string $description): string
    {
        return json_encode([
            'name' => $name,
            'vendor' => $vendor,
            'version' => '1.0.0',
            'description' => $description,
            'settings' => [
                ['key' => 'primary_color', 'type' => 'color', 'default' => '#3b82f6', 'label' => 'Brand Color'],
                ['key' => 'footer_text', 'type' => 'text', 'default' => '', 'label' => 'Footer Note'],
            ],
            'templates' => ['layout', 'home', 'page', 'post'],
            'assets' => [
                'css' => ['assets/css/app.css'],
            ],
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    protected function layoutTemplate(): string
    {
        return <<<'BLADE'
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name')) — {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '{{ theme("primary_color", "#3b82f6") }}',
                    }
                }
            }
        }
    </script>
    @if (isset($theme_css_url))
        <link rel="stylesheet" href="{{ $theme_css_url }}">
    @endif
    <style>
        body { font-family: system-ui, -apple-system, sans-serif; }
    </style>
    @livewireStyles
</head>
<body class="bg-white antialiased text-gray-900">
    <div class="min-h-screen flex flex-col">
        <header class="border-b border-gray-200 bg-white">
            <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between">
                <a href="{{ url('/') }}" class="text-xl font-bold text-gray-900">
                    {{ config('app.name') }}
                </a>
                <nav class="flex items-center gap-6 text-sm">
                    <a href="{{ url('/') }}" class="text-gray-600 hover:text-gray-900">Home</a>
                    <a href="{{ url('/blog') }}" class="text-gray-600 hover:text-gray-900">Blog</a>
                    @auth
                        <a href="{{ url('/admin') }}" class="text-gray-600 hover:text-gray-900">Admin</a>
                    @else
                        <a href="{{ url('/login') }}" class="text-gray-600 hover:text-gray-900">Login</a>
                    @endauth
                </nav>
            </div>
        </header>

        <main class="flex-1">
            @yield('content')
        </main>

        <footer class="border-t border-gray-200 bg-gray-50 py-8">
            <div class="max-w-6xl mx-auto px-4 text-center text-sm text-gray-500">
                @php $footerText = theme('footer_text', ''); @endphp
                @if ($footerText)
                    <p>{{ $footerText }}</p>
                @else
                    <p>&copy; {{ date('Y') }} {{ config('app.name') }}.</p>
                @endif
            </div>
        </footer>
    </div>

    @if (isset($theme_js_url))
        <script src="{{ $theme_js_url }}"></script>
    @endif
    @livewireScripts
</body>
</html>
BLADE;
    }

    protected function homeTemplate(): string
    {
        return <<<'BLADE'
@extends('layout')

@section('title', 'Home')

@section('content')
    <div class="min-h-[70vh] flex flex-col items-center justify-center px-4 text-center"
         style="background: linear-gradient(135deg, {{ theme('primary_color', '#3b82f6') }}11 0%, #ffffff 100%);">
        <h1 class="text-5xl font-bold text-gray-900 tracking-tight">{{ config('app.name') }}</h1>
        <p class="mt-4 text-xl text-gray-500 max-w-lg">{{ config('app.description', 'Your CMS.') }}</p>
        <div class="mt-10 flex gap-4">
            <a href="{{ url('/blog') }}"
               class="px-8 py-3 text-white font-medium rounded-lg transition-colors"
               style="background-color: {{ theme('primary_color', '#3b82f6') }}">
               Read the Blog
            </a>
            <a href="{{ url('/admin') }}"
               class="px-8 py-3 border-2 font-medium rounded-lg transition-colors"
               style="border-color: {{ theme('primary_color', '#3b82f6') }}; color: {{ theme('primary_color', '#3b82f6') }}">
               Admin
            </a>
        </div>
    </div>
@endsection
BLADE;
    }

    protected function pageTemplate(): string
    {
        return <<<'BLADE'
@extends('layout')

@section('title', $page->title)

@section('content')
    <div class="max-w-4xl mx-auto px-4 py-12">
        <h1 class="text-3xl font-bold text-gray-900">{{ $page->title }}</h1>
        @if ($page->excerpt)
            <p class="mt-4 text-lg text-gray-600">{{ $page->excerpt }}</p>
        @endif
        <div class="mt-8 prose max-w-none">
            {!! $page->content !!}
        </div>
    </div>
@endsection
BLADE;
    }

    protected function postTemplate(): string
    {
        return <<<'BLADE'
@extends('layout')

@section('title', $post->title)

@section('content')
    <div class="max-w-4xl mx-auto px-4 py-12">
        <a href="{{ url('/blog') }}" class="text-blue-600 hover:text-blue-800">&larr; Back to blog</a>
        <article class="mt-6">
            <h1 class="text-3xl font-bold text-gray-900">{{ $post->title }}</h1>
            <p class="text-sm text-gray-500 mt-2">
                {{ $post->published_at?->format('M d, Y') }} · {{ $post->author?->name }}
            </p>
            <div class="mt-8 prose max-w-none">
                {!! $post->content !!}
            </div>
        </article>
    </div>
@endsection
BLADE;
    }
}
