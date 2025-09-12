@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">

    <h1 class="text-3xl font-bold mb-6 text-white">{{ $division->name }} - Fighters</h1>

    @if($division->rankings->isEmpty())
        <p class="italic text-gray-400">Šajā divīzijā vēl nav ierakstu.</p>
    @else
        <ul class="space-y-2">
            @php
                $sorted = $division->rankings->sortBy(function($f) {
                    return $f->is_champion ? 0 : $f->rank;
                });
            @endphp

            @foreach($sorted as $fighter)
                <li class="flex justify-between items-center bg-gray-900 p-2 rounded">
                    <span class="text-white">{{ $fighter->is_champion ? 'C' : $fighter->rank }}. {{ $fighter->fighter_name }}</span>

                    @can('is_admin')
                        <div class="flex space-x-2">
                            <form action="{{ route('admin.rankings.destroy', $fighter) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-400 font-bold">Delete</button>
                            </form>

                            <form action="{{ route('ranking.update', $fighter) }}" method="POST" class="flex space-x-1">
                                @csrf
                                @method('PATCH')
                                <input type="text" name="fighter_name" value="{{ $fighter->fighter_name }}" class="p-1 rounded bg-gray-700 text-white" required>
                                <input type="number" name="rank" value="{{ $fighter->rank }}" class="p-1 rounded bg-gray-700 text-white w-16" required>
                                <label class="flex items-center space-x-1">
                                    <input type="checkbox" name="is_champion" {{ $fighter->is_champion ? 'checked' : '' }} class="accent-yellow-400">
                                    <span class="text-sm">Champion</span>
                                </label>
                                <button type="submit" class="bg-green-600 hover:bg-green-500 px-2 rounded transition">Update</button>
                            </form>
                        </div>
                    @endcan
                </li>
            @endforeach
        </ul>
    @endif

    <a href="{{ route('ranking') }}" class="mt-6 inline-block text-blue-500 hover:underline">Back to Divisions</a>
</div>
@endsection
