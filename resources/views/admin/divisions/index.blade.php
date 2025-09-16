<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UFC Divisions</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-black text-white min-h-screen">
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

<div class="max-w-7xl mx-auto py-8 px-4 space-y-6">

    <h1 class="text-3xl font-extrabold mb-6 text-yellow-400 text-center">UFC Divisions</h1>

    @if(auth()->check() && auth()->user()->is_admin)
    <form action="{{ route('admin.divisions.store') }}" method="POST" class="mb-8 flex space-x-2">
        @csrf
        <input type="text" name="name" placeholder="New Division" 
               class="flex-1 p-2 rounded-lg border border-gray-700 bg-gray-800 text-white" required>
        <button type="submit" class="bg-blue-600 hover:bg-blue-500 px-4 py-2 rounded-lg text-white font-semibold transition">Add Division</button>
    </form>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($divisions as $division)
        <div class="bg-gray-900 rounded-2xl shadow-lg overflow-hidden">
            <div class="flex justify-between items-center p-6 bg-gray-800">
                <div>
                    <h2 class="text-2xl font-bold text-white">{{ $division->name }}</h2>
                    <span class="text-gray-400 text-sm">{{ $division->rankings->count() }} fighters</span>
                </div>

                @if(auth()->check() && auth()->user()->is_admin)
                <button onclick="document.getElementById('edit-division-{{ $division->id }}').classList.toggle('hidden')" 
                        class="bg-yellow-600 hover:bg-yellow-500 px-3 py-1 rounded-lg text-black font-semibold transition">
                    Edit
                </button>
                @endif
            </div>

            <div class="bg-gray-800 p-4 space-y-2">
                @php
                    $sorted = $division->rankings->sortBy(fn($f) => $f->is_champion ? 0 : $f->rank);
                @endphp

                @foreach($sorted as $fighter)
                <div class="p-3 bg-gray-700 rounded-lg flex items-center justify-between">
                    <span class="text-white font-medium">
                        {{ $fighter->is_champion ? 'C' : $fighter->rank }}. {{ $fighter->fighter_name }}
                    </span>

                    @if(auth()->check() && auth()->user()->is_admin)
                    <form action="{{ route('admin.fighters.destroy', $fighter) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                onclick="return confirm('Are you sure you want to delete this fighter?');"
                                class="bg-red-600 hover:bg-red-500 px-3 py-1 rounded-lg font-semibold transition">
                            Delete
                        </button>
                    </form>
                    @endif
                </div>
                @endforeach
            </div>

            @if(auth()->check() && auth()->user()->is_admin)
            <div id="edit-division-{{ $division->id }}" class="hidden bg-gray-900 p-4">
                <form action="{{ route('admin.divisions.update', $division) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label class="block text-sm text-gray-300">Division Name</label>
                        <input type="text" name="name" value="{{ $division->name }}" 
                               class="w-full p-2 rounded bg-gray-700 text-white">
                    </div>

                    <h3 class="text-lg font-bold text-yellow-400">Fighters</h3>
                    @foreach($division->rankings as $fighter)
                    <div class="p-3 bg-gray-800 rounded-lg space-y-2">
                        <input type="text" name="fighters[{{ $fighter->id }}][fighter_name]" 
                               value="{{ $fighter->fighter_name }}" 
                               class="w-full p-2 rounded bg-gray-700 text-white">

                        @php
                            $maxRank = $fighter->is_champion ? 16 : 15;
                        @endphp
                        <input type="number" name="fighters[{{ $fighter->id }}][rank]" 
                               value="{{ $fighter->rank }}" min="1" max="{{ $maxRank }}"
                               class="w-full p-2 rounded bg-gray-700 text-white">

                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="fighters[{{ $fighter->id }}][is_champion]" value="1" 
                                   {{ $fighter->is_champion ? 'checked' : '' }}>
                            <span>Champion</span>
                        </label>
                    </div>
                    @endforeach

                    <div class="p-3 bg-gray-800 rounded-lg space-y-2">
                        <input type="text" name="new_fighter[fighter_name]" placeholder="New Fighter Name"
                               class="w-full p-2 rounded bg-gray-700 text-white">
                        <input type="number" name="new_fighter[rank]" placeholder="Rank" min="1" max="15"
                               class="w-full p-2 rounded bg-gray-700 text-white">
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="new_fighter[is_champion]" value="1">
                            <span>Champion</span>
                        </label>
                    </div>

                    <button type="submit" class="bg-green-600 hover:bg-green-500 px-3 py-2 rounded-lg font-semibold transition w-full">
                        Save Changes
                    </button>
                </form>

                <form action="{{ route('admin.divisions.destroy', $division) }}" method="POST" class="mt-4">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="bg-red-600 hover:bg-red-500 px-3 py-2 rounded-lg font-semibold transition w-full">
                        Delete Division
                    </button>
                </form>
            </div>
            @endif
        </div>
        @endforeach
    </div>

</div>
</body>
</html>
