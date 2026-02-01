<?php

namespace App\Http\Controllers;

use App\Models\Friendship;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendshipController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $friends = $user->getFriendsList();
        $pendingReceived = $user->receivedFriendRequests()->with('requester')->get();
        $pendingSent = $user->sentFriendRequests()->with('addressee')->get();

        return view('friendships.index', compact('friends', 'pendingReceived', 'pendingSent'));
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $users = [];

        if ($query) {
            $users = User::where('name', 'like', "%{$query}%")
                ->where('id', '!=', Auth::id())
                ->get();
        }

        return view('friendships.search', compact('users', 'query'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'addressee_id' => 'required|exists:users,id',
        ]);

        $addresseeId = $request->addressee_id;
        $userId = Auth::id();

        if ($userId == $addresseeId) {
            return back()->with('error', 'You cannot add yourself.');
        }

        // Check availability
        $exists = Friendship::where(function($q) use ($userId, $addresseeId) {
            $q->where('requester_id', $userId)->where('addressee_id', $addresseeId);
        })->orWhere(function($q) use ($userId, $addresseeId) {
            $q->where('requester_id', $addresseeId)->where('addressee_id', $userId);
        })->exists();

        if ($exists) {
            return back()->with('error', 'A request or friendship already exists.');
        }

        Friendship::create([
            'requester_id' => $userId,
            'addressee_id' => $addresseeId,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Friend request sent!');
    }

    public function update(Request $request, $id)
    {
        $friendship = Friendship::where('id', $id)
            ->where('addressee_id', Auth::id())
            ->firstOrFail();

        if ($request->action == 'accept') {
            $friendship->update(['status' => 'accepted']);
            return back()->with('success', 'Friend request accepted.');
        }

        return back();
    }

    public function destroy($id)
    {
        $friendship = Friendship::where('id', $id)
            ->where(function ($q) {
                $q->where('requester_id', Auth::id())
                  ->orWhere('addressee_id', Auth::id());
            })
            ->firstOrFail();

        $friendship->delete();

        return back()->with('success', 'Friendship/Request removed.');
    }
}
