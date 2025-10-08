@extends('layouts.app')

@section('title', 'UFC Divisions')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 space-y-8">

    <h1 class="text-4xl font-extrabold text-gray-900 dark:text-yellow-400 text-center">
        UFC Divisions
    </h1>

    {{-- ===== Add New Division (Admin Only) ===== --}}
    @if(auth()->check() && auth()->user()->is_admin)
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-6">
            <form action="{{ route('admin.divisions.store') }}" method="POST" class="flex flex-col sm:flex-row gap-3">
                @csrf
                <div class="flex-1">
                    <input type="text" name="name" placeholder="New Division"
                           class="w-full p-3 rounded-lg border @error('name') border-red-500 @else border-gray-300 dark:border-gray-700 @enderror
                                  bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-yellow-400 transition" required>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-500 px-6 py-3 rounded-lg text-white font-semibold shadow transition">
                    Add Division
                </button>
            </form>
        </div>
    @endif

    {{-- ===== Flash Success / Error ===== --}}
    @if(session('success'))
        <div class="bg-green-500 text-white p-3 rounded-lg shadow-md">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any() && !session('success'))
        <div class="bg-red-600 text-white p-3 rounded-lg shadow-md">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ===== DIVISIONS GRID ===== --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($divisions as $division)
            <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-md border border-gray-200 dark:border-gray-700 overflow-hidden transition hover:shadow-xl">

                {{-- Header --}}
                <div class="flex justify-between items-center p-5 bg-gray-100 dark:bg-gray-800">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $division->name }}</h2>
                        <span class="text-gray-500 dark:text-gray-400 text-sm">
                            {{ $division->rankings->count() }} fighters
                        </span>
                    </div>
                    @if(auth()->check() && auth()->user()->is_admin)
                        <button onclick="document.getElementById('edit-division-{{ $division->id }}').classList.toggle('hidden')"
                                class="bg-yellow-500 hover:bg-yellow-400 px-3 py-1 rounded-lg text-black font-semibold transition">
                             Edit
                        </button>
                    @endif
                </div>

                {{-- Fighters list --}}
                <div class="p-4 space-y-2">
                    @php
                        $sorted = $division->rankings->sortBy(fn($f) => $f->is_champion ? 0 : $f->rank);
                    @endphp
                    @forelse($sorted as $fighter)
                        <div class="p-3 bg-gray-50 dark:bg-gray-800 rounded-lg flex items-center justify-between hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                            <span class="text-gray-900 dark:text-white font-medium">
                                {{ $fighter->is_champion ? 'C' : $fighter->rank }}. {{ $fighter->fighter_name }}
                            </span>
                            @if($fighter->is_champion)
                                <span class="ml-2 text-yellow-400 font-bold text-sm">Champion</span>
                            @endif
                        </div>
                    @empty
                        <p class="text-gray-500 dark:text-gray-400 italic">No fighters yet.</p>
                    @endforelse
                </div>

                {{-- ===== Admin Edit Section ===== --}}
                @if(auth()->check() && auth()->user()->is_admin)
                    <div id="edit-division-{{ $division->id }}" class="hidden p-5 bg-gray-50 dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">

                        {{-- Hidden delete forms (triggered by buttons below) --}}
                        @foreach($division->rankings as $fighter)
                            <form id="del-{{ $fighter->id }}" action="{{ route('ranking.destroy',$fighter) }}" method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        @endforeach

                        <form action="{{ route('admin.divisions.update', $division) }}" method="POST" class="space-y-4 division-form">
                            @csrf
                            @method('PATCH')

                            {{-- Division Name --}}
                            <div>
                                <label class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Division Name</label>
                                <input type="text" name="name" value="{{ old('name', $division->name) }}"
                                       class="w-full p-2 rounded bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white border @error('name') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror">
                            </div>

                            {{-- Fighters --}}
                            <h3 class="text-lg font-bold text-gray-900 dark:text-yellow-400 mb-2">Fighters</h3>
                            @foreach($division->rankings as $fighter)
                                <div class="relative bg-white dark:bg-gray-700 rounded-xl p-4 shadow-sm hover:shadow-md transition">

                                    {{-- Delete button (top-right) --}}
                                    <button type="button"
                                            onclick="if(confirm('Delete this fighter?')) document.getElementById('del-{{ $fighter->id }}').submit();"
                                            class="absolute top-2 right-2 bg-red-600 hover:bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs shadow">
                                        ✕
                                    </button>

                                    <div class="space-y-2">
                                        <input type="text" name="fighters[{{ $fighter->id }}][fighter_name]" value="{{ $fighter->fighter_name }}"
                                               class="w-full p-2 rounded-lg bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-600">
                                        <input type="number" name="fighters[{{ $fighter->id }}][rank]" value="{{ $fighter->rank }}" min="1" max="999"
                                               class="w-full p-2 rounded-lg bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-600">
                                        <label class="flex items-center space-x-2 text-gray-700 dark:text-gray-300">
                                            <input type="checkbox"
                                                   name="fighters[{{ $fighter->id }}][is_champion]"
                                                   value="1"
                                                   class="champion-checkbox accent-yellow-400"
                                                   {{ $fighter->is_champion ? 'checked' : '' }}>
                                            <span>Champion</span>
                                        </label>
                                    </div>
                                </div>
                            @endforeach

                            {{-- Add new fighter --}}
                            <div class="bg-white dark:bg-gray-700 rounded-xl p-4 shadow-sm">
                                <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Add New Fighter</h4>
                                <div class="space-y-2">
                                    <input type="text" name="new_fighter[fighter_name]" placeholder="New Fighter Name"
                                           class="w-full p-2 rounded-lg bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-600">
                                    <input type="number" name="new_fighter[rank]" placeholder="Rank" min="1" max="999"
                                           class="w-full p-2 rounded-lg bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-600">
                                    <label class="flex items-center space-x-2 text-gray-700 dark:text-gray-300">
                                        <input type="checkbox" name="new_fighter[is_champion]" value="1" class="champion-checkbox accent-yellow-400">
                                        <span>Champion</span>
                                    </label>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">⚠️ Max 16 fighters per division.</p>
                                </div>
                            </div>

                            <button type="submit"
                                    class="bg-green-600 hover:bg-green-500 px-3 py-2 rounded-lg font-semibold text-white transition w-full mt-2">
                                Save Changes
                            </button>
                        </form>

                        {{-- Delete Division --}}
                        <form action="{{ route('admin.divisions.destroy', $division) }}" method="POST" class="mt-4">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Delete this division?')"
                                    class="bg-red-600 hover:bg-red-500 px-3 py-2 rounded-lg font-semibold text-white transition w-full">
                                Delete Division
                            </button>
                        </form>
                    </div>
                @endif

            </div>
        @endforeach
    </div>

</div>

{{-- ===== JS: Only one champion checkbox per division (front-end assist) ===== --}}
<script>
document.querySelectorAll('.division-form').forEach(form => {
    const checkboxes = form.querySelectorAll('.champion-checkbox');
    checkboxes.forEach(cb => {
        cb.addEventListener('change', () => {
            if(cb.checked){
                checkboxes.forEach(other => {
                    if(other !== cb) other.checked = false;
                });
            }
        });
    });
});
</script>
@endsection
