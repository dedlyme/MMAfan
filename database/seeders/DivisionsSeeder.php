<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Division;
use App\Models\Ranking;

class DivisionsSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'Flyweight' => [
                'champ' => 'Alexandre Pantoja',
                'contenders' => [
                    1 => 'Joshua Van',
                    2 => 'Brandon Moreno',
                    3 => 'Brandon Royval',
                    4 => 'Amir Albazi',
                    5 => 'Tatsuro Taira',
                    6 => 'Kai Kara-France',
                    7 => 'Manel Kape',
                    8 => 'Alex Perez',
                    9 => 'Asu Almabayev',
                    10 => 'Tim Elliott',
                    11 => 'Steve Erceg',
                    12 => 'Tagir Ulanbekov',
                    13 => 'Charles Johnson',
                    14 => 'Bruno Silva',
                    15 => 'Rafael Estevam',
                ],
            ],

            'Bantamweight' => [
                'champ' => 'Merab Dvalishvili',
                'contenders' => [
                    1 => "Sean O'Malley",
                    2 => 'Umar Nurmagomedov',
                    3 => 'Petr Yan',
                    4 => 'Cory Sandhagen',
                    5 => 'Song Yadong',
                    6 => 'Deiveson Figueiredo',
                    7 => 'Aiemann Zahabi',
                    8 => 'Marlon Vera',
                    9 => 'Mario Bautista',
                    10 => 'Henry Cejudo',
                    11 => 'David Martinez',
                    12 => 'Rob Font',
                    13 => 'Vinicius Oliveira',
                    14 => 'Kyler Phillips',
                    15 => 'Marcus McGhee',
                ],
            ],

            'Featherweight' => [
                'champ' => 'Alexander Volkanovski',
                'contenders' => [
                    1 => 'Movsar Evloev',
                    2 => 'Diego Lopes',
                    3 => 'Yair Rodriguez',
                    4 => 'Lerone Murphy',
                    5 => 'Aljamain Sterling',
                    6 => 'Arnold Allen',
                    7 => 'Youssef Zalal',
                    8 => 'Brian Ortega',
                    9 => 'Josh Emmett',
                    10 => 'Jean Silva',
                    11 => 'Patricio Pitbull',
                    12 => 'Steve Garcia',
                    13 => 'David Onama',
                    14 => 'Dan Ige',
                    15 => 'Giga Chikadze',
                ],
            ],

            'Lightweight' => [
                'champ' => 'Ilia Topuria',
                'contenders' => [
                    1 => 'Islam Makhachev',
                    2 => 'Arman Tsarukyan',
                    3 => 'Charles Oliveira',
                    4 => 'Max Holloway',
                    5 => 'Justin Gaethje',
                    6 => 'Paddy Pimblett',
                    7 => 'Dan Hooker',
                    8 => 'Mateusz Gamrot',
                    9 => 'Beneil Dariush',
                    10 => 'Rafael Fiziev',
                    11 => 'Renato Moicano',
                    12 => 'Michael Chandler',
                    13 => 'Benoît Saint Denis',
                    14 => 'Grant Dawson',
                    15 => 'Mauricio Ruffy',
                ],
            ],

            'Welterweight' => [
                'champ' => 'Jack Della Maddalena',
                'contenders' => [
                    1 => 'Belal Muhammad',
                    2 => 'Sean Brady',
                    3 => 'Shavkat Rakhmonov',
                    4 => 'Leon Edwards',
                    5 => 'Kamaru Usman',
                    6 => 'Ian Machado Garry',
                    7 => 'Joaquin Buckley',
                    8 => 'Michael Morales',
                    9 => 'Carlos Prates',
                    10 => 'Colby Covington',
                    11 => 'Gilbert Burns',
                    12 => 'Geoff Neal',
                    13 => 'Daniel Rodriguez',
                    14 => 'Gabriel Bonfim',
                    15 => 'Mike Malott',
                ],
            ],

            'Middleweight' => [
                'champ' => 'Khamzat Chimaev',
                'contenders' => [
                    1 => 'Dricus Du Plessis',
                    2 => 'Nassourdine Imavov',
                    3 => 'Sean Strickland',
                    4 => 'Anthony Hernandez',
                    5 => 'Brendan Allen',
                    6 => 'Israel Adesanya',
                    7 => 'Caio Borralho',
                    8 => 'Reinier de Ridder',
                    9 => 'Robert Whittaker',
                    10 => 'Michael Page',
                    11 => 'Jared Cannonier',
                    12 => 'Roman Dolidze',
                    13 => 'Paulo Costa',
                    14 => 'Marvin Vettori',
                    15 => 'Joe Pyfer',
                ],
            ],

            'Light Heavyweight' => [
                'champ' => 'Alex Pereira',
                'contenders' => [
                    1 => 'Jiří Procházka',
                    2 => 'Magomed Ankalaev',
                    3 => 'Carlos Ulberg',
                    4 => 'Jan Błachowicz',
                    5 => 'Khalil Rountree Jr.',
                    6 => 'Jamahal Hill',
                    7 => 'Aleksandar Rakić',
                    8 => 'Dominick Reyes',
                    9 => 'Volkan Oezdemir',
                    10 => 'Azamat Murzakanov',
                    11 => 'Bogdan Guskov',
                    12 => 'Johnny Walker',
                    13 => 'Nikita Krylov',
                    14 => 'Alonzo Menifield',
                    15 => 'Zhang Mingyang',
                ],
            ],

            'Heavyweight' => [
                'champ' => 'Tom Aspinall',
                'contenders' => [
                    1 => 'Ciryl Gane',
                    2 => 'Alexander Volkov',
                    3 => 'Sergei Pavlovich',
                    4 => 'Curtis Blaydes',
                    5 => 'Jailton Almeida',
                    6 => 'Waldo Cortes Acosta',
                    7 => 'Serghei Spivac',
                    8 => 'Derrick Lewis',
                    9 => 'Ante Delija',
                    10 => 'Marcin Tybura',
                    11 => 'Tai Tuivasa',
                    12 => 'Shamil Gaziev',
                    13 => 'Mick Parkin',
                    14 => 'Tallison Teixeira',
                    15 => 'Valter Walker',
                ],
            ],

            "Women's Strawweight" => [
                'champ' => 'Zhang Weili',
                'contenders' => [
                    1 => 'Virna Jandiroba',
                    2 => 'Tatiana Suarez',
                    3 => 'Yan Xiaonan',
                    4 => 'Amanda Lemos',
                    5 => 'Mackenzie Dern',
                    6 => 'Loopy Godinez',
                    7 => 'Iasmin Lucindo',
                    8 => 'Tabatha Ricci',
                    9 => 'Jéssica Andrade',
                    10 => 'Gillian Robertson',
                    11 => 'Amanda Ribas',
                    12 => 'Angela Hill',
                    13 => 'Tecia Pennington',
                    14 => 'Alexia Thainara',
                    15 => 'Denise Gomes',
                ],
            ],

            "Women's Flyweight" => [
                'champ' => 'Valentina Shevchenko',
                'contenders' => [
                    1 => 'Manon Fiorot',
                    2 => 'Natalia Silva',
                    3 => 'Alexa Grasso',
                    4 => 'Erin Blanchfield',
                    5 => 'Maycee Barber',
                    6 => 'Rose Namajunas',
                    7 => 'Jasmine Jasudavicius',
                    8 => 'Tracy Cortez',
                    9 => 'Karine Silva',
                    10 => 'Miranda Maverick',
                    11 => "Casey O'Neill",
                    12 => 'Wang Cong',
                    13 => 'Eduarda Moura',
                    14 => 'JJ Aldrich',
                    15 => 'Gabriella Fernandes',
                ],
            ],

            "Women's Bantamweight" => [
                'champ' => 'Kayla Harrison',
                'contenders' => [
                    1 => 'Julianna Peña',
                    2 => 'Raquel Pennington',
                    3 => 'Ketlen Vieira',
                    4 => 'Norma Dumont',
                    5 => 'Yana Santos',
                    6 => 'Irene Aldana',
                    7 => 'Macy Chiasson',
                    8 => 'Ailin Perez',
                    9 => 'Karol Rosa',
                    10 => 'Mayra Bueno Silva',
                    11 => 'Jacqueline Cavalcanti',
                    12 => 'Nora Cornolle',
                    13 => 'Miesha Tate',
                    14 => 'Joselyne Edwards',
                    15 => 'Luana Santos',
                ],
            ],

            "Women's Pound-for-Pound" => [
                'champ' => 'Valentina Shevchenko',
                'contenders' => [
                    1 => 'Valentina Shevchenko',
                    2 => 'Zhang Weili',
                    3 => 'Kayla Harrison',
                    4 => 'Natalia Silva',
                    5 => 'Manon Fiorot',
                    6 => 'Julianna Peña',
                    7 => 'Alexa Grasso',
                    8 => 'Erin Blanchfield',
                    9 => 'Virna Jandiroba',
                    10 => 'Raquel Pennington',
                    11 => 'Tatiana Suarez',
                    12 => 'Rose Namajunas',
                    13 => 'Yan Xiaonan',
                    14 => 'Maycee Barber',
                    15 => 'Amanda Lemos',
                ],
            ],
        ];

        foreach ($data as $divisionName => $pack) {
            $division = Division::firstOrCreate(['name' => $divisionName]);

            // Champion rank = 16
            Ranking::updateOrCreate(
                ['division_id' => $division->id, 'fighter_name' => $pack['champ']],
                ['rank' => 16, 'is_champion' => true]
            );

            foreach ($pack['contenders'] as $rank => $name) {
                Ranking::updateOrCreate(
                    ['division_id' => $division->id, 'fighter_name' => $name],
                    ['rank' => (int) $rank, 'is_champion' => false]
                );
            }
        }
    }
}
