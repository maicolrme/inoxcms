<div>
    <div class="mb-6">
        <h2 class="text-2xl font-semibold text-gray-900">Tags</h2>
        <p class="text-gray-600 mt-1">Label your content with tags.</p>
    </div>

    @if (session('message'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4">
            {{ session('message') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="font-medium text-gray-900 mb-4">{{ $editingId ? 'Edit' : 'New' }} Tag</h3>
            <form wire:submit="save" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" wire:model="name"
                           class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Slug
                        <label class="text-xs text-gray-400 ml-2">
                            <input type="checkbox" wire:model="autoSlug" checked class="rounded"> Auto
                        </label>
                    </label>
                    <input type="text" wire:model="slug"
                           class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('slug') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        {{ $editingId ? 'Update' : 'Create' }}
                    </button>
                    @if ($editingId)
                        <button type="button" wire:click="cancelEdit"
                                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                    @endif
                </div>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="font-medium text-gray-900 mb-4">All Tags</h3>
            <div class="flex flex-wrap gap-2">
                @forelse ($tags as $tag)
                    <div class="inline-flex items-center gap-2 bg-gray-100 px-3 py-1.5 rounded-full">
                        <span class="text-sm text-gray-700">{{ $tag->name }}</span>
                        <button wire:click="startEdit({{ $tag->id }})"
                                class="text-xs text-blue-600 hover:text-blue-800">Edit</button>
                        <button wire:click="delete({{ $tag->id }})"
                                wire:confirm="Delete this tag?"
                                class="text-xs text-red-600 hover:text-red-800">×</button>
                    </div>
                @empty
                    <p class="text-gray-500 text-sm">No tags yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
