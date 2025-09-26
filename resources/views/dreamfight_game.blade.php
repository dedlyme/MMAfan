@extends('layouts.app')

@section('title', 'Dream Fight')

@section('content')
<div class="max-w-3xl mx-auto py-10 px-4 text-center text-white">
    <h1 class="text-4xl font-bold mb-6">Fight #{{ $dreamfight->id }}</h1>
    <p class="mb-2">
        <strong>{{ $dreamfight->playerOne->name }}</strong> vs
        <strong>{{ $dreamfight->playerTwo->name }}</strong>
    </p>
    <p class="mb-6">Round {{ $dreamfight->current_round }} / 3</p>

    <p class="mb-6">Score: {{ $dreamfight->player_one_score }} - {{ $dreamfight->player_two_score }}</p>

    @if($dreamfight->status === 'finished')
        <h2 class="text-green-400 text-3xl font-bold mb-4">
            Winner: {{ $dreamfight->winner }}
        </h2>
        <script>
            setTimeout(()=>{ window.close(); }, 5000);
        </script>
    @else
        @php
            $myChoice = $dreamfight->player_one_id === Auth::id() ? $dreamfight->player_one_choice : $dreamfight->player_two_choice;
        @endphp

        @if(!$myChoice)
            <form method="POST" action="{{ route('dreamfights.choose', $dreamfight) }}">
                @csrf
                <label class="block mb-2">Select your style:</label>
                <select name="choice" class="p-2 rounded bg-gray-800 text-white mb-4">
                    <option value="wrestling">Wrestling</option>
                    <option value="kickbox">Kickbox</option>
                    <option value="jiu-jitsu">Jiu-Jitsu</option>
                </select>
                <button class="bg-red-500 px-4 py-2 rounded font-semibold">Submit</button>
            </form>
        @else
            <p class="text-yellow-300 mb-4">You chose: {{ ucfirst($myChoice) }}</p>
            <p>Waiting for the other player...</p>
        @endif

        <div class="mt-6 text-sm text-gray-400">
            This page refreshes automatically every 5s.
        </div>

        <script>
            setInterval(()=>location.reload(),5000);
        </script>
    @endif
</div>
@endsection
