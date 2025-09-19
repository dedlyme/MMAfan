@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-8">

    <!-- Live Chat Card -->
    <div class="bg-gray-800/50 text-white shadow-2xl rounded-3xl p-6 hover:scale-105 transform transition duration-300 backdrop-blur-lg border border-gray-700/40">
        <h2 class="text-3xl font-extrabold mb-6 text-red-500">Live Chat</h2>
        <div id="chat" class="flex flex-col h-96 border border-gray-700/30 rounded-2xl p-4 bg-gray-900/20 overflow-y-auto backdrop-blur-sm">
            <div id="messages" class="flex-1 mb-4 space-y-3">
                @foreach($messages as $msg)
                    <div class="px-3 py-2 bg-gray-700/40 rounded-xl">
                        <span class="font-semibold text-red-500">{{ $msg->user->name }}:</span> {{ $msg->message }}
                    </div>
                @endforeach
            </div>
            <form action="{{ route('messages.send') }}" method="POST" class="flex space-x-2">
                @csrf
                <input type="text" name="message" placeholder="Type message..."
                       class="flex-1 p-3 rounded-xl border border-gray-600/30 bg-gray-900/20 text-white focus:ring-2 focus:ring-red-500 outline-none transition backdrop-blur-sm" required>
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-6 rounded-xl font-bold shadow-lg hover:shadow-xl transition">Send</button>
            </form>
        </div>
    </div>

    <!-- Dashboard Sections -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-gray-800/50 p-6 rounded-3xl shadow-2xl hover:scale-105 transform transition duration-300 backdrop-blur-lg border border-gray-700/30">
            <h3 class="text-2xl font-bold mb-3 text-red-500">Homepage</h3>
            <p class="text-gray-200">Chat about news in the live chat feature and latest updates.</p>
        </div>
        <div class="bg-gray-800/50 p-6 rounded-3xl shadow-2xl hover:scale-105 transform transition duration-300 backdrop-blur-lg border border-gray-700/30">
            <h3 class="text-2xl font-bold mb-3 text-red-500">Ranking</h3>
            <p class="text-gray-200">Current fighter rankings by weight class and category.</p>
        </div>
        <div class="bg-gray-800/50 p-6 rounded-3xl shadow-2xl hover:scale-105 transform transition duration-300 backdrop-blur-lg border border-gray-700/30">
            <h3 class="text-2xl font-bold mb-3 text-red-500">Pound for Pound</h3>
            <p class="text-gray-200">Top fighters across all weight classes with detailed stats.</p>
        </div>
        <div class="bg-gray-800/50 p-6 rounded-3xl shadow-2xl hover:scale-105 transform transition duration-300 backdrop-blur-lg border border-gray-700/30">
            <h3 class="text-2xl font-bold mb-3 text-red-500">News</h3>
            <p class="text-gray-200">Latest news and updates from the world of MMA and UFC.</p>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    // Auto scroll chat uz apakš
    window.addEventListener('load', () => {
        const chatBox = document.getElementById('chat');
        if (chatBox) {
            chatBox.scrollTop = chatBox.scrollHeight;
        }
    });
</script>
@endpush
