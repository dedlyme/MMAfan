<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Events\MessageSent;

class ChatController extends Controller
{
    public function fetch()
    {
        $messages = Message::with('user')->latest()->take(200)->get()->reverse()->values();
        return view('dashboard', compact('messages'));
    }

    public function send(Request $request)
    {
        $request->validate(['message' => 'required|string|max:1000']);

        $message = Message::create([
            'user_id' => auth()->id(),
            'message' => $request->message,
        ]);

        event(new MessageSent($message));

        return response()->json($message->load('user'));
    }
}
