<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Division;
use App\Models\Ranking;

class RankingController extends Controller
{
    /**
     * Show all divisions with their rankings.
     */
    public function index()
    {
        $divisions = Division::with('rankings')->get();
        return view('ranking', compact('divisions'));
    }

    /**
     * Show one specific division with its fighters.
     */
    public function show(Division $division)
    {
        $division->load('rankings');
        return view('ranking', ['division' => $division]);
    }

    /**
     * Store a new fighter in a division (max 16 fighters, single champion).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'division_id'  => 'required|exists:divisions,id',
            'fighter_name' => 'required|string|max:255',
            'rank'         => 'nullable|integer|min:1|max:999',
            'is_champion'  => 'nullable|boolean',
        ]);

        $division = Division::findOrFail($validated['division_id']);

        // Max 16 fighters
        $count = $division->rankings()->count();
        if ($count >= 16) {
            return back()->withErrors('This division already has the maximum of 16 fighters.')->withInput();
        }

        DB::transaction(function () use ($division, $validated, $count) {
            $isChamp = !empty($validated['is_champion']) ? 1 : 0;
            if ($isChamp) {
                Ranking::where('division_id', $division->id)->update(['is_champion' => 0]);
            }

            Ranking::create([
                'division_id'  => $division->id,
                'fighter_name' => $validated['fighter_name'],
                'rank'         => $validated['rank'] ?? ($count + 1),
                'is_champion'  => $isChamp,
            ]);
        });

        return back()->with('success', 'Fighter added successfully.');
    }

    /**
     * Update a fighter (normalize champion).
     */
    public function update(Request $request, Ranking $ranking)
    {
        $validated = $request->validate([
            'fighter_name' => 'required|string|max:255',
            'rank'         => 'nullable|integer|min:1|max:999',
            'is_champion'  => 'nullable|boolean',
        ]);

        DB::transaction(function () use ($ranking, $validated) {
            $isChamp = !empty($validated['is_champion']) ? 1 : 0;
            if ($isChamp) {
                Ranking::where('division_id', $ranking->division_id)
                    ->where('id', '<>', $ranking->id)
                    ->update(['is_champion' => 0]);
            }

            $ranking->update([
                'fighter_name' => $validated['fighter_name'],
                'rank'         => $validated['rank'] ?? $ranking->rank,
                'is_champion'  => $isChamp,
            ]);
        });

        return back()->with('success', 'Fighter updated successfully.');
    }

    /**
     * Delete a fighter.
     */
    public function destroy(Ranking $ranking)
    {
        $ranking->delete();
        return back()->with('success', 'Fighter removed successfully.');
    }

    /**
     * Update order (if you use drag & drop).
     */
    public function updateOrder(Request $request, Division $division)
    {
        $validated = $request->validate([
            'order'   => 'required|array',
            'order.*' => 'exists:rankings,id',
        ]);

        foreach ($validated['order'] as $position => $fighterId) {
            Ranking::where('id', $fighterId)
                ->where('division_id', $division->id)
                ->update(['rank' => $position + 1]);
        }

        return response()->json(['status' => 'ok']);
    }
}
