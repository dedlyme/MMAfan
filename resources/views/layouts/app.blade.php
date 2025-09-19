<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'UFC MMA Dashboard')</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-900 text-white min-h-screen flex flex-col">

    <!-- Navbar -->
    @include('partials.navbar')

    <!-- Main Content -->
    <main class="flex-1 max-w-7xl mx-auto px-6 py-8 space-y-8">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-gray-400 py-6 mt-auto">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center">
            <p>&copy; {{ date('Y') }} UFC MMA. All rights reserved.</p>
           
        </div>
    </footer>

    @vite('resources/js/app.js')
    @stack('scripts')
</body>
</html>
