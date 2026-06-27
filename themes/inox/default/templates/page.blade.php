@extends('layout')

@section('title', $title ?? 'Page')

@section('content')
    <div class="max-w-4xl mx-auto px-4 py-12">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900">{{ $title ?? 'Page' }}</h1>
        <div class="mt-6 prose prose-gray max-w-none">
            {{ $content ?? '' }}
        </div>
    </div>
@endsection
