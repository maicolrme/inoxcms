{{-- name: Default --}}
{{-- description: Standard page layout with centered content container --}}

@extends('layouts.public')

@section('title', $page->title)

@section('content')
    <div class="max-w-4xl mx-auto px-4 py-8">
        <article>
            <h1 class="text-3xl font-bold text-gray-900">{{ $page->title }}</h1>

            @if ($page->excerpt)
                <p class="mt-4 text-lg text-gray-600">{{ $page->excerpt }}</p>
            @endif

            <div class="mt-8 prose max-w-none">
                {!! $page->content !!}
            </div>

            @if ($page->children->isNotEmpty())
                <div class="mt-12 pt-8 border-t border-gray-200">
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
        </article>
    </div>
@endsection
