@extends('layouts.app')

@section('title', 'Dashboard')

@section('background')
<div class="fixed inset-0 -z-10">
    <img src="{{ asset('dashboard.png') }}" alt="Dashboard Background" class="w-full h-full object-cover">
    <div class="absolute inset-0 bg-black/60"></div>
</div>
@endsection

@section('content')
    {{-- ===== HERO ===== --}}
    <section class="text-center py-20 px-6">
        <h1 class="text-5xl md:text-6xl font-extrabold text-white drop-shadow-lg tracking-tight">
            UFC MMA Dashboard
        </h1>
        <p class="text-white/90 mt-4 text-lg md:text-xl max-w-3xl mx-auto">
            Your personal fight hub — chat live, follow news, and create dream matchups.
        </p>
    </section>

    {{-- ===== QUICK STATS ===== --}}
    @php
        $wins = \App\Models\Dreamfight::where('winner', auth()->user()->name)->count();
        $losses = \App\Models\Dreamfight::where(function($q){
            $q->where('player_one_id', auth()->id())->orWhere('player_two_id', auth()->id());
        })->where('winner', '!=', auth()->user()->name)->whereNotNull('winner')->count();
        $draws = \App\Models\Dreamfight::where(function($q){
            $q->where('player_one_id', auth()->id())->orWhere('player_two_id', auth()->id());
        })->whereNull('winner')->count();
    @endphp

    <section class="grid grid-cols-1 md:grid-cols-3 gap-8 pb-16">
        <div class="bg-white/90 dark:bg-gray-800/90 rounded-3xl shadow-xl p-8 text-center backdrop-blur-md">
            <div class="text-red-500 text-5xl mb-3">👤</div>
            <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Active User</h2>
            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ auth()->user()->name }}</p>
        </div>
        <div class="bg-white/90 dark:bg-gray-800/90 rounded-3xl shadow-xl p-8 text-center backdrop-blur-md">
            <div class="text-red-500 text-5xl mb-3">🥊</div>
            <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Your Record</h2>
            <p class="text-4xl font-bold mt-2">
                <span class="text-green-500">{{ $wins }}W</span> -
                <span class="text-red-500">{{ $losses }}L</span> -
                <span class="text-gray-500 dark:text-gray-300">{{ $draws }}D</span>
            </p>
        </div>
        <div class="bg-white/90 dark:bg-gray-800/90 rounded-3xl shadow-xl p-8 text-center backdrop-blur-md">
            <div class="text-red-500 text-5xl mb-3">🔥</div>
            <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Dream Fights Created</h2>
            <p class="text-4xl font-bold text-gray-900 dark:text-white mt-2">
                {{ \App\Models\Dreamfight::count() }}
            </p>
        </div>
    </section>

    {{-- ===== LIVE CHAT ===== --}}
    <section class="bg-white/90 dark:bg-gray-900/90 rounded-3xl shadow-2xl border border-gray-200/30 dark:border-gray-700/40 p-8 mb-16 backdrop-blur-md">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-3xl font-extrabold text-red-500 drop-shadow-lg tracking-wide">Live Chat</h2>
            <button onclick="document.documentElement.classList.toggle('dark')"
                class="px-4 py-2 rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 text-sm font-semibold hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                Toggle Dark / Light
            </button>
        </div>

        <!-- Scrollable chat area -->
        <div id="chat" class="flex flex-col h-96 rounded-2xl bg-gray-50 dark:bg-gray-800 overflow-y-auto p-4">
            <div id="messages" class="flex-1 mb-4 space-y-3">
                @foreach($messages as $msg)
                    <div class="px-4 py-3 rounded-2xl shadow-md bg-gray-100 text-gray-900 dark:bg-gray-800 dark:text-white">
                        <span class="font-semibold text-red-600 dark:text-red-400">{{ $msg->user->name }}:</span>
                        <span>{{ $msg->message }}</span>
                    </div>
                @endforeach
            </div>

            <!-- Input stays at bottom -->
            <form id="chat-form" action="{{ route('messages.send') }}" method="POST" class="flex space-x-3">
                @csrf
                <input id="chat-input" type="text" name="message" placeholder="Type message..."
                    class="flex-1 p-3 rounded-2xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary outline-none transition-all duration-300"
                    required>
                <button type="submit" class="px-6 py-3 bg-red-500 hover:bg-red-600 text-white rounded-2xl font-bold shadow-lg hover:shadow-xl transition duration-300">
                    Send
                </button>
            </form>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    const chatForm = document.getElementById('chat-form');
    const chatInput = document.getElementById('chat-input');
    const messagesBox = document.getElementById('messages');
    const chatScroll = document.getElementById('chat');

    function scrollToBottom() {
        chatScroll.scrollTop = chatScroll.scrollHeight;
    }
    scrollToBottom();

    // Send message
    chatForm.addEventListener('submit', async function(e){
        e.preventDefault();
        const text = chatInput.value.trim();
        if(!text) return;

        chatInput.value = '';
        chatInput.focus();
        scrollToBottom();

        try {
            await fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ message: text })
            });
        } catch (err) {
            console.error(err);
            alert('Could not send message');
        }
    });

    // Receive messages via Echo
    document.addEventListener('DOMContentLoaded', () => {
        if (window.Echo) {
            window.Echo.channel('chat')
                .listen('.MessageSent', (e) => {
                    const div = document.createElement('div');
                    div.className = "px-4 py-3 rounded-2xl shadow-md bg-gray-100 text-gray-900 dark:bg-gray-800 dark:text-white";
                    div.innerHTML = `<span class="font-semibold text-red-600 dark:text-red-400">${e.user.name}:</span> <span>${e.message}</span>`;
                    messagesBox.appendChild(div);
                    scrollToBottom();
                });
        }
    });
</script>
@endpush
