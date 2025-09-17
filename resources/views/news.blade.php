<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UFC News</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-black text-white min-h-screen relative">

<nav class="sticky top-0 z-50 bg-gradient-to-r from-black/90 via-gray-900/90 to-black/90 backdrop-blur-md shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <a href="{{ route('dashboard') }}" class="text-2xl font-extrabold text-yellow-400 tracking-wide">
                UFC MMA Dashboard
            </a>
            <ul class="hidden md:flex space-x-8 font-medium">
                <li><a href="{{ route('dashboard') }}" class="relative text-white hover:text-yellow-400 transition group">Home<span class="absolute left-0 -bottom-1 w-0 h-0.5 bg-yellow-400 transition-all group-hover:w-full"></span></a></li>
                <li><a href="{{ route('ranking') }}" class="relative text-white hover:text-yellow-400 transition group">Ranking<span class="absolute left-0 -bottom-1 w-0 h-0.5 bg-yellow-400 transition-all group-hover:w-full"></span></a></li>
                <li><a href="{{ route('pound') }}" class="relative text-white hover:text-yellow-400 transition group">Pound for Pound<span class="absolute left-0 -bottom-1 w-0 h-0.5 bg-yellow-400 transition-all group-hover:w-full"></span></a></li>
                <li><a href="{{ route('news') }}" class="relative text-white hover:text-yellow-400 transition group">News<span class="absolute left-0 -bottom-1 w-0 h-0.5 bg-yellow-400 transition-all group-hover:w-full"></span></a></li>
                <li><a href="{{ route('dreamfights.index') }}" class="relative text-white hover:text-yellow-400 transition group">Dream Fights<span class="absolute left-0 -bottom-1 w-0 h-0.5 bg-yellow-400 transition-all group-hover:w-full"></span></a></li>
                @if(auth()->check() && auth()->user()->is_admin)
                    <li><a href="{{ route('admin.divisions.index') }}" class="relative text-white hover:text-yellow-400 transition group">Divisions<span class="absolute left-0 -bottom-1 w-0 h-0.5 bg-yellow-400 transition-all group-hover:w-full"></span></a></li>
                @endif
                <li><a href="{{ route('profile.edit') }}" class="relative text-white hover:text-yellow-400 transition group">Profile<span class="absolute left-0 -bottom-1 w-0 h-0.5 bg-yellow-400 transition-all group-hover:w-full"></span></a></li>
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
            <div class="md:hidden flex items-center">
                <button id="mobile-menu-btn" class="text-white hover:text-yellow-400 focus:outline-none">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
    <div id="mobile-menu" class="hidden md:hidden bg-gray-900/95 px-4 pb-4 space-y-2">
        <a href="{{ route('dashboard') }}" class="block text-white hover:text-yellow-400">Home</a>
        <a href="{{ route('ranking') }}" class="block text-white hover:text-yellow-400">Ranking</a>
        <a href="{{ route('pound') }}" class="block text-white hover:text-yellow-400">Pound for Pound</a>
        <a href="{{ route('news') }}" class="block text-white hover:text-yellow-400">News</a>
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

<div class="fixed inset-0 bg-black/40 z-0"></div>

<div class="relative z-10 py-12 px-4 max-w-5xl mx-auto">

    <h1 class="text-4xl md:text-5xl font-extrabold text-yellow-400 mb-8 text-center">
        Latest MMA / UFC News
    </h1>

    {{-- Search & Filter Form --}}
    <form method="GET" action="{{ route('news') }}" class="mb-8 flex flex-col md:flex-row gap-4">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by title..." 
            class="flex-1 px-4 py-2 rounded-xl bg-gray-800 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-400" />

        <input type="date" name="from_date" value="{{ request('from_date') }}"
            class="px-4 py-2 rounded-xl bg-gray-800 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-400" />

        <button type="submit" 
            class="px-6 py-2 bg-yellow-400 text-black font-bold rounded-xl hover:bg-yellow-500 transition">
            Filter
        </button>

        {{-- Clear Button --}}
        <a href="{{ route('news') }}" 
            class="px-6 py-2 bg-gray-600 text-white font-bold rounded-xl hover:bg-gray-500 transition flex items-center justify-center">
            Clear
        </a>
    </form>

    @php
        $filteredNews = $newsItems;

        if(request('search')) {
            $filteredNews = $filteredNews->filter(fn($item) => stripos($item->get_title(), request('search')) !== false);
        }

        if(request('from_date')) {
            $from = \Carbon\Carbon::parse(request('from_date'));
            $filteredNews = $filteredNews->filter(fn($item) => \Carbon\Carbon::parse($item->get_date('Y-m-d')) >= $from);
        }
    @endphp

    @if($filteredNews->isNotEmpty())
        {{-- Featured News --}}
        @php $featured = $filteredNews->first(); @endphp
        <div class="bg-gray-900/80 rounded-3xl shadow-2xl p-8 mb-10 hover:scale-[1.02] transition transform duration-300">
            <a href="{{ $featured->get_link() }}" target="_blank" class="text-4xl md:text-5xl font-extrabold text-yellow-400 hover:underline">
                {{ $featured->get_title() }}
            </a>
            <p class="text-gray-400 text-sm mt-2">
                {{ $featured->get_date('F j, Y') }}
            </p>
            <p class="text-gray-200 text-lg mt-4">
                {!! Str::limit(strip_tags($featured->get_description()), 300) !!}
            </p>
            <p class="text-xs text-gray-500 mt-3">
                Source: {{ parse_url($featured->get_feed()->get_link(), PHP_URL_HOST) }}
            </p>
        </div>

        {{-- Other News --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($filteredNews->skip(1) as $item)
                <div class="bg-gray-900/70 rounded-2xl shadow-lg p-4 hover:scale-[1.01] transition transform duration-200 group cursor-pointer">
                    <a href="{{ $item->get_link() }}" target="_blank" class="text-xl font-bold text-yellow-400 hover:underline">
                        {{ $item->get_title() }}
                    </a>
                    <p class="text-gray-400 text-xs mt-1">
                        {{ $item->get_date('F j, Y') }}
                    </p>
                    <p class="text-gray-300 text-sm mt-2 line-clamp-3 group-hover:line-clamp-none transition-all">
                        {!! strip_tags($item->get_description()) !!}
                    </p>
                    <p class="text-xs text-gray-500 mt-2">
                        Source: {{ parse_url($item->get_feed()->get_link(), PHP_URL_HOST) }}
                    </p>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-center text-gray-300">No news match your search/filter criteria.</p>
    @endif

</div>

<script>
const btn = document.getElementById('mobile-menu-btn');
const menu = document.getElementById('mobile-menu');
btn.addEventListener('click', () => {
    menu.classList.toggle('hidden');
});
</script>

</body>
</html>
