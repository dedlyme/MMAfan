<nav class="bg-gray-900/90 backdrop-blur-md shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Logo -->
            <div class="flex-shrink-0 flex items-center">
                <a href="{{ route('dashboard') }}" class="text-2xl font-bold text-red-500 hover:text-red-400 transition">
                    UFC MMA
                </a>
            </div>

            <!-- Menu links -->
            <div class="hidden md:flex space-x-6 items-center">
                <a href="{{ route('dashboard') }}" class="text-white hover:text-red-500 transition">Dashboard</a>
                <a href="{{ route('ranking') }}" class="text-white hover:text-red-500 transition">Ranking</a>
                <a href="{{ route('pound') }}" class="text-white hover:text-red-500 transition">Pound for Pound</a>
                <a href="{{ route('news') }}" class="text-white hover:text-red-500 transition">News</a>
                <a href="{{ route('dreamfights.index') }}" class="text-white hover:text-red-500 transition">Dream Fights</a>

                @if(auth()->check() && auth()->user()->is_admin)
                    <a href="{{ route('admin.divisions.index') }}" class="text-yellow-400 hover:text-yellow-300 font-semibold transition">
                        Admin: Divisions
                    </a>
                @endif
            </div>

            <!-- User menu -->
            <div class="flex items-center space-x-4">
                @auth
                    <span class="text-gray-300">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded transition">
                            Logout
                        </button>
                    </form>
                @endauth
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden flex items-center">
                <button id="mobile-menu-btn" class="text-white focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div id="mobile-menu" class="hidden md:hidden bg-gray-900/95 backdrop-blur-md">
        <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-white hover:bg-gray-800">Dashboard</a>
        <a href="{{ route('ranking') }}" class="block px-4 py-2 text-white hover:bg-gray-800">Ranking</a>
        <a href="{{ route('pound') }}" class="block px-4 py-2 text-white hover:bg-gray-800">Pound for Pound</a>
        <a href="{{ route('news') }}" class="block px-4 py-2 text-white hover:bg-gray-800">News</a>
        <a href="{{ route('dreamfights.index') }}" class="block px-4 py-2 text-white hover:bg-gray-800">Dream Fights</a>

        @if(auth()->check() && auth()->user()->is_admin)
            <a href="{{ route('admin.divisions.index') }}" class="block px-4 py-2 text-yellow-400 hover:text-yellow-300">Admin: Divisions</a>
        @endif

        @auth
            <form method="POST" action="{{ route('logout') }}" class="px-4 py-2">
                @csrf
                <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded transition">Logout</button>
            </form>
        @endauth
    </div>
</nav>

<script>
    // Mobile menu toggle
    const btn = document.getElementById('mobile-menu-btn');
    const menu = document.getElementById('mobile-menu');
    if(btn){
        btn.addEventListener('click', () => {
            menu.classList.toggle('hidden');
        });
    }
</script>
