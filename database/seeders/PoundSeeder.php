<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PoundFighter;
use Illuminate\Support\Facades\DB;

class PoundSeeder extends Seeder
{
    public function run(): void
    {
        // ✅ Current UFC Pound-for-Pound Top 10
        $top10 = [
            1  => 'Ilia Topuria',
            2  => 'Islam Makhachev',
            3  => 'Merab Dvalishvili',
            4  => 'Khamzat Chimaev',
            5  => 'Alexandre Pantoja',
            6  => 'Alex Pereira',
            7  => 'Alexander Volkanovski',
            8  => 'Jack Della Maddalena',
            9  => 'Tom Aspinall',
            10 => 'Dricus Du Plessis',
        ];

        DB::transaction(function () use ($top10) {
            foreach ($top10 as $rank => $name) {
                PoundFighter::updateOrCreate(
                    ['rank' => $rank],            // ensure rank stays unique
                    ['fighter_name' => $name]
                );
            }
        });
    }
}
