<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Events\MessageSent;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use HTMLPurifier;
use HTMLPurifier_Config;

class ChatController extends Controller
{
    /**
     * Dashboard / Chat homepage with messages
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
        $userId = Auth::id();

        // ✅ Anti-spam: 1 message every 5 seconds
        $cooldownKey = "chat_cooldown_{$userId}";
        if (Cache::has($cooldownKey)) {
            return response()->json([
                'error' => 'Slow down! Please wait a few seconds before sending another message.'
            ], 429);
        }

        // ✅ Validation (relaxed regex, but still safe)
        $validated = $request->validate([
            'message' => [
                'required',
                'string',
                'max:300',
                // Disallow only dangerous characters, but allow normal punctuation
                'not_regex:/(https?:\/\/|www\.)/i', // block links
            ],
        ], [
            'message.not_regex' => 'Links are not allowed in chat.',
        ]);

        // ✅ Sanitize input
        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);
        $clean = strip_tags($validated['message']);
        $safeMessage = $purifier->purify($clean);

        // ✅ Store safe message
        $message = Message::create([
            'user_id' => $userId,
            'message' => $safeMessage,
        ]);

        // ✅ Start 5-second cooldown
        Cache::put($cooldownKey, true, now()->addSeconds(5));

        // ✅ Broadcast event
        event(new MessageSent($message));

        return response()->json($message->load('user'));
    }
}
