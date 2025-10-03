@extends('layouts.app')

@section('title', 'UFC Divisions')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 space-y-6">

    <h1 class="text-3xl font-extrabold mb-6 text-yellow-400 text-center">UFC Divisions</h1>

    {{-- Admin: Add new division --}}
    @if(auth()->check() && auth()->user()->is_admin)
        <form action="{{ route('admin.divisions.store') }}" method="POST" class="mb-8 flex flex-col sm:flex-row gap-2">
            @csrf
            <div class="flex-1">
                <input type="text" name="name" placeholder="New Division"
                       class="w-full p-3 rounded-lg border @error('name') border-red-500 @else border-gray-700 @enderror bg-gray-800 text-white focus:ring-2 focus:ring-yellow-400">
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit" class="bg-blue-600 hover:bg-blue-500 px-6 py-3 rounded-lg text-white font-semibold transition">
                Add Division
            </button>
        </form>
    @endif

    {{-- Success message --}}
    @if(session('success'))
        <div class="bg-green-600 text-white p-3 rounded-lg shadow-md mb-6">
            {{ session('success') }}
        </div>
    @endif

    {{-- Divisions Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($divisions as $division)
            <div class="bg-gray-900 rounded-2xl shadow-lg overflow-hidden">
                {{-- Division Header --}}
                <div class="flex justify-between items-center p-6 bg-gray-800">
                    <div>
                        <h2 class="text-2xl font-bold text-white">{{ $division->name }}</h2>
                        <span class="text-gray-400 text-sm">{{ $division->rankings->count() }} fighters</span>
                    </div>
                    @if(auth()->check() && auth()->user()->is_admin)
                        <button onclick="document.getElementById('edit-division-{{ $division->id }}').classList.toggle('hidden')"
                                class="bg-yellow-600 hover:bg-yellow-500 px-3 py-1 rounded-lg text-black font-semibold transition">
                            Edit
                        </button>
                    @endif
                </div>

                {{-- Fighters List --}}
                <div class="bg-gray-800 p-4 space-y-2">
                    @php
                        $sorted = $division->rankings->sortBy(fn($f) => $f->is_champion ? 0 : $f->rank);
                    @endphp
                    @foreach($sorted as $fighter)
                        <div class="p-3 bg-gray-700 rounded-lg flex items-center justify-between">
                            <span class="text-white font-medium">
                                {{ $fighter->is_champion ? 'C' : $fighter->rank }}. {{ $fighter->fighter_name }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
