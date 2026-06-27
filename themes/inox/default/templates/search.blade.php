@extends('layout')

@section('title', 'Search')

@section('content')
    @php $primary = theme('primary_color', '#3b82f6'); @endphp
    <div class="max-w-4xl mx-auto px-4 py-12">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Search</h1>
        <form method="GET" action="{{ url('/search') }}" class="mb-8">
            <div class="flex gap-2">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Search..." class="flex-1 border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                <button type="submit" class="px-6 py-2 text-white font-medium rounded-lg text-sm" style="background-color: {{ $primary }}">Search</button>
            </div>
        </form>

        @if ($results ?? false)
            <div class="space-y-6">
                @forelse ($results as $result)
                    <div class="border-b border-gray-100 pb-4">
                        <h2 class="text-lg font-semibold">
                            <a href="{{ $result['url'] ?? '#' }}" class="hover:underline" style="color: {{ $primary }}">{{ $result['title'] ?? '' }}</a>
                        </h2>
                        @if ($result['excerpt'] ?? false)
                            <p class="mt-1 text-sm text-gray-600">{{ $result['excerpt'] }}</p>
                        @endif
                    </div>
                @empty
                    <p class="text-gray-500">No results for "{{ request('q') }}".</p>
                @endforelse
            </div>
        @elseif (request('q'))
            <p class="text-gray-500">No results found.</p>
        @endif
    </div>
@endsection
