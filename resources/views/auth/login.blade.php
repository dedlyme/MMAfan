<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - UFC MMA</title>
    @vite('resources/css/app.css')
</head>
<body class="relative min-h-screen flex items-center justify-center">

    <!-- ===== FONS ===== -->
    <div class="absolute inset-0">
        <img src="{{ asset('login.png') }}" alt="Background"
             class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-black/70 backdrop-blur-md"></div>
    </div>

    <!-- ===== LOGIN KARTE ===== -->
    <div class="relative z-10 w-full max-w-md bg-gray-900/70 backdrop-blur-xl p-8 rounded-2xl shadow-2xl border border-white/10">
        <h1 class="text-3xl font-extrabold text-center text-white mb-6 drop-shadow-md tracking-wider">
            UFC LOGIN
        </h1>

        <!-- Session Status -->
        @if (session('status'))
            <div class="mb-4 text-green-400 text-center">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <!-- Email -->
            <div>
                <label for="email" class="block mb-2 text-sm font-medium text-gray-300">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                       class="w-full px-4 py-3 rounded-lg bg-gray-800 text-white placeholder-gray-400 border border-gray-600 focus:ring-2 focus:ring-red-500 outline-none">
                @error('email')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block mb-2 text-sm font-medium text-gray-300">Password</label>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                       class="w-full px-4 py-3 rounded-lg bg-gray-800 text-white placeholder-gray-400 border border-gray-600 focus:ring-2 focus:ring-red-500 outline-none">
                @error('password')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

         
            <!-- Submit -->
            <button type="submit"
                    class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-full shadow-md transition">
                Log in
            </button>
        </form>

        <!-- Register redirect -->
        <p class="mt-6 text-center text-gray-400 text-sm">
            Don’t have an account?
            <a href="{{ route('register') }}" class="text-red-500 font-semibold hover:underline">
                Register here
            </a>
        </p>
    </div>
</body>
</html>
