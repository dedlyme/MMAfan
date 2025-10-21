@extends('layouts.app')

@section('title', 'Dashboard')

@section('background')
<div class="fixed inset-0 -z-10">
    <img src="{{ asset('dashboard.png') }}" alt="Dashboard Background" class="w-full h-full object-cover">
    <div class="absolute inset-0 bg-black/60"></div>
</div>
@endsection

@section('content')

{{-- ===== HERO / NO BACKGROUND BOX ===== --}}
<section class="text-center pt-16 sm:pt-20 px-4 sm:px-6 bg-transparent">
    <h1 class="text-4xl sm:text-5xl md:text-6xl font-extrabold text-white drop-shadow-lg tracking-tight leading-tight">
        UFC MMA Dashboard
    </h1>
    <p class="text-white/90 mt-4 text-base sm:text-lg md:text-xl max-w-2xl sm:max-w-3xl mx-auto">
        Your personal fight hub — chat live, follow news, and create dream matchups.
    </p>
</section>

{{-- ===== QUICK STATS (FLOATING CARDS) ===== --}}
@php
    $user = auth()->user();
    $wins = \App\Models\Dreamfight::where('winner', $user?->name)->count();
    $losses = \App\Models\Dreamfight::where(function($q) use ($user) {
        $q->where('player_one_id', $user?->id)->orWhere('player_two_id', $user?->id);
    })->where('winner', '!=', $user?->name)->whereNotNull('winner')->count();
    $draws = \App\Models\Dreamfight::where(function($q) use ($user) {
        $q->where('player_one_id', $user?->id)->orWhere('player_two_id', $user?->id);
    })->whereNull('winner')->count();
@endphp

<section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8 pb-10 sm:pb-16 px-4 sm:px-8 bg-transparent">
    <div class="bg-white/90 dark:bg-gray-800/90 rounded-3xl shadow-xl p-6 sm:p-8 text-center">
        <div class="text-red-500 text-4xl sm:text-5xl mb-3">👤</div>
        <h2 class="text-base sm:text-lg font-semibold text-gray-700 dark:text-gray-200">Active User</h2>
        <p class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $user?->name }}</p>
    </div>
    <div class="bg-white/90 dark:bg-gray-800/90 rounded-3xl shadow-xl p-6 sm:p-8 text-center">
        <div class="text-red-500 text-4xl sm:text-5xl mb-3">🥊</div>
        <h2 class="text-base sm:text-lg font-semibold text-gray-700 dark:text-gray-200">Your Record</h2>
        <p class="text-3xl sm:text-4xl font-bold mt-2">
            <span class="text-green-500">{{ $wins }}W</span> -
            <span class="text-red-500">{{ $losses }}L</span> -
            <span class="text-gray-500 dark:text-gray-300">{{ $draws }}D</span>
        </p>
    </div>
    <div class="bg-white/90 dark:bg-gray-800/90 rounded-3xl shadow-xl p-6 sm:p-8 text-center">
        <div class="text-red-500 text-4xl sm:text-5xl mb-3">🔥</div>
        <h2 class="text-base sm:text-lg font-semibold text-gray-700 dark:text-gray-200">Dream Fights Created</h2>
        <p class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white mt-2">
            {{ \App\Models\Dreamfight::count() }}
        </p>
    </div>
</section>

{{-- ===== LIVE CHAT ===== --}}
<section class="bg-white/90 dark:bg-gray-900/90 rounded-3xl shadow-2xl border border-gray-200/30 dark:border-gray-700/40 p-4 sm:p-6 lg:p-8 mb-16 mx-3 sm:mx-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 sm:mb-6 space-y-3 sm:space-y-0">
        <h2 class="text-2xl sm:text-3xl font-extrabold text-red-500">Live Chat</h2>
        <button onclick="document.documentElement.classList.toggle('dark')"
            class="px-3 sm:px-4 py-2 rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 text-sm font-semibold hover:bg-gray-200 dark:hover:bg-gray-600 transition">
            Toggle Dark / Light
        </button>
    </div>

    <div id="chat" class="flex flex-col h-[70vh] sm:h-96 rounded-2xl bg-gray-50 dark:bg-gray-800 overflow-hidden">
        <div id="messages" class="flex-1 overflow-y-auto p-3 sm:p-4 space-y-3 scroll-smooth">
            @foreach($messages as $msg)
                <div class="px-4 py-3 rounded-2xl shadow-md bg-gray-100 text-gray-900 dark:bg-gray-700 dark:text-white break-words">
                    <span class="font-semibold text-red-600 dark:text-red-400">
                        {{ $msg->user->name ?? 'Unknown User' }}:
                    </span>
                    <span>{{ $msg->message }}</span>
                </div>
            @endforeach
        </div>

        <form id="chat-form" action="{{ route('messages.send') }}" method="POST"
            class="flex flex-col sm:flex-row items-center gap-2 sm:gap-3 p-3 border-t border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900">
            @csrf
            <input id="chat-input" type="text" name="message" placeholder="Type a message..."
                class="flex-1 p-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-red-500"
                required>
            <button type="submit"
                class="px-5 py-3 bg-red-500 hover:bg-red-600 text-white rounded-xl font-bold shadow-lg">
                Send
            </button>
        </form>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('chat-form');
    const input = document.getElementById('chat-input');
    const messages = document.getElementById('messages');

    // ✅ Escape HTML to prevent XSS
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function scrollBottom() {
        messages.scrollTo({ top: messages.scrollHeight, behavior: 'smooth' });
    }
    scrollBottom();

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const text = input.value.trim();
        if (!text) return;

        input.value = '';
        input.focus();

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ message: text })
            });

            if (!response.ok) {
                const error = await response.json();
                alert(error.error || 'Error sending message.');
            }
        } catch (err) {
            alert('Connection error, please try again.');
        }
    });

    // ✅ Listen for real-time events securely
    if (window.Echo) {
        window.Echo.channel('chat')
            .listen('.MessageSent', (e) => {
                const div = document.createElement('div');
                div.className = "px-4 py-3 rounded-2xl shadow-md bg-gray-100 text-gray-900 dark:bg-gray-700 dark:text-white break-words";
                div.innerHTML = `<span class='font-semibold text-red-600 dark:text-red-400'>${escapeHtml(e.user.name)}:</span> ${escapeHtml(e.message)}`;
                messages.appendChild(div);
                scrollBottom();
            });
    }
});
</script>
@endpush
