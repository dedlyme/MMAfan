<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UFC Mājaslapa</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
</head>
<body class="h-screen bg-cover bg-center"
      style="background-image: url('/wallpaper.png');">

    <!-- Navigācija -->
    <nav class="bg-black bg-opacity-80 shadow-md">
        <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-white">UFC</h1>
            <div class="space-x-4">
                <a href="{{ route('login') }}" class="text-white font-medium hover:text-red-500">Login</a>
                <a href="{{ route('register') }}" class="text-white font-medium hover:text-red-500">Register</a>
            </div>
        </div>
    </nav>

    <!-- Galvenais saturs -->
    <main class="flex items-center justify-center h-[80vh] text-center">
        <div class="bg-black bg-opacity-80 p-8 rounded-2xl shadow-xl max-w-lg">
            <h2 class="text-4xl font-extrabold mb-4 text-white">Laipni lūgti UFC mājaslapā</h2>
            <p class="text-lg text-gray-100">
                Šeit būs tavs apraksts par UFC, ko vēlāk pielāgosi.
            </p>
        </div>
    </main>
</body>
</html>
