@extends('layouts.app')

@section('title', 'Dream Fights')

@section('content')
<div class="max-w-5xl mx-auto py-12 px-4">
    <h1 class="text-5xl font-extrabold text-red-500 text-center mb-12 drop-shadow-lg">
        Dream Fights Lobby
    </h1>

    @if(session('success'))
        <div class="bg-green-600 text-white p-4 mb-6 rounded-xl shadow-md">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-600 text-white p-4 mb-6 rounded-xl shadow-md">{{ session('error') }}</div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">

        <!-- CREATE CHALLENGE -->
        <div class="bg-gray-900/80 rounded-3xl p-6 shadow-2xl">
            <h2 class="text-2xl font-bold text-red-500 mb-4">Create a Challenge</h2>
            <form method="POST" action="{{ route('dreamfights.create') }}">
                @csrf
                <label class="block text-white font-medium mb-2">Choose Your Fighter:</label>
                <select name="fighter_id" class="w-full p-3 rounded-xl bg-gray-800 text-white border border-gray-700 mb-4">
                    @foreach($fighters as $fighter)
                        <option value="{{ $fighter->id }}">
                            {{ $fighter->first_name }} {{ $fighter->last_name }}
                            @if($fighter->nickname) ({{ $fighter->nickname }}) @endif
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-xl font-semibold">
                    Create Challenge
                </button>
            </form>
        </div>

        <!-- FIGHTS LIST -->
        <div class="bg-gray-900/80 rounded-3xl p-6 shadow-2xl">
            <h2 class="text-2xl font-bold text-red-500 mb-4">Open Fights</h2>

            @forelse($dreamfights as $fight)
                <div class="bg-gray-800/50 rounded-xl p-4 mb-4 shadow-md">
                    <div class="flex justify-between items-center flex-wrap gap-2 mb-3">
                        <div class="text-white font-semibold">
                            {{ $fight->playerOne->name }}
                            @if($fight->player_one_id === Auth::id()) (You) @endif
                            <span class="text-gray-400">vs</span>
                            {{ $fight->playerTwo?->name ?? 'Waiting...' }}
                        </div>
                        <div class="text-gray-300 text-sm">
                            Round: {{ $fight->current_round ?? 1 }}/3
                        </div>
                    </div>

                    <div class="text-gray-300 text-sm mb-2">
                        Score: {{ $fight->player_one_score }} - {{ $fight->player_two_score }}
                    </div>

                    @if($fight->status === 'waiting' && !$fight->player_two_id && Auth::id() !== $fight->player_one_id)
                        <!-- Join fight -->
                        <form method="POST" action="{{ route('dreamfights.join', $fight) }}" class="flex gap-2">
                            @csrf
                            <select name="fighter_id" class="bg-gray-700 text-white rounded-xl p-2">
                                @foreach($fighters as $fighter)
                                    <option value="{{ $fighter->id }}">
                                        {{ $fighter->first_name }} {{ $fighter->last_name }}
                                        @if($fighter->nickname) ({{ $fighter->nickname }}) @endif
                                    </option>
                                @endforeach
                            </select>
                            <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-xl font-semibold">
                                Join & Start
                            </button>
                        </form>
                    @elseif(in_array(Auth::id(), [$fight->player_one_id, $fight->player_two_id]))
                        <!-- If I'm in the fight already -->
                        <a href="{{ route('dreamfights.show', $fight) }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl mt-2">
                            Reopen Game
                        </a>
                    @else
                        <p class="text-gray-400 text-sm">In progress</p>
                    @endif
                </div>
            @empty
                <p class="text-gray-400 text-center">No fights yet.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
