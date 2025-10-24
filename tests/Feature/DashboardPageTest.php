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

    // Visit the login page and log in like a real user
    $page = visit('/login');
    $page->type('email', 'test@example.com')
         ->type('password', 'password')
         ->press('Log in');

    return $page;
};

it('logs in and visits dashboard', function () {
    $page = loginBrowser();

    // Visit the dashboard after login
    $page = visit('/dashboard');

    // Confirm dashboard is loaded
    $page->assertSee('UFC MMA Dashboard');
});

it('logs in and sends a live chat message', function () {
    $page = loginBrowser();

    // Go to dashboard
    $page = visit('/dashboard');

    // Type and send a chat message
    $page->type('#chat-input', 'test successful')
         ->press('Send');

    // Verify message appears in chat
    $page->assertSee('test successful');
});
