<div>
    @if ($message)
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4">{{ $message }}</div>
    @endif

    @if ($themeInfo)
        <p class="text-sm text-gray-500 mb-4">{{ $themeInfo['vendor'] }}/{{ $themeInfo['name'] }} v{{ $themeInfo['version'] }} — <a href="{{ route('admin.themes') }}" wire:navigate class="text-blue-600 hover:underline">Manage themes</a></p>
    @endif

    @if (!empty($schema))
        <form wire:submit="save" class="space-y-4">
            @foreach ($schema as $field)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ $field['label'] }}</label>

                    @if ($field['type'] === 'color')
                        <div class="flex items-center gap-3">
                            <input type="color" wire:model="settings.{{ $field['key'] }}" class="w-10 h-10 border border-gray-300 rounded cursor-pointer">
                            <input type="text" wire:model="settings.{{ $field['key'] }}" class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none font-mono">
                        </div>

                    @elseif ($field['type'] === 'select' && isset($field['options']))
                        <select wire:model="settings.{{ $field['key'] }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                            @foreach ($field['options'] as $option)
                                <option value="{{ $option }}">{{ $option }}</option>
                            @endforeach
                        </select>

                    @elseif ($field['type'] === 'boolean')
                        <label class="inline-flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" wire:model="settings.{{ $field['key'] }}" value="1" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm text-gray-600">Enabled</span>
                        </label>

                    @elseif ($field['type'] === 'textarea')
                        <textarea wire:model="settings.{{ $field['key'] }}" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"></textarea>

                    @elseif ($field['type'] === 'media')
                        <div class="flex items-center gap-2">
                            <input type="text" wire:model="settings.{{ $field['key'] }}" placeholder="/storage/path/to/file" class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                            <span class="text-xs text-gray-400">Path or URL</span>
                        </div>

                    @else
                        <input type="text" wire:model="settings.{{ $field['key'] }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    @endif
                </div>
            @endforeach

            <div class="flex justify-end pt-4 border-t">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">Save Theme Settings</button>
            </div>
        </form>
    @else
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-sm text-yellow-800">
            No settings defined for this theme. Add a <code class="font-mono text-xs bg-yellow-100 px-1 rounded">settings</code> array in <code class="font-mono text-xs bg-yellow-100 px-1 rounded">theme.json</code> to expose configuration options.
        </div>
    @endif
</div>
