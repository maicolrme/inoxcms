<div>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-semibold text-gray-900">Modules</h2>
            <p class="text-gray-600 mt-1">Install, activate, and manage your modules.</p>
        </div>
    </div>

    @if (session('message'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4">{{ session('message') }}</div>
    @endif
    @if (session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4">{{ session('error') }}</div>
    @endif

    @if ($installing)
        <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-lg mb-4 flex items-center gap-3">
            <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
            {{ $installStatus ?: 'Installing module...' }}
        </div>
    @endif

    <div class="flex gap-1 mb-6 border-b border-gray-200">
        <button wire:click="$set('tab', 'installed')"
                class="px-4 py-2.5 text-sm font-medium rounded-t-lg -mb-px border border-transparent
                {{ $tab === 'installed' ? 'text-blue-700 bg-white border-gray-200 border-b-white' : 'text-gray-500 hover:text-gray-700' }}">
            Installed ({{ count($modules) }})
        </button>
        <button wire:click="$set('tab', 'browse')"
                class="px-4 py-2.5 text-sm font-medium rounded-t-lg -mb-px border border-transparent
                {{ $tab === 'browse' ? 'text-blue-700 bg-white border-gray-200 border-b-white' : 'text-gray-500 hover:text-gray-700' }}">
            Browse Marketplace
        </button>
    </div>

    @if ($tab === 'installed')
        <div class="mb-4 flex items-center gap-3 flex-wrap">
            <div class="flex-1 min-w-[200px]">
                <input type="text" wire:model.live.debounce="search" placeholder="Search installed modules..."
                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
            </div>
            <button wire:click="refresh" class="px-3 py-2 text-sm border border-gray-300 rounded-lg hover:bg-gray-50">
                Refresh
            </button>
            <button wire:click="activateAll" wire:confirm="Activate all modules?"
                    class="px-3 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Activate All
            </button>
            <button wire:click="deactivateAll" wire:confirm="Deactivate all modules?"
                    class="px-3 py-2 text-sm border border-red-300 text-red-700 rounded-lg hover:bg-red-50">
                Deactivate All
            </button>
        </div>

        @php $filtered = $this->filtered_modules; @endphp

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse ($filtered as $name => $module)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 flex flex-col {{ ($module['active'] ?? false) ? '' : 'opacity-75' }}">
                    <div class="flex items-start justify-between mb-3">
                        <div class="min-w-0 flex-1">
                            <h3 class="font-medium text-gray-900 truncate">{{ $module['title'] ?? $module['name'] ?? $name }}</h3>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-xs text-gray-400">{{ $module['vendor'] ?? '?' }}/{{ $module['name'] ?? $name }}</span>
                                <span class="text-xs text-gray-400">v{{ $module['version'] ?? '0.1.0' }}</span>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium shrink-0
                            {{ ($module['active'] ?? false) ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                            {{ ($module['active'] ?? false) ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-600 flex-1 line-clamp-2">{{ $module['description'] ?? 'No description.' }}</p>
                    <div class="mt-4 flex items-center gap-2 pt-3 border-t border-gray-100">
                        <button wire:click="toggle('{{ $name }}')"
                                class="px-3 py-1.5 text-sm rounded-lg font-medium transition-colors
                                {{ ($module['active'] ?? false) ? 'bg-red-50 text-red-700 hover:bg-red-100' : 'bg-blue-50 text-blue-700 hover:bg-blue-100' }}">
                            {{ ($module['active'] ?? false) ? 'Deactivate' : 'Activate' }}
                        </button>
                        <button wire:click="showDetails('{{ $name }}')"
                                class="px-3 py-1.5 text-sm text-gray-600 hover:text-gray-900 rounded-lg hover:bg-gray-100">
                            Details
                        </button>
                        <button wire:click="deleteModule('{{ $name }}')" wire:confirm="Delete module '{{ $name }}'? This cannot be undone."
                                class="px-3 py-1.5 text-sm text-red-600 hover:text-red-800 rounded-lg hover:bg-red-50 ml-auto">
                            Delete
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12 text-gray-500 bg-white rounded-xl border border-gray-200">
                    <p class="text-lg">{{ $search ? 'No modules match your search.' : 'No modules found.' }}</p>
                    <p class="text-sm mt-1">Place modules in the <code class="bg-gray-100 px-1 rounded">modules/</code> directory or install from the Marketplace.</p>
                </div>
            @endforelse
        </div>
    @endif

    @if ($tab === 'browse')
        <div class="mb-6 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="font-medium text-gray-900 mb-4">Install New Module</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Upload ZIP file</label>
                    <form wire:submit="installFromUpload" class="space-y-3">
                        <input type="file" wire:model="upload" accept=".zip"
                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        @error('upload') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700" wire:loading.attr="disabled">
                            Upload & Install
                        </button>
                    </form>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Install from URL</label>
                    <form wire:submit="installFromUrl" class="space-y-3">
                        <input type="url" wire:model="installUrl" placeholder="https://modules.inox.ai/inox-seo.zip"
                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        @error('installUrl') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700" wire:loading.attr="disabled">
                            Download & Install
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="mb-4">
            <input type="text" wire:model.live.debounce="registrySearch" placeholder="Search marketplace..."
                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
        </div>

        @php $filteredRegistry = $this->filtered_registry; @endphp

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse ($filteredRegistry as $module)
                @php $installed = $this->isModuleInstalledFromRegistry($module['name']); @endphp
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 flex flex-col {{ $installed ? 'border-green-200' : '' }}">
                    <div class="flex items-start justify-between mb-3">
                        <div>
                            <h3 class="font-medium text-gray-900">{{ $module['title'] ?? $module['name'] }}</h3>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-xs text-gray-400">{{ $module['vendor'] }}/{{ $module['name'] }}</span>
                                <span class="text-xs text-gray-400">v{{ $module['version'] }}</span>
                            </div>
                        </div>
                        @if ($installed)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                Installed
                            </span>
                        @endif
                    </div>
                    <p class="text-sm text-gray-600 flex-1 line-clamp-3">{{ $module['description'] }}</p>
                    <div class="mt-4 pt-3 border-t border-gray-100 flex items-center gap-2">
                        @if ($installed)
                            <button wire:click="toggle('{{ $module['name'] }}')"
                                    class="px-3 py-1.5 text-sm rounded-lg font-medium {{ isset($this->modules[$module['name']]) && ($this->modules[$module['name']]['active'] ?? false) ? 'bg-red-50 text-red-700 hover:bg-red-100' : 'bg-blue-50 text-blue-700 hover:bg-blue-100' }}">
                                {{ isset($this->modules[$module['name']]) && ($this->modules[$module['name']]['active'] ?? false) ? 'Deactivate' : 'Activate' }}
                            </button>
                        @elseif ($module['download_url'])
                            <button wire:click="installFromRegistry('{{ $module['download_url'] }}')"
                                    class="px-3 py-1.5 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                Install
                            </button>
                        @else
                            <span class="text-xs text-gray-400 italic">Coming soon</span>
                        @endif
                        @if (!empty($module['requirements']['php']))
                            <span class="text-xs text-gray-400 ml-auto">PHP {{ $module['requirements']['php'] }}</span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12 text-gray-500 bg-white rounded-xl border border-gray-200">
                    <p class="text-lg">{{ $registrySearch ? 'No modules match your search.' : 'No modules available in the marketplace.' }}</p>
                </div>
            @endforelse
        </div>
    @endif

    @if ($selectedModule)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50" wire:click.self="closeDetails">
            <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-lg mx-4 max-h-[80vh] overflow-y-auto">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">{{ $selectedModule['title'] ?? $selectedModule['name'] ?? 'Module' }}</h3>
                        <p class="text-sm text-gray-400">v{{ $selectedModule['version'] ?? '0.1.0' }}</p>
                    </div>
                    <button wire:click="closeDetails" class="text-gray-400 hover:text-gray-600 text-xl leading-none">&times;</button>
                </div>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Name</dt>
                        <dd class="text-gray-900 font-medium">{{ $selectedModule['name'] ?? '-' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Vendor</dt>
                        <dd class="text-gray-900">{{ $selectedModule['vendor'] ?? '-' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Version</dt>
                        <dd class="text-gray-900">{{ $selectedModule['version'] ?? '-' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Status</dt>
                        <dd class="text-gray-900">{{ ($selectedModule['active'] ?? false) ? 'Active' : 'Inactive' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Provider</dt>
                        <dd class="text-gray-900 font-mono text-xs">{{ $selectedModule['provider'] ?? '-' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Path</dt>
                        <dd class="text-gray-700 font-mono text-xs break-all max-w-[300px]">{{ $selectedModule['path'] ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 mb-1">Description</dt>
                        <dd class="text-gray-700">{{ $selectedModule['description'] ?? 'No description.' }}</dd>
                    </div>
                </dl>
                <div class="mt-6 flex gap-2">
                    <button wire:click="toggle('{{ $selectedModule['name_key'] ?? $selectedModule['name'] }}')"
                            class="px-4 py-2 text-sm font-medium rounded-lg transition-colors
                            {{ ($selectedModule['active'] ?? false) ? 'bg-red-50 text-red-700 hover:bg-red-100' : 'bg-blue-50 text-blue-700 hover:bg-blue-100' }}">
                        {{ ($selectedModule['active'] ?? false) ? 'Deactivate' : 'Activate' }}
                    </button>
                    <button wire:click="closeDetails" class="px-4 py-2 text-sm border border-gray-300 rounded-lg hover:bg-gray-50">
                        Close
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
