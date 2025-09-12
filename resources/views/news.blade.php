@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-6">
    <h1 class="text-3xl font-bold mb-6">Latest UFC News</h1>

    @if(count($news) > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($news as $article)
                <div class="bg-gray-900 text-white p-4 rounded-lg shadow-lg">
                    <h2 class="text-xl font-semibold mb-2">{{ $article['title'] }}</h2>
                    <p class="mb-2">{{ $article['description'] ?? '' }}</p>
                    <a href="{{ $article['url'] }}" target="_blank" class="text-blue-500 hover:underline">Read more</a>
                </div>
            @endforeach
        </div>
    @else
        <p>No news found.</p>
    @endif
</div>
@endsection
