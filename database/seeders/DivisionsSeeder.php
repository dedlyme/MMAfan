<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Division;
use App\Models\Ranking;

class DivisionsSeeder extends Seeder
{
    public function run(): void
    {
        // Division name => [Champion, contenders... (rank 1..N)]
        $data = [
            'Flyweight' => [
                'champ' => 'Alexandre Pantoja',
                'contenders' => [
                    1 => 'Brandon Royval',
                    2 => 'Amir Albazi',
                    3 => 'Brandon Moreno',
                    4 => 'Matheus Nicolau',
                    5 => 'Manel Kape',
                ],
            ],
            'Bantamweight' => [
                'champ' => "Sean O'Malley",
                'contenders' => [
                    1 => 'Merab Dvalishvili',
                    2 => 'Cory Sandhagen',
                    3 => 'Marlon Vera',
                    4 => 'Petr Yan',
                    5 => 'Umar Nurmagomedov',
                ],
            ],
            'Featherweight' => [
                'champ' => 'Ilia Topuria',
                'contenders' => [
                    1 => 'Max Holloway',
                    2 => 'Brian Ortega',
                    3 => 'Alexander Volkanovski',
                    4 => 'Movsar Evloev',
                    5 => 'Arnold Allen',
                ],
            ],
            'Lightweight' => [
                'champ' => 'Islam Makhachev',
                'contenders' => [
                    1 => 'Arman Tsarukyan',
                    2 => 'Charles Oliveira',
                    3 => 'Justin Gaethje',
                    4 => 'Dustin Poirier',
                    5 => 'Mateusz Gamrot',
                ],
            ],
            'Welterweight' => [
                'champ' => 'Leon Edwards',
                'contenders' => [
                    1 => 'Belal Muhammad',
                    2 => 'Kamaru Usman',
                    3 => 'Shavkat Rakhmonov',
                    4 => 'Colby Covington',
                    5 => 'Ian Garry',
                ],
            ],
            'Middleweight' => [
                'champ' => 'Dricus du Plessis',
                'contenders' => [
                    1 => 'Sean Strickland',
                    2 => 'Israel Adesanya',
                    3 => 'Jared Cannonier',
                    4 => 'Robert Whittaker',
                    5 => 'Khamzat Chimaev',
                ],
            ],
            'Light Heavyweight' => [
                'champ' => 'Alex Pereira',
                'contenders' => [
                    1 => 'Jiri Prochazka',
                    2 => 'Magomed Ankalaev',
                    3 => 'Jamahal Hill',
                    4 => 'Jan Blachowicz',
                    5 => 'Aleksandar Rakic',
                ],
            ],
            'Heavyweight' => [
                'champ' => 'Tom Aspinall',
                'contenders' => [
                    1 => 'Ciryl Gane',
                    2 => 'Sergei Pavlovich',
                    3 => 'Curtis Blaydes',
                    4 => 'Jailton Almeida',
                    5 => 'Stipe Miocic',
                ],
            ],
            "Women’s Strawweight" => [
                'champ' => 'Weili Zhang',
                'contenders' => [
                    1 => 'Yan Xiaonan',
                    2 => 'Tatiana Suarez',
                    3 => 'Mackenzie Dern',
                    4 => 'Amanda Lemos',
                    5 => 'Jessica Andrade',
                ],
            ],
            "Women’s Flyweight" => [
                'champ' => 'Alexa Grasso',
                'contenders' => [
                    1 => 'Valentina Shevchenko',
                    2 => 'Manon Fiorot',
                    3 => 'Erin Blanchfield',
                    4 => 'Maycee Barber',
                    5 => 'Taila Santos',
                ],
            ],
            "Women’s Bantamweight" => [
                'champ' => 'Raquel Pennington',
                'contenders' => [
                    1 => 'Julianna Peña',
                    2 => 'Ketlen Vieira',
                    3 => 'Irene Aldana',
                    4 => 'Miesha Tate',
                    5 => 'Holly Holm',
                ],
            ],
        ];

        foreach ($data as $divisionName => $pack) {
            $division = Division::firstOrCreate(['name' => $divisionName]);

            // Champion (rank = 0 so your UI sorts champion first)
            Ranking::updateOrCreate(
                ['division_id' => $division->id, 'fighter_name' => $pack['champ']],
                ['rank' => 0, 'is_champion' => true]
            );

            // Contenders 1..N
            foreach ($pack['contenders'] as $rank => $name) {
                Ranking::updateOrCreate(
                    ['division_id' => $division->id, 'fighter_name' => $name],
                    ['rank' => (int) $rank, 'is_champion' => false]
                );
            }
        }
    }
}
