<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\PoundFighter;

class PoundAdminController extends Controller
{
    /**
     * ✅ Show Pound-for-Pound list
     */
    public function index()
    {
        $fighters = PoundFighter::orderBy('rank')->get();
        return view('pound', compact('fighters'));
    }

    /**
     * ✅ Add a new fighter (max 10, unique ranks)
     */
    public function store(Request $request)
    {
        $request->validate([
            'fighter_name' => 'required|string|max:255',
            'rank'         => 'required|integer|min:1|max:10'
        ]);

        // Maximum 10 fighters
        if (PoundFighter::count() >= 10) {
            return back()->with('warning', '⚠️ Maksimālais cīnītāju skaits P4P sarakstā ir 10.');
        }

        // Rank already exists
        if (PoundFighter::where('rank', $request->rank)->exists()) {
            return back()->with('warning', "⚠️ Rank {$request->rank} jau ir aizņemts. Izvēlies citu ranku.");
        }

        PoundFighter::create([
            'fighter_name' => $request->fighter_name,
            'rank'         => $request->rank
        ]);

        return back()->with('success', '✅ Cīnītājs veiksmīgi pievienots!');
    }

    /**
     * ✅ Update all fighters at once (safe for rank swapping)
     */
    public function updateAll(Request $request)
    {
        $fightersData = $request->input('fighters', []);

        // Check for duplicates
        $ranks = [];
        foreach ($fightersData as $id => $data) {
            if (!empty($data['rank'])) {
                $ranks[] = $data['rank'];
            }
        }

        if (count($ranks) !== count(array_unique($ranks))) {
            return back()->with('warning', '⚠️ Katram cīnītājam jābūt ar unikālu ranku!');
        }

        if (count($fightersData) > 10) {
            return back()->with('warning', '⚠️ Nevar būt vairāk par 10 cīnītājiem P4P sarakstā.');
        }

        try {
            DB::transaction(function () use ($fightersData) {
                // Step 1: Temporary rank shift to prevent unique constraint collision
                foreach ($fightersData as $id => $data) {
                    DB::table('pound_fighters')->where('id', $id)->update(['rank' => 1000 + $id]);
                }

                // Step 2: Update to intended ranks
                foreach ($fightersData as $id => $data) {
                    DB::table('pound_fighters')->where('id', $id)->update([
                        'fighter_name' => $data['fighter_name'] ?? '',
                        'rank'         => $data['rank'] ?? null,
                        'updated_at'   => now(),
                    ]);
                }
            });
        } catch (\Exception $e) {
            return back()->with('warning', '⚠️ Neizdevās saglabāt izmaiņas. Pārbaudi vai rangi nav dublēti.');
        }

        return back()->with('success', '✅ Visi cīnītāji tika veiksmīgi atjaunoti!');
    }

    /**
     * ✅ Update single fighter safely
     */
    public function update(Request $request, PoundFighter $fighter)
    {
        $request->validate([
            'fighter_name' => 'required|string|max:255',
            'rank'         => 'required|integer|min:1|max:10'
        ]);

        if (PoundFighter::where('rank', $request->rank)
            ->where('id', '!=', $fighter->id)
            ->exists()) {
            return back()->with('warning', "⚠️ Rank {$request->rank} jau ir aizņemts.");
        }

        $fighter->update([
            'fighter_name' => $request->fighter_name,
            'rank'         => $request->rank
        ]);

        return back()->with('success', '✅ Cīnītājs atjaunots veiksmīgi!');
    }

    /**
     * ✅ Delete fighter
     */
    public function destroy(PoundFighter $fighter)
    {
        $fighter->delete();
        return back()->with('success', '🗑️ Cīnītājs dzēsts veiksmīgi!');
    }
}
