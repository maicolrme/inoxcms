@extends('layouts.public')

@section('title', $post->title)

@section('content')
    <div class="max-w-4xl mx-auto px-4 py-8">
        <a href="{{ route('blog.index') }}" class="text-blue-600 hover:text-blue-800">&larr; Back to blog</a>

        <article class="mt-6">
            <h1 class="text-3xl font-bold text-gray-900">{{ $post->title }}</h1>
            <p class="text-sm text-gray-500 mt-2">
                {{ $post->published_at->format('M d, Y') }} ·
                {{ $post->author?->name }}
            </p>

            @if ($post->categories->isNotEmpty())
                <div class="mt-3 flex gap-2">
                    @foreach ($post->categories as $category)
                        <span class="inline-block px-2 py-0.5 bg-gray-100 rounded text-xs text-gray-600">{{ $category->name }}</span>
                    @endforeach
                </div>
            @endif

            <div class="mt-8 prose max-w-none">
                {!! $post->content !!}
            </div>

            @if ($post->tags->isNotEmpty())
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <p class="text-sm font-medium text-gray-700">Tags:</p>
                    <div class="mt-2 flex flex-wrap gap-2">
                        @foreach ($post->tags as $tag)
                            <span class="inline-block px-3 py-1 bg-gray-100 rounded-full text-sm text-gray-600">{{ $tag->name }}</span>
                        @endforeach
                    </div>
                </div>
            @endif
        </article>
    </div>
@endsection
