<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dream Fights - UFC MMA</title>
@vite('resources/css/app.css')
</head>
<body class="bg-black text-white min-h-screen">

<nav class="sticky top-0 z-50 bg-gradient-to-r from-black/90 via-gray-900/90 to-black/90 backdrop-blur-md shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <a href="{{ route('dashboard') }}" class="text-2xl font-extrabold text-yellow-400 tracking-wide">
                UFC MMA Dashboard
            </a>
            <ul class="hidden md:flex space-x-8 font-medium">
                <li><a href="{{ route('dashboard') }}" class="relative text-white hover:text-yellow-400 transition group">Home</a></li>
                <li><a href="{{ route('ranking') }}" class="relative text-white hover:text-yellow-400 transition group">Ranking</a></li>
                <li><a href="{{ route('pound') }}" class="relative text-white hover:text-yellow-400 transition group">Pound for Pound</a></li>
                <li><a href="{{ route('news') }}" class="relative text-white hover:text-yellow-400 transition group">News</a></li>
                <li><a href="{{ route('dreamfights.index') }}" class="relative text-white hover:text-yellow-400 transition group">Dream Fights</a></li>
                @if(auth()->check() && auth()->user()->is_admin)
                    <li><a href="{{ route('admin.divisions.index') }}" class="relative text-white hover:text-yellow-400 transition group">Divisions</a></li>
                @endif
                <li><a href="{{ route('profile.edit') }}" class="relative text-white hover:text-yellow-400 transition group">Profile</a></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="relative text-white hover:text-red-400 transition group">Logout</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mx-auto py-12 px-4">
    <h1 class="text-4xl font-extrabold text-yellow-400 mb-8">Dream Fights</h1>

    @if(session('success'))
        <div class="bg-green-600 text-white p-4 mb-6 rounded shadow">{{ session('success') }}</div>
    @endif

    <!-- Search by username -->
    <div class="mb-6">
        <form method="GET" action="{{ route('dreamfights.index') }}" class="flex space-x-2">
            <input type="text" name="username" placeholder="Search by username..." value="{{ request('username') }}"
                   class="flex-1 p-2 rounded bg-gray-800 text-white border border-gray-700 focus:ring-2 focus:ring-yellow-400">
            <button type="submit" class="bg-yellow-400 text-black px-4 py-2 rounded hover:bg-yellow-300">Search</button>
            <a href="{{ route('dreamfights.index') }}" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-500">Clear</a>
        </form>
    </div>

    <!-- Create New Dream Fight -->
    <div class="bg-gray-900/50 p-6 rounded-2xl shadow-2xl backdrop-blur-sm mb-8">
        <h2 class="text-2xl font-bold text-yellow-400 mb-4">Create New Dream Fight</h2>
        <form method="POST" action="{{ route('dreamfights.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-white font-medium mb-1">Fighter One</label>
                <div class="flex space-x-2 mb-2">
                    <input type="text" id="search-fighter-one" placeholder="Search fighter..." class="flex-1 p-2 rounded bg-gray-800 text-white border border-gray-700 focus:ring-2 focus:ring-yellow-400">
                    <button type="button" id="clear-fighter-one" class="px-3 py-1 rounded bg-red-600 hover:bg-red-500 text-white">Clear</button>
                </div>
                <select name="fighter_one_name" id="fighter-one" required size="5" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700 focus:ring-2 focus:ring-yellow-400">
                    @foreach($fighters as $fighter)
                        <option value="{{ $fighter['FirstName'] }} {{ $fighter['LastName'] }}" data-weight="{{ $fighter['WeightClass'] }}">
                            {{ $fighter['FirstName'] }} {{ $fighter['LastName'] }}
                            @if(!empty($fighter['Nickname'])) ({{ $fighter['Nickname'] }}) @endif
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-white font-medium mb-1">Fighter Two</label>
                <div class="flex space-x-2 mb-2">
                    <input type="text" id="search-fighter-two" placeholder="Search fighter..." class="flex-1 p-2 rounded bg-gray-800 text-white border border-gray-700 focus:ring-2 focus:ring-yellow-400">
                    <button type="button" id="clear-fighter-two" class="px-3 py-1 rounded bg-red-600 hover:bg-red-500 text-white">Clear</button>
                </div>
                <select name="fighter_two_name" id="fighter-two" required size="5" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700 focus:ring-2 focus:ring-yellow-400">
                    @foreach($fighters as $fighter)
                        <option value="{{ $fighter['FirstName'] }} {{ $fighter['LastName'] }}" data-weight="{{ $fighter['WeightClass'] }}">
                            {{ $fighter['FirstName'] }} {{ $fighter['LastName'] }}
                            @if(!empty($fighter['Nickname'])) ({{ $fighter['Nickname'] }}) @endif
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="bg-yellow-400 text-black font-bold px-6 py-2 rounded hover:bg-yellow-300 shadow hover:shadow-lg transition">Save Dream Fight</button>
        </form>
    </div>

    <!-- List of Dream Fights -->
    <div class="overflow-x-auto bg-gray-900/50 rounded-2xl shadow-2xl backdrop-blur-sm">
        <table class="min-w-full divide-y divide-gray-700 text-white">
            <thead>
                <tr class="bg-gray-800/70">
                    <th class="px-6 py-3 text-left font-medium">Fighter One</th>
                    <th class="px-6 py-3 text-left font-medium">Fighter Two</th>
                    <th class="px-6 py-3 text-left font-medium">Created By</th>
                    <th class="px-6 py-3 text-left font-medium">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
                @forelse($dreamfights as $fight)
                    <tr class="hover:bg-gray-800/40 transition">
                        <td class="px-6 py-3">{{ $fight->fighter_one_name }}</td>
                        <td class="px-6 py-3">{{ $fight->fighter_two_name }}</td>
                        <td class="px-6 py-3">{{ $fight->user->name }}</td>
                        <td class="px-6 py-3 space-x-2">
                            @if(auth()->user()->is_admin || $fight->user_id === auth()->id())
                                <form method="GET" action="{{ route('dreamfights.edit', $fight) }}" class="inline">
                                    <button class="bg-yellow-400 text-black px-3 py-1 rounded hover:bg-yellow-300 transition">Edit</button>
                                </form>
                                <form method="POST" action="{{ route('dreamfights.destroy', $fight) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Delete fight?')" class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-500 transition">Delete</button>
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

<script>
// Filter input for fighter selects
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
</script>

</body>
</html>
