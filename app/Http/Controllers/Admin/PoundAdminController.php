<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PoundFighter;

class PoundAdminController extends Controller
{
    /**
     * Rāda P4P sarakstu
     */
    public function index()
    {
        $fighters = PoundFighter::orderBy('rank')->get();
        return view('pound', compact('fighters'));
    }

    /**
     * ✅ Pievieno jaunu cīnītāju (max 10, unikāli ranki)
     */
    public function store(Request $request)
    {
        $request->validate([
            'fighter_name' => 'required|string|max:255',
            'rank'         => 'required|integer|min:1|max:10'
        ]);

        // Maksimums 10
        if (PoundFighter::count() >= 10) {
            return back()->with('warning', '⚠️ Maksimālais cīnītāju skaits P4P sarakstā ir 10.');
        }

        // Jau eksistē tāds rank
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
     * ✅ Atjauno VISUS cīnītājus vienā piegājienā
     */
    public function updateAll(Request $request)
    {
        $fightersData = $request->input('fighters', []);

        // Savācam visus rankus lai pārbaudītu dublikātus
        $ranks = [];
        foreach ($fightersData as $id => $data) {
            if (!empty($data['rank'])) {
                $ranks[] = $data['rank'];
            }
        }

        // Dublikātu pārbaude
        if (count($ranks) !== count(array_unique($ranks))) {
            return back()->with('warning', '⚠️ Katram cīnītājam jābūt ar unikālu ranku!');
        }

        // Maksimums 10
        if (count($fightersData) > 10) {
            return back()->with('warning', '⚠️ Nevar būt vairāk par 10 cīnītājiem P4P sarakstā.');
        }

        // Saglabājam izmaiņas
        foreach ($fightersData as $id => $data) {
            $fighter = PoundFighter::find($id);
            if ($fighter) {
                $fighter->update([
                    'fighter_name' => $data['fighter_name'] ?? $fighter->fighter_name,
                    'rank'         => $data['rank'] ?? $fighter->rank,
                ]);
            }
        }

        return back()->with('success', '✅ Visi cīnītāji tika veiksmīgi atjaunoti!');
    }

    /**
     * ✅ Atjauno atsevišķu cīnītāju
     */
    public function update(Request $request, PoundFighter $fighter)
    {
        $request->validate([
            'fighter_name' => 'required|string|max:255',
            'rank'         => 'required|integer|min:1|max:10'
        ]);

        if (PoundFighter::where('rank', $request->rank)->where('id', '!=', $fighter->id)->exists()) {
            return back()->with('warning', "⚠️ Rank {$request->rank} jau ir aizņemts.");
        }

        $fighter->update([
            'fighter_name' => $request->fighter_name,
            'rank'         => $request->rank
        ]);

        return back()->with('success', '✅ Cīnītājs atjaunots veiksmīgi!');
    }

    /**
     * ✅ Dzēš cīnītāju
     */
    public function destroy(PoundFighter $fighter)
    {
        $fighter->delete();
        return back()->with('success', '🗑️ Cīnītājs dzēsts veiksmīgi!');
    }
}
