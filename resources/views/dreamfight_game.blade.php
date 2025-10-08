@extends('layouts.app')

@section('title', 'Dream Fight')

@section('content')
<div class="max-w-5xl mx-auto py-10 px-4 text-center text-gray-900 dark:text-gray-100">

    {{-- Big Fighter Names --}}
    <div class="flex justify-between items-center mb-10">
        <div class="w-1/2 text-left">
            @if($dreamfight->fighterOne)
                <h2 class="text-4xl md:text-5xl font-extrabold text-red-500 drop-shadow-lg uppercase">
                    {{ $dreamfight->fighterOne->first_name }} {{ $dreamfight->fighterOne->last_name }}
                </h2>
                @if($dreamfight->fighterOne->nickname)
                    <p class="text-xl italic text-gray-400">“{{ $dreamfight->fighterOne->nickname }}”</p>
                @endif
            @else
                <p class="text-gray-400 text-2xl">Waiting...</p>
            @endif
        </div>

        <div class="text-5xl font-extrabold text-gray-500">VS</div>

        <div class="w-1/2 text-right">
            @if($dreamfight->fighterTwo)
                <h2 class="text-4xl md:text-5xl font-extrabold text-blue-500 drop-shadow-lg uppercase">
                    {{ $dreamfight->fighterTwo->first_name }} {{ $dreamfight->fighterTwo->last_name }}
                </h2>
                @if($dreamfight->fighterTwo->nickname)
                    <p class="text-xl italic text-gray-400">“{{ $dreamfight->fighterTwo->nickname }}”</p>
                @endif
            @else
                <p class="text-gray-400 text-2xl">Waiting...</p>
            @endif
        </div>
    </div>

    {{-- Fight Stats --}}
    <p class="text-gray-600 dark:text-gray-400 mb-1">
        Round {{ $dreamfight->current_round }} / 3
    </p>

    <p class="font-semibold mb-6">
        Score:
        <span class="text-green-600 dark:text-green-400">{{ $dreamfight->player_one_score }}</span>
        -
        <span class="text-green-600 dark:text-green-400">{{ $dreamfight->player_two_score }}</span>
    </p>

    @if($dreamfight->status === 'finished')
        <h2 class="text-3xl font-bold text-green-600 dark:text-green-400 mb-4">
            Winner: {{ $dreamfight->winner }}
        </h2>
        <p class="text-gray-500 dark:text-gray-400">This window will close in 5 seconds…</p>
        <script>setTimeout(()=>window.close(), 5000);</script>
    @else
        @php
            $myChoice = $dreamfight->player_one_id === Auth::id()
                ? $dreamfight->player_one_choice
                : $dreamfight->player_two_choice;
        @endphp

        @if(!$myChoice)
            {{-- CHOICE PIE --}}
            <form id="fightChoiceForm" method="POST" action="{{ route('dreamfights.choose', $dreamfight) }}">
                @csrf
                <input type="hidden" name="choice" id="choiceInput">

                <div class="mx-auto mt-6">
                    <svg id="pie" viewBox="0 0 320 320" width="300" height="300" class="mx-auto drop-shadow-xl">
                        <circle cx="160" cy="160" r="154"
                                class="stroke-gray-300 dark:stroke-gray-600"
                                fill="transparent" stroke-width="4"/>

                        <g class="slice" data-choice="wrestling">
                            <path class="slice-path" fill="rgba(0,0,0,.04)"></path>
                            <image class="icon" href="/icons/wrestling.svg" width="40" height="40"/>
                            <text class="label text-sm font-semibold">Wrestling</text>
                        </g>

                        <g class="slice" data-choice="kickbox">
                            <path class="slice-path" fill="rgba(0,0,0,.04)"></path>
                            <image class="icon" href="/icons/kickboxing.svg" width="40" height="40"/>
                            <text class="label text-sm font-semibold">Kickbox</text>
                        </g>

                        <g class="slice" data-choice="jiu-jitsu">
                            <path class="slice-path" fill="rgba(0,0,0,.04)"></path>
                            <image class="icon" href="/icons/jiu-jitsu.svg" width="40" height="40"/>
                            <text class="label text-sm font-semibold">Jiu-Jitsu</text>
                        </g>

                        <g id="centerLabelGroup" pointer-events="none">
                            <circle cx="160" cy="160" r="56" class="fill-gray-200 dark:fill-gray-700"></circle>
                            <text id="centerLabel" x="160" y="160" text-anchor="middle"
                                  dominant-baseline="middle"
                                  class="font-bold text-base">Choose</text>
                        </g>
                    </svg>
                </div>
                <p class="text-gray-500 dark:text-gray-400 mt-4">Click a slice to lock your move.</p>
            </form>
        @else
            <p class="text-yellow-600 dark:text-yellow-400 mb-4 text-lg font-semibold">
                You chose: {{ ucfirst($myChoice) }}
            </p>
            <p class="text-gray-500 dark:text-gray-400">Waiting for the other player…</p>
        @endif

        <div class="mt-6 text-sm text-gray-500 dark:text-gray-400">
            This page refreshes automatically every 10s.
        </div>
        <script>setInterval(()=>location.reload(), 10000);</script>
    @endif
</div>

<script>
(function () {
    const svg = document.getElementById('pie');
    if (!svg) return;

    const cx = 160, cy = 160, R = 150;
    const ICON_DIST  = 90;
    const LABEL_DIST = 120;

    const slices = [
        { choice: 'wrestling', start: -90, end: 30 },
        { choice: 'kickbox',   start: 30,  end: 150 },
        { choice: 'jiu-jitsu', start: 150, end: 270 },
    ];

    const toRad = d => d * Math.PI / 180;
    const mid = (a, b) => (a + b) / 2;
    const polar = (r, angleDeg) => {
        const rad = toRad(angleDeg);
        return {x: cx + r * Math.cos(rad), y: cy + r * Math.sin(rad)};
    };
    const path = (r, a0, a1) => {
        const p0 = polar(r, a0);
        const p1 = polar(r, a1);
        const large = Math.abs(a1 - a0) > 180 ? 1 : 0;
        return `M ${cx} ${cy} L ${p0.x} ${p0.y} A ${r} ${r} 0 ${large} 1 ${p1.x} ${p1.y} Z`;
    };

    svg.querySelectorAll('g.slice').forEach((g,i)=>{
        const conf = slices[i];
        const p = g.querySelector('.slice-path');
        const img = g.querySelector('.icon');
        const txt = g.querySelector('.label');

        p.setAttribute('d', path(R, conf.start, conf.end));

        const m = mid(conf.start, conf.end);
        const iconPos = polar(ICON_DIST, m);
        const labelPos = polar(LABEL_DIST, m);

        img.setAttribute('x', iconPos.x - 20);
        img.setAttribute('y', iconPos.y - 20);
        txt.setAttribute('x', labelPos.x);
        txt.setAttribute('y', labelPos.y);
        txt.setAttribute('text-anchor', 'middle');
        txt.setAttribute('dominant-baseline', 'middle');

        g.style.cursor='pointer';
        g.addEventListener('mouseenter',()=>p.setAttribute('fill','rgba(0,0,0,.08)'));
        g.addEventListener('mouseleave',()=>p.setAttribute('fill','rgba(0,0,0,.04)'));
        g.addEventListener('click',()=>{
            document.getElementById('choiceInput').value=conf.choice;
            document.getElementById('centerLabel').textContent =
                conf.choice.charAt(0).toUpperCase()+conf.choice.slice(1);
            p.setAttribute('fill','rgba(0,0,0,.12)');
            setTimeout(()=>document.getElementById('fightChoiceForm').submit(),350);
        });
    });
})();
</script>
@endsection
