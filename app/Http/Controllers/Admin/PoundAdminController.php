<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\PoundFighter;

class PoundAdminController extends Controller
{
    /**
     * Show the Pound-for-Pound ranking page.
     */
    public function index()
    {
        $fighters = PoundFighter::orderBy('rank', 'asc')->get();
        return view('pound', compact('fighters'));
    }

    /**
     * Add a new fighter.
     */
    public function store(Request $request)
    {
        $request->validate([
            'fighter_name' => 'required|string|max:255',
            'rank' => 'required|integer|min:1|max:10',
        ]);

        PoundFighter::create($request->only('fighter_name', 'rank'));

        return back()->with('success', 'Fighter added successfully!');
    }

    /**
     * ✅ Save all fighters in one go (avoids duplicate rank conflicts).
     */
    public function updateAll(Request $request)
    {
        $fightersData = $request->input('fighters', []);

        DB::transaction(function () use ($fightersData) {
            foreach ($fightersData as $id => $data) {
                PoundFighter::where('id', $id)->update(['rank' => -$id]);
            }

            // 🔹 Step 2: Update to desired rank and fighter name
            foreach ($fightersData as $id => $data) {
                PoundFighter::where('id', $id)->update([
                    'fighter_name' => $data['fighter_name'],
                    'rank' => $data['rank'],
                ]);
            }
        });

        return back()->with('success', '✅ All changes saved successfully!');
    }

    /**
     * Delete a fighter.
     */
    public function destroy(PoundFighter $fighter)
    {
        $fighter->delete();
        return back()->with('success', 'Fighter deleted successfully!');
    }
}
