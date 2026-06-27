<div>
    <div class="mb-6">
        <h2 class="text-2xl font-semibold text-gray-900">Settings</h2>
        <p class="text-gray-600 mt-1">Configure your system.</p>
    </div>

    @if (session('message'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4">{{ session('message') }}</div>
    @endif

    @php
        $engine = app('module.engine');
        $builtInTabs = ['general' => 'General', 'content' => 'Content', 'cache' => 'Cache', 'features' => 'Features', 'mail' => 'Mail'];
        $moduleTabs = collect($engine->getSettingsTabs())->pluck('label', 'key')->toArray();
        $moduleComponents = collect($engine->getSettingsComponents())->pluck('label', 'key')->toArray();
        $allTabs = array_merge($builtInTabs, $moduleTabs, $moduleComponents);
        $isModuleTab = array_key_exists($tab, $moduleTabs);
        $isComponentTab = array_key_exists($tab, $moduleComponents);
        $tabComponent = $engine->getSettingsComponentForTab($tab);
    @endphp

    <div class="flex gap-1 mb-6 border-b border-gray-200 flex-wrap">
        @foreach ($allTabs as $key => $label)
            <button wire:click="switchTab('{{ $key }}')"
                    class="px-4 py-2.5 text-sm font-medium rounded-t-lg -mb-px border border-transparent
                    {{ $tab === $key ? 'text-blue-700 bg-white border-gray-200 border-b-white' : 'text-gray-500 hover:text-gray-700' }}">
                {{ $label }}
            </button>
        @endforeach
    </div>

    @if ($isComponentTab && $tabComponent)
        @livewire($tabComponent, key($tab))
    @elseif ($isModuleTab)
        {!! $engine->renderSettingsTab($tab) !!}
    @else
        <form wire:submit="save">
            @if ($tab === 'general')
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-4">
                    <h3 class="font-medium text-gray-900">General Settings</h3>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Site Name</label>
                        <input type="text" wire:model="siteName" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tagline</label>
                        <input type="text" wire:model="siteDescription" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Project Type</label>
                        <select wire:model="projectType" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="website">Website / Blog</option>
                            <option value="ecommerce">E-commerce</option>
                            <option value="api">API / Headless</option>
                        </select>
                    </div>
                </div>
            @endif

            @if ($tab === 'content')
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-4">
                    <h3 class="font-medium text-gray-900">Content Settings</h3>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Default Post Status</label>
                        <select wire:model="defaultStatus" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Posts Per Page</label>
                        <input type="number" wire:model="perPage" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="inline-flex items-center">
                            <input type="checkbox" wire:model="enableExcerpts" class="rounded border-gray-300">
                            <span class="ml-2 text-sm text-gray-700">Enable excerpts</span>
                        </label>
                    </div>
                </div>
            @endif

            @if ($tab === 'cache')
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-4">
                    <h3 class="font-medium text-gray-900">Cache Settings</h3>
                    <div class="space-y-3">
                        <label class="inline-flex items-center"><input type="checkbox" wire:model="pageCache" class="rounded border-gray-300"> <span class="ml-2 text-sm text-gray-700">Page cache</span></label><br>
                        <label class="inline-flex items-center"><input type="checkbox" wire:model="objectCache" class="rounded border-gray-300"> <span class="ml-2 text-sm text-gray-700">Object cache</span></label><br>
                        <label class="inline-flex items-center"><input type="checkbox" wire:model="fragmentCache" class="rounded border-gray-300"> <span class="ml-2 text-sm text-gray-700">Fragment cache</span></label>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Cache Driver</label>
                        <select wire:model="cacheDriver" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="file">File</option>
                            <option value="database">Database</option>
                            <option value="redis">Redis</option>
                        </select>
                    </div>
                </div>
            @endif

            @if ($tab === 'features')
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-4">
                    <h3 class="font-medium text-gray-900">Feature Toggles</h3>
                    <div class="space-y-4">
                        <label class="flex items-center justify-between p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                            <div>
                                <p class="font-medium text-gray-900">Realtime</p>
                                <p class="text-sm text-gray-500">Enable real-time updates via server-sent events.</p>
                            </div>
                            <button type="button" wire:click="$toggle('featureRealtime')"
                                    class="relative w-11 h-6 rounded-full transition-colors {{ $featureRealtime ? 'bg-blue-600' : 'bg-gray-300' }}">
                                <span class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform {{ $featureRealtime ? 'translate-x-5' : '' }}"></span>
                            </button>
                        </label>
                        <label class="flex items-center justify-between p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                            <div>
                                <p class="font-medium text-gray-900">AI Layer</p>
                                <p class="text-sm text-gray-500">Enable AI-powered features (content generation, etc.).</p>
                            </div>
                            <button type="button" wire:click="$toggle('featureAi')"
                                    class="relative w-11 h-6 rounded-full transition-colors {{ $featureAi ? 'bg-blue-600' : 'bg-gray-300' }}">
                                <span class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform {{ $featureAi ? 'translate-x-5' : '' }}"></span>
                            </button>
                        </label>
                    </div>
                </div>
            @endif

            @if ($tab === 'mail')
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-4">
                    <h3 class="font-medium text-gray-900">Mail Settings</h3>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Mail Driver</label>
                        <select wire:model="mailDriver" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="log">Log (development)</option>
                            <option value="smtp">SMTP</option>
                            <option value="sendmail">Sendmail</option>
                            <option value="ses">Amazon SES</option>
                            <option value="postmark">Postmark</option>
                            <option value="mailgun">Mailgun</option>
                        </select>
                    </div>
                    @if ($mailDriver === 'smtp')
                        <div class="grid grid-cols-2 gap-4">
                            <div><label class="block text-sm font-medium text-gray-700">Host</label><input type="text" wire:model="mailHost" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></div>
                            <div><label class="block text-sm font-medium text-gray-700">Port</label><input type="number" wire:model="mailPort" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></div>
                            <div><label class="block text-sm font-medium text-gray-700">Username</label><input type="text" wire:model="mailUsername" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></div>
                            <div><label class="block text-sm font-medium text-gray-700">Password</label><input type="password" wire:model="mailPassword" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></div>
                            <div><label class="block text-sm font-medium text-gray-700">Encryption</label>
                                <select wire:model="mailEncryption" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="tls">TLS</option>
                                    <option value="ssl">SSL</option>
                                    <option value="null">None</option>
                                </select>
                            </div>
                        </div>
                    @endif
                    <div class="grid grid-cols-2 gap-4">
                        <div><label class="block text-sm font-medium text-gray-700">From Address</label><input type="email" wire:model="mailFromAddress" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></div>
                        <div><label class="block text-sm font-medium text-gray-700">From Name</label><input type="text" wire:model="mailFromName" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></div>
                    </div>
                </div>
            @endif

            <div class="mt-6 flex justify-end">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    Save {{ ucfirst($tab) }} Settings
                </button>
            </div>
        </form>
    @endif
</div>
