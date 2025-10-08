@extends('layouts.app')

@section('title', 'Dream Fights')

@section('content')
<div class="max-w-6xl mx-auto py-12 px-4 text-gray-900 dark:text-gray-100">
    <h1 class="text-5xl font-extrabold text-red-600 dark:text-red-500 text-center mb-10">
        Dream Fights Lobby
    </h1>

    @if(session('success'))
        <div class="bg-green-600 text-white p-4 mb-6 rounded-xl shadow-md">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-600 text-white p-4 mb-6 rounded-xl shadow-md">{{ session('error') }}</div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

        {{-- CREATE CHALLENGE --}}
        <div class="bg-white/70 dark:bg-gray-800/90 rounded-3xl p-6 shadow-2xl border border-gray-200/60 dark:border-gray-700/60 backdrop-blur">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-red-400 mb-4 flex items-center gap-2">
                Create a Challenge
            </h2>

            <form method="POST" action="{{ route('dreamfights.create') }}" class="space-y-3">
                @csrf
                <label class="block text-gray-700 dark:text-gray-300 font-medium">Choose Your Fighter:</label>

                <input
                    type="text"
                    id="searchFighter"
                    placeholder="Search fighter..."
                    class="w-full px-4 py-2 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-red-500 outline-none"
                >

                <select
                    id="fighterSelect"
                    name="fighter_id"
                    required
                    class="w-full p-3 rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-600 max-h-56 overflow-y-auto"
                >
                    {{-- Fallback options for no-JS --}}
                    <option value="" selected disabled>-- Select Fighter --</option>
                    @foreach($fighters as $fighter)
                        <option value="{{ $fighter->id }}">
                            {{ $fighter->first_name }} {{ $fighter->last_name }}
                            @if($fighter->nickname) ({{ $fighter->nickname }}) @endif
                        </option>
                    @endforeach
                </select>

                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-xl font-semibold transition">
                    Open Fight
                </button>
            </form>
        </div>

        {{-- FIGHTS LIST --}}
        <div class="bg-white/70 dark:bg-gray-800/90 rounded-3xl p-6 shadow-2xl border border-gray-200/60 dark:border-gray-700/60 backdrop-blur">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-red-400 mb-4">Available Fights</h2>

            @forelse($dreamfights as $fight)
                <div class="bg-gray-100 dark:bg-gray-800 rounded-xl p-4 mb-4 shadow-md border border-gray-200 dark:border-gray-700">
                    <div class="flex justify-between items-center flex-wrap gap-2 mb-3">
                        <div class="font-semibold text-gray-900 dark:text-white">
                            {{ $fight->playerOne?->name ?? 'Waiting...' }}
                            @if($fight->player_one_id === Auth::id())
                                <span class="text-green-600 dark:text-green-400">(You)</span>
                            @endif
                            <span class="text-gray-500 dark:text-gray-400">vs</span>
                            {{ $fight->playerTwo?->name ?? 'Waiting...' }}
                        </div>
                        <div class="text-gray-500 dark:text-gray-400 text-sm">
                            Round: {{ $fight->current_round ?? 1 }}/3
                        </div>
                    </div>

                    <div class="text-gray-700 dark:text-gray-300 text-sm mb-2">
                        Score: {{ $fight->player_one_score }} - {{ $fight->player_two_score }}
                    </div>

                    @if($fight->status === 'waiting' && !$fight->player_two_id && Auth::id() !== $fight->player_one_id)
                        {{-- JOIN FIGHT --}}
                        <form method="POST" action="{{ route('dreamfights.join', $fight) }}" class="flex flex-col gap-2">
                            @csrf

                            <input
                                type="text"
                                class="px-3 py-2 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-red-500 join-search"
                                placeholder="Search fighter to join..."
                                data-target="select-{{ $fight->id }}"
                            >

                            <select
                                id="select-{{ $fight->id }}"
                                name="fighter_id"
                                required
                                class="rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white p-2 border border-gray-300 dark:border-gray-600"
                            >
                                <option value="" selected disabled>-- Select Fighter --</option>
                                @foreach($fighters as $fighter)
                                    <option value="{{ $fighter->id }}">
                                        {{ $fighter->first_name }} {{ $fighter->last_name }}
                                        @if($fighter->nickname) ({{ $fighter->nickname }}) @endif
                                    </option>
                                @endforeach
                            </select>

                            <button class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-xl font-semibold">
                                Join & Start
                            </button>
                        </form>
                    @elseif(in_array(Auth::id(), [$fight->player_one_id, $fight->player_two_id]))
                        <a href="{{ route('dreamfights.show', $fight) }}"
                           class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl mt-2">
                            {{ $fight->status === 'finished' ? 'Reopen Fight' : 'Open Fight' }}
                        </a>
                    @else
                        <p class="text-gray-500 dark:text-gray-400 text-sm">In progress</p>
                    @endif
                </div>
            @empty
                <p class="text-gray-600 dark:text-gray-400 text-center">No fights yet.</p>
            @endforelse
        </div>
    </div>
</div>

{{-- ===== FIGHTERS DATA FOR JS (simple array) ===== --}}
<script>
    window.FIGHTERS = @json($fighterList);
</script>

{{-- ===== JS SEARCH (robust: rebuilds options; prevents Enter submit) ===== --}}
<script>
(function(){
    // Utility: build option label
    function labelFor(f) {
        const nick = f.nickname ? ` (${f.nickname})` : '';
        return `${f.first_name} ${f.last_name}${nick}`.trim();
    }

    // Utility: render options into a <select>
    function renderOptions(selectEl, items) {
        const placeholder = document.createElement('option');
        placeholder.value = '';
        placeholder.textContent = '-- Select Fighter --';
        placeholder.disabled = true;
        placeholder.selected = true;

        selectEl.innerHTML = '';
        selectEl.appendChild(placeholder);

        items.forEach(f => {
            const opt = document.createElement('option');
            opt.value = f.id;
            opt.textContent = labelFor(f);
            selectEl.appendChild(opt);
        });
    }

    // Utility: filter fighters by query
    function filterList(query) {
        const q = (query || '').toLowerCase();
        if (!q) return window.FIGHTERS;
        return window.FIGHTERS.filter(f => {
            return (
                (f.first_name && f.first_name.toLowerCase().includes(q)) ||
                (f.last_name && f.last_name.toLowerCase().includes(q)) ||
                (f.nickname && f.nickname.toLowerCase().includes(q))
            );
        });
    }

    // ===== CREATE CHALLENGE =====
    const createSearch = document.getElementById('searchFighter');
    const createSelect = document.getElementById('fighterSelect');

    if (createSelect) {
        // initialize with all fighters
        renderOptions(createSelect, window.FIGHTERS);

        // prevent Enter from submitting the form when typing in the search
        createSearch.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') e.preventDefault();
        });

        // live filter: rebuild options
        createSearch.addEventListener('input', (e) => {
            const list = filterList(e.target.value);
            renderOptions(createSelect, list);
        });
    }

    // ===== JOIN FIGHT search/select (multiple blocks) =====
    document.querySelectorAll('.join-search').forEach(input => {
        const targetId = input.getAttribute('data-target');
        const joinSelect = document.getElementById(targetId);

        // initialize with all
        if (joinSelect) {
            renderOptions(joinSelect, window.FIGHTERS);
        }

        // prevent Enter-submit
        input.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') e.preventDefault();
        });

        // live filter per join card
        input.addEventListener('input', (e) => {
            const list = filterList(e.target.value);
            renderOptions(joinSelect, list);
        });
    });
})();
</script>
@endsection
