<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Events\MessageSent;

class ChatController extends Controller
{
    /**
     * Dashboard / Chat homepage with messages
     */
    public function fetch()
    {
        // Fetch last 200 messages (newest first, then reverse)
        $messages = Message::with('user')
            ->latest()
            ->take(200)
            ->get()
            ->reverse()
            ->values();

        // ✅ Return Laravel view directly
        return view('dashboard', compact('messages'));
    }

    /**
     * Receive new message from user (AJAX POST)
     */
    public function send(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $message = Message::create([
            'user_id' => auth()->id(),
            'message' => $request->message,
        ]);

        event(new MessageSent($message));

        return response()->json($message->load('user'));
    }
}
