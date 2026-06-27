@extends('layouts.admin')

@section('content')
    <div class="mb-8">
        <h2 class="text-2xl font-semibold text-gray-900">Dashboard</h2>
        <p class="text-gray-600">Welcome to your INOX admin panel.</p>
    </div>

    @php
        $widgets = app('module.engine')->getDashboardWidgets();
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-sm font-medium text-gray-500">Users</h3>
            <p class="text-3xl font-bold text-gray-900 mt-1">{{ \App\Models\User::count() }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-sm font-medium text-gray-500">Active Modules</h3>
            <p class="text-3xl font-bold text-gray-900 mt-1">{{ count(config('inox.modules.active', [])) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-sm font-medium text-gray-500">System Status</h3>
            <p class="text-sm font-medium text-green-600 mt-2">● Operational</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-sm font-medium text-gray-500">PHP Version</h3>
            <p class="text-3xl font-bold text-gray-900 mt-1">{{ PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION }}</p>
        </div>
    </div>

    @if ($widgets)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach ($widgets as $widget)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 {{ $widget['grid_class'] ?? '' }}">
                    @if ($widget['title'])
                        <h3 class="font-medium text-gray-900 mb-4">{{ $widget['title'] }}</h3>
                    @endif
                    {!! $widget['content'] !!}
                </div>
            @endforeach
        </div>
    @endif
@endsection
