@extends('layouts.app')

@section('title', 'UFC Divisions')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 space-y-6">

    <h1 class="text-3xl font-extrabold mb-6 text-yellow-400 text-center">UFC Divisions</h1>

    {{-- Admin: Add new division --}}
    @if(auth()->check() && auth()->user()->is_admin)
        <form action="{{ route('admin.divisions.store') }}" method="POST" class="mb-8 flex space-x-2">
            @csrf
            <input type="text" name="name" placeholder="New Division" class="flex-1 p-2 rounded-lg border border-gray-700 bg-gray-800 text-white" required>
            <button type="submit" class="bg-blue-600 hover:bg-blue-500 px-4 py-2 rounded-lg text-white font-semibold transition">
                Add Division
            </button>
        </form>
    @endif

    {{-- Divisions Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($divisions as $division)
            <div class="bg-gray-900 rounded-2xl shadow-lg overflow-hidden">

                {{-- Division Header --}}
                <div class="flex justify-between items-center p-6 bg-gray-800">
                    <div>
                        <h2 class="text-2xl font-bold text-white">{{ $division->name }}</h2>
                        <span class="text-gray-400 text-sm">{{ $division->rankings->count() }} fighters</span>
                    </div>
                    @if(auth()->check() && auth()->user()->is_admin)
                        <button onclick="document.getElementById('edit-division-{{ $division->id }}').classList.toggle('hidden')" class="bg-yellow-600 hover:bg-yellow-500 px-3 py-1 rounded-lg text-black font-semibold transition">
                            Edit
                        </button>
                    @endif
                </div>

                {{-- Fighters List --}}
                <div class="bg-gray-800 p-4 space-y-2">
                    @php
                        $sorted = $division->rankings->sortBy(fn($f) => $f->is_champion ? 0 : $f->rank);
                    @endphp
                    @foreach($sorted as $fighter)
                        <div class="p-3 bg-gray-700 rounded-lg flex items-center justify-between">
                            <span class="text-white font-medium">
                                {{ $fighter->is_champion ? 'C' : $fighter->rank }}. {{ $fighter->fighter_name }}
                            </span>
                            @if(auth()->check() && auth()->user()->is_admin)
                                <form action="{{ route('admin.fighters.destroy', $fighter) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Delete this fighter?');" class="bg-red-600 hover:bg-red-500 px-3 py-1 rounded-lg font-semibold transition">
                                        Delete
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endforeach
                </div>

                {{-- Admin Edit Section --}}
                @if(auth()->check() && auth()->user()->is_admin)
                    <div id="edit-division-{{ $division->id }}" class="hidden bg-gray-900 p-4">
                        <form action="{{ route('admin.divisions.update', $division) }}" method="POST" class="space-y-4">
                            @csrf
                            @method('PATCH')

                            {{-- Division Name --}}
                            <div>
                                <label class="block text-sm text-gray-300">Division Name</label>
                                <input type="text" name="name" value="{{ $division->name }}" class="w-full p-2 rounded bg-gray-700 text-white">
                            </div>

                            {{-- Fighters --}}
                            <h3 class="text-lg font-bold text-yellow-400">Fighters</h3>
                            @foreach($division->rankings as $fighter)
                                <div class="p-3 bg-gray-800 rounded-lg space-y-2">
                                    <input type="text" name="fighters[{{ $fighter->id }}][fighter_name]" value="{{ $fighter->fighter_name }}" class="w-full p-2 rounded bg-gray-700 text-white">
                                    @php $maxRank = $fighter->is_champion ? 16 : 15; @endphp
                                    <input type="number" name="fighters[{{ $fighter->id }}][rank]" value="{{ $fighter->rank }}" min="1" max="{{ $maxRank }}" class="w-full p-2 rounded bg-gray-700 text-white">
                                    <label class="flex items-center space-x-2">
                                        <input type="checkbox" name="fighters[{{ $fighter->id }}][is_champion]" value="1" {{ $fighter->is_champion ? 'checked' : '' }}>
                                        <span>Champion</span>
                                    </label>
                                </div>
                            @endforeach

                            {{-- Add new fighter --}}
                            <div class="p-3 bg-gray-800 rounded-lg space-y-2">
                                <input type="text" name="new_fighter[fighter_name]" placeholder="New Fighter Name" class="w-full p-2 rounded bg-gray-700 text-white">
                                <input type="number" name="new_fighter[rank]" placeholder="Rank" min="1" max="15" class="w-full p-2 rounded bg-gray-700 text-white">
                                <label class="flex items-center space-x-2">
                                    <input type="checkbox" name="new_fighter[is_champion]" value="1">
                                    <span>Champion</span>
                                </label>
                            </div>

                            <button type="submit" class="bg-green-600 hover:bg-green-500 px-3 py-2 rounded-lg font-semibold transition w-full">
                                Save Changes
                            </button>
                        </form>

                        {{-- Delete Division --}}
                        <form action="{{ route('admin.divisions.destroy', $division) }}" method="POST" class="mt-4">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 hover:bg-red-500 px-3 py-2 rounded-lg font-semibold transition w-full">
                                Delete Division
                            </button>
                        </form>
                    </div>
                @endif

            </div>
        @endforeach
    </div>

</div>

@push('scripts')
<script>
    // Mobile menu toggle
    const btn = document.getElementById('mobile-menu-btn');
    const menu = document.getElementById('mobile-menu');
    if(btn){
        btn.addEventListener('click', () => {
            menu.classList.toggle('hidden');
        });
    }

    // Ensure only one champion per division
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('[id^="edit-division-"]').forEach(divisionForm => {
            const championCheckboxes = divisionForm.querySelectorAll('input[type="checkbox"][name*="[is_champion]"]');
            championCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', () => {
                    if (checkbox.checked) {
                        championCheckboxes.forEach(cb => {
                            if (cb !== checkbox) cb.checked = false;
                        });
                    }
                });
            });
        });
    });
</script>
@endpush
@endsection
