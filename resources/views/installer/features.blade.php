<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>INOX - Features</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 antialiased">
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-2xl w-full mx-4">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900">INOX</h1>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
                <div class="flex items-center justify-center mb-6">
                    <div class="flex items-center space-x-2 text-sm">
                        <span class="bg-green-600 text-white rounded-full w-8 h-8 flex items-center justify-center font-semibold">✓</span>
                        <span class="text-green-600 font-medium">Welcome</span>
                        <span class="text-gray-300 mx-2">→</span>
                        <span class="bg-green-600 text-white rounded-full w-8 h-8 flex items-center justify-center font-semibold">✓</span>
                        <span class="text-green-600 font-medium">Type</span>
                        <span class="text-gray-300 mx-2">→</span>
                        <span class="bg-green-600 text-white rounded-full w-8 h-8 flex items-center justify-center font-semibold">✓</span>
                        <span class="text-green-600 font-medium">Database</span>
                        <span class="text-gray-300 mx-2">→</span>
                        <span class="bg-blue-600 text-white rounded-full w-8 h-8 flex items-center justify-center font-semibold">4</span>
                        <span class="text-blue-600 font-medium">Features</span>
                        <span class="text-gray-300 mx-2">→</span>
                        <span class="bg-gray-200 text-gray-500 rounded-full w-8 h-8 flex items-center justify-center">5</span>
                        <span class="text-gray-400">Admin</span>
                    </div>
                </div>

                <h2 class="text-2xl font-semibold text-gray-900 mb-4">Optional Extras</h2>
                <p class="text-gray-600 mb-6">Choose additional capabilities for your project. More can be added later as modules.</p>

                <form method="POST" action="{{ route('installer.features.post') }}">
                    @csrf

                    <div class="space-y-3">
                        <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:border-blue-300">
                            <input type="checkbox" name="realtime" value="1" class="rounded">
                            <div class="ml-3">
                                <span class="font-medium text-gray-900">Real-time</span>
                                <p class="text-sm text-gray-500">WebSockets via Laravel Reverb. Live updates, presence, notifications.</p>
                            </div>
                        </label>

                        <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:border-blue-300">
                            <input type="checkbox" name="ai" value="1" class="rounded">
                            <div class="ml-3">
                                <span class="font-medium text-gray-900">AI Layer</span>
                                <p class="text-sm text-gray-500">Multi-provider AI (Ollama, Claude, GPT). Content generation, SEO, agents.</p>
                            </div>
                        </label>
                    </div>

                    <div class="mt-8 flex items-center justify-between">
                        <a href="{{ route('installer.database') }}" class="text-gray-600 hover:text-gray-900">← Back</a>
                        <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            Continue →
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
