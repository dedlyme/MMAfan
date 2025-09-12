<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UFC News</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-black text-white min-h-screen relative">

    <!-- Background image -->
    <div class="fixed inset-0 z-0">
        <img src="{{ asset('wallpaper.png') }}" alt="Background" class="w-full h-full object-cover filter brightness-50">
    </div>

    <!-- Dark overlay -->
    <div class="fixed inset-0 bg-black/40 z-0"></div>

    <!-- Navigation bar -->
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
                        <a href="{{ route('dashboard') }}" class="relative text-white hover:text-yellow-400 transition group">
                            Home
                            <span class="absolute left-0 -bottom-1 w-0 h-0.5 bg-yellow-400 transition-all group-hover:w-full"></span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('ranking') }}" class="relative text-white hover:text-yellow-400 transition group">
                            Ranking
                            <span class="absolute left-0 -bottom-1 w-0 h-0.5 bg-yellow-400 transition-all group-hover:w-full"></span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('pound') }}" class="relative text-white hover:text-yellow-400 transition group">
                            Pound for Pound
                            <span class="absolute left-0 -bottom-1 w-0 h-0.5 bg-yellow-400 transition-all group-hover:w-full"></span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('news') }}" class="relative text-white hover:text-yellow-400 transition group">
                            News
                            <span class="absolute left-0 -bottom-1 w-0 h-0.5 bg-yellow-400 transition-all group-hover:w-full"></span>
                        </a>
                    </li>
                    @if(auth()->check() && auth()->user()->is_admin)
                        <li>
                            <a href="{{ route('admin.divisions.index') }}" class="relative text-white hover:text-yellow-400 transition group">
                                Divisions
                                <span class="absolute left-0 -bottom-1 w-0 h-0.5 bg-yellow-400 transition-all group-hover:w-full"></span>
                            </a>
                        </li>
                    @endif
                    <li>
                        <a href="{{ route('profile.edit') }}" class="relative text-white hover:text-yellow-400 transition group">
                            Profile
                            <span class="absolute left-0 -bottom-1 w-0 h-0.5 bg-yellow-400 transition-all group-hover:w-full"></span>
                        </a>
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="relative text-white hover:text-red-400 transition group">
                                Logout
                                <span class="absolute left-0 -bottom-1 w-0 h-0.5 bg-red-400 transition-all group-hover:w-full"></span>
                            </button>
                        </form>
                    </li>
                </ul>

                <!-- Mobile Hamburger -->
                <div class="md:hidden flex items-center">
                    <button id="mobile-menu-btn" class="text-white hover:text-yellow-400 focus:outline-none">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
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

    <!-- Main News Content -->
    <div class="relative z-10 py-12 px-4 max-w-7xl mx-auto">

        <h1 class="text-4xl md:text-5xl font-extrabold text-yellow-400 mb-8 text-center">Latest UFC News</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($newsItems as $item)
                <div class="bg-gray-900/60 text-white rounded-2xl shadow-2xl backdrop-blur-sm border border-gray-700/30 hover:scale-105 transform transition duration-300 overflow-hidden">
                    @if($item->get_enclosure())
                        <img src="{{ $item->get_enclosure()->link }}" alt="News Image" class="w-full h-48 object-cover">
                    @endif
                    <div class="p-6">
                        <a href="{{ $item->get_link() }}" target="_blank" class="text-xl md:text-2xl font-bold text-yellow-400 hover:underline">
                            {{ $item->get_title() }}
                        </a>
                        <p class="text-gray-300 text-sm mt-2 mb-4">{{ $item->get_date('F j, Y') }}</p>
                        <p class="text-gray-200 text-sm line-clamp-4">{!! $item->get_description() !!}</p>
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-300 col-span-full">No news available at the moment. Please check back later.</p>
            @endforelse
        </div>
    </div>

    <script>
        // Mobile menu toggle
        const btn = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');
        btn.addEventListener('click', () => {
            menu.classList.toggle('hidden');
        });
    </script>

</body>
</html>
