@extends('layout')

@section('title', 'Page Not Found')

@section('content')
    @php $primary = theme('primary_color', '#3b82f6'); @endphp
    <div class="min-h-[60vh] flex flex-col items-center justify-center px-4 text-center">
        <span class="text-8xl font-bold text-gray-200" style="color: {{ $primary }}22">404</span>
        <h1 class="mt-4 text-3xl font-bold text-gray-900">Page Not Found</h1>
        <p class="mt-2 text-gray-500 max-w-md">The page you are looking for does not exist or has been moved.</p>
        <a href="{{ url('/') }}" class="mt-8 px-6 py-3 text-white font-medium rounded-lg transition-colors" style="background-color: {{ $primary }}">Go Home</a>
    </div>
@endsection
