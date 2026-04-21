<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Events\MessageSent;
use App\Events\MessageDeleted;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
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

    public function send(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        if ($user->isChatMuted()) {
            return response()->json([
                'error' => 'You are muted in chat until '.$user->chat_muted_until?->format('Y-m-d H:i'),
                'reason' => $user->chat_mute_reason,
                'muted_until' => $user->chat_muted_until?->toDateTimeString(),
            ], 403);
        }

        $cooldownKey = "chat_cooldown_{$user->id}";
        if (Cache::has($cooldownKey)) {
            return response()->json(['error' => 'Slow down! Wait a few seconds.'], 429);
        }

        $validated = $request->validate([
            'message' => [
                'required',
                'string',
                'max:300',
                'not_regex:/https?:\\/\\/|www\\./i',
            ],
        ], [
            'message.not_regex' => 'Links are not allowed in chat.',
        ]);

        try {
            $safeMessage = strip_tags($validated['message']);

            $message = Message::create([
                'user_id' => $user->id,
                'message' => $safeMessage,
            ]);

            Cache::put($cooldownKey, true, now()->addSeconds(5));
            event(new MessageSent($message->load('user')));

            return response()->json($message->load('user'), 201);
        } catch (\Throwable $e) {
            Log::error('Chat send error: '.$e->getMessage());

            return response()->json([
                'error' => 'Server error while sending message.'
            ], 500);
        }
    }

    public function destroy(Message $message)
    {
        $user = Auth::user();

        if (!$user || !$user->is_admin) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            $deletedId = $message->id;
            $message->delete();

            event(new MessageDeleted($deletedId));

            return response()->json(['success' => true]);
        } catch (\Throwable $e) {
            Log::error('Chat delete error: '.$e->getMessage());

            return response()->json([
                'error' => 'Server error while deleting message.'
            ], 500);
        }
    }
}