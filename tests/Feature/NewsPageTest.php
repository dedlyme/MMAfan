<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function loginBrowser()
{
    // Izveido testa lietotāju
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
    ]);

    // Atver login lapu un pieslēdzas
    $page = visit('/login');
    $page->type('email', 'test@example.com')
         ->type('password', 'password')
         ->press('Log in');

    return $page;
};

it('logs in and visits news page with loaded RSS sources', function () {
    // Ielogojas kā lietotājs
    $page = loginBrowser();

    // Pāriet uz jaunumu sadaļu
    $page = visit('/news');

    // Pārbauda, vai lapas virsraksts redzams
    $page->assertSee('Latest MMA / UFC News');

    // Dod RSS plūsmām laiku ielādēties
    sleep(8);

    // Pārbauda, vai parādās “Source:” — tātad ir ieraksti
    $page->assertSee('Source:');

    // Papildu pārbaude — vai redzams vismaz viens avots (piemēram, Sherdog)
    $page->assertSee('sherdog.com');
});
