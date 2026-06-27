{{-- name: Full Width --}}
{{-- description: Full-width page layout without content container restrictions --}}

@extends('layouts.public')

@section('title', $page->title)

@section('content')
    <article>
        <div class="px-4 py-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-8 text-center">{{ $page->title }}</h1>

            @if ($page->excerpt)
                <p class="text-lg text-gray-600 text-center max-w-2xl mx-auto mb-8">{{ $page->excerpt }}</p>
            @endif

            <div class="prose max-w-none">
                {!! $page->content !!}
            </div>

            @if ($page->children->isNotEmpty())
                <div class="mt-12 pt-8 border-t border-gray-200 max-w-4xl mx-auto">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Subpages</h2>
                    <ul class="space-y-2">
                        @foreach ($page->children as $child)
                            <li>
                                <a href="/{{ $child->slug }}" class="text-blue-600 hover:text-blue-800">
                                    {{ $child->title }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </article>
@endsection
