@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-12">

    <!-- Hero / Intro Section -->
    <section class="text-center py-16 px-8 bg-gradient-to-r from-red-600 to-red-400 dark:from-gray-900 dark:to-gray-700 rounded-3xl shadow-2xl transition-colors duration-500">
        <h1 class="text-6xl font-extrabold text-white mb-4 drop-shadow-lg tracking-tight">
            UFC MMA Dashboard
        </h1>
        <p class="text-white/90 text-xl max-w-3xl mx-auto">
            Engage with live chat, stay updated with fighter news, and track dream fights. Experience the ultimate MMA control center.
        </p>
    </section>

    <!-- Quick Stats Cards -->
    <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl p-8 transition-transform transform hover:-translate-y-2 hover:shadow-2xl duration-300">
            <h2 class="text-xl font-semibold text-red-500 mb-3">Total Messages</h2>
            <p class="text-gray-900 dark:text-gray-100 text-4xl font-bold">{{ $messages->count() }}</p>
            <p class="text-gray-500 dark:text-gray-400 mt-2">Messages sent in live chat</p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl p-8 transition-transform transform hover:-translate-y-2 hover:shadow-2xl duration-300">
            <h2 class="text-xl font-semibold text-red-500 mb-3">Active User</h2>
            <p class="text-gray-900 dark:text-gray-100 text-4xl font-bold">{{ auth()->user()->name }}</p>
            <p class="text-gray-500 dark:text-gray-400 mt-2">You are currently logged in</p>
        </div>
    </section>

    <!-- Live Chat Section -->
    <section class="bg-white/90 dark:bg-gray-900/80 shadow-2xl rounded-3xl p-8 backdrop-blur-lg border border-gray-200/30 dark:border-gray-700/40 transition-colors duration-500">
        <h2 class="text-3xl font-extrabold mb-6 text-red-500 drop-shadow-lg text-center tracking-wide">
            Live Chat
        </h2>

        <div id="chat" class="flex flex-col h-96 border border-gray-200/30 dark:border-gray-700/30 rounded-2xl p-4 bg-gray-50/50 dark:bg-gray-800/30 overflow-y-auto backdrop-blur-sm transition-colors duration-500">
            <div id="messages" class="flex-1 mb-4 space-y-3">
                @foreach($messages as $msg)
                    <div class="px-4 py-3 bg-red-50 dark:bg-red-900/30 rounded-2xl shadow-sm text-gray-900 dark:text-gray-100 transition-colors duration-300">
                        <span class="font-semibold text-red-600 dark:text-red-400">{{ $msg->user->name }}:</span>
                        <span>{{ $msg->message }}</span>
                    </div>
                @endforeach
            </div>

            <form id="chat-form" action="{{ route('messages.send') }}" method="POST" class="flex space-x-3 mt-3">
                @csrf
                <input id="chat-input" type="text" name="message" placeholder="Type message..."
                    class="flex-1 p-3 rounded-2xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-red-500 outline-none transition-all duration-300"
                    required>
                <button type="submit" class="px-6 py-3 bg-red-500 hover:bg-red-600 text-white rounded-2xl font-bold shadow-lg hover:shadow-xl transition duration-300">
                    Send
                </button>
            </form>
        </div>
    </section>

    <!-- News / Dream Fights Section -->
    <section class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl p-8 transition-transform transform hover:-translate-y-2 hover:shadow-3xl duration-300">
            <h2 class="text-2xl font-bold text-red-500 mb-4 tracking-wide">Latest News</h2>
            <p class="text-gray-800 dark:text-gray-200 text-lg">
                Stay up-to-date with UFC MMA news, fight announcements, and major events around the world. All information in real-time with a clean, modern layout.
            </p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl p-8 transition-transform transform hover:-translate-y-2 hover:shadow-3xl duration-300">
            <h2 class="text-2xl font-bold text-red-500 mb-4 tracking-wide">Dream Fights</h2>
            <p class="text-gray-800 dark:text-gray-200 text-lg">
                Plan and track dream fights with friends. Compare fighters, weight classes, and predicted outcomes. Your fantasy MMA experience, beautifully organized.
            </p>
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

    function scrollChat() {
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    scrollChat();

    chatForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(chatForm);

        fetch(chatForm.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': formData.get('_token'),
                'Accept': 'application/json'
            },
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
                msg.classList.add('px-4', 'py-3', 'bg-red-50', 'dark:bg-red-900/30', 'rounded-2xl', 'shadow-sm', 'text-gray-900', 'dark:text-gray-100', 'transition-colors', 'duration-300');
                msg.innerHTML = `<span class="font-semibold text-red-600 dark:text-red-400">${e.user.name}:</span> ${e.message}`;
                messagesDiv.appendChild(msg);
                scrollChat();
            });
    }
});
</script>
@endpush
