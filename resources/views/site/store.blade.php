<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'INOX') }} — Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white antialiased">
    <div class="min-h-screen flex flex-col items-center justify-center">
        <span class="text-4xl">🛒</span>
        <h1 class="mt-4 text-4xl font-bold text-gray-900">{{ config('app.name', 'INOX') }}</h1>
        <p class="mt-2 text-lg text-gray-500">Store coming soon.</p>
        <div class="mt-8 flex gap-4">
            <a href="{{ url('/admin') }}" class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">Admin</a>
        </div>
    </div>
</body>
</html>
