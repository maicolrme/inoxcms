<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>INOX - Database Setup</title>
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
                        <span class="bg-blue-600 text-white rounded-full w-8 h-8 flex items-center justify-center font-semibold">3</span>
                        <span class="text-blue-600 font-medium">Database</span>
                        <span class="text-gray-300 mx-2">→</span>
                        <span class="bg-gray-200 text-gray-500 rounded-full w-8 h-8 flex items-center justify-center">4</span>
                        <span class="text-gray-400">Features</span>
                        <span class="text-gray-300 mx-2">→</span>
                        <span class="bg-gray-200 text-gray-500 rounded-full w-8 h-8 flex items-center justify-center">5</span>
                        <span class="text-gray-400">Admin</span>
                    </div>
                </div>

                <h2 class="text-2xl font-semibold text-gray-900 mb-4">Database Configuration</h2>
                <p class="text-gray-600 mb-6">Choose your database driver. SQLite works out of the box with zero configuration.</p>

                <form method="POST" action="{{ route('installer.database.post') }}">
                    @csrf

                    <div class="space-y-4">
                        <label class="flex items-start p-4 border rounded-lg cursor-pointer hover:border-blue-300 transition-colors {{ old('driver', 'sqlite') === 'sqlite' ? 'border-blue-500 bg-blue-50' : 'border-gray-200' }}">
                            <input type="radio" name="driver" value="sqlite" class="mt-1" {{ old('driver', 'sqlite') === 'sqlite' ? 'checked' : '' }}>
                            <div class="ml-3">
                                <span class="font-medium text-gray-900">SQLite</span>
                                <p class="text-sm text-gray-500">Zero configuration. File-based. Great for most projects.</p>
                            </div>
                        </label>

                        <label class="flex items-start p-4 border rounded-lg cursor-pointer hover:border-blue-300 transition-colors {{ old('driver') === 'mysql' ? 'border-blue-500 bg-blue-50' : 'border-gray-200' }}">
                            <input type="radio" name="driver" value="mysql" class="mt-1" {{ old('driver') === 'mysql' ? 'checked' : '' }}>
                            <div class="ml-3">
                                <span class="font-medium text-gray-900">MySQL</span>
                                <p class="text-sm text-gray-500">Existing database on your hosting account.</p>
                            </div>
                        </label>
                    </div>

                    @error('driver')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror

                    <div class="mt-8 flex items-center justify-between">
                        <a href="{{ route('installer.type') }}" class="text-gray-600 hover:text-gray-900">← Back</a>
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
