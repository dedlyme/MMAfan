@extends('layouts.app')

@section('title', 'Pound for Pound')

@section('content')
<div class="max-w-5xl mx-auto py-12 px-4 space-y-8">

    <!-- Page Title -->
    <div class="text-center">
        <h1 class="text-5xl md:text-6xl font-extrabold text-gray-900 dark:text-white mb-2">
            Pound for Pound Top 10
        </h1>
        <p class="text-gray-600 dark:text-gray-300 text-lg">
            The most elite fighters ranked regardless of weight class.
        </p>
    </div>

    <div class="bg-white dark:bg-gray-900 rounded-3xl p-6 shadow-2xl transition-colors duration-300">

        @if(auth()->check() && auth()->user()->is_admin)
            <!-- ✅ Single Save-All Form -->
            <form action="{{ route('admin.pound.updateAll') }}" method="POST" class="space-y-5">
                @csrf
                @method('PATCH')

                @foreach($fighters as $fighter)
                    <div class="flex flex-col md:flex-row justify-between items-center p-5 bg-gray-100 dark:bg-gray-800 rounded-2xl hover:shadow-lg transition transform hover:scale-[1.02]">

                        <!-- Rank Badge + Name -->
                        <div class="flex items-center w-full md:w-1/2 space-x-4">
                            <div class="flex-shrink-0 w-12 h-12 rounded-full bg-red-500 text-white font-extrabold text-xl flex items-center justify-center shadow-md">
                                {{ $fighter->rank }}
                            </div>

                            <input
                                type="text"
                                name="fighters[{{ $fighter->id }}][fighter_name]"
                                value="{{ $fighter->fighter_name }}"
                                class="flex-1 p-2 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-red-500 transition"
                                required
                            >
                        </div>

                        <!-- Rank Input -->
                        <div class="flex items-center mt-3 md:mt-0">
                            <input
                                type="number"
                                name="fighters[{{ $fighter->id }}][rank]"
                                value="{{ $fighter->rank }}"
                                min="1" max="10"
                                class="p-2 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-red-500 transition w-24 text-center"
                                required
                            >
                        </div>

                        <!-- Delete Button -->
                        <div class="mt-3 md:mt-0">
                            <button
                                type="button"
                                class="bg-red-600 hover:bg-red-500 text-white px-4 py-2 rounded-lg shadow-md transition"
                                onclick="deleteFighter({{ $fighter->id }})">
                                Delete
                            </button>
                        </div>
                    </div>
                @endforeach

                <!-- Save All -->
                <div class="text-right mt-6">
                    <button type="submit" class="bg-green-600 hover:bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg transition transform hover:scale-105">
                        💾 Save All Changes
                    </button>
                </div>
            </form>

            <!-- Add New Fighter -->
            <div class="mt-10 bg-gray-100 dark:bg-gray-800 p-6 rounded-2xl shadow-md">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Add New Fighter</h2>
                <form action="{{ route('admin.pound.store') }}" method="POST" class="flex flex-col md:flex-row space-y-3 md:space-y-0 md:space-x-3">
                    @csrf
                    <input
                        type="text"
                        name="fighter_name"
                        placeholder="New Fighter Name"
                        class="p-3 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white flex-1 focus:ring-2 focus:ring-red-500 transition"
                        required
                    >
                    <input
                        type="number"
                        name="rank"
                        placeholder="Rank"
                        min="1" max="10"
                        class="p-3 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white w-28 focus:ring-2 focus:ring-red-500 transition"
                        required
                    >
                    <button
                        type="submit"
                        class="bg-blue-600 hover:bg-blue-500 text-white px-6 py-3 rounded-lg shadow-md transition transform hover:scale-105">
                        ➕ Add Fighter
                    </button>
                </form>
            </div>
        @else
            <!-- Normal User View -->
            @foreach($fighters as $fighter)
                <div class="flex justify-between items-center p-5 bg-gray-100 dark:bg-gray-800 rounded-2xl shadow-md transition">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0 w-12 h-12 rounded-full bg-red-500 text-white font-extrabold text-xl flex items-center justify-center shadow-md">
                            {{ $fighter->rank }}
                        </div>
                        <span class="text-xl md:text-2xl font-semibold text-gray-900 dark:text-white">{{ $fighter->fighter_name }}</span>
                    </div>
                </div>
            @endforeach
        @endif

    </div>
</div>
@endsection

@push('scripts')
<script>
function deleteFighter(id) {
    if(!confirm('Delete this fighter?')) return;

    fetch(`/admin/pound/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    }).then(() => window.location.reload());
}
</script>
@endpush
