<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use willvincent\Feeds\Facades\FeedsFacade as FeedReader;
use Illuminate\Support\Facades\Cache;

class NewsController extends Controller
{
    public function index()
    {
        $feedUrls = [
            'https://www.ufc.com/rss.xml',
            'https://www.espn.com/espn/rss/mma/news',
            'https://www.sherdog.com/rss/news.xml',
        ];

        $newsItems = Cache::remember('mma_news', now()->addMinutes(30), function () use ($feedUrls) {
            $items = collect();

            foreach ($feedUrls as $url) {
                try {
                    $feed = FeedReader::make($url);
                    foreach ($feed->get_items(0, 5) as $item) {
                        // Just give a default placeholder since we removed Bing lookup
                        $item->image = 'https://via.placeholder.com/800x450?text=No+Image';
                        $items->push($item);
                    }
                } catch (\Exception $e) {
                    \Log::warning("RSS feed problem at URL: $url, error: ".$e->getMessage());
                    continue;
                }
            }

            return $items->sortByDesc(fn($item) => $item->get_date('U') ?? 0)->values();
        });

        return view('news', compact('newsItems'));
    }
}
