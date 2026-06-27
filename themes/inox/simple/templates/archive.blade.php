@extends('layout')

@section('title', 'Archive')

@section('content')
    <div class="max-w-4xl mx-auto px-4 py-12">
        <h1 class="text-3xl font-bold text-gray-900">Archive</h1>
        <p class="mt-2 text-gray-500">All published posts.</p>
        @php $posts = \App\Models\Post::posts()->published()->orderBy('published_at', 'desc')->paginate(10); @endphp
        <div class="mt-8 space-y-4">
            @forelse ($posts as $post)
                <article class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                    <h2 class="font-semibold"><a href="{{ route('blog.show', $post->slug) }}" class="hover:text-blue-600">{{ $post->title }}</a></h2>
                    <p class="text-xs text-gray-500 mt-1">{{ $post->published_at->format('M d, Y') }}</p>
                </article>
            @empty
                <p class="text-gray-500 text-center py-8">No posts yet.</p>
            @endforelse
        </div>
        <div class="mt-6">{{ $posts->links() }}</div>
    </div>
@endsection
