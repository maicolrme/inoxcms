@extends('layout')

@section('title', 'Archive')

@section('content')
    @php $primary = theme('primary_color', '#3b82f6'); @endphp
    <div class="max-w-4xl mx-auto px-4 py-12">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">{{ $title ?? 'Archive' }}</h1>

        <div class="space-y-8">
            @forelse (($posts ?? []) as $post)
                <article class="border-b border-gray-100 pb-8">
                    <h2 class="text-xl font-semibold">
                        <a href="{{ url('/blog/' . $post->slug) }}" class="text-gray-900 hover:underline" style="color: {{ $primary }}">
                            {{ $post->title }}
                        </a>
                    </h2>
                    @if (isset($post->excerpt))
                        <p class="mt-2 text-gray-600">{{ $post->excerpt }}</p>
                    @endif
                    <div class="mt-3 text-sm text-gray-500">
                        @if (isset($post->published_at))
                            <time datetime="{{ $post->published_at }}">{{ \Carbon\Carbon::parse($post->published_at)->format('M j, Y') }}</time>
                        @endif
                    </div>
                </article>
            @empty
                <p class="text-gray-500">No posts found.</p>
            @endforelse
        </div>

        @if (isset($posts) && method_exists($posts, 'links'))
            <div class="mt-8">
                {{ $posts->links() }}
            </div>
        @endif
    </div>
@endsection
