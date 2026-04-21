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
use App\Http\Controllers\Admin\ChatModerationController;
use App\Http\Controllers\DreamfightController;

Route::get('/', fn() => view('welcome'));

Route::middleware(['auth', 'verified'])->group(function () {
    // Chat
    Route::get('/dashboard', [ChatController::class, 'fetch'])->name('dashboard');
    Route::post('/chat/send', [ChatController::class, 'send'])->name('chat.send');
    Route::delete('/chat/{message}', [ChatController::class, 'destroy'])->name('chat.destroy');

    // Rankings
    Route::get('/ranking', [RankingController::class, 'index'])->name('ranking');
    Route::get('/ranking/{division}', [RankingController::class, 'show'])->name('ranking.show');
    Route::post('/ranking', [RankingController::class, 'store'])->name('ranking.store');
    Route::patch('/ranking/{ranking}', [RankingController::class, 'update'])->name('ranking.update');
    Route::delete('/ranking/{ranking}', [RankingController::class, 'destroy'])->name('ranking.destroy');
    Route::patch('/admin/divisions/{division}/rankings/order', [RankingController::class, 'updateOrder'])->name('admin.rankings.updateOrder');

    // Admin
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/pound', [PoundAdminController::class, 'index'])->name('pound.index');
        Route::post('/pound', [PoundAdminController::class, 'store'])->name('pound.store');
        Route::patch('/pound/update-all', [PoundAdminController::class, 'updateAll'])->name('pound.updateAll');
        Route::patch('/pound/{fighter}', [PoundAdminController::class, 'update'])->name('pound.update');
        Route::delete('/pound/{fighter}', [PoundAdminController::class, 'destroy'])->name('pound.destroy');

        Route::get('/divisions', function () {
            abort_unless(auth()->user()?->is_admin, 403);
            $divisions = Division::with('rankings')->get();
            return view('admin.divisions.index', compact('divisions'));
        })->name('divisions.index');

        Route::post('/divisions', function (Request $request) {
            abort_unless(auth()->user()?->is_admin, 403);
            $request->validate([
                'name' => 'required|string|max:255|unique:divisions,name'
            ]);

            Division::create(['name' => $request->name]);

            return redirect()
                ->route('admin.divisions.index')
                ->with('success', 'Division created.');
        })->name('divisions.store');

        Route::patch('/divisions/{division}', [DivisionController::class, 'update'])->name('divisions.update');

        Route::delete('/divisions/{division}', function (Division $division) {
            abort_unless(auth()->user()?->is_admin, 403);

            $division->delete();

            return redirect()
                ->route('admin.divisions.index')
                ->with('success', 'Division deleted.');
        })->name('divisions.destroy');

        Route::post('/divisions/{division}/rankings', [AdminRankingController::class, 'store'])->name('rankings.store');
        Route::delete('/rankings/{ranking}', [AdminRankingController::class, 'destroy'])->name('fighters.destroy');

        // Admin chat moderation
        Route::get('/chat-moderation', [ChatModerationController::class, 'index'])->name('chat-moderation.index');
        Route::post('/chat-moderation/{user}/mute', [ChatModerationController::class, 'mute'])->name('chat-moderation.mute');
        Route::post('/chat-moderation/{user}/unmute', [ChatModerationController::class, 'unmute'])->name('chat-moderation.unmute');
    });

    // News
    Route::get('/news', [NewsController::class, 'index'])->name('news');

    // Pound-for-Pound (user)
    Route::get('/pound', [PoundController::class, 'index'])->name('pound');

    // Dreamfights
    Route::prefix('dreamfights')->name('dreamfights.')->group(function () {
        Route::get('/', [DreamfightController::class, 'index'])->name('index');
        Route::post('/', [DreamfightController::class, 'create'])->name('create');
        Route::post('/{dreamfight}/join', [DreamfightController::class, 'join'])->name('join');
        Route::get('/{dreamfight}', [DreamfightController::class, 'show'])->name('show');
        Route::post('/{dreamfight}/choose', [DreamfightController::class, 'choose'])->name('choose');
    });
});

require __DIR__.'/auth.php';