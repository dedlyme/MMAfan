<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::updateOrCreate(
            ['email' => 'admin@ufc.test'],
            [
                'name'     => 'admin',
                'password' => Hash::make('password'),
                'is_admin' => true,
            ]
        );

        // Regular user
        User::updateOrCreate(
            ['email' => 'user@ufc.test'],
            [
                'name'     => 'Kristaps Vasarajs',
                'password' => Hash::make('password'),
                'is_admin' => false,
            ]
        );
    }
}
