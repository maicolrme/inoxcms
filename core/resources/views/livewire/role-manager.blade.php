<div>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-semibold text-gray-900">Role Manager</h2>
            <p class="text-gray-600 mt-1">Create and manage user roles with granular permissions.</p>
        </div>
        <button wire:click="create" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
            + New Role
        </button>
    </div>

    @if ($showForm)
        <div class="fixed inset-0 bg-black/40 z-40 flex items-center justify-center" wire:click.self="$set('showForm', false)">
            <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto p-6 z-50">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ $editingRoleId ? 'Edit Role' : 'New Role' }}</h3>

                <form wire:submit="save" class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                            <input type="text" wire:model="name" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                            <input type="text" wire:model="slug" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                            @error('slug') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea wire:model="description" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Permissions</label>
                        @forelse ($permissionGroups as $group => $perms)
                            <div class="mb-4">
                                <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">{{ $group ?: 'General' }}</h4>
                                <div class="flex flex-wrap gap-3">
                                    @foreach ($perms as $perm)
                                        <label class="inline-flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                                            <input type="checkbox" wire:model="selectedPermissions" value="{{ $perm->id }}" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                            {{ $perm->name }}
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-400">No permissions defined yet. Run the seeder to populate permissions.</p>
                        @endforelse
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t">
                        <button type="button" wire:click="$set('showForm', false)" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800 transition-colors">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">Save</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200">
                    <th class="text-left px-4 py-3 font-medium text-gray-500">Role</th>
                    <th class="text-left px-4 py-3 font-medium text-gray-500">Description</th>
                    <th class="text-left px-4 py-3 font-medium text-gray-500">Permissions</th>
                    <th class="text-right px-4 py-3 font-medium text-gray-500">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($roles as $role)
                    <tr class="border-b border-gray-100 last:border-0 hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <span class="font-medium text-gray-900">{{ $role->name }}</span>
                            <span class="text-gray-400 text-xs ml-2">({{ $role->slug }})</span>
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $role->description ?: '—' }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center gap-1 text-xs text-gray-500">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                {{ $role->permissions->count() }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            @if ($role->slug !== 'super-admin')
                                <button wire:click="edit({{ $role->id }})" class="text-blue-600 hover:text-blue-800 text-sm font-medium mr-2">Edit</button>
                                <button wire:click="delete({{ $role->id }})" wire:confirm="Delete this role?" class="text-red-600 hover:text-red-800 text-sm font-medium">Delete</button>
                            @else
                                <span class="text-gray-400 text-sm">Protected</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-8 text-center text-gray-400">No roles defined yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div x-data="{ show: false, msg: '' }"
         x-on:notify.window="msg = $event.detail.message; show = true; setTimeout(() => show = false, 2000)"
         x-show="show"
         x-transition.duration.300ms
         class="fixed bottom-4 right-4 bg-green-600 text-white px-4 py-2 rounded-lg shadow-lg text-sm z-50">
        <span x-text="msg"></span>
    </div>
</div>
