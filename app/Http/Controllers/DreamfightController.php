<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dreamfight;
use App\Models\Fighter;
use Illuminate\Support\Facades\Auth;

class DreamfightController extends Controller
{
    // --- LIST OF FIGHTS ---
    public function index()
    {
        $fighters = Fighter::orderBy('first_name')->orderBy('last_name')->get();
        $dreamfights = Dreamfight::with(['playerOne', 'playerTwo'])->latest()->get();

        return view('dreamfights', compact('fighters', 'dreamfights'));
    }

    // --- CREATE FIGHT ---
    public function create(Request $request)
    {
        $request->validate([
            'fighter_id' => 'required|exists:fighters,id'
        ]);

        Dreamfight::create([
            'player_one_id' => Auth::id(),
            'player_one_fighter_id' => $request->fighter_id,
            'status' => 'waiting',
            'current_round' => 1,
            'player_one_score' => 0,
            'player_two_score' => 0,
        ]);

        return redirect()->route('dreamfights.index')->with('success', 'Fight created! Waiting for opponent.');
    }

    // --- JOIN FIGHT ---
    public function join(Dreamfight $dreamfight, Request $request)
    {
        if ($dreamfight->status !== 'waiting' || $dreamfight->player_two_id) {
            return back()->with('error', 'This fight is not available to join.');
        }

        $request->validate([
            'fighter_id' => 'required|exists:fighters,id'
        ]);

        $dreamfight->update([
            'player_two_id' => Auth::id(),
            'player_two_fighter_id' => $request->fighter_id,
            'status' => 'in_progress',
            'player_one_choice' => null,
            'player_two_choice' => null,
        ]);

        return redirect()->route('dreamfights.show', $dreamfight);
    }

    // --- SHOW GAME PAGE ---
    public function show(Dreamfight $dreamfight)
    {
        // Only players can enter the fight page
        abort_unless(in_array(Auth::id(), [$dreamfight->player_one_id, $dreamfight->player_two_id]), 403);

        return view('dreamfight_game', compact('dreamfight'));
    }

    // --- PLAYER MAKES A CHOICE ---
    public function choose(Dreamfight $dreamfight, Request $request)
    {
        abort_unless(in_array(Auth::id(), [$dreamfight->player_one_id, $dreamfight->player_two_id]), 403);

        $request->validate([
            'choice' => 'required|in:wrestling,kickbox,jiu-jitsu'
        ]);

        if ($dreamfight->status !== 'in_progress') return back();

        if (Auth::id() === $dreamfight->player_one_id && !$dreamfight->player_one_choice) {
            $dreamfight->player_one_choice = $request->choice;
        }

        if (Auth::id() === $dreamfight->player_two_id && !$dreamfight->player_two_choice) {
            $dreamfight->player_two_choice = $request->choice;
        }

        $dreamfight->save();

        // If BOTH choices now present → calculate round
        if ($dreamfight->player_one_choice && $dreamfight->player_two_choice) {
            $this->resolveRound($dreamfight);
        }

        return back();
    }

    private function resolveRound(Dreamfight $fight)
    {
        $p1 = $fight->player_one_choice;
        $p2 = $fight->player_two_choice;

        // MMA RPS rules
        $beats = [
            'wrestling' => 'kickbox',
            'kickbox' => 'jiu-jitsu',
            'jiu-jitsu' => 'wrestling'
        ];

        if ($p1 && $p2) {
            if ($p1 === $p2) {
                // draw round
            } elseif ($beats[$p1] === $p2) {
                $fight->player_one_score++;
            } else {
                $fight->player_two_score++;
            }
        }

        // Reset choices for next round or finish
        if ($fight->current_round < 3) {
            $fight->current_round++;
            $fight->player_one_choice = null;
            $fight->player_two_choice = null;
        } else {
            // Fight finished
            if ($fight->player_one_score > $fight->player_two_score) {
                $fight->winner = $fight->playerOne->name ?? 'P1';
            } elseif ($fight->player_two_score > $fight->player_one_score) {
                $fight->winner = $fight->playerTwo->name ?? 'P2';
            } else {
                $fight->winner = 'Draw';
            }
            $fight->status = 'finished';
        }

        $fight->save();
    }
}
