<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Division;
use App\Models\Ranking;

class DivisionController extends Controller
{
    public function index()
    {
        $divisions = Division::with('rankings')->get();
        return view('admin.divisions.index', compact('divisions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:divisions,name'
        ]);

        Division::create(['name' => $request->name]);

        return redirect()->route('admin.divisions.index')->with('success', 'Division created successfully.');
    }

    public function update(Request $request, Division $division)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $division->update(['name' => $request->name]);

        // ✅ Savācam visus rankus no esošajiem + jaunajiem
        $ranks = [];

        if ($request->has('fighters')) {
            foreach ($request->fighters as $id => $fighterData) {
                $rank = isset($fighterData['rank']) ? (int) $fighterData['rank'] : null;

                if ($rank) {
                    if ($rank > 16) {
                        return back()->withErrors(['fighters.' . $id . '.rank' => "Rank cannot be greater than 16."]);
                    }
                    if (in_array($rank, $ranks)) {
                        return back()->withErrors(['fighters.' . $id . '.rank' => "Duplicate rank ($rank) is not allowed."]);
                    }
                    $ranks[] = $rank;
                }
            }
        }

        if ($request->filled('new_fighter.rank')) {
            $newRank = (int) $request->new_fighter['rank'];
            if ($newRank > 16) {
                return back()->withErrors(['new_fighter.rank' => "Rank cannot be greater than 16."]);
            }
            if (in_array($newRank, $ranks)) {
                return back()->withErrors(['new_fighter.rank' => "Duplicate rank ($newRank) is not allowed."]);
            }
            $ranks[] = $newRank;
        }

        // === Update existing fighters ===
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

        // === Add new fighter ===
        if ($request->filled('new_fighter.fighter_name')) {
            if ($division->rankings()->count() >= 16) {
                return back()->withErrors(['new_fighter.fighter_name' => 'This division already has the maximum of 16 fighters.']);
            }

            if (isset($request->new_fighter['is_champion']) && $division->rankings()->where('is_champion', true)->exists()) {
                return back()->withErrors(['new_fighter.is_champion' => 'This division already has a champion.']);
            }

            $division->rankings()->create([
                'fighter_name' => $request->new_fighter['fighter_name'],
                'rank' => $request->new_fighter['rank'] ?? ($division->rankings()->count() + 1),
                'is_champion' => isset($request->new_fighter['is_champion']) ? 1 : 0,
            ]);
        }

        return redirect()->route('admin.divisions.index')->with('success', 'Division updated successfully.');
    }

    public function destroy(Division $division)
    {
        $division->delete();
        return redirect()->route('admin.divisions.index')->with('success', 'Division deleted successfully.');
    }

    public function destroyFighter(Ranking $ranking)
    {
        $ranking->delete();
        return back()->with('success', 'Fighter deleted successfully.');
    }
}
