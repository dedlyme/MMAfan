<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PoundFighter;

class PoundController extends Controller
{
    public function index()
    {
        // Iegūst no datubāzes kā Eloquent kolekciju (object), ne array
        $fighters = PoundFighter::orderBy('rank', 'asc')->get(); 

        return view('pound', compact('fighters'));
    }
}

