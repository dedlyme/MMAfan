<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Division;
use App\Models\Ranking;

class RankingController extends Controller
{
    public function index()
    {
        $divisions = Division::with('rankings')->get();
        return view('ranking', compact('divisions'));
    }

    public function show(Division $division)
    {
        $division->load('rankings');
        return view('ranking', ['division' => $division]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'division_id'  => 'required|exists:divisions,id',
            'fighter_name' => 'required|string|max:255',
            'rank'         => 'nullable|integer',
            'is_champion'  => 'nullable|boolean',
        ]);

        $division = Division::findOrFail($validated['division_id']);

        // ✅ Pārbauda vai fighter jau eksistē šajā divīzijā
        if (Ranking::where('division_id', $division->id)
            ->where('fighter_name', $validated['fighter_name'])
            ->exists()) {
            return back()->with('error', 'This fighter already exists in this division.');
        }

        // ✅ Pārbauda vai jau ir čempions šajā divīzijā
        if (!empty($validated['is_champion']) &&
            Ranking::where('division_id', $division->id)
                ->where('is_champion', true)
                ->exists()) {
            return back()->with('error', 'This division already has a champion.');
        }

        Ranking::create([
            'division_id'  => $division->id,
            'fighter_name' => $validated['fighter_name'],
            'rank'         => $validated['rank'] ?? ($division->rankings()->count() + 1),
            'is_champion'  => $validated['is_champion'] ?? 0,
        ]);

        return back()->with('success', 'Fighter added successfully.');
    }

    public function update(Request $request, Ranking $ranking)
    {
        $validated = $request->validate([
            'fighter_name' => 'required|string|max:255',
            'rank'         => 'nullable|integer',
            'is_champion'  => 'nullable|boolean',
        ]);

        // ✅ Pārbauda vai jau ir cits fighter ar tādu pašu vārdu šajā divīzijā
        if (Ranking::where('division_id', $ranking->division_id)
            ->where('fighter_name', $validated['fighter_name'])
            ->where('id', '!=', $ranking->id)
            ->exists()) {
            return back()->with('error', 'This fighter name already exists in this division.');
        }

        // ✅ Ja tiek uzstādīts čempions, pārbauda vai jau nav cits čempions
        if (!empty($validated['is_champion']) &&
            Ranking::where('division_id', $ranking->division_id)
                ->where('is_champion', true)
                ->where('id', '!=', $ranking->id)
                ->exists()) {
            return back()->with('error', 'This division already has a champion.');
        }

        $ranking->update([
            'fighter_name' => $validated['fighter_name'],
            'rank'         => $validated['rank'] ?? $ranking->rank,
            'is_champion'  => $validated['is_champion'] ?? 0,
        ]);

        return back()->with('success', 'Fighter updated successfully.');
    }

    public function destroy(Ranking $ranking)
    {
        $ranking->delete();
        return back()->with('success', 'Fighter removed successfully.');
    }

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
