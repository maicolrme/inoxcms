{{-- name: With Sidebar --}}
{{-- description: Two-column layout with subpage navigation in the sidebar --}}

@extends('layouts.public')

@section('title', $page->title)

@section('content')
    <div class="max-w-6xl mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <aside class="lg:col-span-1">
                <nav class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sticky top-8">
                    <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-3">
                        {{ $page->parent?->title ?? 'Pages' }}
                    </h3>
                    <ul class="space-y-1">
                        @php $sidebarPages = $page->parent?->children ?? collect([$page]); @endphp
                        @foreach ($sidebarPages as $child)
                            <li>
                                <a href="/{{ $child->slug }}"
                                   class="block px-3 py-2 rounded-lg text-sm {{ $child->id === $page->id ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                                    {{ $child->title }}
                                </a>
                            </li>
                        @endforeach
                    </ul>

                    @if ($page->children->isNotEmpty())
                        <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mt-6 mb-3">Subpages</h3>
                        <ul class="space-y-1">
                            @foreach ($page->children as $child)
                                <li>
                                    <a href="/{{ $child->slug }}"
                                       class="block px-3 py-2 rounded-lg text-sm text-gray-600 hover:bg-gray-50">
                                        {{ $child->title }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </nav>
            </aside>

            <main class="lg:col-span-3">
                <article>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $page->title }}</h1>

                    @if ($page->excerpt)
                        <p class="mt-4 text-lg text-gray-600">{{ $page->excerpt }}</p>
                    @endif

                    <div class="mt-8 prose max-w-none">
                        {!! $page->content !!}
                    </div>
                </article>
            </main>
        </div>
    </div>
@endsection
