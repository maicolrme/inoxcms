<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>INOX - Admin Account</title>
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
                        <span class="bg-green-600 text-white rounded-full w-8 h-8 flex items-center justify-center font-semibold">✓</span>
                        <span class="text-green-600 font-medium">Features</span>
                        <span class="text-gray-300 mx-2">→</span>
                        <span class="bg-blue-600 text-white rounded-full w-8 h-8 flex items-center justify-center font-semibold">5</span>
                        <span class="text-blue-600 font-medium">Admin</span>
                    </div>
                </div>

                <h2 class="text-2xl font-semibold text-gray-900 mb-4">Create Admin Account</h2>
                <p class="text-gray-600 mb-6">Set up your administrator account.</p>

                <form method="POST" action="{{ route('installer.admin.post') }}">
                    @csrf

                    <div class="space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', 'Admin') }}" required
                                   class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email', 'admin@inox.dev') }}" required
                                   class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('email') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                            <input type="password" name="password" id="password" required minlength="8"
                                   class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('password') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="mt-8 flex items-center justify-between">
                        <a href="{{ route('installer.features') }}" class="text-gray-600 hover:text-gray-900">← Back</a>
                        <a href="{{ route('installer.type') }}" class="text-gray-600 hover:text-gray-900">← Back</a>
                        <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            Complete Installation →
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
