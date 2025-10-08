<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Events\MessageSent;

class ChatController extends Controller
{
    /**
     * Dashboard / čata sākumlapa ar ziņām
     */
    public function fetch()
    {
        // Paņemam pēdējās 200 ziņas (svaigākās uz augšu, tad apgriežam)
        $messages = Message::with('user')
            ->latest()
            ->take(200)
            ->get()
            ->reverse()
            ->values();

        return view('dashboard', compact('messages'));
    }

    /**
     * Saņem jaunu ziņu no lietotāja (AJAX POST)
     */
    public function send(Request $request)
    {
        // Validācija
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        // Saglabājam ziņu datubāzē
        $message = Message::create([
            'user_id' => auth()->id(),
            'message' => $request->message,
        ]);

        // Izsaucam notikumu (ja vēlāk gribēsi reāllaika atjaunošanu ar Echo/Pusher)
        event(new MessageSent($message));

        // Atgriežam JSON priekš AJAX
        return response()->json($message->load('user'));
    }
}
