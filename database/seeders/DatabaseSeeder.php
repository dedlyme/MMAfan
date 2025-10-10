<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UsersTableSeeder::class,
            DivisionsSeeder::class,    // UFC divisions + rankings
            PoundSeeder::class,        // Top-10 P4P
        ]);
    }
}
