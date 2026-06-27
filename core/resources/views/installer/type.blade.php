<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>INOX - Project Type</title>
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
                        <span class="bg-blue-600 text-white rounded-full w-8 h-8 flex items-center justify-center font-semibold">2</span>
                        <span class="text-blue-600 font-medium">Type</span>
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

                <h2 class="text-2xl font-semibold text-gray-900 mb-4">What are you building?</h2>
                <p class="text-gray-600 mb-6">Choose the project type. Inox will configure itself with the right modules and features automatically.</p>

                <form method="POST" action="{{ route('installer.type.post') }}">
                    @csrf

                    <div class="space-y-4">
                        <label class="flex items-start p-4 border rounded-lg cursor-pointer hover:border-blue-300 transition-colors {{ old('type') === 'website' ? 'border-blue-500 bg-blue-50' : 'border-gray-200' }}">
                            <input type="radio" name="type" value="website" class="mt-1" {{ old('type') === 'website' ? 'checked' : '' }} checked>
                            <div class="ml-3">
                                <span class="font-medium text-gray-900">Website / Blog / CMS</span>
                                <p class="text-sm text-gray-500 mt-1">Classic CMS with pages, posts, themes, and visual builder.</p>
                            </div>
                        </label>

                        <label class="flex items-start p-4 border rounded-lg cursor-pointer hover:border-blue-300 transition-colors {{ old('type') === 'ecommerce' ? 'border-blue-500 bg-blue-50' : 'border-gray-200' }}">
                            <input type="radio" name="type" value="ecommerce" class="mt-1" {{ old('type') === 'ecommerce' ? 'checked' : '' }}>
                            <div class="ml-3">
                                <span class="font-medium text-gray-900">E-commerce</span>
                                <p class="text-sm text-gray-500 mt-1">Products, cart, orders, inventory, payments.</p>
                            </div>
                        </label>

                        <label class="flex items-start p-4 border rounded-lg cursor-pointer hover:border-blue-300 transition-colors {{ old('type') === 'api' ? 'border-blue-500 bg-blue-50' : 'border-gray-200' }}">
                            <input type="radio" name="type" value="api" class="mt-1" {{ old('type') === 'api' ? 'checked' : '' }}>
                            <div class="ml-3">
                                <span class="font-medium text-gray-900">API / Headless Backend</span>
                                <p class="text-sm text-gray-500 mt-1">REST API with auth. No frontend. Visual Schema Builder active.</p>
                            </div>
                        </label>
                    </div>

                    @error('type')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror

                    <div class="mt-8 flex items-center justify-between">
                        <a href="{{ route('installer.welcome') }}" class="text-gray-600 hover:text-gray-900">← Back</a>
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
