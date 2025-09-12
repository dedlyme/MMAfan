<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use willvincent\Feeds\Facades\FeedsFacade as FeedReader;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class NewsController extends Controller
{
    public function index()
    {
        $feedUrl = 'https://www.ufc.com/rss.xml';

        // Cache the feed for 30 minutes
        $newsItems = Cache::remember('ufc_news', now()->addMinutes(30), function() use ($feedUrl) {
            $feed = FeedReader::make($feedUrl);
            return $feed->get_items(0, 10); // get latest 10 items
        });

        // Prepare events for the calendar from news feed (example: future events)
        $events = [];
        foreach ($newsItems as $item) {
            // Example: if the feed contains dates in description or enclosure, parse them
            // For simplicity, we’ll randomly assign events to future dates
            $date = Carbon::today()->addDays(rand(0, 15))->format('Y-m-d');
            $events[] = [
                'date' => $date,
                'title' => $item->get_title(),
            ];
        }

        $today = Carbon::today()->format('Y-m-d');

        return view('news', compact('newsItems', 'events', 'today'));
    }
}
