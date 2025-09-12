<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pound for Pound</title>
  @vite('resources/css/app.css')
  <style>
      body::before {
          content: "";
          position: fixed;
          inset: 0;
          background: url('{{ asset("wallpaper2.png") }}') center/cover no-repeat;
          filter: brightness(0.4);
          z-index: -1;
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
  <div class="max-w-4xl mx-auto py-12 px-4">

      <!-- Page title -->
      <h1 class="text-5xl font-extrabold text-yellow-400 mb-10 text-center drop-shadow-lg">
          Pound for Pound Top 10
      </h1>

      <!-- Fighters List -->
      <div class="bg-gray-900/80 backdrop-blur-md rounded-2xl p-6 space-y-4 shadow-2xl">

          @foreach($fighters as $fighter)
              <div class="flex justify-between items-center p-4 bg-gray-800/60 rounded-xl hover:bg-gray-700/70 transition transform hover:scale-[1.02] shadow-md">
                  
                  <!-- Fighter info -->
                  <div class="flex items-center space-x-4">
                      <span class="w-10 h-10 flex items-center justify-center bg-yellow-400 text-black font-bold rounded-full shadow-md">
                          {{ $fighter->rank }}
                      </span>
                      <span class="text-lg font-semibold">{{ $fighter->fighter_name }}</span>
                  </div>

                  <!-- Admin tools -->
                  @if(auth()->check() && auth()->user()->is_admin)
                      <div class="flex space-x-2">
                          <!-- Edit form -->
                          <form action="{{ route('admin.pound.update', $fighter) }}" method="POST" class="flex space-x-2 items-center">
                              @csrf
                              @method('PATCH')
                              <input type="text" name="fighter_name" value="{{ $fighter->fighter_name }}" 
                                     class="p-1 rounded bg-gray-700 text-white w-32 focus:ring-2 focus:ring-yellow-400 transition" required>
                              <input type="number" name="rank" value="{{ $fighter->rank }}" min="1" max="10" 
                                     class="p-1 rounded bg-gray-700 text-white w-16 focus:ring-2 focus:ring-yellow-400 transition" required>
                              <button type="submit" class="bg-green-600 hover:bg-green-500 text-white px-3 py-1 rounded-lg shadow">
                                  Save
                              </button>
                          </form>

                          <!-- Delete form -->
                          <form action="{{ route('admin.pound.destroy', $fighter) }}" method="POST">
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="bg-red-600 hover:bg-red-500 text-white px-3 py-1 rounded-lg shadow">
                                  Delete
                              </button>
                          </form>
                      </div>
                  @endif
              </div>
          @endforeach

          <!-- Admin: Add new fighter -->
          @if(auth()->check() && auth()->user()->is_admin)
              <form action="{{ route('admin.pound.store') }}" method="POST" class="mt-6 flex space-x-2">
                  @csrf
                  <input type="text" name="fighter_name" placeholder="New Fighter" 
                         class="p-2 rounded bg-gray-700 text-white flex-1 focus:ring-2 focus:ring-yellow-400 transition" required>
                  <input type="number" name="rank" placeholder="Rank" min="1" max="10" 
                         class="p-2 rounded bg-gray-700 text-white w-24 focus:ring-2 focus:ring-yellow-400 transition" required>
                  <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white px-5 py-2 rounded-lg shadow">
                      Add
                  </button>
              </form>
          @endif

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

<div class="max-w-4xl mx-auto py-12 px-4">
</body>
</html>
