<div>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-semibold text-gray-900">Theme Manager</h2>
            <p class="text-gray-600 mt-1">Manage your site themes.</p>
        </div>
    </div>

    <div class="flex gap-1 mb-6 border-b border-gray-200">
        <button wire:click="$set('tab', 'installed')" class="px-4 py-2.5 text-sm font-medium rounded-t-lg -mb-px border border-transparent {{ $tab === 'installed' ? 'text-blue-700 bg-white border-gray-200 border-b-white' : 'text-gray-500 hover:text-gray-700' }}">
            Installed ({{ count($installedThemes) }})
        </button>
        <button wire:click="$set('tab', 'browse')" class="px-4 py-2.5 text-sm font-medium rounded-t-lg -mb-px border border-transparent {{ $tab === 'browse' ? 'text-blue-700 bg-white border-gray-200 border-b-white' : 'text-gray-500 hover:text-gray-700' }}">
            Browse Marketplace
        </button>
    </div>

    @if ($tab === 'installed')
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse ($installedThemes as $key => $theme)
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden {{ $activeThemeKey === $key ? 'ring-2 ring-blue-500' : '' }}">
                    <div class="h-32 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                        @if ($activeThemeKey === $key)
                            <span class="inline-block px-3 py-1 bg-blue-600 text-white text-xs font-medium rounded-full">Active</span>
                        @else
                            <span class="text-gray-400 text-xs">{{ $theme['vendor'] }}/{{ $theme['name'] }}</span>
                        @endif
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-900">{{ $theme['name'] }}</h3>
                        <p class="text-xs text-gray-500 mt-0.5">by {{ $theme['vendor'] }} · v{{ $theme['version'] }}</p>
                        @if ($theme['description'] ?? false)
                            <p class="text-sm text-gray-600 mt-2">{{ $theme['description'] }}</p>
                        @endif
                        <div class="flex items-center gap-2 mt-4 pt-3 border-t border-gray-100">
                            @if ($activeThemeKey !== $key)
                                <button wire:click="activate('{{ $key }}')" wire:confirm="Activate this theme?" class="px-3 py-1.5 text-xs font-medium bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">Activate</button>
                                <button wire:click="delete('{{ $key }}')" wire:confirm="Delete this theme?" class="px-3 py-1.5 text-xs font-medium text-red-600 border border-red-200 rounded-lg hover:bg-red-50 transition-colors">Delete</button>
                            @else
                                <span class="text-xs text-blue-600 font-medium">Currently active</span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white rounded-xl border border-gray-200 p-8 text-center text-gray-400">
                    No themes installed.
                </div>
            @endforelse
        </div>

    @else
        <div class="mb-4">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search themes..." class="w-full max-w-md border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse ($registryThemes as $key => $theme)
                @php $isInstalled = isset($installedThemes[$key]); @endphp
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden {{ $isInstalled ? 'opacity-75' : '' }}">
                    <div class="h-32 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                        <span class="text-gray-400 text-xs">{{ $theme['vendor'] }}/{{ $theme['name'] }}</span>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-900">{{ $theme['name'] }}</h3>
                        <p class="text-xs text-gray-500 mt-0.5">by {{ $theme['vendor'] }} · v{{ $theme['version'] }}</p>
                        @if ($theme['description'] ?? false)
                            <p class="text-sm text-gray-600 mt-2">{{ $theme['description'] }}</p>
                        @endif
                        <div class="mt-4 pt-3 border-t border-gray-100">
                            @if ($isInstalled)
                                <span class="text-xs text-green-600 font-medium">Installed</span>
                            @else
                                <span class="text-xs text-gray-400">Available in marketplace</span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white rounded-xl border border-gray-200 p-8 text-center text-gray-400">
                    @if ($search)
                        No themes match "{{ $search }}".
                    @else
                        No themes available in the marketplace.
                    @endif
                </div>
            @endforelse
        </div>
    @endif

    <div x-data="{ show: false, msg: '' }"
         x-on:notify.window="msg = $event.detail.message; show = true; setTimeout(() => show = false, 2500)"
         x-show="show"
         x-transition.duration.300ms
         class="fixed bottom-4 right-4 bg-green-600 text-white px-4 py-2 rounded-lg shadow-lg text-sm z-50">
        <span x-text="msg"></span>
    </div>
</div>
