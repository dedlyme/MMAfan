@extends('layouts.app')

@section('title', 'Dream Fights - UFC MMA')

@section('content')
<div class="relative z-10 max-w-6xl mx-auto py-12 px-4">

    <h1 class="text-5xl font-extrabold text-red-500 mb-10 text-center drop-shadow-lg">
        Dream Fights
    </h1>

    {{-- Success Message --}}
    @if(session('success'))
        <div id="success-msg" class="bg-green-600 text-white p-4 mb-6 rounded-xl shadow-lg transition-opacity duration-500">
            {{ session('success') }}
        </div>
    @endif

    <!-- Search Form -->
    <div class="mb-8">
        <form method="GET" action="{{ route('dreamfights.index') }}" class="flex flex-col md:flex-row gap-3">
            <input type="text" name="username" value="{{ request('username') }}" placeholder="Search by username..."
                   class="flex-1 p-3 rounded-xl bg-gray-800 text-white border border-gray-700 focus:ring-2 focus:ring-red-500 transition">
            <div class="flex gap-2">
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-5 py-3 rounded-xl shadow-md transition">
                    Search
                </button>
                <a href="{{ route('dreamfights.index') }}" class="bg-gray-700 hover:bg-gray-600 text-white px-5 py-3 rounded-xl shadow-md transition">
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Create New Dream Fight -->
    <div class="bg-gray-900/70 backdrop-blur-md rounded-3xl p-8 mb-10 shadow-2xl">
        <h2 class="text-3xl font-bold text-red-500 mb-6">Create New Dream Fight</h2>
        <form method="POST" action="{{ route('dreamfights.store') }}" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach(['one','two'] as $i)
                    <div>
                        <label class="block text-white font-semibold mb-2">Fighter {{ ucfirst($i) }}</label>
                        <div class="flex gap-2 mb-2">
                            <input type="text" id="search-fighter-{{ $i }}" placeholder="Search fighter..."
                                   class="flex-1 p-2 rounded-xl bg-gray-800 text-white border border-gray-700 focus:ring-2 focus:ring-red-500 transition">
                            <button type="button" id="clear-fighter-{{ $i }}" class="px-4 py-2 rounded-xl bg-red-600 hover:bg-red-500 text-white transition">
                                Clear
                            </button>
                        </div>
                        <select name="fighter_{{ $i }}_name" id="fighter-{{ $i }}" required size="5"
                                class="w-full p-3 rounded-xl bg-gray-800 text-white border border-gray-700 focus:ring-2 focus:ring-red-500 transition">
                            @foreach($fighters as $fighter)
                                <option value="{{ $fighter['FirstName'] }} {{ $fighter['LastName'] }}"
                                        data-weight="{{ $fighter['WeightClass'] }}">
                                    {{ $fighter['FirstName'] }} {{ $fighter['LastName'] }}
                                    @if(!empty($fighter['Nickname'])) ({{ $fighter['Nickname'] }}) @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endforeach
            </div>

            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold px-6 py-3 rounded-xl shadow-lg transition">
                Save Dream Fight
            </button>
        </form>
    </div>

    <!-- Dream Fights Table -->
    <div class="overflow-x-auto bg-gray-900/60 backdrop-blur-md rounded-3xl shadow-2xl">
        <table class="min-w-full divide-y divide-gray-700 text-white">
            <thead class="bg-gray-800/80">
                <tr>
                    <th class="px-6 py-3 text-left font-medium">Fighter One</th>
                    <th class="px-6 py-3 text-left font-medium">Fighter Two</th>
                    <th class="px-6 py-3 text-left font-medium">Created By</th>
                    <th class="px-6 py-3 text-left font-medium">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
                @forelse($dreamfights as $fight)
                    <tr class="hover:bg-gray-800/50 transition">
                        <td class="px-6 py-3">{{ $fight->fighter_one_name }}</td>
                        <td class="px-6 py-3">{{ $fight->fighter_two_name }}</td>
                        <td class="px-6 py-3">{{ $fight->user->name }}</td>
                        <td class="px-6 py-3 flex flex-wrap gap-2">
                            @if(auth()->user()->is_admin || $fight->user_id === auth()->id())
                                <a href="{{ route('dreamfights.edit', $fight) }}"
                                   class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-xl transition shadow-md">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('dreamfights.destroy', $fight) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Delete fight?')"
                                            class="bg-red-700 hover:bg-red-600 text-white px-3 py-2 rounded-xl transition shadow-md">
                                        Delete
                                    </button>
                                </form>
                            @else
                                <span class="text-gray-400 italic">No actions</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-3 text-center text-gray-400">No dream fights found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection

@push('scripts')
<script>
// Filter function for fighter selects
function filterSelect(inputId, selectId) {
    const input = document.getElementById(inputId);
    const select = document.getElementById(selectId);

    input.addEventListener('keyup', function() {
        const filter = input.value.toLowerCase();
        Array.from(select.options).forEach(option => {
            option.style.display = option.text.toLowerCase().includes(filter) ? '' : 'none';
        });
        for (let i = 0; i < select.options.length; i++) {
            if (select.options[i].style.display !== 'none') {
                select.selectedIndex = i;
                break;
            }
        }
    });
}

// Fighter Two weight restriction
function restrictFighterTwo() {
    const fighterOne = document.getElementById('fighter-one');
    const fighterTwo = document.getElementById('fighter-two');
    const weightOrder = [
        "Atomweight", "Strawweight", "Flyweight", "Bantamweight", "Featherweight",
        "Lightweight", "Welterweight", "Middleweight", "Light Heavyweight", "Heavyweight",
        "Women’s Flyweight", "Women’s Bantamweight", "Women’s Featherweight", "Women’s Lightweight"
    ];

    fighterOne.addEventListener('change', function() {
        if (!fighterOne.selectedOptions[0]) return;
        const selectedWeight = fighterOne.selectedOptions[0].dataset.weight;
        const selectedIndex = weightOrder.findIndex(w => selectedWeight.includes(w));

        Array.from(fighterTwo.options).forEach(option => {
            const optionIndex = weightOrder.findIndex(w => option.dataset.weight.includes(w));
            option.style.display = (optionIndex >= selectedIndex - 1 && optionIndex <= selectedIndex + 1) ? '' : 'none';
        });

        for (let i = 0; i < fighterTwo.options.length; i++) {
            if (fighterTwo.options[i].style.display !== 'none') {
                fighterTwo.selectedIndex = i;
                break;
            }
        }
    });
}

// Clear buttons
document.getElementById('clear-fighter-one').addEventListener('click', () => document.getElementById('fighter-one').selectedIndex = -1);
document.getElementById('clear-fighter-two').addEventListener('click', () => document.getElementById('fighter-two').selectedIndex = -1);

filterSelect('search-fighter-one', 'fighter-one');
filterSelect('search-fighter-two', 'fighter-two');
restrictFighterTwo();

// Fade out success message after 5 seconds
const msg = document.getElementById('success-msg');
if(msg){
    setTimeout(() => {
        msg.style.opacity = 0;
        setTimeout(() => msg.remove(), 500); // remove after fade out
    }, 5000);
}
</script>
@endpush
