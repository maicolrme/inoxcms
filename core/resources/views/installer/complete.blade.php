<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>INOX - Installation Complete</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 antialiased">
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-2xl w-full mx-4">
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-gray-900">INOX</h1>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>

                <h2 class="text-2xl font-semibold text-gray-900 mb-2">Installation Complete!</h2>
                <p class="text-gray-600 mb-2">Your INOX site is ready.</p>
                <p class="text-sm text-gray-500 mb-8">You can now log in to the admin panel.</p>

                <div class="flex items-center justify-center gap-4">
                    <a href="{{ url('/admin') }}" class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                        Go to Admin
                    </a>
                    <a href="{{ url('/') }}" class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                        View Site
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
