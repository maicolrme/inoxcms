<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Blog') — {{ config('app.name', 'INOX') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @livewireStyles
</head>
<body class="bg-gray-50 antialiased">
    <div class="min-h-screen">
        <header class="bg-white border-b border-gray-200">
            <div class="max-w-4xl mx-auto px-4 py-4 flex items-center justify-between">
                <a href="{{ url('/') }}" class="text-xl font-bold text-gray-900">{{ config('app.name', 'INOX') }}</a>
                <nav class="flex items-center gap-4 text-sm">
                    <a href="{{ url('/blog') }}" class="text-gray-600 hover:text-gray-900">Blog</a>
                    <a href="{{ url('/login') }}" class="text-gray-600 hover:text-gray-900">Login</a>
                </nav>
            </div>
        </header>
        <main>
            @yield('content')
        </main>
    </div>
    @livewireScripts
</body>
</html>
