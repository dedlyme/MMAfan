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
        // Fetch last 200 messages (newest first, then reverse)
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

        // ✅ Anti-spam: only allow one message every 5 seconds per user
        $cooldownKey = "chat_cooldown_{$userId}";
        if (Cache::has($cooldownKey)) {
            return response()->json([
                'error' => 'Slow down! Please wait a few seconds before sending another message.'
            ], 429);
        }

        // ✅ Validate input
        $validated = $request->validate([
            'message' => [
                'required',
                'string',
                'max:300',
                'regex:/^[^<>]*$/',              // disallow HTML tags
                'not_regex:/(https?:\/\/|www\.)/i', // disallow URLs
            ],
        ], [
            'message.not_regex' => 'Links are not allowed in chat.',
            'message.regex' => 'HTML tags are not allowed.',
        ]);

        // ✅ Sanitize input deeply
        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);
        $safeMessage = $purifier->purify(strip_tags($validated['message']));

        // ✅ Create message
        $message = Message::create([
            'user_id' => $userId,
            'message' => $safeMessage,
        ]);

        // ✅ Start cooldown (5 seconds)
        Cache::put($cooldownKey, true, now()->addSeconds(5));

        // ✅ Broadcast for realtime
        event(new MessageSent($message));

        return response()->json($message->load('user'));
    }
}
