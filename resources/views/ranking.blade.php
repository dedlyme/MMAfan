@extends('layouts.app')

@section('title', 'UFC Rankings')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">

    <!-- 🥋 HEADER -->
    <section class="text-center">
        <h1 class="text-5xl md:text-6xl font-extrabold text-red-600 mb-6 drop-shadow">
            UFC Rankings
        </h1>
        <p class="text-gray-600 dark:text-white/70 text-lg max-w-2xl mx-auto">
            Explore divisions, champions, and the top fighters in each weight class.
        </p>
    </section>

    <!-- 📊 DIVISIONS -->
    @if(isset($divisions) && $divisions->isNotEmpty())
        <h2 class="text-3xl font-bold mb-6 text-gray-800 dark:text-white">
            Divisions
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($divisions as $d)
                <a href="{{ route('ranking.show', $d) }}"
                   class="bg-white dark:bg-[#111] rounded-2xl p-6 shadow-md hover:shadow-xl transition duration-300 transform hover:-translate-y-2 group">
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-white group-hover:text-red-600 transition">
                        {{ $d->name }}
                    </h3>
                    <span class="text-gray-500 dark:text-white/70 text-sm block mt-2">
                        {{ $d->rankings->count() }} fighters
                    </span>
                </a>
            @endforeach
        </div>
    @endif

    <!-- 🥇 TOP FIGHTERS ROTATOR -->
    <div class="mt-16 space-y-6">
        <h2 id="rotating-title" class="text-4xl font-extrabold mb-6 text-red-600">
            Top Fighters
        </h2>
        <div id="rotating-container"
             class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 opacity-100 transition-opacity duration-500">
            <div id="rotating-placeholder" class="col-span-full text-center text-gray-400 dark:text-white/70 py-6">
                Loading...
            </div>
        </div>
    </div>

    @php
        $divisionsForJs = [];
        if(isset($divisions) && $divisions->isNotEmpty()) {
            foreach($divisions as $d) {
                $rankings = $d->rankings->sortBy(fn($f) => $f->is_champion ? 0 : $f->rank)->values();
                $rankingsArr = [];
                foreach ($rankings as $f) {
                    $rankingsArr[] = [
                        'fighter_name' => $f->fighter_name,
                        'rank' => $f->rank,
                        'is_champion' => (bool)$f->is_champion,
                    ];
                }
                $divisionsForJs[] = ['name'=>$d->name, 'rankings'=>$rankingsArr];
            }
        } elseif(isset($division)) {
            $rankings = $division->rankings->sortBy(fn($f) => $f->is_champion ? 0 : $f->rank)->values();
            $rankingsArr = [];
            foreach ($rankings as $f) {
                $rankingsArr[] = ['fighter_name'=>$f->fighter_name,'rank'=>$f->rank,'is_champion'=>(bool)$f->is_champion];
            }
            $divisionsForJs[] = ['name'=>$division->name,'rankings'=>$rankingsArr];
        }
    @endphp

    @push('scripts')
    <script>
        const divisions = {!! json_encode($divisionsForJs, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT) !!} || [];
        let currentIndex = 0;
        const titleEl = document.getElementById('rotating-title');
        const containerEl = document.getElementById('rotating-container');

        function renderDivision(index){
            if(!divisions || divisions.length === 0){
                titleEl.textContent = "No divisions available";
                containerEl.innerHTML = '<div class="col-span-full text-center text-gray-400 dark:text-white/70 py-6">No fighters to show.</div>';
                return;
            }

            const div = divisions[index];
            titleEl.textContent = `${div.name} — Top Fighters`;
            containerEl.style.opacity = 0;

            setTimeout(() => {
                containerEl.innerHTML = '';
                if(!div.rankings || div.rankings.length === 0){
                    containerEl.innerHTML = '<div class="col-span-full text-center text-gray-400 dark:text-white/70 py-6">No fighters in this division yet.</div>';
                    containerEl.style.opacity = 1;
                    return;
                }

                const grid = document.createElement('div');
                grid.className = "grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 col-span-full";

                div.rankings.forEach(f => {
                    const fighterEl = document.createElement('div');
                    fighterEl.className = "bg-white dark:bg-[#111] rounded-2xl p-4 shadow hover:shadow-xl transform transition duration-300 hover:-translate-y-1 flex justify-between items-center";
                    const left = document.createElement('div');
                    left.innerHTML = `<span class="text-lg font-semibold text-gray-900 dark:text-white">${f.is_champion ? 'C' : f.rank}. ${f.fighter_name}</span>`;
                    if(f.is_champion) left.innerHTML += ' <span class="ml-2 text-red-600 font-bold">Champion</span>';
                    fighterEl.appendChild(left);
                    grid.appendChild(fighterEl);
                });

                containerEl.appendChild(grid);
                containerEl.style.opacity = 1;
            }, 200);
        }

        renderDivision(currentIndex);

        if(divisions.length > 1){
            setInterval(() => {
                currentIndex = (currentIndex+1)%divisions.length;
                renderDivision(currentIndex);
            },10000);
        }
    </script>
    @endpush
</div>
@endsection
