@extends('layouts.app')

@section('title', 'Welcome')

@section('background')
    <!-- Fullscreen background video -->
    <div class="fixed inset-0 -z-10 overflow-hidden">
        <iframe 
            src="https://drive.google.com/file/d/1F-MDW7pERvPThhxPtQgUfIMKKiR31bv1/preview?autoplay=1&mute=1&loop=1"
            frameborder="0"
            allow="autoplay; fullscreen"
            class="absolute top-0 left-0 w-full h-full object-cover">
        </iframe>
        <!-- Dark overlay for readability -->
        <div class="absolute inset-0 bg-black/50"></div>
    </div>
@endsection

@section('content')
<div class="min-h-screen flex flex-col items-center justify-center text-center px-6 relative z-10">
    <!-- Title -->
    <h1 class="text-5xl md:text-6xl font-extrabold text-white drop-shadow-lg mb-4">
        Welcome to UFC MMA Universe
    </h1>

    <!-- Subtitle -->
    <p class="text-lg md:text-xl text-gray-200 max-w-2xl mb-10">
        Step into the octagon of data — explore fighter rankings, pound-for-pound lists, dream fights, and live chat with other MMA fans.
    </p>

    <!-- Buttons -->
    <div class="flex flex-col sm:flex-row gap-4">
        @auth
            <a href="{{ route('dashboard') }}"
               class="bg-red-600 hover:bg-red-500 text-white px-8 py-3 rounded-xl text-lg font-bold shadow-md transition transform hover:scale-105">
                Go to Dashboard
            </a>
        @else
            <a href="{{ route('login') }}"
               class="bg-red-600 hover:bg-red-500 text-white px-8 py-3 rounded-xl text-lg font-bold shadow-md transition transform hover:scale-105">
                Login
            </a>
            <a href="{{ route('register') }}"
               class="bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-900 dark:text-gray-100 px-8 py-3 rounded-xl text-lg font-bold shadow-md transition transform hover:scale-105">
                Register
            </a>
        @endauth
    </div>
</div>
@endsection
