<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PoundFighter;
use Illuminate\Support\Facades\DB;

class PoundSeeder extends Seeder
{
    public function run(): void
    {
        $top10 = [
            1  => 'Islam Makhachev',
            2  => 'Alex Pereira',
            3  => 'Ilia Topuria',
            4  => 'Leon Edwards',
            5  => 'Tom Aspinall',
            6  => 'Sean O\'Malley',
            7  => 'Max Holloway',
            8  => 'Dricus du Plessis',
            9  => 'Weili Zhang',
            10 => 'Alexandre Pantoja',
        ];

        DB::transaction(function () use ($top10) {
            foreach ($top10 as $rank => $name) {
                PoundFighter::updateOrCreate(
                    ['rank' => $rank],           // keep rank unique
                    ['fighter_name' => $name]
                );
            }
        });
    }
}
