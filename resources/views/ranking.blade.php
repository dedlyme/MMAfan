@extends('layouts.app')

@section('content')
    <h1 class="text-5xl md:text-6xl font-extrabold text-red-500 text-center mb-10 drop-shadow-lg">UFC Rankings</h1>

    @if(isset($divisions) && $divisions->isNotEmpty())
        <h2 class="text-3xl font-bold mb-6 text-white drop-shadow">Divisions</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($divisions as $d)
                <a href="{{ route('ranking.show', $d) }}" 
                   class="bg-gray-800/50 hover:bg-gray-700/60 backdrop-blur-md p-6 rounded-3xl shadow-2xl transform transition duration-300 hover:scale-105 flex flex-col items-center justify-center text-center hover:shadow-red-500/50">
                    <h3 class="text-2xl font-bold text-white mb-2">{{ $d->name }}</h3>
                    <span class="text-gray-400 text-sm">{{ $d->rankings->count() }} fighters</span>
                </a>
            @endforeach
        </div>
    @endif

    <div class="mt-12 space-y-6">
        <h2 id="rotating-title" class="text-4xl font-bold mb-6 text-red-500 drop-shadow">Top Fighters</h2>
        <div id="rotating-container" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 opacity-100 transition-opacity duration-500">
            <div id="rotating-placeholder" class="col-span-full text-center text-gray-400 py-6">Loading...</div>
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
                containerEl.innerHTML = '<div class="col-span-full text-center text-gray-400 py-6">No fighters to show.</div>';
                return;
            }

            const div = divisions[index];
            titleEl.textContent = div.name + " - Top 15 + Champion";
            containerEl.style.opacity = 0;
            setTimeout(() => {
                containerEl.innerHTML = '';
                if(!div.rankings || div.rankings.length === 0){
                    containerEl.innerHTML = '<div class="col-span-full text-center text-gray-400 py-6">Šajā divīzijā vēl nav ierakstu.</div>';
                    containerEl.style.opacity = 1;
                    return;
                }

                const grid = document.createElement('div');
                grid.className = "grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 col-span-full";
                div.rankings.forEach(f => {
                    const fighterEl = document.createElement('div');
                    fighterEl.className = "bg-gray-800/50 hover:bg-gray-700/60 backdrop-blur-md p-4 rounded-2xl shadow-lg flex justify-between items-center transition duration-300 transform hover:scale-105 hover:shadow-red-500/50";
                    const left = document.createElement('div');
                    left.innerHTML = `<span class="text-xl font-semibold text-white">${f.is_champion ? 'C' : f.rank}. ${f.fighter_name}</span>`;
                    if(f.is_champion) left.innerHTML += ' <span class="text-red-500 font-bold ml-2">Champion</span>';
                    fighterEl.appendChild(left);
                    grid.appendChild(fighterEl);
                });
                containerEl.appendChild(grid);
                containerEl.style.opacity = 1;
            }, 180);
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
@endsection
