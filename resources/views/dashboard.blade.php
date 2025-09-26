@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">

    <!-- 🥊 HERO -->
    <section class="relative rounded-3xl overflow-hidden shadow-2xl">
        <div class="absolute inset-0 bg-gradient-to-r from-red-700 to-red-500 dark:from-gray-900 dark:to-gray-800 opacity-90"></div>
        <div class="relative z-10 text-center px-6 py-20">
            <h1 class="text-5xl md:text-6xl font-extrabold text-white drop-shadow-lg tracking-tight">
                UFC MMA Dashboard
            </h1>
            <p class="text-white/90 mt-4 text-lg md:text-xl max-w-3xl mx-auto">
                Your personal fight hub — chat live, follow news, and create dream matchups.
            </p>
        </div>
    </section>

    <!-- ⚡ QUICK STATS -->
    @php
        $wins = \App\Models\Dreamfight::where('winner', auth()->user()->name)->count();
        $losses = \App\Models\Dreamfight::where(function($q){
            $q->where('player_one_id', auth()->id())->orWhere('player_two_id', auth()->id());
        })->where('winner', '!=', auth()->user()->name)->whereNotNull('winner')->count();
        $draws = \App\Models\Dreamfight::where(function($q){
            $q->where('player_one_id', auth()->id())->orWhere('player_two_id', auth()->id());
        })->whereNull('winner')->count();
    @endphp

    <section class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Active User -->
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl p-8 text-center transition hover:-translate-y-2 hover:shadow-2xl duration-300">
            <div class="text-red-500 text-5xl mb-3">👤</div>
            <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Active User</h2>
            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ auth()->user()->name }}</p>
            <p class="text-gray-500 dark:text-gray-400 mt-2 text-sm">You are currently logged in</p>
        </div>

        <!-- Dreamfights Record -->
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl p-8 text-center transition hover:-translate-y-2 hover:shadow-2xl duration-300">
            <div class="text-red-500 text-5xl mb-3">🥊</div>
            <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Your Record</h2>
            <p class="text-4xl font-bold mt-2">
                <span class="text-green-500">{{ $wins }}W</span> -
                <span class="text-red-500">{{ $losses }}L</span> -
                <span class="text-gray-500 dark:text-gray-300">{{ $draws }}D</span>
            </p>
            <p class="text-gray-500 dark:text-gray-400 mt-2 text-sm">Dream Fights record</p>
        </div>

        <!-- Dream Fights Created -->
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl p-8 text-center transition hover:-translate-y-2 hover:shadow-2xl duration-300">
            <div class="text-red-500 text-5xl mb-3">🔥</div>
            <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Dream Fights Created</h2>
            <p class="text-4xl font-bold text-gray-900 dark:text-white mt-2">
                {{ \App\Models\Dreamfight::count() }}
            </p>
            <p class="text-gray-500 dark:text-gray-400 mt-2 text-sm">Fantasy matchups made by users</p>
        </div>
    </section>

    <!-- 💬 LIVE CHAT -->
    <section class="bg-white dark:bg-gray-900 rounded-3xl shadow-2xl border border-gray-200/30 dark:border-gray-700/40 p-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-3xl font-extrabold text-red-500 drop-shadow-lg tracking-wide">
                Live Chat
            </h2>
            <button onclick="document.documentElement.classList.toggle('dark')"
                class="px-4 py-2 rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 text-sm font-semibold hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                Toggle Dark / Light
            </button>
        </div>

        <div id="chat" class="flex flex-col h-96 rounded-2xl bg-gray-50 dark:bg-gray-800 overflow-y-auto p-4">
            <div id="messages" class="flex-1 mb-4 space-y-3">
                @foreach($messages as $msg)
                    <div class="px-4 py-3 rounded-2xl shadow-md transition-colors duration-300
                                bg-gray-100 text-gray-900
                                dark:bg-gray-800 dark:text-white">
                        <span class="font-semibold text-red-600 dark:text-red-400">{{ $msg->user->name }}:</span>
                        <span>{{ $msg->message }}</span>
                    </div>
                @endforeach
            </div>

            <form id="chat-form" action="{{ route('messages.send') }}" method="POST" class="flex space-x-3 mt-3">
                @csrf
                <input id="chat-input" type="text" name="message" placeholder="Type message..."
                    class="flex-1 p-3 rounded-2xl border border-gray-300 dark:border-gray-600
                           bg-white dark:bg-gray-900 text-gray-900 dark:text-white
                           focus:ring-2 focus:ring-primary outline-none transition-all duration-300"
                    required>
                <button type="submit" class="px-6 py-3 bg-red-500 hover:bg-red-600 text-white rounded-2xl font-bold shadow-lg hover:shadow-xl transition duration-300">
                    Send
                </button>
            </form>
        </div>
    </section>

</div>
@endsection

@push('scripts')
<script>
window.addEventListener('load', function() {
    const chatForm = document.getElementById('chat-form');
    const chatInput = document.getElementById('chat-input');
    const messagesDiv = document.getElementById('messages');
    const chatBox = document.getElementById('chat');

    function scrollChat() { chatBox.scrollTop = chatBox.scrollHeight; }
    scrollChat();

    chatForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(chatForm);

        fetch(chatForm.action, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': formData.get('_token'), 'Accept': 'application/json' },
            body: formData
        })
        .then(res => res.json())
        .then(() => { chatInput.value = ''; })
        .catch(err => console.error('Chat error:', err));
    });

    if (window.Echo) {
        window.Echo.channel('chat')
            .listen('.MessageSent', (e) => {
                const msg = document.createElement('div');
                msg.className = "px-4 py-3 rounded-2xl shadow-md transition-colors duration-300 bg-gray-100 text-gray-900 dark:bg-gray-800 dark:text-white";
                msg.innerHTML = `<span class='font-semibold text-red-600 dark:text-red-400'>${e.user.name}:</span> ${e.message}`;
                messagesDiv.appendChild(msg);
                scrollChat();
            });
    }
});
</script>
@endpush
