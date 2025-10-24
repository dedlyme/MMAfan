<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function loginBrowser()
{
    // Create a test user
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
    ]);

    // Visit login page and log in like a real user
    $page = visit('/login');
    $page->type('email', 'test@example.com')
         ->type('password', 'password')
         ->press('Log in');

    return $page;
};

it('logs in and sees Open Fight button on Dream Fights page', function () {
    // Log in
    $page = loginBrowser();

    // Visit Dream Fights page
    $page = visit('/dreamfights');

    // Confirm the page loaded
    $page->assertSee('Dream Fights Lobby');

    // Wait a little just to be safe
    sleep(2);

    // Check that the "Open Fight" button is visible
    $page->assertSee('Open Fight');
});
