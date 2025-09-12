<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;

class ChatController extends Controller
{
    public function fetch()
    {
        $messages = Message::with('user')->get();
        return view('dashboard', compact('messages'));
    }

    public function send(Request $request)
    {
        $request->validate(['message' => 'required']);

        Message::create([
            'user_id' => auth()->id(),
            'message' => $request->message,
        ]);

        // Pēc ziņas saglabāšanas refresh lapu
        return redirect()->back();
    }
}
