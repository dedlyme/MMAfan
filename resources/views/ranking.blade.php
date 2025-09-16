<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>UFC Rankings</title>
@vite('resources/css/app.css')
<style>
    body::before {
        content: "";
        position: fixed;
        inset: 0;
        background: url('{{ asset("wallpaper.png") }}') center/cover no-repeat;
        filter: brightness(0.5) blur(4px);
        z-index: -1;
    }

    /* Neliela fade animācija rotējošajam konteineram */
    #rotating-container {
        transition: opacity 0.6s ease;
    }
</style>
</head>
<body class="text-white min-h-screen relative">

<!-- Navigation -->
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

<div class="py-10 px-6 max-w-7xl mx-auto space-y-10">

    <h1 class="text-5xl md:text-6xl font-extrabold text-yellow-400 text-center mb-10 drop-shadow-lg">UFC Rankings</h1>

    <!-- Divisions Grid -->
    @if(isset($divisions) && $divisions->isNotEmpty())
        <h2 class="text-3xl font-bold mb-6 text-white drop-shadow">Divisions</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($divisions as $d)
                <a href="{{ route('ranking.show', $d) }}" 
                   class="bg-gray-900/50 hover:bg-gray-800/70 backdrop-blur-sm p-6 rounded-3xl shadow-2xl transform transition duration-300 hover:scale-105 flex flex-col items-center justify-center text-center hover:shadow-yellow-400/50">
                    <h3 class="text-2xl font-bold text-white mb-2">{{ $d->name }}</h3>
                    <span class="text-gray-400 text-sm">{{ $d->rankings->count() }} fighters</span>
                </a>
            @endforeach
        </div>
    @endif

    <!-- Rotating Division Top 16 -->
    <div class="mt-12 space-y-6">
        <h2 id="rotating-title" class="text-4xl font-bold mb-6 text-yellow-400 drop-shadow">Top Fighters</h2>
        <div id="rotating-container" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 opacity-100">
            <!-- JS will inject fighters here -->
            <div id="rotating-placeholder" class="col-span-full text-center text-gray-400">
                Loading...
            </div>
        </div>
    </div>

</div>

@php
    // Sagatavojam drošu masīvu JS izmantošanai
    $divisionsForJs = [];

    if (isset($divisions) && $divisions->isNotEmpty()) {
        foreach ($divisions as $d) {
            $rankings = $d->rankings->sortBy(function($f){
                return $f->is_champion ? 0 : $f->rank;
            })->values();

            $rankingsArr = [];
            foreach ($rankings as $f) {
                $rankingsArr[] = [
                    'fighter_name' => $f->fighter_name,
                    'rank' => $f->rank,
                    'is_champion' => (bool) $f->is_champion,
                ];
            }

            $divisionsForJs[] = [
                'name' => $d->name,
                'rankings' => $rankingsArr,
            ];
        }
    } elseif (isset($division)) {
        $rankings = $division->rankings->sortBy(function($f){
            return $f->is_champion ? 0 : $f->rank;
        })->values();

        $rankingsArr = [];
        foreach ($rankings as $f) {
            $rankingsArr[] = [
                'fighter_name' => $f->fighter_name,
                'rank' => $f->rank,
                'is_champion' => (bool) $f->is_champion,
            ];
        }

        $divisionsForJs[] = [
            'name' => $division->name,
            'rankings' => $rankingsArr,
        ];
    }
@endphp

<script>
    // divisionsForJs no PHP (droši JSON-ēts)
    const divisions = {!! json_encode($divisionsForJs, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT) !!} || [];
    let currentIndex = 0;
    const titleEl = document.getElementById('rotating-title');
    const containerEl = document.getElementById('rotating-container');
    const placeholder = document.getElementById('rotating-placeholder');

    function renderDivision(index){
        if (!divisions || divisions.length === 0) {
            titleEl.textContent = "No divisions available";
            containerEl.innerHTML = '<div class="col-span-full text-center text-gray-400">No fighters to show.</div>';
            return;
        }

        const div = divisions[index];
        titleEl.textContent = div.name + " - Top 15 + Champion";

        // Fade out
        containerEl.style.opacity = 0;

        // small timeout to allow fade-out
        setTimeout(() => {
            containerEl.innerHTML = ''; // clear previous

            if (!div.rankings || div.rankings.length === 0) {
                containerEl.innerHTML = '<div class="col-span-full text-center text-gray-400">Šajā divīzijā vēl nav ierakstu.</div>';
                containerEl.style.opacity = 1;
                return;
            }

            // create grid
            const grid = document.createElement('div');
            grid.className = "grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 col-span-full";

            div.rankings.forEach(f => {
                const fighterEl = document.createElement('div');
                fighterEl.className = "bg-gray-900/50 hover:bg-gray-800/70 backdrop-blur-sm p-4 rounded-2xl shadow-lg flex justify-between items-center transition duration-300 transform hover:scale-105 hover:shadow-yellow-400/50";

                const left = document.createElement('div');
                left.innerHTML = `<span class="text-xl font-semibold text-white">${f.is_champion ? 'C' : f.rank}. ${f.fighter_name}</span>`;
                if (f.is_champion) {
                    left.innerHTML += ' <span class="text-yellow-400 font-bold ml-2">Champion</span>';
                }

                fighterEl.appendChild(left);
                grid.appendChild(fighterEl);
            });

            containerEl.appendChild(grid);

            // Fade in
            containerEl.style.opacity = 1;
        }, 180); // small fade delay
    }

    // initial render
    renderDivision(currentIndex);

    // rotate if more than one division
    if (divisions.length > 1) {
        setInterval(() => {
            currentIndex = (currentIndex + 1) % divisions.length;
            renderDivision(currentIndex);
        }, 10000); // ik pēc 10 sekundēm
    }


    // Mobile menu toggle
    const btn = document.getElementById('mobile-menu-btn');
    const menu = document.getElementById('mobile-menu');
    btn.addEventListener('click', () => {
        menu.classList.toggle('hidden');
    });

</script>
<div class="max-w-4xl mx-auto py-12 px-4">

</body>
</html>
