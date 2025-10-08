<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Division;
use App\Models\Ranking;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\RankingController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PoundController;
use App\Http\Controllers\Admin\PoundAdminController;
use App\Http\Controllers\Admin\AdminRankingController;
use App\Http\Controllers\Admin\DivisionController;
use App\Http\Controllers\DreamfightController;

/*
|--------------------------------------------------------------------------
| Guest / Public
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Dashboard (Chat)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [ChatController::class, 'fetch'])->name('dashboard');
    Route::post('/messages', [ChatController::class, 'send'])->name('messages.send');
});

/*
|--------------------------------------------------------------------------
| Profile
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Rankings (Public View + CRUD)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/ranking', [RankingController::class, 'index'])->name('ranking');
    Route::get('/ranking/{division}', [RankingController::class, 'show'])->name('ranking.show');

    Route::post('/ranking', [RankingController::class, 'store'])->name('ranking.store');
    Route::patch('/ranking/{ranking}', [RankingController::class, 'update'])->name('ranking.update');
    Route::delete('/ranking/{ranking}', [RankingController::class, 'destroy'])->name('ranking.destroy');

    Route::patch('/admin/divisions/{division}/rankings/order', [RankingController::class, 'updateOrder'])
        ->name('admin.rankings.updateOrder');
});

/*
|--------------------------------------------------------------------------
| Admin: Pound for Pound CRUD
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/pound', [PoundAdminController::class, 'index'])->name('pound.index');
    Route::post('/pound', [PoundAdminController::class, 'store'])->name('pound.store');
    Route::patch('/pound/update-all', [PoundAdminController::class, 'updateAll'])->name('pound.updateAll');
    Route::patch('/pound/{fighter}', [PoundAdminController::class, 'update'])->name('pound.update');
    Route::delete('/pound/{fighter}', [PoundAdminController::class, 'destroy'])->name('pound.destroy');
});

/*
|--------------------------------------------------------------------------
| Admin: Divisions & Fighters CRUD
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    Route::get('/admin/divisions', function () {
        abort_unless(auth()->user()?->is_admin, 403);
        $divisions = Division::with('rankings')->get();
        return view('admin.divisions.index', compact('divisions'));
    })->name('admin.divisions.index');

    Route::post('/admin/divisions', function (Request $request) {
        abort_unless(auth()->user()?->is_admin, 403);
        $request->validate(['name' => 'required|string|max:255|unique:divisions,name']);
        Division::create(['name' => $request->name]);
        return redirect()->route('admin.divisions.index')->with('success', 'Division created.');
    })->name('admin.divisions.store');

    Route::delete('/admin/divisions/{division}', function (Division $division) {
        abort_unless(auth()->user()?->is_admin, 403);
        $division->delete();
        return redirect()->route('admin.divisions.index')->with('success', 'Division deleted.');
    })->name('admin.divisions.destroy');

    // ✅ Use DivisionController@update instead of old inline closure
    Route::patch('/admin/divisions/{division}', [DivisionController::class, 'update'])
        ->name('admin.divisions.update');

    // Extra: Admin add/remove fighters
    Route::post('/admin/divisions/{division}/rankings', [AdminRankingController::class, 'store'])->name('admin.rankings.store');
    Route::delete('/admin/rankings/{ranking}', [AdminRankingController::class, 'destroy'])->name('admin.fighters.destroy');
});

/*
|--------------------------------------------------------------------------
| News
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/news', [NewsController::class, 'index'])->name('news');
});

/*
|--------------------------------------------------------------------------
| Pound for Pound (User View)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/pound', [PoundController::class, 'index'])->name('pound');
});

/*
|--------------------------------------------------------------------------
| Dreamfights (Online Mini-Game)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('dreamfights')->name('dreamfights.')->group(function () {
    Route::get('/', [DreamfightController::class, 'index'])->name('index');
    Route::post('/', [DreamfightController::class, 'create'])->name('create');
    Route::post('/{dreamfight}/join', [DreamfightController::class, 'join'])->name('join');
    Route::get('/{dreamfight}', [DreamfightController::class, 'show'])->name('show');
    Route::post('/{dreamfight}/choose', [DreamfightController::class, 'choose'])->name('choose');
});

require __DIR__.'/auth.php';
