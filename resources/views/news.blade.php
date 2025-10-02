@extends('layouts.app')

@section('title', 'UFC News')

@section('content')
<div class="relative z-10 py-12 px-4 max-w-6xl mx-auto">

    <!-- 📰 Page Title -->
    <div class="text-center mb-12">
        <h1 class="text-5xl font-extrabold text-gray-900 dark:text-white drop-shadow-lg">
             Latest MMA / UFC News
        </h1>
        <p class="text-gray-600 dark:text-gray-400 mt-2 text-lg">
            Stay updated with breaking MMA & UFC stories from multiple top sources.
        </p>
    </div>

    <!-- 🔎 Search & Filter -->
    <form method="GET" action="{{ route('news') }}"
          class="mb-10 flex flex-col md:flex-row gap-4 items-center bg-gray-100 dark:bg-gray-800/60 rounded-2xl p-4 shadow-lg">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by title..."
            class="flex-1 px-4 py-3 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500 transition" />

        <input type="date" name="from_date" value="{{ request('from_date') }}"
            class="px-4 py-3 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500 transition" />

        <button type="submit"
            class="px-6 py-3 bg-red-500 text-white font-bold rounded-xl hover:bg-red-600 transition shadow-md">
            Filter
        </button>

        <a href="{{ route('news') }}"
            class="px-6 py-3 bg-gray-300 dark:bg-gray-600 text-gray-900 dark:text-white font-bold rounded-xl hover:bg-gray-400 dark:hover:bg-gray-500 transition shadow-md flex items-center justify-center">
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
        {{-- 🌟 Featured Article --}}
        @php $featured = $filteredNews->first(); @endphp
        <div class="bg-gradient-to-r from-red-600 to-red-500 dark:from-gray-800 dark:to-gray-900 text-white rounded-3xl shadow-2xl p-8 mb-12 transition transform hover:scale-[1.01]">
            <a href="{{ $featured->get_link() }}" target="_blank"
               class="text-3xl md:text-4xl font-extrabold leading-snug hover:underline">
                {{ $featured->get_title() }}
            </a>
            <p class="mt-2 text-sm opacity-80">{{ $featured->get_date('F j, Y') }}</p>
            <p class="mt-4 text-base opacity-90">
                {!! Str::limit(strip_tags($featured->get_description()), 250) !!}
            </p>
            <p class="text-xs opacity-70 mt-3">
                Source: {{ parse_url($featured->get_feed()->get_link(), PHP_URL_HOST) }}
            </p>
        </div>

        {{-- 📰 Other News --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($filteredNews->skip(1) as $item)
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 transition transform hover:scale-[1.02] hover:shadow-xl">
                    <a href="{{ $item->get_link() }}" target="_blank"
                       class="block text-xl font-bold text-gray-900 dark:text-white hover:text-red-500 dark:hover:text-red-400 transition">
                        {{ $item->get_title() }}
                    </a>
                    <p class="text-gray-500 dark:text-gray-400 text-xs mt-1">
                        {{ $item->get_date('F j, Y') }}
                    </p>
                    <p class="text-gray-700 dark:text-gray-300 text-sm mt-3 line-clamp-3">
                        {!! strip_tags($item->get_description()) !!}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-3">
                        Source: {{ parse_url($item->get_feed()->get_link(), PHP_URL_HOST) }}
                    </p>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-center text-gray-500 dark:text-gray-400 mt-12 text-lg">No news match your search or filter criteria.</p>
    @endif

</div>
@endsection
