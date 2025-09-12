<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>UFC MMA Dashboard</title>
@vite('resources/css/app.css')
<style>
    /* Fona slideshow */
    .slideshow {
        position: fixed;
        inset: 0;
        z-index: -1;
        width: 100%;
        height: 100%;
        overflow: hidden;
    }
    .slideshow img {
        position: absolute;
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: 0;
        animation: slideShow 25s infinite;
        filter: brightness(0.7);
    }
    .slideshow img:nth-child(1) { animation-delay: 0s; }
    .slideshow img:nth-child(2) { animation-delay: 5s; }
    .slideshow img:nth-child(3) { animation-delay: 10s; }
    .slideshow img:nth-child(4) { animation-delay: 15s; }
    .slideshow img:nth-child(5) { animation-delay: 20s; }

    @keyframes slideShow {
        0% { opacity: 0; }
        10% { opacity: 1; }
        20% { opacity: 1; }
        30% { opacity: 0; }
        100% { opacity: 0; }
    }
</style>
</head>
<body class="bg-black text-white min-h-screen relative">

<!-- Fona slideshow -->
<div class="slideshow">
    <img src="{{ asset('wallpaper.png') }}" alt="">
    <img src="{{ asset('wallpaper2.png') }}" alt="">
    <img src="{{ asset('wallpaper3.png') }}" alt="">
    <img src="{{ asset('wallpaper4.png') }}" alt="">
    <img src="{{ asset('wallpaper5.png') }}" alt="">
</div>

<!-- Dark overlay -->
<div class="fixed inset-0 bg-black/40 z-0"></div>

<!-- Main content -->
<div class="relative z-10">

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

    <!-- Dashboard content -->
    <div class="py-8 px-6 max-w-7xl mx-auto space-y-6">

        <!-- Chat Card -->
        <div class="bg-gray-900/30 text-white overflow-hidden shadow-2xl rounded-2xl p-6 hover:scale-105 transform transition duration-300 backdrop-blur-sm border border-gray-700/20">
            <h2 class="text-2xl font-bold mb-4 text-yellow-400">Live Chat</h2>
            <div id="chat" class="flex flex-col h-96 border border-gray-700/20 rounded p-4 bg-gray-900/20 overflow-y-auto backdrop-blur-sm">
                <div id="messages" class="flex-1 mb-4 space-y-2">
                    @foreach($messages as $msg)
                        <div class="px-2 py-1 bg-gray-900/30 rounded">
                            <b>{{ $msg->user->name }}:</b> {{ $msg->message }}
                        </div>
                    @endforeach
                </div>
                <form action="{{ route('messages.send') }}" method="POST" class="flex">
                    @csrf
                    <input type="text" name="message" placeholder="Type message..." required
                           class="border border-gray-600/30 p-2 flex-1 rounded bg-gray-900/20 text-white focus:ring-2 focus:ring-yellow-400 transition backdrop-blur-sm">
                    <button type="submit" class="bg-yellow-400 hover:bg-yellow-300 text-black px-4 ml-2 rounded shadow hover:shadow-lg transition">Send</button>
                </form>
            </div>
        </div>

        <!-- Dashboard Sections -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-gray-900/50 text-white p-6 rounded-2xl shadow-2xl hover:scale-105 transform transition duration-300 backdrop-blur-sm">
                <h3 class="text-xl font-bold mb-2 text-yellow-400">Homepage</h3>
                <p>Chat about news in Live chat feature.</p>
            </div>

            <div class="bg-gray-900/50 text-white p-6 rounded-2xl shadow-2xl hover:scale-105 transform transition duration-300 backdrop-blur-sm">
                <h3 class="text-xl font-bold mb-2 text-yellow-400">Ranking</h3>
                <p>Current fighter rankings by category.</p>
            </div>

            <div class="bg-gray-900/50 text-white p-6 rounded-2xl shadow-2xl hover:scale-105 transform transition duration-300 backdrop-blur-sm">
                <h3 class="text-xl font-bold mb-2 text-yellow-400">Pound for Pound</h3>
                <p>Top fighters across all weight classes.</p>
            </div>

            <div class="bg-gray-900/50 text-white p-6 rounded-2xl shadow-2xl hover:scale-105 transform transition duration-300 backdrop-blur-sm">
                <h3 class="text-xl font-bold mb-2 text-yellow-400">News</h3>
                <p>Latest news and updates from the world of MMA and UFC.</p>
            </div>
        </div>

    </div>
</div>
<script>
 
    // Mobile menu toggle
    const btn = document.getElementById('mobile-menu-btn');
    const menu = document.getElementById('mobile-menu');
    btn.addEventListener('click', () => {
        menu.classList.toggle('hidden');
    });

    // Auto scroll chat uz apakš
    window.addEventListener('load', () => {
        const chatBox = document.getElementById('chat');
        if (chatBox) {
            chatBox.scrollTop = chatBox.scrollHeight;
        }
    });

</script>

<div class="max-w-4xl mx-auto py-12 px-4">
</body>
</html>
