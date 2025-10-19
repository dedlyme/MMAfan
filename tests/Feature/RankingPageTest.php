<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function loginBrowser()
{
    // Create a user
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
    ]);

    // Go to login page and sign in
    $page = visit('/login');
    $page->type('email', 'test@example.com')
        ->type('password', 'password')
        ->press('Log in');

    // Return the authenticated page
    return $page;
}

it('shows divisions when logged in', function () {
    // ✅ Authenticate first
    $page = loginBrowser();

    // ✅ Now visit your protected ranking page
    $page = visit('/ranking');

    // ✅ Check main contents
    $page->assertSee('UFC Rankings');
    $page->assertSee('Divisions');
});

it('shows message when there are no divisions', function () {
    // ✅ Authenticate
    $page = loginBrowser();

    // ✅ Visit ranking page
    $page = visit('/ranking');

    // ✅ Should show fallback message when empty
    $page->assertSee('No divisions available');
});
