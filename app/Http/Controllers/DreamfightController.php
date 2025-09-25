<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Dreamfight;
use App\Models\Fighter;

class DreamfightController extends Controller
{
    public function index()
    {
        // ✅ Load fighters (sorted by first + last name)
        $fighters = Fighter::orderBy('first_name')->orderBy('last_name')->get();

        // ✅ Load fights with players & order newest first
        $dreamfights = Dreamfight::with(['playerOne', 'playerTwo'])
            ->orderByDesc('created_at')
            ->get();

        return view('dreamfights', compact('fighters', 'dreamfights'));
    }

    public function create(Request $request)
    {
        $request->validate([
            'fighter_id' => 'required|exists:fighters,id',
        ]);

        Dreamfight::create([
            'player_one_id' => Auth::id(),
            'player_one_fighter_id' => $request->fighter_id,
            'current_round' => 1,
            'player_one_score' => 0,
            'player_two_score' => 0,
            'status' => 'waiting',
        ]);

        return redirect()->route('dreamfights.index')
            ->with('success', 'Challenge created! Waiting for another player...');
    }

    public function join(Request $request, Dreamfight $dreamfight)
    {
        $request->validate([
            'fighter_id' => 'required|exists:fighters,id',
        ]);

        if ($dreamfight->player_two_id) {
            return redirect()->route('dreamfights.index')
                ->with('error', 'Fight already has two players.');
        }

        $dreamfight->update([
            'player_two_id' => Auth::id(),
            'player_two_fighter_id' => $request->fighter_id,
            'status' => 'in_progress',
        ]);

        return redirect()->route('dreamfights.index')
            ->with('success', 'You joined the fight!');
    }

    public function choose(Request $request, Dreamfight $dreamfight)
    {
        $request->validate([
            'choice' => 'required|in:wrestling,kickbox,jiu-jitsu',
        ]);

        // Save player choice
        if ($dreamfight->player_one_id === Auth::id()) {
            $dreamfight->player_one_choice = $request->choice;
        } elseif ($dreamfight->player_two_id === Auth::id()) {
            $dreamfight->player_two_choice = $request->choice;
        }

        // If both players have chosen, decide winner for this round
        if ($dreamfight->player_one_choice && $dreamfight->player_two_choice) {
            $winner = $this->determineWinner($dreamfight->player_one_choice, $dreamfight->player_two_choice);

            if ($winner === 'p1') {
                $dreamfight->player_one_score += 1;
            } elseif ($winner === 'p2') {
                $dreamfight->player_two_score += 1;
            }

            // Reset choices for next round
            $dreamfight->player_one_choice = null;
            $dreamfight->player_two_choice = null;

            // If 3 rounds done → finish fight
            if ($dreamfight->current_round >= 3) {
                $dreamfight->status = 'finished';
                if ($dreamfight->player_one_score > $dreamfight->player_two_score) {
                    $dreamfight->winner = $dreamfight->playerOne->name ?? 'Player 1';
                } elseif ($dreamfight->player_two_score > $dreamfight->player_one_score) {
                    $dreamfight->winner = $dreamfight->playerTwo->name ?? 'Player 2';
                } else {
                    $dreamfight->winner = 'Draw';
                }
            } else {
                $dreamfight->current_round += 1;
            }
        }

        $dreamfight->save();

        return redirect()->route('dreamfights.index');
    }

    private function determineWinner($p1, $p2)
    {
        $beats = [
            'wrestling' => 'kickbox',
            'kickbox' => 'jiu-jitsu',
            'jiu-jitsu' => 'wrestling',
        ];

        if ($p1 === $p2) {
            return null; // draw
        }

        return $beats[$p1] === $p2 ? 'p1' : 'p2';
    }
}
