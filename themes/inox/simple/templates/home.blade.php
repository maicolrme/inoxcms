@extends('layout')

@section('title', 'Home')

@section('content')
    @php $primary = theme('primary_color', '#2563eb'); @endphp

    <div class="max-w-4xl mx-auto px-4 py-16 text-center">
        <h1 class="text-4xl font-bold text-gray-900 tracking-tight">{{ config('app.name') }}</h1>
        <p class="mt-3 text-lg text-gray-500">{{ config('app.description', 'A simple site.') }}</p>
    </div>

    @if (theme('show_home_posts', true))
        @php
            $posts = \App\Models\Post::posts()->published()->orderBy('published_at', 'desc')->take(5)->get();
        @endphp
        <div class="max-w-4xl mx-auto px-4 pb-16">
            <h2 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-6">Latest Posts</h2>
            <div class="space-y-6">
                @forelse ($posts as $post)
                    <article class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900">
                            <a href="{{ route('blog.show', $post->slug) }}" class="hover:text-blue-600">
                                {{ $post->title }}
                            </a>
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">{{ $post->published_at->format('M d, Y') }}</p>
                        @if ($post->excerpt)
                            <p class="mt-2 text-gray-600 text-sm">{{ Str::limit($post->excerpt, 200) }}</p>
                        @endif
                    </article>
                @empty
                    <p class="text-gray-500 text-center py-8">No posts yet.</p>
                @endforelse
            </div>
            <div class="mt-8 text-center">
                <a href="{{ url('/blog') }}" class="text-sm font-medium hover:underline" style="color: {{ $primary }}">
                    View all posts →
                </a>
            </div>
        </div>
    @endif
@endsection
