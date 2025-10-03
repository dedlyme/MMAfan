@extends('layouts.app')

@section('title', $division->name . ' - Fighters')

@section('content')
<div class="container mx-auto p-6">

    {{-- ====== Page Title ====== --}}
    <h1 class="text-3xl font-bold mb-6 text-white">{{ $division->name }} — Fighters</h1>

    {{-- ====== Error / Success Messages ====== --}}
    @if(session('success'))
        <div class="bg-green-600 text-white p-3 rounded-lg mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-600 text-white p-3 rounded-lg mb-4">
            {{ $errors->first() }}
        </div>
    @endif

    {{-- ====== Fighters List ====== --}}
    @if($division->rankings->isEmpty())
        <p class="italic text-gray-400">Šajā divīzijā vēl nav ierakstu.</p>
    @else
        @php
            // Champion vienmēr pirmajā vietā
            $sorted = $division->rankings->sortBy(function($f) {
                return $f->is_champion ? 0 : $f->rank;
            });
        @endphp

        <ul class="space-y-2">
            @foreach($sorted as $fighter)
                <li class="flex flex-col md:flex-row md:justify-between md:items-center bg-gray-900 p-3 rounded-lg">
                    <span class="text-white text-lg">
                        {{ $fighter->is_champion ? 'C' : $fighter->rank }}. {{ $fighter->fighter_name }}
                        @if($fighter->is_champion)
                            <span class="ml-2 text-yellow-400 font-semibold">Champion</span>
                        @endif
                    </span>

                    {{-- ====== Admin Controls ====== --}}
                    @can('is_admin')
                        <div class="flex flex-wrap gap-2 mt-3 md:mt-0">

                            {{-- Delete Fighter --}}
                            <form action="{{ route('admin.rankings.destroy', $fighter) }}" method="POST" onsubmit="return confirm('Delete this fighter?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 hover:bg-red-500 text-white px-3 py-1 rounded-lg font-semibold transition">
                                    Delete
                                </button>
                            </form>

                            {{-- Edit Fighter --}}
                            <form action="{{ route('ranking.update', $fighter) }}" method="POST" class="flex flex-wrap gap-2">
                                @csrf
                                @method('PATCH')
                                <input type="text" name="fighter_name" value="{{ $fighter->fighter_name }}"
                                       class="p-2 rounded bg-gray-700 text-white text-sm w-40"
                                       placeholder="Name" required>

                                <input type="number" name="rank" value="{{ $fighter->rank }}"
                                       class="p-2 rounded bg-gray-700 text-white text-sm w-20"
                                       placeholder="Rank" min="1" max="15">

                                <label class="flex items-center space-x-1 text-sm text-gray-300">
                                    <input type="checkbox" name="is_champion" {{ $fighter->is_champion ? 'checked' : '' }} class="accent-yellow-400">
                                    <span>Champion</span>
                                </label>

                                <button type="submit" class="bg-green-600 hover:bg-green-500 px-3 py-1 rounded-lg font-semibold transition">
                                    Update
                                </button>
                            </form>
                        </div>
                    @endcan
                </li>
            @endforeach
        </ul>
    @endif

    {{-- ====== Add New Fighter (Only Admin) ====== --}}
    @can('is_admin')
        <div class="mt-8 bg-gray-800 p-4 rounded-lg">
            <h3 class="text-xl font-bold text-yellow-400 mb-3">Add New Fighter</h3>
            <form action="{{ route('ranking.store') }}" method="POST" class="flex flex-wrap gap-2">
                @csrf
                <input type="hidden" name="division_id" value="{{ $division->id }}">

                <input type="text" name="fighter_name" placeholder="Fighter Name"
                       class="p-2 rounded bg-gray-700 text-white flex-1 min-w-[150px]" required>

                <input type="number" name="rank" placeholder="Rank" min="1" max="15"
                       class="p-2 rounded bg-gray-700 text-white w-24">

                <label class="flex items-center space-x-1 text-sm text-gray-300">
                    <input type="checkbox" name="is_champion" class="accent-yellow-400">
                    <span>Champion</span>
                </label>

                <button type="submit" class="bg-blue-600 hover:bg-blue-500 px-4 py-2 rounded-lg font-semibold text-white transition">
                    Add Fighter
                </button>
            </form>
        </div>
    @endcan

    {{-- ====== Back Button ====== --}}
    <a href="{{ route('ranking') }}" class="mt-6 inline-block text-blue-400 hover:underline">← Back to Divisions</a>
</div>
@endsection
