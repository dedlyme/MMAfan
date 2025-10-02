@extends('layouts.app')

@section('title', 'Dream Fight')

@section('content')
<div class="max-w-3xl mx-auto py-10 px-4 text-center text-white">
    <h1 class="text-4xl font-bold mb-4">Fight #{{ $dreamfight->id }}</h1>

    <p class="mb-1">
        <strong>{{ $dreamfight->playerOne?->name ?? 'Waiting...' }}</strong>
        vs
        <strong>{{ $dreamfight->playerTwo?->name ?? 'Waiting...' }}</strong>
    </p>

    <p class="text-gray-400 mb-6">Round {{ $dreamfight->current_round }} / 3</p>
    <p class="mb-6 font-semibold">Score: {{ $dreamfight->player_one_score }} - {{ $dreamfight->player_two_score }}</p>

    @if($dreamfight->status === 'finished')
        <h2 class="text-green-400 text-3xl font-bold mb-4">Winner: {{ $dreamfight->winner }}</h2>
        <p class="text-gray-400">This window will close in 5 seconds…</p>
        <script>setTimeout(()=>window.close(), 5000);</script>
    @else
        @php
            $myChoice = $dreamfight->player_one_id === Auth::id()
                ? $dreamfight->player_one_choice
                : $dreamfight->player_two_choice;
        @endphp

        @if(!$myChoice)
            <form id="fightChoiceForm" method="POST" action="{{ route('dreamfights.choose', $dreamfight) }}">
                @csrf
                <input type="hidden" name="choice" id="choiceInput">

                <div class="mx-auto mt-6">
                    <svg id="pie" viewBox="0 0 320 320" width="300" height="300"
                         class="mx-auto drop-shadow-xl"
                         aria-label="Choose Style">
                        <!-- Outer ring -->
                        <circle cx="160" cy="160" r="154" fill="transparent" stroke="rgba(148,163,184,.8)" stroke-width="4"/>

                        <!-- Slices (paths are filled by JS) -->
                        <g class="slice" data-choice="wrestling">
                            <path class="slice-path" fill="rgba(255,255,255,.06)"></path>
                            <text class="slice-label" text-anchor="middle" dominant-baseline="middle"
                                  fill="#fff" font-size="16" font-weight="600">Wrestling</text>
                        </g>

                        <g class="slice" data-choice="kickbox">
                            <path class="slice-path" fill="rgba(255,255,255,.06)"></path>
                            <text class="slice-label" text-anchor="middle" dominant-baseline="middle"
                                  fill="#fff" font-size="16" font-weight="600">Kickbox</text>
                        </g>

                        <g class="slice" data-choice="jiu-jitsu">
                            <path class="slice-path" fill="rgba(255,255,255,.06)"></path>
                            <text class="slice-label" text-anchor="middle" dominant-baseline="middle"
                                  fill="#fff" font-size="16" font-weight="600">Jiu-Jitsu</text>
                        </g>

                        <!-- Center label -->
                        <g id="centerLabelGroup" pointer-events="none">
                            <circle cx="160" cy="160" r="56" fill="rgba(0,0,0,.35)"></circle>
                            <text id="centerLabel" x="160" y="160" text-anchor="middle" dominant-baseline="middle"
                                  fill="#e5e7eb" font-size="18" font-weight="700">
                                Choose Style
                            </text>
                        </g>
                    </svg>
                </div>
                <p class="text-gray-400 mt-4">Click any section to lock your move.</p>
            </form>
        @else
            <p class="text-yellow-300 mb-4 text-lg font-semibold">You chose: {{ ucfirst($myChoice) }}</p>
            <p class="text-gray-300">Waiting for the other player…</p>
        @endif

        <div class="mt-6 text-sm text-gray-500">This page refreshes automatically every 10s.</div>
        <script>setInterval(()=>location.reload(), 10000);</script>
    @endif
</div>

{{-- JS for SVG pie --}}
<script>
(function () {
    const svg = document.getElementById('pie');
    if (!svg) return;

    // Geometry
    const cx = 160, cy = 160, R = 150;
    const slices = [
        { choice: 'wrestling', start: -90, end: 30 },   // top
        { choice: 'kickbox',   start: 30,  end: 150 },  // bottom-right
        { choice: 'jiu-jitsu', start: 150, end: 270 },  // bottom-left
    ];

    function deg2rad(d){ return (d - 90) * Math.PI / 180; } // SVG 0° is at 3 o'clock; shift -90 to put 0° at top
    function polar(xc, yc, r, angDeg){
        const a = deg2rad(angDeg);
        return { x: xc + r * Math.cos(a), y: yc + r * Math.sin(a) };
    }
    function wedgePath(xc, yc, r, a0, a1){
        const p0 = polar(xc, yc, r, a0);
        const p1 = polar(xc, yc, r, a1);
        const largeArc = (Math.abs(a1 - a0) > 180) ? 1 : 0;
        return `M ${xc} ${yc} L ${p0.x} ${p0.y} A ${r} ${r} 0 ${largeArc} 1 ${p1.x} ${p1.y} Z`;
    }
    function labelPos(xc, yc, r, a0, a1){
        const mid = (a0 + a1) / 2;
        const p = polar(xc, yc, r * 0.62, mid); // 62% radius so it never clips
        return p;
    }

    // Build the 3 wedges + labels
    const gEls = svg.querySelectorAll('g.slice');
    gEls.forEach((g, i) => {
        const conf = slices[i];
        const path = g.querySelector('.slice-path');
        const text = g.querySelector('.slice-label');

        path.setAttribute('d', wedgePath(cx, cy, R, conf.start, conf.end));

        const lp = labelPos(cx, cy, R, conf.start, conf.end);
        text.setAttribute('x', lp.x);
        text.setAttribute('y', lp.y);

        // Hover effect
        g.style.cursor = 'pointer';
        g.addEventListener('mouseenter', () => {
            path.setAttribute('fill', 'rgba(255,255,255,.12)');
        });
        g.addEventListener('mouseleave', () => {
            path.setAttribute('fill', 'rgba(255,255,255,.06)');
        });

        // Click -> set choice and submit
        g.addEventListener('click', () => {
            const choice = g.getAttribute('data-choice');
            document.getElementById('choiceInput').value = choice;
            const nice = choice.charAt(0).toUpperCase() + choice.slice(1);
            document.getElementById('centerLabel').textContent = nice;

            // brief visual confirm
            path.setAttribute('fill', 'rgba(255,255,255,.18)');
            setTimeout(() => document.getElementById('fightChoiceForm').submit(), 350);
        });
    });
})();
</script>
@endsection
