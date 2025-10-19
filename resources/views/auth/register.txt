<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - UFC MMA</title>
    @vite('resources/css/app.css')
    <style>
        /* 🚫 Prevent horizontal scrolling */
        html, body {
            overflow-x: hidden;
        }
    </style>
</head>
<body class="relative bg-black text-white min-h-screen flex items-center justify-center overflow-x-hidden">

    <!-- 🌄 Background with overlay -->
    <div class="absolute inset-0">
        <img src="{{ asset('wallpaper4.png') }}" alt="Background" 
             class="w-full h-full object-cover brightness-50">
        <div class="absolute inset-0 bg-black/70"></div>
    </div>

    <!-- 🪟 Register card -->
    <div class="relative z-10 w-full max-w-md bg-gray-900/70 backdrop-blur-lg rounded-3xl shadow-2xl p-8 animate-fade-in">
        <div class="text-center mb-8">
            <div class="flex justify-center mb-4">
                <svg class="w-16 h-16 text-yellow-400 drop-shadow-lg" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4m6 6H6a2 2 0 01-2-2V6a2 2 0 012-2h6a2 2 0 012 2v4l6 6z"/>
                </svg>
            </div>
            <h1 class="text-4xl font-extrabold text-yellow-400 tracking-wide drop-shadow-lg">Create Account</h1>
            <p class="mt-2 text-gray-300 text-sm">Join the <span class="text-yellow-400 font-semibold">UFC MMA</span> community today</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf

            <!-- Name -->
            <div>
                <label for="name" class="block mb-2 text-sm font-semibold text-gray-200">Full Name</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                       class="w-full p-3 rounded-xl bg-gray-800 border border-gray-700 text-white focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 transition">
                @error('name')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block mb-2 text-sm font-semibold text-gray-200">Email Address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                       class="w-full p-3 rounded-xl bg-gray-800 border border-gray-700 text-white focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 transition">
                @error('email')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block mb-2 text-sm font-semibold text-gray-200">Password</label>
                <input id="password" type="password" name="password" required autocomplete="new-password"
                       class="w-full p-3 rounded-xl bg-gray-800 border border-gray-700 text-white focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 transition">
                @error('password')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block mb-2 text-sm font-semibold text-gray-200">Confirm Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                       class="w-full p-3 rounded-xl bg-gray-800 border border-gray-700 text-white focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 transition">
                @error('password_confirmation')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit -->
            <button type="submit" 
                class="w-full py-3 rounded-xl bg-yellow-400 text-black font-bold text-lg shadow-lg hover:bg-yellow-300 transition transform hover:scale-[1.02]">
                Create Account
            </button>
        </form>

        <!-- Login redirect -->
        <p class="mt-6 text-center text-gray-400 text-sm">
            Already have an account? 
            <a href="{{ route('login') }}" class="text-yellow-400 hover:underline font-semibold">Log in here</a>
        </p>
    </div>

    <!-- ✨ Animations -->
    <style>
        @keyframes fade-in {
            0% { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in { animation: fade-in 0.8s ease-out forwards; }
    </style>
</body>
</html>
