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
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
    </div>

    <!-- ===== LOGIN KARTE ===== -->
    <div class="relative z-10 w-full max-w-md bg-white/10 backdrop-blur-xl p-8 rounded-2xl shadow-2xl border border-white/20">
        <h1 class="text-3xl font-extrabold text-center text-white mb-6 drop-shadow-md">Login</h1>

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
                <label for="email" class="block mb-2 text-sm font-medium text-gray-200">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                       class="w-full px-4 py-3 rounded-lg bg-white/20 text-white placeholder-gray-300 border border-white/30 focus:ring-2 focus:ring-yellow-400 outline-none">
                @error('email')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block mb-2 text-sm font-medium text-gray-200">Password</label>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                       class="w-full px-4 py-3 rounded-lg bg-white/20 text-white placeholder-gray-300 border border-white/30 focus:ring-2 focus:ring-yellow-400 outline-none">
                @error('password')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember me + Forgot -->
            <div class="flex items-center justify-between text-sm">
                <label class="flex items-center text-gray-200">
                    <input id="remember_me" type="checkbox" name="remember"
                           class="rounded border-gray-600 bg-white/20 text-yellow-400 focus:ring-yellow-400">
                    <span class="ml-2">Remember me</span>
                </label>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-yellow-400 hover:underline">
                        Forgot password?
                    </a>
                @endif
            </div>

            <!-- Submit -->
            <button type="submit"
                    class="w-full bg-yellow-400 text-black font-bold py-3 rounded-full shadow-md hover:bg-yellow-300 transition">
                Log in
            </button>
        </form>

        <!-- Register redirect -->
        <p class="mt-6 text-center text-gray-300 text-sm">
            Don’t have an account?
            <a href="{{ route('register') }}" class="text-yellow-400 font-semibold hover:underline">
                Register here
            </a>
        </p>
    </div>
</body>
</html>
