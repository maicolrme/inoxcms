@extends('layouts.public')

@section('title', 'Home')

@section('content')
    <div class="min-h-[70vh] flex flex-col items-center justify-center px-4">
        <h1 class="text-5xl md:text-6xl font-bold text-gray-900 tracking-tight">{{ config('app.name', 'INOX') }}</h1>
        <p class="mt-4 text-lg text-gray-500 text-center max-w-md">{{ config('app.description', 'Your modern PHP CMS.') }}</p>
        <div class="mt-8 flex gap-4">
            <a href="{{ url('/admin') }}" class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">Admin</a>
            <a href="{{ url('/blog') }}" class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">Blog</a>
        </div>
    </div>
@endsection
