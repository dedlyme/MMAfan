<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PoundFighter;

class PoundAdminController extends Controller
{
    public function index()
    {
        $fighters = PoundFighter::orderBy('rank', 'asc')->get();
        return view('pound', compact('fighters'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fighter_name' => 'required|string|max:255',
            'rank' => 'required|integer|min:1|max:10',
        ]);

        PoundFighter::create($request->only('fighter_name', 'rank'));

        return back();
    }

    public function update(Request $request, PoundFighter $fighter)
    {
        $request->validate([
            'fighter_name' => 'required|string|max:255',
            'rank' => 'required|integer|min:1|max:10',
        ]);

        $fighter->update($request->only('fighter_name', 'rank'));
        return back();
    }

    public function destroy(PoundFighter $fighter)
    {
        $fighter->delete();
        return back();
    }
}
