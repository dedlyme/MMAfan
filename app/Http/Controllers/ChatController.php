<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Events\MessageSent;
use App\Events\MessageDeleted;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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

        if (!$userId) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        // Anti-spam: 1 message every 5 seconds
        $cooldownKey = "chat_cooldown_{$userId}";
        if (Cache::has($cooldownKey)) {
            return response()->json([
                'error' => 'Slow down! Please wait a few seconds before sending another message.'
            ], 429);
        }

        // Validation
        // NOTE: not_regex must be provided without enclosing delimiters in the rule string
        $validated = $request->validate([
            'message' => [
                'required',
                'string',
                'max:300',
                // Block common link patterns (disallows http(s) and www.)
                'not_regex:/https?:\\/\\/|www\\./i',
            ],
        ], [
            'message.not_regex' => 'Links are not allowed in chat.',
        ]);

        try {
            // Sanitize input: strip tags then run through HTMLPurifier
            $config = HTMLPurifier_Config::createDefault();
            // Optional: tighten allowed elements (none here)
            $purifier = new HTMLPurifier($config);
            $clean = strip_tags($validated['message']);
            $safeMessage = $purifier->purify($clean);

            // Store safe message
            $message = Message::create([
                'user_id' => $userId,
                'message' => $safeMessage,
            ]);

            // Start cooldown
            Cache::put($cooldownKey, true, now()->addSeconds(5));

            // Broadcast the new message (implements ShouldBroadcast)
            event(new MessageSent($message->load('user')));

            return response()->json($message->load('user'), 201);

        } catch (\Throwable $e) {
            // Log the error for debugging (don't expose internals to client)
            Log::error('Chat send error: '.$e->getMessage(), [
                'stack' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Server error while sending message.'], 500);
        }
    }

    /**
     * Admin deletes a message
     */
    public function destroy(Message $message)
    {
        $user = Auth::user();

        if (!$user || !$user->is_admin) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            $deletedId = $message->id;
            $message->delete();

            // Broadcast deletion so clients can remove it
            event(new MessageDeleted($deletedId));

            return response()->json(['success' => true]);
        } catch (\Throwable $e) {
            \Log::error('Chat delete error: '.$e->getMessage());
            return response()->json(['error' => 'Server error while deleting message.'], 500);
        }
    }
}
