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
            'rank' => 'nullable|integer|min:1',
            'is_champion' => 'nullable|boolean',
        ]);

        if ($request->is_champion) {
            Ranking::where('division_id', $division->id)->update(['is_champion' => false]);
        }

        $division->rankings()->create([
            'fighter_name' => $request->fighter_name,
            'rank' => $request->rank,
            'is_champion' => $request->is_champion ?? false,
        ]);

        return back();
    }

    public function destroy(Ranking $ranking)
    {
        $ranking->delete();
        return back();
    }
}
