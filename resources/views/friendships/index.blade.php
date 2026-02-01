@extends('layouts.template')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="font-weight-bold mb-0">My Friends</h2>
        </div>
        <div class="col-md-4 text-right">
            <a href="{{ route('friends.search') }}" class="btn btn-primary rounded-pill shadow-sm">
                <i class="fas fa-user-plus mr-2"></i> Find Friends
            </a>
        </div>
    </div>

    <!-- Incoming Requests -->
    @if($pendingReceived->count() > 0)
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent border-bottom">
                <h5 class="mb-0 text-primary"><i class="fas fa-inbox mr-2"></i> Friend Requests</h5>
            </div>
            <div class="list-group list-group-flush">
                @foreach($pendingReceived as $request)
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            @if($request->requester->profile_photo_path)
                                <img src="{{ asset('storage/' . $request->requester->profile_photo_path) }}" class="rounded-circle mr-3" width="40" height="40" style="object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-light d-flex justify-content-center align-items-center mr-3" style="width: 40px; height: 40px;">
                                    <i class="fas fa-user text-muted"></i>
                                </div>
                            @endif
                            <span class="font-weight-bold">{{ $request->requester->name }}</span>
                        </div>
                        <div>
                            <form action="{{ route('friends.update', $request->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="action" value="accept">
                                <button type="submit" class="btn btn-success btn-sm rounded-pill px-3">Accept</button>
                            </form>
                            <form action="{{ route('friends.destroy', $request->id) }}" method="POST" class="d-inline ml-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3">Decline</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Friends List -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            @if($friends->count() > 0)
                <div class="list-group list-group-flush">
                    @foreach($friends as $friend)
                        <div class="list-group-item d-flex justify-content-between align-items-center p-3">
                            <div class="d-flex align-items-center">
                                @if($friend->profile_photo_path)
                                    <img src="{{ asset('storage/' . $friend->profile_photo_path) }}" class="rounded-circle mr-3" width="50" height="50" style="object-fit: cover;">
                                @else
                                    <div class="rounded-circle bg-secondary text-white d-flex justify-content-center align-items-center mr-3" style="width: 50px; height: 50px;">
                                        {{ strtoupper(substr($friend->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <h5 class="mb-0 font-weight-bold">{{ $friend->name }}</h5>
                                    <small class="text-muted">Friend</small>
                                </div>
                            </div>
                            <div>
                                <a href="{{ route('messages.show', $friend->id) }}" class="btn btn-primary btn-sm rounded-pill px-3 mr-2">
                                    <i class="fas fa-comment mr-1"></i> Message
                                </a>
                                <!-- Unfriend Button -->
                                @php
                                    // Find the friendship ID
                                    $friendshipId = App\Models\Friendship::where(function($q) use ($friend) {
                                        $q->where('requester_id', Auth::id())->where('addressee_id', $friend->id);
                                    })->orWhere(function($q) use ($friend) {
                                        $q->where('requester_id', $friend->id)->where('addressee_id', Auth::id());
                                    })->value('id');
                                @endphp
                                <form action="{{ route('friends.destroy', $friendshipId) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to remove this friend?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-secondary btn-sm rounded-circle" title="Unfriend">
                                        <i class="fas fa-user-minus"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-user-friends fa-3x text-muted mb-3 opacity-50"></i>
                    <p class="text-muted mb-3">You haven't added any friends yet.</p>
                    <a href="{{ route('friends.search') }}" class="btn btn-primary rounded-pill px-4">Find Friends</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
