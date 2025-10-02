<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'UFC MMA Dashboard')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-lightBg text-gray-900 dark:bg-darkBg dark:text-white min-h-screen flex flex-col transition-colors duration-500">

    {{-- ====== NAVBAR (vienmēr redzama) ====== --}}
    <header class="relative z-50">
        @include('partials.navbar')
    </header>

    {{-- ====== BACKGROUND (no bērna lapas) ====== --}}
    @yield('background')

    {{-- ====== GALVENĀ SATURA ZONA ====== --}}
    <main class="flex-1 relative z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            @yield('content')
        </div>
    </main>

    {{-- ====== FOOTER ====== --}}
    <footer class="bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 py-6 mt-auto transition-colors duration-500 relative z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center">
            <p>&copy; {{ date('Y') }} UFC MMA. All rights reserved.</p>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
