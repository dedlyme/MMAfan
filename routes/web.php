<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
use App\Models\Division;
use App\Models\Ranking;
use Illuminate\Http\Request;
use App\Http\Controllers\RankingController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PoundController;
use App\Http\Controllers\Admin\PoundAdminController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\Admin\AdminRankingController;
use App\Http\Controllers\DreamfightController;

/* ------------------------------
    Admin Pound for Pound CRUD
--------------------------------*/
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/pound', [PoundAdminController::class, 'index'])->name('pound.index');
    Route::post('/pound', [PoundAdminController::class, 'store'])->name('pound.store');
    Route::patch('/pound/{fighter}', [PoundAdminController::class, 'update'])->name('pound.update');
    Route::delete('/pound/{fighter}', [PoundAdminController::class, 'destroy'])->name('pound.destroy');

    // **Jauns maršruts visiem cīkstoņiem “Save All”**
    Route::patch('/pound/update-all', [PoundAdminController::class, 'updateAll'])
        ->name('pound.updateAll');
});

/* ------------------------------
    Viesu lapa un Dashboard
--------------------------------*/
Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

/* ------------------------------
    Profile CRUD
--------------------------------*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/* ------------------------------
    Chat routes
--------------------------------*/
Route::middleware('auth')->group(function () {
    Route::get('/messages', [ChatController::class, 'fetch']);
    Route::post('/messages', [ChatController::class, 'send']);
    Route::get('/dashboard', [ChatController::class, 'fetch'])->name('dashboard');
    Route::post('/messages', [ChatController::class, 'send'])->name('messages.send');
});

/* ------------------------------
    Ranking routes
--------------------------------*/
Route::middleware('auth')->group(function () {
    Route::get('/ranking', [RankingController::class, 'index'])->name('ranking');
    Route::post('/ranking', [RankingController::class, 'store'])->name('ranking.store');
    Route::delete('/ranking/{ranking}', [RankingController::class, 'destroy'])->name('ranking.destroy');
    Route::patch('/ranking/{ranking}', [RankingController::class, 'update'])->name('ranking.update');
});

// Admin Divisions ranking order
Route::middleware('auth')->group(function () {
    Route::patch('/admin/divisions/{division}/rankings/order', [RankingController::class, 'updateOrder'])
        ->name('admin.rankings.updateOrder');
});

// Rāda visas divīzijas
Route::get('/ranking', function () {
    $divisions = Division::all();
    return view('ranking', compact('divisions'));
})->name('ranking');

// Rāda konkrētu divīziju ar top 16 + čempionu
Route::get('/ranking/{division}', function (Division $division) {
    return view('ranking', compact('division'));
})->name('ranking.show');

/* ------------------------------
    Admin Divisions + Fighters CRUD
--------------------------------*/
Route::middleware('auth')->group(function () {
    Route::get('/admin/divisions', function () {
        $user = auth()->user();
        if (!$user || !$user->is_admin) abort(403, 'Unauthorized');

        $divisions = Division::with('rankings')->get();
        return view('admin.divisions.index', compact('divisions'));
    })->name('admin.divisions.index');

    Route::post('/admin/divisions', function (Request $request) {
        $user = auth()->user();
        if (!$user || !$user->is_admin) abort(403, 'Unauthorized');
        $request->validate(['name' => 'required|string|max:255']);
        Division::create(['name' => $request->name]);
        return redirect()->route('admin.divisions.index');
    })->name('admin.divisions.store');

    Route::delete('/admin/rankings/{ranking}', [AdminRankingController::class, 'destroy'])->name('admin.fighters.destroy');

    Route::delete('/admin/divisions/{division}', function (Division $division) {
        $user = auth()->user();
        if (!$user || !$user->is_admin) abort(403, 'Unauthorized');
        $division->delete();
        return redirect()->route('admin.divisions.index');
    })->name('admin.divisions.destroy');

    Route::post('/admin/divisions/{division}/rankings', [AdminRankingController::class, 'store'])->name('admin.rankings.store');

    Route::patch('/admin/divisions/{division}', function (Request $request, Division $division) {
        $user = auth()->user();
        if (!$user || !$user->is_admin) abort(403, 'Unauthorized');
        $request->validate(['name' => 'required|string|max:255']);
        $division->update(['name' => $request->name]);

        if ($request->has('fighters')) {
            foreach ($request->fighters as $id => $fighterData) {
                $fighter = Ranking::find($id);
                if ($fighter && $fighter->division_id === $division->id) {
                    $fighter->fighter_name = $fighterData['fighter_name'];
                    $fighter->rank = $fighterData['rank'];
                    $fighter->is_champion = isset($fighterData['is_champion']) ? 1 : 0;
                    $fighter->save();
                }
            }
        }

        if ($request->filled('new_fighter.fighter_name')) {
            $division->rankings()->create([
                'fighter_name' => $request->new_fighter['fighter_name'],
                'rank' => $request->new_fighter['rank'] ?? ($division->rankings()->count() + 1),
                'is_champion' => isset($request->new_fighter['is_champion']) ? 1 : 0,
            ]);
        }

        return redirect()->route('admin.divisions.index')->with('success', 'Division updated successfully.');
    })->name('admin.divisions.update');
});

/* ------------------------------
    News Route
--------------------------------*/
Route::middleware('auth')->group(function () {
    Route::get('/news', [NewsController::class, 'index'])->name('news');
});

/* ------------------------------
    Pound for Pound Route
--------------------------------*/
Route::middleware('auth')->group(function () {
    Route::get('/pound', [PoundController::class, 'index'])->name('pound');
});

/* ------------------------------
    Dreamfights Routes
--------------------------------*/
Route::middleware('auth')->prefix('dreamfights')->name('dreamfights.')->group(function () {
    Route::get('/', [DreamfightController::class, 'index'])->name('index');
    Route::post('/', [DreamfightController::class, 'store'])->name('store');
    Route::get('/{dreamfight}/edit', [DreamfightController::class, 'edit'])->name('edit');
    Route::patch('/{dreamfight}', [DreamfightController::class, 'update'])->name('update');
    Route::resource('dreamfights', DreamfightController::class);
    Route::delete('/{dreamfight}', [DreamfightController::class, 'destroy'])->name('destroy');
});

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/pound', [PoundAdminController::class, 'index'])->name('pound.index');
    Route::post('/pound', [PoundAdminController::class, 'store'])->name('pound.store');
    Route::patch('/pound/{fighter}', [PoundAdminController::class, 'update'])->name('pound.update');
    Route::delete('/pound/{fighter}', [PoundAdminController::class, 'destroy'])->name('pound.destroy');

    // Save All
    Route::patch('/pound/update-all', [PoundAdminController::class, 'updateAll'])->name('pound.updateAll');
});


require __DIR__.'/auth.php';
