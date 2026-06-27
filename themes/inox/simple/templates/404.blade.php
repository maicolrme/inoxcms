@extends('layout')

@section('title', '404 — Page Not Found')

@section('content')
    <div class="max-w-4xl mx-auto px-4 py-24 text-center">
        <h1 class="text-6xl font-bold text-gray-200">404</h1>
        <p class="mt-4 text-lg text-gray-500">Page not found.</p>
        <a href="{{ url('/') }}" class="mt-6 inline-block text-sm font-medium hover:underline" style="color: {{ theme('primary_color', '#2563eb') }}">Go home →</a>
    </div>
@endsection
