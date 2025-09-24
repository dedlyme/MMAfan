<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dreamfight;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class DreamfightController extends Controller
{
    public function index(Request $request)
    {
        // Use cached fighters to avoid slow API
        $fighters = Cache::remember('fighters_list', 3600, function () {
            $apiKey = config('services.sportsdata.key');
            $data = Http::timeout(5)->get("https://api.sportsdata.io/v3/mma/scores/json/FightersBasic?key={$apiKey}")->json();

            return collect($data)
                ->filter(fn($f) => !isset($f['Status']) || $f['Status'] === 'Active')
                ->map(fn($f) => [
                    'FirstName'   => $f['FirstName'] ?? '',
                    'LastName'    => $f['LastName'] ?? '',
                    'Nickname'    => $f['Nickname'] ?? '',
                    'WeightClass' => $f['WeightClass'] ?? '',
                    'Wins'        => $f['Wins'] ?? 0,
                    'Losses'      => $f['Losses'] ?? 0,
                    'Draws'       => $f['Draws'] ?? 0,
                    'NoContests'  => $f['NoContests'] ?? 0,
                ])
                ->values();
        });

        $dreamfights = Dreamfight::with('user')
            ->when($request->filled('username'), fn($q) => $q->whereHas('user', fn($uq) => $uq->where('name', 'like', '%' . $request->username . '%')))
            ->latest()
            ->get();

        return view('dreamfights', compact('fighters', 'dreamfights'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fighter_one_name' => 'required|string',
            'fighter_two_name' => 'required|string|different:fighter_one_name',
        ]);

        Dreamfight::create([
            'user_id' => Auth::id(),
            'fighter_one_name' => $request->fighter_one_name,
            'fighter_two_name' => $request->fighter_two_name,
        ]);

        return redirect()->route('dreamfights.index')->with('success', 'Dream fight saved!');
    }

    public function edit(Dreamfight $dreamfight)
    {
        $fighters = Cache::get('fighters_list', collect());
        return view('dreamfights.edit', compact('dreamfight', 'fighters'));
    }

    public function update(Request $request, Dreamfight $dreamfight)
    {
        $request->validate([
            'fighter_one_name' => 'required|string',
            'fighter_two_name' => 'required|string|different:fighter_one_name',
        ]);

        $dreamfight->update([
            'fighter_one_name' => $request->fighter_one_name,
            'fighter_two_name' => $request->fighter_two_name,
        ]);

        return redirect()->route('dreamfights.index')->with('success', 'Dream fight updated!');
    }

    public function destroy(Dreamfight $dreamfight)
    {
        $dreamfight->delete();
        return redirect()->route('dreamfights.index')->with('success', 'Dream fight deleted!');
    }
}
