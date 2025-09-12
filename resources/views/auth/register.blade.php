<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - UFC MMA</title>
    @vite('resources/css/app.css')
</head>
<body class="relative bg-black text-white min-h-screen flex items-center justify-center">

    <!-- Background with blur -->
    <div class="absolute inset-0">
        <img src="{{ asset('wallpaper4.png') }}" alt="Background" 
             class="w-full h-full object-cover filter blur-sm scale-105">
        <div class="absolute inset-0 bg-black/70"></div>
    </div>

    <!-- Register card -->
    <div class="relative z-10 bg-gray-900/80 backdrop-blur-lg p-8 rounded-2xl shadow-xl w-full max-w-md">
        <h1 class="text-3xl font-bold text-center text-yellow-400 mb-6">Register</h1>

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf

            <!-- Name -->
            <div>
                <label for="name" class="block mb-2 text-sm font-medium">Name</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                       class="w-full p-3 rounded-lg bg-gray-800 border border-gray-700 text-white focus:ring-2 focus:ring-yellow-400">
                @error('name')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block mb-2 text-sm font-medium">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                       class="w-full p-3 rounded-lg bg-gray-800 border border-gray-700 text-white focus:ring-2 focus:ring-yellow-400">
                @error('email')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block mb-2 text-sm font-medium">Password</label>
                <input id="password" type="password" name="password" required autocomplete="new-password"
                       class="w-full p-3 rounded-lg bg-gray-800 border border-gray-700 text-white focus:ring-2 focus:ring-yellow-400">
                @error('password')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block mb-2 text-sm font-medium">Confirm Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                       class="w-full p-3 rounded-lg bg-gray-800 border border-gray-700 text-white focus:ring-2 focus:ring-yellow-400">
                @error('password_confirmation')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit -->
            <button type="submit" 
                    class="w-full bg-yellow-400 text-black font-bold py-3 rounded-lg hover:bg-yellow-300 transition">
                Register
            </button>
        </form>

        <!-- Login redirect -->
        <p class="mt-6 text-center text-gray-400">
            Already have an account? 
            <a href="{{ route('login') }}" class="text-yellow-400 hover:underline">
                Login here
            </a>
        </p>
    </div>
</body>
</html>
