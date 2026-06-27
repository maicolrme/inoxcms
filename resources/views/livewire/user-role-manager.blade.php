<div>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-semibold text-gray-900">User Permissions</h2>
            <p class="text-gray-600 mt-1">Assign roles to users to control their access.</p>
        </div>
        <div class="relative">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search users..." class="w-64 border border-gray-300 rounded-lg pl-9 pr-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
            <svg class="absolute left-2.5 top-2.5 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </div>
    </div>

    @if ($showForm)
        <div class="fixed inset-0 bg-black/40 z-40 flex items-center justify-center" wire:click.self="$set('showForm', false)">
            <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6 z-50">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Assign Roles</h3>

                @php $user = \App\Models\User::find($editingUserId); @endphp
                @if ($user)
                    <p class="text-sm text-gray-600 mb-4">{{ $user->name }} &lt;{{ $user->email }}&gt;</p>
                @endif

                <div class="space-y-2 mb-6">
                    @forelse ($roles as $role)
                        <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 cursor-pointer hover:bg-gray-50 transition-colors">
                            <input type="checkbox" wire:model="selectedRoles" value="{{ $role->id }}" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <div>
                                <span class="text-sm font-medium text-gray-900">{{ $role->name }}</span>
                                @if ($role->description)
                                    <p class="text-xs text-gray-500">{{ $role->description }}</p>
                                @endif
                            </div>
                        </label>
                    @empty
                        <p class="text-sm text-gray-400">No roles available.</p>
                    @endforelse
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" wire:click="$set('showForm', false)" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800 transition-colors">Cancel</button>
                    <button type="button" wire:click="save" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">Save</button>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200">
                    <th class="text-left px-4 py-3 font-medium text-gray-500">User</th>
                    <th class="text-left px-4 py-3 font-medium text-gray-500">Roles</th>
                    <th class="text-right px-4 py-3 font-medium text-gray-500">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr class="border-b border-gray-100 last:border-0 hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <span class="font-medium text-gray-900">{{ $user->name }}</span>
                            <span class="text-gray-400 text-xs ml-2">{{ $user->email }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap gap-1">
                                @forelse ($user->roles as $role)
                                    <span class="inline-block px-2 py-0.5 text-xs font-medium bg-blue-100 text-blue-700 rounded-full">{{ $role->name }}</span>
                                @empty
                                    <span class="text-gray-400 text-xs">No roles</span>
                                @endforelse
                            </div>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <button wire:click="edit({{ $user->id }})" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Assign Roles</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="px-4 py-8 text-center text-gray-400">No users found.</td></tr>
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
