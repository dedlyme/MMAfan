<?php

use App\Models\User;
use App\Models\Division;
use App\Models\Ranking;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/*
|--------------------------------------------------------------------------
| Division Page Tests
|--------------------------------------------------------------------------
| Šis tests pārbauda vai “Diviziju sadaļa” ielādējas un rāda cīnītājus.
| Tas izmanto Pest v4 sintaksi (vienkāršāku, nekā PHPUnit).
*/

it('renders the divisions page and displays fighters', function () {
    // Izveido lietotāju (piemēram, adminu)
    $user = User::factory()->create();

    // Izveido testu divīziju un cīnītājus
    $division = Division::factory()->create(['name' => 'Lightweight']);
    Ranking::factory()->create([
        'division_id' => $division->id,
        'fighter_name' => 'Conor McGregor',
        'rank' => 1
    ]);
    Ranking::factory()->create([
        'division_id' => $division->id,
        'fighter_name' => 'Dustin Poirier',
        'rank' => 2
    ]);

    // Autentificē lietotāju un ielādē lapu
    $response = actingAs($user)->get(route('admin.divisions.index'));

    // Pārbauda vai lapa ielādējas pareizi
    $response->assertStatus(200);

    // Pārbauda, vai redzams nosaukums un abi cīnītāji
    $response->assertSee('Lightweight');
    $response->assertSee('Conor McGregor');
    $response->assertSee('Dustin Poirier');
});
