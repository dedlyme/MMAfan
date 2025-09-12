<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UFC MMA</title>
    @vite('resources/css/app.css')
</head>
<body class="relative bg-black text-white min-h-screen overflow-hidden">

    <!-- Background with blur -->
    <div class="absolute inset-0">
        <img src="{{ asset('wallpaper.png') }}" alt="Background" 
             class="w-full h-full object-cover filter blur-sm scale-105">
        <div class="absolute inset-0 bg-black/70"></div>
    </div>

    <!-- Navigation bar -->
    <nav class="relative z-10 flex justify-between items-center p-6 bg-black/40 backdrop-blur-md">
        <div class="text-2xl font-bold tracking-wide text-yellow-400">
            UFC MMA
        </div>
        <ul class="flex space-x-6">
            <li>
                <a href="{{ route('login') }}" 
                   class="px-4 py-2 border border-yellow-400 text-yellow-400 rounded-lg hover:bg-yellow-400 hover:text-black transition">
                   Login
                </a>
            </li>
            <li>
                <a href="{{ route('register') }}" 
                   class="px-4 py-2 bg-yellow-400 text-black font-semibold rounded-lg hover:bg-yellow-300 transition">
                   Register
                </a>
            </li>
        </ul>
    </nav>

    <!-- Hero section -->
    <section class="relative z-10 flex flex-col justify-center items-center text-center h-screen px-6">
        <h1 class="text-5xl md:text-7xl font-extrabold text-white drop-shadow-lg animate-fade-in">
            Welcome to <span class="text-yellow-400">UFC & MMA</span>
        </h1>
        <p class="mt-6 text-xl md:text-2xl max-w-2xl text-gray-300 animate-slide-up">
            Follow the latest fights, rankings, and breaking news in the world of Mixed Martial Arts.
        </p>

        <div class="mt-10 flex space-x-6 animate-bounce-slow">
            <a href="{{ route('login') }}" 
               class="px-6 py-3 bg-yellow-400 text-black font-bold rounded-lg hover:bg-yellow-300 transition">
               Get Started
            </a>
            <a href="{{ route('register') }}" 
               class="px-6 py-3 border-2 border-yellow-400 text-yellow-400 font-bold rounded-lg hover:bg-yellow-400 hover:text-black transition">
               Join Now
            </a>
        </div>
    </section>

    <!-- Tailwind Animations -->
    <style>
        @keyframes fade-in {
            0% { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        @keyframes slide-up {
            0% { opacity: 0; transform: translateY(40px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        @keyframes bounce-slow {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        .animate-fade-in {
            animation: fade-in 1s ease-out forwards;
        }
        .animate-slide-up {
            animation: slide-up 1.5s ease-out forwards;
        }
        .animate-bounce-slow {
            animation: bounce-slow 3s infinite;
        }
    </style>
</body>
</html>
