<nav class="bg-lightBg dark:bg-darkBg backdrop-blur-md shadow-lg transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <!-- Logo -->
            <div class="flex-shrink-0 flex items-center">
                <a href="{{ route('dashboard') }}"
                   class="text-2xl font-bold text-primary hover:text-red-600 transition">
                    UFC MMA
                </a>
            </div>

            <!-- Desktop Menu -->
            <div class="hidden md:flex space-x-6 items-center text-textLight dark:text-white">
                <a href="{{ route('dashboard') }}" class="hover:text-primary transition">Dashboard</a>
                <a href="{{ route('ranking') }}" class="hover:text-primary transition">Ranking</a>
                <a href="{{ route('pound') }}" class="hover:text-primary transition">Pound for Pound</a>
                <a href="{{ route('news') }}" class="hover:text-primary transition">News</a>
                <a href="{{ route('dreamfights.index') }}" class="hover:text-primary transition">Dream Fights</a>

                @if(auth()->check() && auth()->user()->is_admin)
                    <a href="{{ route('admin.divisions.index') }}"
                       class="text-textLight dark:text-white hover:underline font-semibold transition">
                        Admin: Divisions
                    </a>
                @endif
            </div>

            <!-- Right controls -->
            <div class="flex items-center space-x-4 relative">
                <!-- Dark/Light Toggle -->
                <button id="theme-toggle"
                        class="p-2 rounded-full bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 transition focus:outline-none">
                    <svg id="theme-icon" class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2a8 8 0 100 16 8 8 0 000-16z"/>
                    </svg>
                </button>

                @auth
                    <!-- User Dropdown -->
                    <div class="relative">
                        <button id="userMenuBtn"
                                class="flex items-center space-x-2 font-medium text-gray-800 dark:text-gray-200 hover:text-red-500 transition focus:outline-none">
                            <span>{{ auth()->user()?->name }}</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- Dropdown -->
                        <div id="userMenu"
                             class="hidden absolute right-0 mt-2 w-40 bg-white dark:bg-gray-800 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700 z-50">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        class="w-full text-left block px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @endauth
            </div>

            <!-- Mobile Burger -->
            <div class="md:hidden flex items-center">
                <button id="mobile-menu-btn" class="text-textLight dark:text-gray-200 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden md:hidden bg-lightBg dark:bg-darkBg">
        <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-textLight dark:text-white hover:bg-gray-800">Dashboard</a>
        <a href="{{ route('ranking') }}" class="block px-4 py-2 text-textLight dark:text-white hover:bg-gray-800">Ranking</a>
        <a href="{{ route('pound') }}" class="block px-4 py-2 text-textLight dark:text-white hover:bg-gray-800">Pound for Pound</a>
        <a href="{{ route('news') }}" class="block px-4 py-2 text-textLight dark:text-white hover:bg-gray-800">News</a>
        <a href="{{ route('dreamfights.index') }}" class="block px-4 py-2 text-textLight dark:text-white hover:bg-gray-800">Dream Fights</a>

        @if(auth()->check() && auth()->user()->is_admin)
            <a href="{{ route('admin.divisions.index') }}"
               class="block px-4 py-2 text-textLight dark:text-white hover:bg-gray-800 font-semibold">
                Admin: Divisions
            </a>
        @endif

        @auth
            <form method="POST" action="{{ route('logout') }}" class="px-4 py-2 border-t border-gray-200 dark:border-gray-700">
                @csrf
                <button type="submit"
                        class="w-full bg-primary hover:bg-red-600 text-white px-3 py-2 rounded transition">
                    Logout
                </button>
            </form>
        @endauth
    </div>
</nav>

<script>
    // Mobile Menu Toggle
    const btn = document.getElementById('mobile-menu-btn');
    const menu = document.getElementById('mobile-menu');
    if (btn) {
        btn.addEventListener('click', () => menu.classList.toggle('hidden'));
    }

    // Theme Toggle
    const themeToggle = document.getElementById('theme-toggle');
    const html = document.documentElement;
    const themeIcon = document.getElementById('theme-icon');
    const setIcon = () => {
        themeIcon.innerHTML = html.classList.contains('dark')
            ? '<path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />'
            : '<path d="M10 2a8 8 0 100 16 8 8 0 000-16z"/>';
    };
    themeToggle.addEventListener('click', () => {
        html.classList.toggle('dark');
        localStorage.setItem('theme', html.classList.contains('dark') ? 'dark' : 'light');
        setIcon();
    });
    if (localStorage.getItem('theme') === 'dark') html.classList.add('dark');
    setIcon();

    // User Menu Dropdown
    const userBtn = document.getElementById('userMenuBtn');
    const userMenu = document.getElementById('userMenu');
    if (userBtn) {
        userBtn.addEventListener('click', () => userMenu.classList.toggle('hidden'));
        document.addEventListener('click', e => {
            if (!userBtn.contains(e.target) && !userMenu.contains(e.target)) {
                userMenu.classList.add('hidden');
            }
        });
    }
</script>
