@extends('layouts.app')

@section('title', 'UFC News')

@section('content')
<div class="relative z-10 py-12 px-4 max-w-6xl mx-auto">

    <h1 class="text-4xl md:text-5xl font-extrabold text-red-500 mb-10 text-center drop-shadow-lg">
        Latest MMA / UFC News
    </h1>

    {{-- Search & Filter Form --}}
    <form method="GET" action="{{ route('news') }}" class="mb-10 flex flex-col md:flex-row gap-4 items-center">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by title..." 
            class="flex-1 px-4 py-3 rounded-xl bg-gray-800 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500 transition" />

        <input type="date" name="from_date" value="{{ request('from_date') }}"
            class="px-4 py-3 rounded-xl bg-gray-800 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500 transition" />

        <button type="submit" 
            class="px-6 py-3 bg-red-500 text-white font-bold rounded-xl hover:bg-red-600 transition shadow-md">
            Filter
        </button>

        <a href="{{ route('news') }}" 
            class="px-6 py-3 bg-gray-700 text-white font-bold rounded-xl hover:bg-gray-600 transition shadow-md flex items-center justify-center">
            Clear
        </a>
    </form>

    @php
        $filteredNews = $newsItems;

        if(request('search')) {
            $filteredNews = $filteredNews->filter(fn($item) => stripos($item->get_title(), request('search')) !== false);
        }

        if(request('from_date')) {
            $from = \Carbon\Carbon::parse(request('from_date'));
            $filteredNews = $filteredNews->filter(fn($item) => \Carbon\Carbon::parse($item->get_date('Y-m-d')) >= $from);
        }
    @endphp

    @if($filteredNews->isNotEmpty())
        {{-- Featured News --}}
        @php $featured = $filteredNews->first(); @endphp
        <div class="bg-gray-800/70 backdrop-blur-md rounded-3xl shadow-2xl p-8 mb-12 hover:scale-[1.02] transition transform duration-300">
            <a href="{{ $featured->get_link() }}" target="_blank" class="text-4xl md:text-5xl font-extrabold text-red-500 hover:underline">
                {{ $featured->get_title() }}
            </a>
            <p class="text-gray-400 text-sm mt-2">
                {{ $featured->get_date('F j, Y') }}
            </p>
            <p class="text-gray-200 text-lg mt-4">
                {!! Str::limit(strip_tags($featured->get_description()), 300) !!}
            </p>
            <p class="text-xs text-gray-500 mt-3">
                Source: {{ parse_url($featured->get_feed()->get_link(), PHP_URL_HOST) }}
            </p>
        </div>

        {{-- Other News --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($filteredNews->skip(1) as $item)
                <div class="bg-gray-800/60 backdrop-blur-sm rounded-2xl shadow-lg p-5 hover:scale-[1.03] transition transform duration-300 group cursor-pointer">
                    <a href="{{ $item->get_link() }}" target="_blank" class="text-xl font-bold text-red-500 hover:underline">
                        {{ $item->get_title() }}
                    </a>
                    <p class="text-gray-400 text-xs mt-1">
                        {{ $item->get_date('F j, Y') }}
                    </p>
                    <p class="text-gray-300 text-sm mt-2 line-clamp-3 group-hover:line-clamp-none transition-all duration-300">
                        {!! strip_tags($item->get_description()) !!}
                    </p>
                    <p class="text-xs text-gray-500 mt-2">
                        Source: {{ parse_url($item->get_feed()->get_link(), PHP_URL_HOST) }}
                    </p>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-center text-gray-400 mt-12">No news match your search/filter criteria.</p>
    @endif

</div>
@endsection
