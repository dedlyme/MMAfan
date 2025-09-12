<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UFC Divisions</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-black text-white min-h-screen">

<nav class="flex justify-between items-center p-4 bg-gray-900/90 sticky top-0 z-50">
    <div class="text-xl font-bold">UFC MMA Dashboard</div>
    <ul class="flex space-x-6 items-center">
        <li><a href="{{ route('dashboard') }}" class="hover:text-gray-300 transition">Home</a></li>
        <li><a href="{{ route('ranking') }}" class="hover:text-gray-300 transition">Ranking</a></li>
        <li><a href="{{ route('pound') }}" class="hover:text-gray-300 transition">Pound for Pound</a></li>
        <li><a href="{{ route('news') }}" class="hover:text-gray-300 transition">News</a></li>

        <!-- Admin link uz Divisions CRUD -->
        @if(auth()->check() && auth()->user()->is_admin)
            <li><a href="{{ route('admin.divisions.index') }}" class="hover:text-gray-300 transition">Divisions</a></li>
        @endif

        <li><a href="{{ route('profile.edit') }}" class="hover:text-gray-300 transition">Profile</a></li>

        <!-- Breeze Logout -->
        <li>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="hover:text-gray-300 transition">Logout</button>
            </form>
        </li>
    </ul>
</nav>
    <div class="max-w-7xl mx-auto py-8 px-4 space-y-6">

        <h1 class="text-3xl font-extrabold mb-6 text-yellow-400 text-center">UFC Divisions</h1>

        <!-- Add Division (Admin Only) -->
        @if(auth()->check() && auth()->user()->is_admin)
        <form action="{{ route('admin.divisions.store') }}" method="POST" class="mb-8 flex space-x-2">
            @csrf
            <input type="text" name="name" placeholder="New Division" 
                   class="flex-1 p-2 rounded-lg border border-gray-700 bg-gray-800 text-white" required>
            <button type="submit" class="bg-blue-600 hover:bg-blue-500 px-4 py-2 rounded-lg text-white font-semibold transition">Add Division</button>
        </form>
        @endif

        <!-- Divisions Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($divisions as $division)
                <div class="bg-gray-900 rounded-2xl shadow-lg overflow-hidden">
                    <div class="flex justify-between items-center p-6 bg-gray-800">
                        <div>
                            <h2 class="text-2xl font-bold text-white">{{ $division->name }}</h2>
                            <span class="text-gray-400 text-sm">{{ $division->rankings->count() }} fighters</span>
                        </div>

                        <!-- Edit Division Button (Admin only) -->
                        @if(auth()->check() && auth()->user()->is_admin)
                        <button onclick="document.getElementById('edit-division-{{ $division->id }}').classList.toggle('hidden')" 
                                class="bg-yellow-600 hover:bg-yellow-500 px-3 py-1 rounded-lg text-black font-semibold transition">
                            Edit
                        </button>
                        @endif
                    </div>

                    <!-- Fighters List -->
                    <div class="bg-gray-800 p-4 space-y-2">
                        @php
                            $sorted = $division->rankings->sortBy(function($f) {
                                return $f->is_champion ? 0 : $f->rank;
                            });
                        @endphp

                        @foreach($sorted as $fighter)
                            <div class="flex justify-between items-center p-2 bg-gray-700 rounded-lg">
                                <span class="text-white font-medium">
                                    {{ $fighter->is_champion ? 'C' : $fighter->rank }}. {{ $fighter->fighter_name }}
                                </span>
                            </div>
                        @endforeach
                    </div>

                    <!-- Hidden Edit Form -->
                    @if(auth()->check() && auth()->user()->is_admin)
                    <div id="edit-division-{{ $division->id }}" class="hidden bg-gray-900 p-4">
                        <form action="{{ route('admin.divisions.update', $division) }}" method="POST" class="space-y-4">
                            @csrf
                            @method('PATCH')

                            <!-- Division Name -->
                            <div>
                                <label class="block text-sm text-gray-300">Division Name</label>
                                <input type="text" name="name" value="{{ $division->name }}" 
                                       class="w-full p-2 rounded bg-gray-700 text-white">
                            </div>

                            <!-- Fighters Editing -->
                            <h3 class="text-lg font-bold text-yellow-400">Fighters</h3>
                            @foreach($division->rankings as $fighter)
                                <div class="p-3 bg-gray-800 rounded-lg space-y-2">
                                    <input type="text" name="fighters[{{ $fighter->id }}][fighter_name]" 
                                           value="{{ $fighter->fighter_name }}" 
                                           class="w-full p-2 rounded bg-gray-700 text-white">

                                    <!-- Dynamic max rank: 16 for champion, 15 for others -->
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

                            <!-- Add New Fighter -->
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
