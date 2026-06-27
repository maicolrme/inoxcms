@extends('layout')

@section('title', $post->title ?? 'Post')

@section('content')
    @php $primary = theme('primary_color', '#3b82f6'); @endphp
    <article class="max-w-4xl mx-auto px-4 py-12">
        <header class="mb-8">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900">{{ $post->title ?? '' }}</h1>
            <div class="mt-4 flex items-center gap-4 text-sm text-gray-500">
                @if (isset($post->author))
                    <span>{{ $post->author->name }}</span>
                @endif
                @if (isset($post->published_at))
                    <span>&middot;</span>
                    <time datetime="{{ $post->published_at }}">{{ \Carbon\Carbon::parse($post->published_at)->format('M j, Y') }}</time>
                @endif
            </div>
            @if (isset($post->categories) && $post->categories->count())
                <div class="mt-3 flex flex-wrap gap-2">
                    @foreach ($post->categories as $cat)
                        <span class="inline-block px-2.5 py-0.5 text-xs font-medium rounded-full text-white" style="background-color: {{ $primary }}">{{ $cat->name }}</span>
                    @endforeach
                </div>
            @endif
        </header>
        <div class="prose prose-gray max-w-none">
            {!! $post->body ?? '' !!}
        </div>
    </article>
@endsection
