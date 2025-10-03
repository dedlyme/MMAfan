<?php
// app/Console/Commands/FixRankingDuplicates.php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixRankingDuplicates extends Command
{
    protected $signature = 'fix:rankings';
    protected $description = 'Fix duplicate champions and fighter names in rankings table';

    public function handle()
    {
        $this->info('Fixing duplicate champions...');
        DB::statement("
            UPDATE rankings r
            JOIN (
                SELECT division_id, id
                FROM rankings
                WHERE is_champion = 1
                GROUP BY division_id
                HAVING COUNT(*) > 1
            ) dup ON dup.division_id = r.division_id
            SET r.is_champion = 0
            WHERE r.id != dup.id
        ");

        $this->info('Fixing duplicate fighter names...');
        DB::statement("
            DELETE r1 FROM rankings r1
            JOIN rankings r2 
              ON r1.division_id = r2.division_id 
             AND r1.fighter_name = r2.fighter_name 
             AND r1.id > r2.id
        ");

        $this->info('Duplicates fixed.');
    }
}

