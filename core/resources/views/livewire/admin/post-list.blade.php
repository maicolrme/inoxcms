<div>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-semibold text-gray-900">Posts</h2>
            <p class="text-gray-600 mt-1">Manage your content.</p>
        </div>
        <a href="{{ route('admin.posts.create') }}" wire:navigate
           class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
            New Post
        </a>
    </div>

    @if (session('message'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4">
            {{ session('message') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-4 border-b border-gray-200">
            <div class="flex gap-4">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search posts..."
                       class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">

                <select wire:model.live="status" class="rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">All status</option>
                    <option value="draft">Draft</option>
                    <option value="published">Published</option>
                    <option value="archived">Archived</option>
                </select>

                <select wire:model.live="type" class="rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">All types</option>
                    <option value="post">Post</option>
                    <option value="page">Page</option>
                </select>
            </div>
        </div>

        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-200 text-left text-sm font-medium text-gray-500">
                    <th wire:click="sortBy('title')" class="px-6 py-3 cursor-pointer hover:text-gray-700">
                        Title @if ($sortField === 'title') {{ $sortDirection === 'asc' ? '↑' : '↓' }} @endif
                    </th>
                    <th class="px-6 py-3">Status</th>
                    <th wire:click="sortBy('type')" class="px-6 py-3 cursor-pointer hover:text-gray-700">
                        Type @if ($sortField === 'type') {{ $sortDirection === 'asc' ? '↑' : '↓' }} @endif
                    </th>
                    <th class="px-6 py-3">Author</th>
                    <th wire:click="sortBy('created_at')" class="px-6 py-3 cursor-pointer hover:text-gray-700">
                        Created @if ($sortField === 'created_at') {{ $sortDirection === 'asc' ? '↑' : '↓' }} @endif
                    </th>
                    <th class="px-6 py-3">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($posts as $post)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.posts.edit', $post) }}" wire:navigate
                               class="font-medium text-gray-900 hover:text-blue-600">
                                {{ $post->title }}
                            </a>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $post->status === 'published' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $post->status === 'draft' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $post->status === 'archived' ? 'bg-gray-100 text-gray-800' : '' }}">
                                {{ $post->statusLabel() }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ ucfirst($post->type) }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $post->author?->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $post->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.posts.edit', $post) }}" wire:navigate
                                   class="text-sm text-blue-600 hover:text-blue-800">Edit</a>
                                <button wire:click="deletePost({{ $post->id }})"
                                        wire:confirm="Move this post to trash?"
                                        class="text-sm text-red-600 hover:text-red-800">Trash</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <p class="text-lg">No posts found.</p>
                            <a href="{{ route('admin.posts.create') }}" wire:navigate
                               class="mt-2 inline-block text-blue-600 hover:text-blue-800">Create your first post →</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="p-4 border-t border-gray-200">
            {{ $posts->links() }}
        </div>
    </div>
</div>
