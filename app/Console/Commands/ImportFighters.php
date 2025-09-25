<?php
// app/Console/Commands/ImportFighters.php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Fighter;

class ImportFighters extends Command
{
    protected $signature = 'import:fighters';
    protected $description = 'Fetch fighters from API and store in database';

    public function handle()
    {
        $apiKey = config('services.sportsdata.key');

        $this->info('Fetching fighters from API...');
        $response = Http::timeout(20)->get("https://api.sportsdata.io/v3/mma/scores/json/FightersBasic?key={$apiKey}");

        if ($response->failed()) {
            $this->error('Failed to fetch fighters.');
            return 1;
        }

        $data = $response->json();

        foreach ($data as $f) {
            if (isset($f['Status']) && $f['Status'] !== 'Active') continue;

            Fighter::updateOrCreate(
                ['first_name' => $f['FirstName'] ?? '', 'last_name' => $f['LastName'] ?? ''],
                [
                    'nickname' => $f['Nickname'] ?? null,
                    'weight_class' => $f['WeightClass'] ?? null,
                ]
            );
        }

        $this->info('Fighters imported successfully!');
        return 0;
    }
}

