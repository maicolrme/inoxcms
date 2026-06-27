@extends('layout')

@section('title', $post->title)

@section('content')
    <div class="max-w-4xl mx-auto px-4 py-12">
        <a href="{{ url('/blog') }}" class="text-sm text-gray-500 hover:text-gray-700">&larr; Back to blog</a>
        <article class="mt-6">
            <h1 class="text-3xl font-bold text-gray-900">{{ $post->title }}</h1>
            <p class="text-sm text-gray-500 mt-2">{{ $post->published_at?->format('M d, Y') }} · {{ $post->author?->name }}</p>
            <div class="mt-8 prose prose-gray max-w-none">
                {!! $post->content !!}
            </div>
        </article>
    </div>
@endsection
