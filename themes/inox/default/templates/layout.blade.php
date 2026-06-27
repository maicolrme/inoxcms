<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name')) — {{ config('app.name', 'INOX') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '{{ theme('primary_color', '#3b82f6') }}',
                        secondary: '{{ theme('secondary_color', '#10b981') }}',
                    }
                }
            }
        }
    </script>
    @if (isset($theme_css_url) && $theme_css_url)
        <link rel="stylesheet" href="{{ $theme_css_url }}">
    @endif
    <style>
        body { font-family: '{{ theme('font_body', 'Inter') }}', sans-serif; }
        .theme-primary { color: {{ theme('primary_color', '#3b82f6') }}; }
        .theme-bg-primary { background-color: {{ theme('primary_color', '#3b82f6') }}; }
        .theme-border-primary { border-color: {{ theme('primary_color', '#3b82f6') }}; }
    </style>
    @livewireStyles
</head>
<body class="bg-white antialiased text-gray-900">
    <div class="min-h-screen flex flex-col">
        <header class="border-b border-gray-200 bg-white">
            <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between">
                <a href="{{ url('/') }}" class="text-xl font-bold" style="color: {{ theme('primary_color', '#3b82f6') }}">
                    {{ config('app.name', 'INOX') }}
                </a>
                <nav class="flex items-center gap-6 text-sm">
                    <a href="{{ url('/') }}" class="text-gray-600 hover:text-gray-900 font-medium">Home</a>
                    <a href="{{ url('/blog') }}" class="text-gray-600 hover:text-gray-900 font-medium">Blog</a>
                    @auth
                        <a href="{{ url('/admin') }}" class="text-gray-600 hover:text-gray-900 font-medium">Admin</a>
                    @else
                        <a href="{{ url('/login') }}" class="text-gray-600 hover:text-gray-900 font-medium">Login</a>
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
                    <p>&copy; {{ date('Y') }} {{ config('app.name', 'INOX') }}. All rights reserved.</p>
                @endif
            </div>
        </footer>
    </div>

    @if (isset($theme_js_url) && $theme_js_url)
        <script src="{{ $theme_js_url }}"></script>
    @endif
    @livewireScripts
</body>
</html>
