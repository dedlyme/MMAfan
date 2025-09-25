@extends('layouts.app')

@section('title', 'Dream Fights')

@section('content')
<div class="max-w-5xl mx-auto py-12 px-4">
    <h1 class="text-5xl font-extrabold text-red-500 text-center mb-12 drop-shadow-lg">
        Dream Fights
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
            <h2 class="text-2xl font-bold text-red-500 mb-4">Fights</h2>

            @forelse($dreamfights as $fight)
                <div class="bg-gray-800/50 rounded-xl p-4 mb-4 shadow-md">
                    <div class="flex justify-between items-center flex-wrap gap-2 mb-3">
                        <div class="text-white font-semibold">
                            {{ $fight->playerOne->name }} ({{ $fight->player_one_id === Auth::id() ? 'You' : 'P1' }})
                            <span class="text-gray-400">vs</span>
                            {{ $fight->playerTwo?->name ?? 'Waiting...' }}
                            @if($fight->player_two_id === Auth::id()) (You) @endif
                        </div>
                        <div class="text-gray-300 text-sm">
                            Round: {{ $fight->current_round ?? 1 }}/3
                        </div>
                    </div>

                    <div class="text-gray-300 text-sm mb-2">
                        Score: {{ $fight->player_one_score }} - {{ $fight->player_two_score }}
                    </div>

                    {{-- JOIN FORM --}}
                    @if($fight->status === 'waiting' && !$fight->player_two_id && Auth::id() !== $fight->player_one_id)
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
                                Join
                            </button>
                        </form>
                    @endif

                    {{-- CHOOSE MOVE --}}
                    @php $canChoose = ($fight->status === 'in_progress') && (
                        ($fight->player_one_id === Auth::id() && !$fight->player_one_choice) ||
                        ($fight->player_two_id === Auth::id() && !$fight->player_two_choice)
                    ); @endphp

                    @if($canChoose)
                        <form method="POST" action="{{ route('dreamfights.choose', $fight) }}" class="flex gap-2 mt-2">
                            @csrf
                            <select name="choice" class="bg-gray-700 text-white rounded-xl p-2">
                                <option value="wrestling">Wrestling</option>
                                <option value="kickbox">Kickbox</option>
                                <option value="jiu-jitsu">Jiu-Jitsu</option>
                            </select>
                            <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-xl font-semibold">
                                Choose
                            </button>
                        </form>
                    @endif

                    {{-- ROUND TIMER --}}
                    @if($fight->status === 'in_progress' && $fight->round_end_time)
                        <div class="text-yellow-400 font-semibold mt-2">
                            Round ends at: {{ \Carbon\Carbon::parse($fight->round_end_time)->format('H:i:s') }}
                        </div>
                    @endif

                    {{-- WINNER --}}
                    @if($fight->status === 'finished')
                        <div class="text-green-400 font-bold mt-2">
                            Winner: {{ $fight->winner }}
                        </div>
                    @endif
                </div>
            @empty
                <p class="text-gray-400 text-center">No fights yet.</p>
            @endforelse

            <p class="text-gray-500 text-center mt-4 text-sm">
                🔄 Refresh the page every ~15s to see updates.
            </p>
        </div>
    </div>
</div>
@endsection
