<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>UFC Rankings</title>
@vite('resources/css/app.css')
<style>
    /* Background slideshow */
    .slideshow {
        position: fixed;
        inset: 0;
        z-index: -1;
        width: 100%;
        height: 100%;
        overflow: hidden;
    }
    .slideshow img {
        position: absolute;
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: 0;
        animation: slideShow 25s infinite;
        filter: brightness(0.6);
    }
    .slideshow img:nth-child(1) { animation-delay: 0s; }
    .slideshow img:nth-child(2) { animation-delay: 5s; }
    .slideshow img:nth-child(3) { animation-delay: 10s; }
    .slideshow img:nth-child(4) { animation-delay: 15s; }
    .slideshow img:nth-child(5) { animation-delay: 20s; }

    @keyframes slideShow {
        0% { opacity: 0; }
        10% { opacity: 1; }
        20% { opacity: 1; }
        30% { opacity: 0; }
        100% { opacity: 0; }
    }
</style>
</head>
<body class="bg-black text-white min-h-screen relative">

<!-- Background slideshow -->
<div class="slideshow">
    <img src="{{ asset('wallpaper.png') }}" alt="">
    <img src="{{ asset('wallpaper2.png') }}" alt="">
    <img src="{{ asset('wallpaper3.png') }}" alt="">
    <img src="{{ asset('wallpaper4.png') }}" alt="">
    <img src="{{ asset('wallpaper5.png') }}" alt="">
</div>

<!-- Dark overlay -->
<div class="fixed inset-0 bg-black/50 z-0"></div>

<!-- Main content -->
<div class="relative z-10">

    <!-- Navigation bar -->
    <nav class="flex justify-between items-center p-4 bg-gray-900/80 sticky top-0 backdrop-blur-md z-10">
        <div class="text-xl font-bold drop-shadow-lg">UFC MMA Dashboard</div>
        <ul class="flex space-x-6 items-center">
            <li><a href="{{ route('dashboard') }}" class="hover:text-yellow-400 transition">Home</a></li>
            <li><a href="{{ route('ranking') }}" class="hover:text-yellow-400 transition">Ranking</a></li>
            <li><a href="{{ route('pound') }}" class="hover:text-yellow-400 transition">Pound for Pound</a></li>
            <li><a href="{{ route('news') }}" class="hover:text-yellow-400 transition">News</a></li>

            @if(auth()->check() && auth()->user()->is_admin)
                <li><a href="{{ route('admin.divisions.index') }}" class="hover:text-yellow-400 transition">Divisions</a></li>
            @endif

            <li><a href="{{ route('profile.edit') }}" class="hover:text-yellow-400 transition">Profile</a></li>
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="hover:text-yellow-400 transition">Logout</button>
                </form>
            </li>
        </ul>
    </nav>

    <div class="py-8 px-6 max-w-7xl mx-auto">

        <h1 class="text-4xl font-extrabold mb-8 text-yellow-400 text-center">UFC Rankings</h1>

        <!-- Rotating Division Display -->
        <div id="rotating-division" class="space-y-4">
            <!-- JS will populate this container -->
        </div>

    </div>
</div>

<script>
    const divisions = @json($divisions); // no controller padotās divīzijas
    let currentIndex = 0;

    const container = document.getElementById('rotating-division');

    function showDivision(index) {
        const division = divisions[index];
        container.innerHTML = ''; // clear previous

        const title = document.createElement('h2');
        title.className = "text-3xl font-bold mb-6 text-yellow-400";
        title.textContent = division.name + ' - Top 15 + Champion';
        container.appendChild(title);

        const grid = document.createElement('div');
        grid.className = "grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4";

        // Sort by champion first, then rank
        const sorted = division.rankings.sort((a, b) => (b.is_champion - a.is_champion) || (a.rank - b.rank));

        sorted.forEach(fighter => {
            const card = document.createElement('div');
            card.className = "bg-gray-900/80 p-4 rounded-2xl shadow-lg flex justify-between items-center hover:scale-105 transform transition duration-300";

            const nameDiv = document.createElement('div');
            nameDiv.innerHTML = `<span class="text-xl font-semibold text-white">${fighter.is_champion ? 'C' : fighter.rank}. ${fighter.fighter_name}</span>` +
                                (fighter.is_champion ? '<span class="text-yellow-400 font-bold ml-2">Champion</span>' : '');
            card.appendChild(nameDiv);

            grid.appendChild(card);
        });

        container.appendChild(grid);
    }

    // Initial display
    showDivision(currentIndex);

    // Change division every 10 seconds
    setInterval(() => {
        currentIndex = (currentIndex + 1) % divisions.length;
        showDivision(currentIndex);
    }, 10000);
</script>

</body>
</html>
