<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Fighter;

class SyncFighters extends Command
{
    protected $signature = 'fighters:sync';
    protected $description = 'Fetch fighters from SportsData API and store/update in DB';

    public function handle()
    {
        $apiKey = env('SPORTSDATA_API_KEY'); // put your key in .env
        $url = "https://api.sportsdata.io/v3/mma/scores/json/FightersBasic?key={$apiKey}";

        $this->info("Fetching fighters from API...");
        $response = Http::get($url);

        if ($response->failed()) {
            $this->error('API request failed: '.$response->body());
            return 1;
        }

        $fighters = $response->json();
        $this->info("Found ".count($fighters)." fighters. Syncing...");

        foreach ($fighters as $f) {
            Fighter::updateOrCreate(
                ['external_id' => $f['FighterId']],
                [
                    'first_name' => $f['FirstName'] ?? '',
                    'last_name'  => $f['LastName'] ?? '',
                    'nickname'   => $f['Nickname'] ?? null,
                ]
            );
        }

        $this->info('Sync complete!');
        return 0;
    }
}
