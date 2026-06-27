<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>INOX - Installation</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 antialiased">
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-2xl w-full mx-4">
            <div class="text-center mb-8">
                <h1 class="text-5xl font-bold text-gray-900">INOX</h1>
                <p class="text-lg text-gray-600 mt-2">Installation Wizard</p>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
                <div class="flex items-center justify-center mb-6">
                    <div class="flex items-center space-x-2 text-sm">
                        <span class="bg-blue-600 text-white rounded-full w-8 h-8 flex items-center justify-center font-semibold">1</span>
                        <span class="text-gray-400">Welcome</span>
                        <span class="text-gray-300 mx-2">→</span>
                        <span class="bg-gray-200 text-gray-500 rounded-full w-8 h-8 flex items-center justify-center">2</span>
                        <span class="text-gray-400">Type</span>
                        <span class="text-gray-300 mx-2">→</span>
                        <span class="bg-gray-200 text-gray-500 rounded-full w-8 h-8 flex items-center justify-center">3</span>
                        <span class="text-gray-400">Database</span>
                        <span class="text-gray-300 mx-2">→</span>
                        <span class="bg-gray-200 text-gray-500 rounded-full w-8 h-8 flex items-center justify-center">4</span>
                        <span class="text-gray-400">Features</span>
                        <span class="text-gray-300 mx-2">→</span>
                        <span class="bg-gray-200 text-gray-500 rounded-full w-8 h-8 flex items-center justify-center">5</span>
                        <span class="text-gray-400">Admin</span>
                    </div>
                </div>

                <h2 class="text-2xl font-semibold text-gray-900 mb-4">Welcome to INOX</h2>
                <p class="text-gray-600 mb-6">The modern PHP CMS that doesn't rust. This wizard will guide you through setting up your site in just a few steps.</p>

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <p class="text-sm text-blue-800">We'll configure your database, storage, optional features, and create your admin account.</p>
                </div>

                <a href="{{ route('installer.type') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                    Start Installation
                    <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>
    </div>
</body>
</html>
