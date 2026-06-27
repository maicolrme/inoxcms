@extends('layouts.public')

@section('title', 'Blog')

@section('content')
    <div class="max-w-4xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Blog</h1>

        @forelse ($posts as $post)
            <article class="mb-8 pb-8 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">
                    <a href="{{ route('blog.show', $post->slug) }}" class="hover:text-blue-600">{{ $post->title }}</a>
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    {{ $post->published_at->format('M d, Y') }} ·
                    {{ $post->author?->name }}
                </p>
                @if ($post->excerpt)
                    <p class="mt-3 text-gray-600">{{ $post->excerpt }}</p>
                @endif
                <div class="mt-3 flex gap-2">
                    @foreach ($post->categories as $category)
                        <span class="inline-block px-2 py-0.5 bg-gray-100 rounded text-xs text-gray-600">{{ $category->name }}</span>
                    @endforeach
                </div>
            </article>
        @empty
            <p class="text-gray-500">No posts published yet.</p>
        @endforelse

        <div class="mt-8">
            {{ $posts->links() }}
        </div>
    </div>
@endsection
