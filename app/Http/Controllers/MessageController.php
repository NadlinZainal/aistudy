<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Fetch friends and include the friendship ID for the current user
        $users = $user->friends()->get()->map(function($friend) use ($user) {
            $friendship = \App\Models\Friendship::where(function($q) use ($user, $friend) {
                $q->where('requester_id', $user->id)->where('addressee_id', $friend->id);
            })->orWhere(function($q) use ($user, $friend) {
                $q->where('requester_id', $friend->id)->where('addressee_id', $user->id);
            })->first();
            
            $friend->friendship_id = $friendship ? $friendship->id : null;
            return $friend;
        });

        return view('messages.index', compact('users'));
    }

    public function show(User $user)
    {
        if (!Auth::user()->isFriendWith($user->id)) {
            return redirect()->route('messages.index')->with('error', 'You must be friends to message this user.');
        }

        $messages = Message::where(function($query) use ($user) {
            $query->where('sender_id', Auth::id())
                  ->where('receiver_id', $user->id);
        })->orWhere(function($query) use ($user) {
            $query->where('sender_id', $user->id)
                  ->where('receiver_id', Auth::id());
        })->orderBy('created_at', 'asc')->with('flashcard')->get();

        return view('messages.show', compact('user', 'messages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'content' => 'required|string',
            'flashcard_id' => 'nullable|exists:flashcard,id',
        ]);
        
        if (!Auth::user()->isFriendWith($request->receiver_id)) {
            return back()->with('error', 'You must be friends to message this user.');
        }

        Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'content' => $request->content,
            'flashcard_id' => $request->flashcard_id,
        ]);

        return back();
    }
}
