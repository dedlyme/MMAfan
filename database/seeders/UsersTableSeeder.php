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
            ['email' => 'ipb22.k.vasarajs@vtdt.edu.lv'],
            [
                'name'     => 'admin',
                'password' => Hash::make('kristaps123'),
                'is_admin' => true,
            ]
        );
    }
}
