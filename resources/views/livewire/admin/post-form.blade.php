<div>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-semibold text-gray-900">{{ $postId ? 'Edit' : 'New' }} {{ ucfirst($type) }}</h2>
            <p class="text-gray-600 mt-1">{{ $postId ? 'Update your content.' : 'Create new content.' }}</p>
        </div>
        @php $backRoute = $type === 'page' ? 'admin.pages.index' : 'admin.posts.index'; @endphp
        <a href="{{ route($backRoute) }}" wire:navigate
           class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
            ← Back
        </a>
    </div>

    @if (session('message'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit="save" class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-4">
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                <input type="text" id="title" wire:model="title"
                       class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-lg">
                @error('title') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="slug" class="block text-sm font-medium text-gray-700">
                    Slug
                    <label class="text-xs text-gray-400 ml-2">
                        <input type="checkbox" wire:model="autoSlug" checked class="rounded"> Auto
                    </label>
                </label>
                <input type="text" id="slug" wire:model="slug"
                       class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                @error('slug') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div wire:ignore>
                <label class="block text-sm font-medium text-gray-700">Content</label>
                <div id="content-editor" class="mt-1 block w-full rounded-lg border border-gray-300" style="min-height: 400px;">{!! $content !!}</div>
                <textarea id="content-hidden" class="hidden">{{ $content }}</textarea>
                @error('content') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="excerpt" class="block text-sm font-medium text-gray-700">Excerpt</label>
                <textarea id="excerpt" wire:model="excerpt" rows="3"
                          class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                @error('excerpt') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-4">
                <h3 class="font-medium text-gray-900">Settings</h3>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    <select wire:model="status" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="draft">Draft</option>
                        <option value="published">Published</option>
                        <option value="archived">Archived</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Type</label>
                    <select wire:model="type" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="post">Post</option>
                        <option value="page">Page</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Template</label>
                    <select wire:model="template" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">— Default —</option>
                        @foreach ($this->templates as $tpl)
                            <option value="{{ $tpl['value'] }}"
                                    title="{{ $tpl['description'] ?? '' }}">
                                {{ $tpl['label'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                @if ($type === 'page')
                <div>
                    <label class="block text-sm font-medium text-gray-700">Parent Page</label>
                    <select wire:model="parentId" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">— No parent —</option>
                        @foreach ($parents as $parent)
                            <option value="{{ $parent->id }}">{{ $parent->title }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-4">
                <h3 class="font-medium text-gray-900">Taxonomy</h3>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Categories</label>
                    <div class="mt-1 space-y-2 max-h-40 overflow-y-auto">
                        @foreach ($categories as $category)
                            <label class="flex items-center">
                                <input type="checkbox" wire:model="selectedCategories" value="{{ $category->id }}"
                                       class="rounded border-gray-300">
                                <span class="ml-2 text-sm text-gray-700">{{ $category->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Tags</label>
                    <div class="mt-1 flex flex-wrap gap-2">
                        @foreach ($tags as $tag)
                            <label class="inline-flex items-center">
                                <input type="checkbox" wire:model="selectedTags" value="{{ $tag->id }}"
                                       class="rounded border-gray-300">
                                <span class="ml-1 text-sm text-gray-700">{{ $tag->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            @php $cancelRoute = $type === 'page' ? 'admin.pages.index' : 'admin.posts.index'; @endphp
            <a href="{{ route($cancelRoute) }}" wire:navigate
               class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                {{ $postId ? 'Update' : 'Create' }}
            </button>
        </div>
    </form>
</div>

@script
<script>
    let quillInstance = null;

    function initQuill() {
        const el = document.getElementById('content-editor');
        if (!el || typeof Quill === 'undefined' || quillInstance) return;

        quillInstance = new Quill(el, {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ header: [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ list: 'ordered' }, { list: 'bullet' }],
                    ['link', 'image'],
                    ['blockquote', 'code-block'],
                    ['clean']
                ]
            },
            placeholder: 'Start writing...',
        });

        quillInstance.on('text-change', () => {
            $wire.set('content', quillInstance.root.innerHTML);
        });
    }

    function destroyQuill() {
        if (quillInstance) {
            quillInstance.destroy();
            quillInstance = null;
        }
    }

    document.addEventListener('livewire:navigated', () => {
        destroyQuill();
        initQuill();
    });
</script>
@endscript
