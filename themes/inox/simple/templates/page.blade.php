@extends('layout')

@section('title', $page->title)

@section('content')
    <div class="max-w-4xl mx-auto px-4 py-12">
        <h1 class="text-3xl font-bold text-gray-900">{{ $page->title }}</h1>
        @if ($page->excerpt)
            <p class="mt-4 text-lg text-gray-500">{{ $page->excerpt }}</p>
        @endif
        <div class="mt-8 prose prose-gray max-w-none">
            {!! $page->content !!}
        </div>
    </div>
@endsection
