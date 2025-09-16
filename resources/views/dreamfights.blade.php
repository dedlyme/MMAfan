<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dream Fights - UFC MMA</title>
@vite('resources/css/app.css')

<nav class="sticky top-0 z-50 bg-gradient-to-r from-black/90 via-gray-900/90 to-black/90 backdrop-blur-md shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            
            <!-- Logo / Brand -->
            <a href="{{ route('dashboard') }}" class="text-2xl font-extrabold text-yellow-400 tracking-wide">
                UFC MMA Dashboard
            </a>

            <!-- Desktop Menu -->
            <ul class="hidden md:flex space-x-8 font-medium">
                <li>
                    <a href="{{ route('dashboard') }}" 
                       class="relative text-white hover:text-yellow-400 transition group">
                        Home
                        <span class="absolute left-0 -bottom-1 w-0 h-0.5 bg-yellow-400 transition-all group-hover:w-full"></span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('ranking') }}" 
                       class="relative text-white hover:text-yellow-400 transition group">
                        Ranking
                        <span class="absolute left-0 -bottom-1 w-0 h-0.5 bg-yellow-400 transition-all group-hover:w-full"></span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('pound') }}" 
                       class="relative text-white hover:text-yellow-400 transition group">
                        Pound for Pound
                        <span class="absolute left-0 -bottom-1 w-0 h-0.5 bg-yellow-400 transition-all group-hover:w-full"></span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('news') }}" 
                       class="relative text-white hover:text-yellow-400 transition group">
                        News
                        <span class="absolute left-0 -bottom-1 w-0 h-0.5 bg-yellow-400 transition-all group-hover:w-full"></span>
                    </a>
                </li>

                <!-- Dream Fights Link -->
                <li>
                    <a href="{{ route('dreamfights.index') }}" 
                       class="relative text-white hover:text-yellow-400 transition group">
                        Dream Fights
                        <span class="absolute left-0 -bottom-1 w-0 h-0.5 bg-yellow-400 transition-all group-hover:w-full"></span>
                    </a>
                </li>

                @if(auth()->check() && auth()->user()->is_admin)
                    <li>
                        <a href="{{ route('admin.divisions.index') }}" 
                           class="relative text-white hover:text-yellow-400 transition group">
                            Divisions
                            <span class="absolute left-0 -bottom-1 w-0 h-0.5 bg-yellow-400 transition-all group-hover:w-full"></span>
                        </a>
                    </li>
                @endif

                <li>
                    <a href="{{ route('profile.edit') }}" 
                       class="relative text-white hover:text-yellow-400 transition group">
                        Profile
                        <span class="absolute left-0 -bottom-1 w-0 h-0.5 bg-yellow-400 transition-all group-hover:w-full"></span>
                    </a>
                </li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" 
                                class="relative text-white hover:text-red-400 transition group">
                            Logout
                            <span class="absolute left-0 -bottom-1 w-0 h-0.5 bg-red-400 transition-all group-hover:w-full"></span>
                        </button>
                    </form>
                </li>
            </ul>

            <!-- Mobile Hamburger -->
            <div class="md:hidden flex items-center">
                <button id="mobile-menu-btn" class="text-white hover:text-yellow-400 focus:outline-none">
                    <!-- Hamburger Icon -->
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden md:hidden bg-gray-900/95 px-4 pb-4 space-y-2">
        <a href="{{ route('dashboard') }}" class="block text-white hover:text-yellow-400">Home</a>
        <a href="{{ route('ranking') }}" class="block text-white hover:text-yellow-400">Ranking</a>
        <a href="{{ route('pound') }}" class="block text-white hover:text-yellow-400">Pound for Pound</a>
        <a href="{{ route('news') }}" class="block text-white hover:text-yellow-400">News</a>
        <!-- Dream Fights Link -->
        <a href="{{ route('dreamfights.index') }}" class="block text-white hover:text-yellow-400">Dream Fights</a>
        @if(auth()->check() && auth()->user()->is_admin)
            <a href="{{ route('admin.divisions.index') }}" class="block text-white hover:text-yellow-400">Divisions</a>
        @endif
        <a href="{{ route('profile.edit') }}" class="block text-white hover:text-yellow-400">Profile</a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="block text-left text-red-400 hover:text-red-300 w-full">Logout</button>
        </form>
    </div>
</nav>
</head>

<body class="bg-black text-white min-h-screen">

<div class="container mx-auto py-12 px-4">
    <h1 class="text-4xl font-extrabold text-yellow-400 mb-8">Dream Fights</h1>

    @if(session('success'))
        <div class="bg-green-600 text-white p-4 mb-6 rounded shadow">
            {{ session('success') }}
        </div>
    @endif

    <!-- Create New Dream Fight -->
    <div class="bg-gray-900/50 p-6 rounded-2xl shadow-2xl backdrop-blur-sm mb-8">
        <h2 class="text-2xl font-bold text-yellow-400 mb-4">Create New Dream Fight</h2>
        <form method="POST" action="{{ route('dreamfights.store') }}" class="space-y-4">
            @csrf

            <!-- Fighter One -->
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

            <!-- Fighter Two -->
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

            <button type="submit" class="bg-yellow-400 text-black font-bold px-6 py-2 rounded hover:bg-yellow-300 shadow hover:shadow-lg transition">
                Save Dream Fight
            </button>
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
                @foreach($dreamfights as $fight)
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
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
// Filter input
function filterSelect(inputId, selectId) {
    const input = document.getElementById(inputId);
    const select = document.getElementById(selectId);

    input.addEventListener('keyup', function() {
        const filter = input.value.toLowerCase();
        Array.from(select.options).forEach(option => {
            option.style.display = option.text.toLowerCase().includes(filter) ? '' : 'none';
        });

        // Auto-select first visible
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
