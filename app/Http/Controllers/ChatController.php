<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Events\MessageSent;

class ChatController extends Controller
{
    /**
     * Dashboard / chat page with last messages
     */
    public function fetch()
    {
        $messages = Message::with('user')
            ->latest()
            ->take(200)
            ->get()
            ->reverse()
            ->values();

        return view('dashboard', compact('messages'));
    }

    /**
     * Receive new message from user (AJAX POST)
     */
    public function send(Request $request)
    {
        // Validate input length/type
        $data = $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        // 🛡 SECURITY: Remove all HTML, CSS, JS (prevents XSS)
        $cleanMessage = strip_tags($data['message']);

        // Save message to database
        $message = Message::create([
            'user_id' => auth()->id(),
            'message' => $cleanMessage,
        ]);

        // Broadcast real-time event
        event(new MessageSent($message));

        // Return message for chat append
        return response()->json($message->load('user'));
    }
}
