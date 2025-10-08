<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Fighter;

class ImportFighters extends Command
{
    protected $signature = 'import:fighters';
    protected $description = 'Fetch fighters from API and store them in the database';

    public function handle()
    {
        $apiKey = config('services.sportsdata.key');

        $this->info('Fetching fighters from API...');
        $response = Http::timeout(30)->get("https://api.sportsdata.io/v3/mma/scores/json/FightersBasic?key={$apiKey}");

        if ($response->failed()) {
            $this->error('Failed to fetch fighters (HTTP '.$response->status().').');
            return 1;
        }

        $data = $response->json();
        if (!is_array($data)) {
            $this->error('Unexpected API response format.');
            return 1;
        }

        $count = 0;
        foreach ($data as $f) {
            // Some API entries may be missing names
            $first = $f['FirstName'] ?? null;
            $last  = $f['LastName'] ?? null;

            if (!$first && !$last) {
                continue;
            }

            Fighter::updateOrCreate(
                [
                    // use FighterId as unique key
                    'external_id' => $f['FighterId'] ?? null,
                ],
                [
                    'first_name' => $first,
                    'last_name'  => $last,
                    'nickname'   => $f['Nickname'] ?? null,
                ]
            );
            $count++;
        }

        $this->info("✅ Imported or updated {$count} fighters.");
        return 0;
    }
}
