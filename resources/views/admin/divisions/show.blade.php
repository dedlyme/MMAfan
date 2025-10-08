@extends('layouts.app')

@section('title', $division->name . ' - Fighters')

@section('content')
<div class="max-w-5xl mx-auto p-6">

    {{-- ====== PAGE TITLE ====== --}}
    <h1 class="text-4xl font-extrabold mb-8 text-gray-900 dark:text-white">
        {{ $division->name }} — Fighters
    </h1>

    {{-- ====== FEEDBACK MESSAGES ====== --}}
    @if(session('success'))
        <div class="bg-green-500/90 text-white p-3 rounded-lg mb-4 shadow-md">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-500/90 text-white p-3 rounded-lg mb-4 shadow-md">
            {{ $errors->first() }}
        </div>
    @endif

    {{-- ====== FIGHTERS LIST ====== --}}
    @if($division->rankings->isEmpty())
        <p class="italic text-gray-600 dark:text-gray-400">
            No fighters added yet in this division.
        </p>
    @else
        @php
            // Champion first
            $sorted = $division->rankings->sortBy(function($f) {
                return $f->is_champion ? 0 : $f->rank;
            });
        @endphp

        <ul class="space-y-3">
            @foreach($sorted as $fighter)
                <li class="flex flex-col md:flex-row md:justify-between md:items-center
                           bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700
                           rounded-xl p-4 shadow-sm hover:shadow-md transition">
                    <span class="text-gray-900 dark:text-white text-lg font-semibold">
                        {{ $fighter->is_champion ? 'C' : $fighter->rank }}. {{ $fighter->fighter_name }}
                        @if($fighter->is_champion)
                            <span class="ml-2 text-yellow-500 font-bold text-sm uppercase tracking-wide">Champion</span>
                        @endif
                    </span>

                    {{-- ====== ADMIN CONTROLS ====== --}}
                    @can('is_admin')
                        <div class="flex flex-wrap gap-2 mt-3 md:mt-0">

                            {{-- DELETE --}}
                            <form action="{{ route('admin.rankings.destroy', $fighter) }}" method="POST"
                                  onsubmit="return confirm('Delete this fighter?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="bg-red-600 hover:bg-red-500 text-white px-3 py-1 rounded-lg font-semibold transition">
                                    Delete
                                </button>
                            </form>

                            {{-- EDIT --}}
                            <form action="{{ route('ranking.update', $fighter) }}" method="POST" class="flex flex-wrap gap-2">
                                @csrf
                                @method('PATCH')
                                <input type="text" name="fighter_name" value="{{ $fighter->fighter_name }}"
                                       class="p-2 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white text-sm w-40 focus:ring-2 focus:ring-blue-500"
                                       placeholder="Name" required>

                                <input type="number" name="rank" value="{{ $fighter->rank }}"
                                       class="p-2 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white text-sm w-20 focus:ring-2 focus:ring-blue-500"
                                       placeholder="Rank" min="1" max="15">

                                <label class="flex items-center space-x-1 text-sm text-gray-700 dark:text-gray-300">
                                    <input type="checkbox" name="is_champion" {{ $fighter->is_champion ? 'checked' : '' }}
                                           class="accent-yellow-400">
                                    <span>Champion</span>
                                </label>

                                <button type="submit"
                                        class="bg-green-600 hover:bg-green-500 px-3 py-1 rounded-lg font-semibold text-white transition">
                                    Update
                                </button>
                            </form>
                        </div>
                    @endcan
                </li>
            @endforeach
        </ul>
    @endif

    {{-- ====== ADD NEW FIGHTER (ADMIN ONLY) ====== --}}
    @can('is_admin')
        <div class="mt-10 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 p-6 rounded-xl shadow-md">
            <h3 class="text-2xl font-bold text-gray-900 dark:text-yellow-400 mb-4">
                Add New Fighter
            </h3>
            <form action="{{ route('ranking.store') }}" method="POST" class="flex flex-col md:flex-row flex-wrap gap-3">
                @csrf
                <input type="hidden" name="division_id" value="{{ $division->id }}">

                <input type="text" name="fighter_name" placeholder="Fighter Name"
                       class="p-2 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white flex-1 focus:ring-2 focus:ring-blue-500" required>

                <input type="number" name="rank" placeholder="Rank" min="1" max="15"
                       class="p-2 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white w-24 focus:ring-2 focus:ring-blue-500">

                <label class="flex items-center space-x-1 text-sm text-gray-700 dark:text-gray-300">
                    <input type="checkbox" name="is_champion" class="accent-yellow-400">
                    <span>Champion</span>
                </label>

                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-500 px-4 py-2 rounded-lg font-semibold text-white transition">
                    Add Fighter
                </button>
            </form>
        </div>
    @endcan

    {{-- ====== BACK BUTTON ====== --}}
    <div class="mt-8">
        <a href="{{ route('ranking') }}"
           class="inline-block text-blue-600 dark:text-blue-400 hover:underline">
            ← Back to Divisions
        </a>
    </div>
</div>
@endsection
