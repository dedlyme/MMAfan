<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Login' }}</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
</head>
<body class="h-screen bg-cover bg-center flex items-center justify-center"
      style="background-image: url('/wallpaper2.png');">

    <div class="bg-black bg-opacity-80 p-8 rounded-2xl shadow-xl w-full max-w-md">
        {{ $slot }}
    </div>
</body>
</html>
