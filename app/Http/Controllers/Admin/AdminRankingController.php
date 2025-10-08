<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Division;
use App\Models\Ranking;

class AdminRankingController extends Controller
{
    public function store(Request $request, Division $division)
    {
        $request->validate([
            'fighter_name' => 'required|string|max:255',
            'rank' => 'nullable|integer|min:1|max:16',
            'is_champion' => 'nullable|boolean',
        ]);

        // ✅ Pārbaudām vai jau ir tāds pats rank
        if ($request->filled('rank') && $division->rankings()->where('rank', $request->rank)->exists()) {
            return back()->withErrors(['rank' => "Rank {$request->rank} is already taken in this division."]);
        }

        // ✅ Pārbaudām max 16 cīnītāji
        if ($division->rankings()->count() >= 16) {
            return back()->withErrors(['fighter_name' => 'This division already has the maximum of 16 fighters.']);
        }

        // ✅ Tikai viens čempions
        if ($request->is_champion && $division->rankings()->where('is_champion', true)->exists()) {
            return back()->withErrors(['is_champion' => 'This division already has a champion.']);
        }

        $division->rankings()->create([
            'fighter_name' => $request->fighter_name,
            'rank' => $request->rank ?? ($division->rankings()->count() + 1),
            'is_champion' => $request->is_champion ?? false,
        ]);

        return back()->with('success', 'Fighter added successfully.');
    }

    public function destroy(Ranking $ranking)
    {
        $ranking->delete();
        return back()->with('success', 'Fighter deleted successfully.');
    }
}
