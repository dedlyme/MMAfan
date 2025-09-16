<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dreamfight;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class DreamfightController extends Controller
{
    // Rāda visas dream fights un formu jaunas cīņas izveidei
    public function index()
    {
        // Kešo visu cīkstoņu sarakstu uz 6 stundām
        $fighters = Cache::remember('fighters_list', 21600, function () {
            $data = Http::get('https://api.sportsdata.io/v3/mma/scores/json/FightersBasic?key=d966c6cdce8143da883523ed754cd488')->json();

            return collect($data)
                ->filter(fn($fighter) => !isset($fighter['Status']) || $fighter['Status'] === 'Active')
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

        $dreamfights = Dreamfight::latest()->with('user')->get();

        return view('dreamfights', compact('fighters', 'dreamfights'));
    }

    // Saglabā jaunu cīņu
    public function store(Request $request)
    {
        $request->validate([
            'fighter_one_name' => 'required|string',
            'fighter_two_name' => 'required|string|different:fighter_one_name',
        ]);

        Dreamfight::create([
            'user_id'          => Auth::id(),
            'fighter_one_name' => $request->fighter_one_name,
            'fighter_two_name' => $request->fighter_two_name,
        ]);

        return redirect()->route('dreamfights.index')->with('success', 'Dream fight saved!');
    }

    // Rediģē cīņu
    public function edit(Dreamfight $dreamfight)
    {
        // Tikai admins vai cīņas autors var rediģēt
        if (Auth::user()->is_admin !== 1 && $dreamfight->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Ja kešā nav, atjauno
        $fighters = Cache::remember('fighters_list', 21600, function () {
            $data = Http::get('https://api.sportsdata.io/v3/mma/scores/json/FightersBasic?key=d966c6cdce8143da883523ed754cd488')->json();

            return collect($data)
                ->filter(fn($fighter) => !isset($fighter['Status']) || $fighter['Status'] === 'Active')
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

        return view('dreamfights.edit', compact('dreamfight', 'fighters'));
    }

    // Atjaunina cīņu
    public function update(Request $request, Dreamfight $dreamfight)
    {
        if (Auth::user()->is_admin !== 1 && $dreamfight->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

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

    // Dzēš cīņu
    public function destroy(Dreamfight $dreamfight)
    {
        if (Auth::user()->is_admin !== 1 && $dreamfight->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $dreamfight->delete();
        return redirect()->route('dreamfights.index')->with('success', 'Dream fight deleted!');
    }
}
