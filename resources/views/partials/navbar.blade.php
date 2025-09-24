<nav class="bg-lightBg dark:bg-darkBg/90 backdrop-blur-md shadow-lg transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <!-- Logo -->
            <div class="flex-shrink-0 flex items-center">
                <a href="{{ route('dashboard') }}" class="text-2xl font-bold text-primary dark:text-red-400 hover:text-red-600 dark:hover:text-red-300 transition">
                    UFC MMA
                </a>
            </div>

            <!-- Menu links -->
            <div class="hidden md:flex space-x-6 items-center text-textLight dark:text-textDark">
                <a href="{{ route('dashboard') }}" class="hover:text-primary dark:hover:text-red-400 transition">Dashboard</a>
                <a href="{{ route('ranking') }}" class="hover:text-primary dark:hover:text-red-400 transition">Ranking</a>
                <a href="{{ route('pound') }}" class="hover:text-primary dark:hover:text-red-400 transition">Pound for Pound</a>
                <a href="{{ route('news') }}" class="hover:text-primary dark:hover:text-red-400 transition">News</a>
                <a href="{{ route('dreamfights.index') }}" class="hover:text-primary dark:hover:text-red-400 transition">Dream Fights</a>

                @if(auth()->check() && auth()->user()->is_admin)
                    <a href="{{ route('admin.divisions.index') }}" class="text-textLight dark:text-textDark hover:underline font-semibold transition">
                        Admin: Divisions
                    </a>
                @endif
            </div>

            <!-- Right controls -->
            <div class="flex items-center space-x-4">
                <!-- Dark/Light Mode Toggle -->
                <button id="theme-toggle" class="p-2 rounded-full bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 transition focus:outline-none">
                    <svg id="theme-icon" class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2a8 8 0 100 16 8 8 0 000-16z"/>
                    </svg>
                </button>

                @auth
                    <span class="text-gray-800 dark:text-gray-200 font-medium">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="bg-primary dark:bg-red-500 hover:bg-red-600 dark:hover:bg-red-400 text-white px-3 py-1 rounded transition">
                            Logout
                        </button>
                    </form>
                @endauth
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden flex items-center">
                <button id="mobile-menu-btn" class="text-textLight dark:text-textDark focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div id="mobile-menu" class="hidden md:hidden bg-lightBg dark:bg-darkBg/95 backdrop-blur-md">
        <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-textLight dark:text-textDark hover:bg-gray-800">Dashboard</a>
        <a href="{{ route('ranking') }}" class="block px-4 py-2 text-textLight dark:text-textDark hover:bg-gray-800">Ranking</a>
        <a href="{{ route('pound') }}" class="block px-4 py-2 text-textLight dark:text-textDark hover:bg-gray-800">Pound for Pound</a>
        <a href="{{ route('news') }}" class="block px-4 py-2 text-textLight dark:text-textDark hover:bg-gray-800">News</a>
        <a href="{{ route('dreamfights.index') }}" class="block px-4 py-2 text-textLight dark:text-textDark hover:bg-gray-800">Dream Fights</a>

        @if(auth()->check() && auth()->user()->is_admin)
            <a href="{{ route('admin.divisions.index') }}" class="block px-4 py-2 text-textLight dark:text-textDark hover:underline font-semibold">Admin: Divisions</a>
        @endif

        @auth
            <form method="POST" action="{{ route('logout') }}" class="px-4 py-2">
                <span class="block text-gray-800 dark:text-gray-200 mb-2 font-medium">{{ auth()->user()->name }}</span>
                <button type="submit" class="w-full bg-primary dark:bg-red-500 hover:bg-red-600 dark:hover:bg-red-400 text-white px-3 py-2 rounded transition">Logout</button>
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

    // Dark/Light mode toggle
    const themeToggle = document.getElementById('theme-toggle');
    const html = document.documentElement;
    const themeIcon = document.getElementById('theme-icon');

    const setIcon = () => {
        if(html.classList.contains('dark')){
            themeIcon.innerHTML = '<path d="M10 2a8 8 0 100 16 8 8 0 000-16z"/>'; // moon icon
        } else {
            themeIcon.innerHTML = '<path d="M10 2a8 8 0 100 16 8 8 0 000-16z"/>'; // sun icon
        }
    };

    themeToggle.addEventListener('click', () => {
        html.classList.toggle('dark');
        localStorage.setItem('theme', html.classList.contains('dark') ? 'dark' : 'light');
        setIcon();
    });

    // Apply saved theme on load
    if(localStorage.getItem('theme') === 'dark'){
        html.classList.add('dark');
    } else {
        html.classList.remove('dark');
    }
    setIcon();
</script>
