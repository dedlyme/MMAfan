@extends('layouts.app')

@section('title', 'Pound for Pound')

@section('content')
<style>
    body::before {
        content: "";
        position: fixed;
        inset: 0;
        filter: brightness(0.3);
        z-index: -1;
    }
</style>

<div class="max-w-5xl mx-auto py-12 px-4">

    <!-- Page title -->
    <h1 class="text-5xl md:text-6xl font-extrabold text-red-500 mb-12 text-center drop-shadow-lg">
        Pound for Pound Top 10
    </h1>

    <!-- Fighters List -->
    <div class="bg-gray-900/70 backdrop-blur-md rounded-3xl p-6 space-y-5 shadow-2xl">
        @foreach($fighters as $fighter)
            <div class="flex justify-between items-center p-5 bg-gray-800/60 rounded-2xl hover:bg-gray-700/70 transition transform hover:scale-[1.03] shadow-lg">
                
                <!-- Fighter info -->
                <div class="flex items-center space-x-4">
                    <span class="w-12 h-12 flex items-center justify-center bg-red-500 text-white font-bold rounded-full shadow-md text-lg">
                        {{ $fighter->rank }}
                    </span>
                    <span class="text-xl md:text-2xl font-semibold text-white">{{ $fighter->fighter_name }}</span>
                </div>

                <!-- Admin tools -->
                @if(auth()->check() && auth()->user()->is_admin)
                    <div class="flex space-x-3">
                        <!-- Edit form -->
                        <form action="{{ route('admin.pound.update', $fighter) }}" method="POST" class="flex space-x-2 items-center">
                            @csrf
                            @method('PATCH')
                            <input type="text" name="fighter_name" value="{{ $fighter->fighter_name }}" 
                                   class="p-2 rounded bg-gray-700 text-white w-36 focus:ring-2 focus:ring-red-500 transition" required>
                            <input type="number" name="rank" value="{{ $fighter->rank }}" min="1" max="10" 
                                   class="p-2 rounded bg-gray-700 text-white w-20 focus:ring-2 focus:ring-red-500 transition" required>
                            <button type="submit" class="bg-green-600 hover:bg-green-500 text-white px-4 py-2 rounded-lg shadow-md transition">
                                Save
                            </button>
                        </form>

                        <!-- Delete form -->
                        <form action="{{ route('admin.pound.destroy', $fighter) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 hover:bg-red-500 text-white px-4 py-2 rounded-lg shadow-md transition">
                                Delete
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        @endforeach

        <!-- Admin: Add new fighter -->
        @if(auth()->check() && auth()->user()->is_admin)
            <form action="{{ route('admin.pound.store') }}" method="POST" class="mt-8 flex flex-col md:flex-row space-y-3 md:space-y-0 md:space-x-3">
                @csrf
                <input type="text" name="fighter_name" placeholder="New Fighter" 
                       class="p-3 rounded bg-gray-700 text-white flex-1 focus:ring-2 focus:ring-red-500 transition" required>
                <input type="number" name="rank" placeholder="Rank" min="1" max="10" 
                       class="p-3 rounded bg-gray-700 text-white w-28 focus:ring-2 focus:ring-red-500 transition" required>
                <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white px-6 py-3 rounded-lg shadow-md transition">
                    Add
                </button>
            </form>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    const btn = document.getElementById('mobile-menu-btn');
    const menu = document.getElementById('mobile-menu');
    if(btn){
        btn.addEventListener('click', () => {
            menu.classList.toggle('hidden');
        });
    }
</script>
@endpush
