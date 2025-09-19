<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use willvincent\Feeds\Facades\FeedsFacade as FeedReader;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class NewsController extends Controller
{
    public function index()
    {
        $feedUrls = [
            'https://www.ufc.com/rss.xml',
            'https://www.espn.com/espn/rss/mma/news',
            'https://www.mmafighting.com/rss/current',
            'https://www.sherdog.com/rss/news.xml',
        ];

        $newsItems = Cache::remember('mma_news', now()->addMinutes(30), function () use ($feedUrls) {
            $items = collect();

            foreach ($feedUrls as $url) {
                try {
                    $feed = FeedReader::make($url);
                    foreach ($feed->get_items(0, 5) as $item) {
                        $item->image = $this->getImageForTitle($item->get_title());
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

    private function getImageForTitle($title)
    {
        $key = env('BING_IMAGE_SEARCH_KEY');
        $endpoint = env('BING_IMAGE_SEARCH_ENDPOINT');

        try {
            $response = Http::withHeaders([
                'Ocp-Apim-Subscription-Key' => $key
            ])->get($endpoint, [
                'q' => $title . ' UFC',
                'count' => 1,
                'safeSearch' => 'Moderate'
            ]);

            $data = $response->json();
            return $data['value'][0]['contentUrl'] ?? 'https://via.placeholder.com/800x450?text=No+Image';
        } catch (\Exception $e) {
            \Log::warning("Bing Image Search error for title $title: " . $e->getMessage());
            return 'https://via.placeholder.com/800x450?text=No+Image';
        }
    }
}
