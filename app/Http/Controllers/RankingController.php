<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
     * Store a new fighter in a division.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'division_id'  => 'required|exists:divisions,id',
            'fighter_name' => 'required|string|max:255',
            'rank'         => 'nullable|integer',
            'is_champion'  => 'nullable|boolean',
        ]);

        Ranking::create([
            'division_id'  => $validated['division_id'],
            'fighter_name' => $validated['fighter_name'],
            'rank'         => $validated['rank'] ?? (Ranking::where('division_id', $validated['division_id'])->count() + 1),
            'is_champion'  => $validated['is_champion'] ?? 0,
        ]);

        return back()->with('success', 'Fighter added successfully.');
    }

    /**
     * Update a fighter.
     */
    public function update(Request $request, Ranking $ranking)
    {
        $validated = $request->validate([
            'fighter_name' => 'required|string|max:255',
            'rank'         => 'nullable|integer',
            'is_champion'  => 'nullable|boolean',
        ]);

        $ranking->update([
            'fighter_name' => $validated['fighter_name'],
            'rank'         => $validated['rank'] ?? $ranking->rank,
            'is_champion'  => $validated['is_champion'] ?? 0,
        ]);

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
     * Update the order of fighters in a division (drag & drop support).
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
