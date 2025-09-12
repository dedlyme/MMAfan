<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - UFC MMA</title>
    @vite('resources/css/app.css')
</head>
<body class="relative bg-black text-white min-h-screen flex items-center justify-center">

    <!-- Background with blur -->
    <div class="absolute inset-0">
        <img src="{{ asset('wallpaper2.png') }}" alt="Background" 
             class="w-full h-full object-cover filter blur-sm scale-105">
        <div class="absolute inset-0 bg-black/70"></div>
    </div>

    <!-- Login card -->
    <div class="relative z-10 bg-gray-900/80 backdrop-blur-lg p-8 rounded-2xl shadow-xl w-full max-w-md">
        <h1 class="text-3xl font-bold text-center text-yellow-400 mb-6">Login</h1>

        <!-- Session Status -->
        @if (session('status'))
            <div class="mb-4 text-green-400 text-center">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <!-- Email Address -->
            <div>
                <label for="email" class="block mb-2 text-sm font-medium">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                       class="w-full p-3 rounded-lg bg-gray-800 border border-gray-700 text-white focus:ring-2 focus:ring-yellow-400">
                @error('email')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block mb-2 text-sm font-medium">Password</label>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                       class="w-full p-3 rounded-lg bg-gray-800 border border-gray-700 text-white focus:ring-2 focus:ring-yellow-400">
                @error('password')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between">
                <label for="remember_me" class="flex items-center text-sm">
                    <input id="remember_me" type="checkbox" name="remember"
                           class="rounded border-gray-600 bg-gray-800 text-yellow-400 focus:ring-yellow-400">
                    <span class="ml-2">Remember me</span>
                </label>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" 
                       class="text-sm text-yellow-400 hover:underline">
                        Forgot password?
                    </a>
                @endif
            </div>

            <!-- Submit -->
            <button type="submit" 
                    class="w-full bg-yellow-400 text-black font-bold py-3 rounded-lg hover:bg-yellow-300 transition">
                Log in
            </button>
        </form>

        <!-- Register redirect -->
        <p class="mt-6 text-center text-gray-400">
            Don't have an account? 
            <a href="{{ route('register') }}" class="text-yellow-400 hover:underline">
                Register here
            </a>
        </p>
    </div>
</body>
</html>
