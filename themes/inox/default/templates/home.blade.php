@extends('layout')

@section('title', 'Home')

@section('content')
    @php
        $hero = theme('hero_layout', 'fullscreen');
        $primary = theme('primary_color', '#3b82f6');
    @endphp

    @if ($hero === 'fullscreen')
        <div class="min-h-[80vh] flex flex-col items-center justify-center px-4 text-center"
             style="background: linear-gradient(135deg, {{ $primary }}11 0%, #ffffff 100%);">
            <h1 class="text-5xl md:text-7xl font-bold text-gray-900 tracking-tight">{{ config('app.name', 'INOX') }}</h1>
            <p class="mt-4 text-xl text-gray-500 max-w-lg">{{ config('app.description', 'Your modern PHP CMS.') }}</p>
            <div class="mt-10 flex gap-4">
                <a href="{{ url('/blog') }}" class="px-8 py-3 text-white font-medium rounded-lg transition-colors" style="background-color: {{ $primary }}; hover:opacity-90">Read the Blog</a>
                <a href="{{ url('/admin') }}" class="px-8 py-3 border-2 font-medium rounded-lg transition-colors" style="border-color: {{ $primary }}; color: {{ $primary }}">Admin Panel</a>
            </div>
        </div>
    @elseif ($hero === 'boxed')
        <div class="max-w-4xl mx-auto px-4 py-24 text-center">
            <h1 class="text-4xl md:text-6xl font-bold text-gray-900">{{ config('app.name', 'INOX') }}</h1>
            <p class="mt-4 text-lg text-gray-500">{{ config('app.description', 'Your modern PHP CMS.') }}</p>
            <div class="mt-8 flex gap-4 justify-center">
                <a href="{{ url('/blog') }}" class="px-6 py-3 text-white font-medium rounded-lg" style="background-color: {{ $primary }}">Read the Blog</a>
                <a href="{{ url('/admin') }}" class="px-6 py-3 border-2 rounded-lg font-medium" style="border-color: {{ $primary }}; color: {{ $primary }}">Admin Panel</a>
            </div>
        </div>
    @else
        <div class="max-w-4xl mx-auto px-4 py-16">
            <h1 class="text-3xl font-bold text-gray-900">{{ config('app.name', 'INOX') }}</h1>
            <p class="mt-2 text-gray-500">{{ config('app.description', 'Your modern PHP CMS.') }}</p>
            <div class="mt-6 flex gap-3">
                <a href="{{ url('/blog') }}" class="px-5 py-2 text-sm text-white font-medium rounded-lg" style="background-color: {{ $primary }}">Blog</a>
                <a href="{{ url('/admin') }}" class="px-5 py-2 text-sm border rounded-lg font-medium" style="border-color: {{ $primary }}; color: {{ $primary }}">Admin</a>
            </div>
        </div>
    @endif
@endsection
