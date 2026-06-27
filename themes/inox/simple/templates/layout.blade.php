<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name')) — {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: { colors: { primary: '{{ theme("primary_color", "#2563eb") }}' } } }
        }
    </script>
    @if (isset($theme_css_url))
        <link rel="stylesheet" href="{{ $theme_css_url }}">
    @endif
    <style>
        body { font-family: system-ui, -apple-system, sans-serif; }
        .prose a { color: {{ theme('primary_color', '#2563eb') }}; }
    </style>
    @livewireStyles
</head>
<body class="bg-gray-50 antialiased text-gray-900">
    <div class="min-h-screen flex flex-col">
        <header class="bg-white border-b border-gray-200">
            <div class="max-w-4xl mx-auto px-4 py-5 flex items-center justify-between">
                <a href="{{ url('/') }}" class="text-lg font-semibold text-gray-900 tracking-tight">
                    {{ config('app.name') }}
                </a>
                <nav class="flex items-center gap-5 text-sm text-gray-600">
                    <a href="{{ url('/') }}" class="hover:text-gray-900">Home</a>
                    <a href="{{ url('/blog') }}" class="hover:text-gray-900">Blog</a>
                    @auth
                        <a href="{{ url('/admin') }}" class="hover:text-gray-900">Admin</a>
                    @else
                        <a href="{{ url('/login') }}" class="hover:text-gray-900">Login</a>
                    @endauth
                </nav>
            </div>
        </header>

        <main class="flex-1">
            @yield('content')
        </main>

        <footer class="border-t border-gray-200 bg-white py-6 mt-12">
            <div class="max-w-4xl mx-auto px-4 text-center text-xs text-gray-400">
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
