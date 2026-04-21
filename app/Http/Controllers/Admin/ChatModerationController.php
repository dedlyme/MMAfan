<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ChatModerationController extends Controller
{
    public function index()
    {
        abort_unless(auth()->user()?->is_admin, 403);

        $users = User::orderBy('name')->get();

        return view('admin.chat-moderation.index', compact('users'));
    }

    public function mute(Request $request, User $user)
    {
        abort_unless(auth()->user()?->is_admin, 403);

        if ($user->is_admin) {
            return redirect()
                ->route('admin.chat-moderation.index')
                ->with('error', 'You cannot mute another admin.');
        }

        $validated = $request->validate([
            'mute_until' => ['required', 'date', 'after:now'],
            'reason' => ['required', 'string', 'max:1000'],
        ]);

        $user->update([
            'chat_muted_until' => $validated['mute_until'],
            'chat_mute_reason' => $validated['reason'],
        ]);

        return redirect()
            ->route('admin.chat-moderation.index')
            ->with('success', "{$user->name} has been muted in chat.");
    }

    public function unmute(User $user)
    {
        abort_unless(auth()->user()?->is_admin, 403);

        $user->update([
            'chat_muted_until' => null,
            'chat_mute_reason' => null,
        ]);

        return redirect()
            ->route('admin.chat-moderation.index')
            ->with('success', "{$user->name} has been unmuted.");
    }
}