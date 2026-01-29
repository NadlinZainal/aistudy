@extends('layouts.template')

@section('content')
<div class="container py-5">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><i class="fas fa-comments mr-2"></i> Direct Messages</h4>
            <a href="{{ route('friends.search') }}" class="btn btn-sm btn-light rounded-pill"><i class="fas fa-plus mr-1"></i> Add Friend</a>
        </div>
        <div class="card-body p-0">
            <div class="list-group list-group-flush">
                @forelse($users as $user)
                    <a href="{{ route('messages.show', $user->id) }}" class="list-group-item list-group-item-action d-flex align-items-center p-3">
                        <div class="mr-3">
                             @if($user->profile_photo_path)
                                <img src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="{{ $user->name }}" class="rounded-circle" width="50" height="50" style="object-fit: cover;">
                            @else
                                 <div class="rounded-circle bg-secondary text-white d-flex justify-content-center align-items-center" style="width: 50px; height: 50px;">
                                    <span style="font-size: 1.2rem; font-weight: bold;">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                </div>
                            @endif
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="mb-1 text-dark font-weight-bold">{{ $user->name }}</h5>
                            <p class="mb-0 text-muted small">Tap to start chatting</p>
                        </div>
                        <div>
                            <i class="fas fa-chevron-right text-muted"></i>
                        </div>
                    </a>
                @empty
                    <div class="text-center py-5">
                        <i class="fas fa-users-slash fa-3x text-muted mb-3 opacity-50"></i>
                        <p class="text-muted">You have no friends to message yet.</p>
                        <a href="{{ route('friends.search') }}" class="btn btn-primary btn-sm rounded-pill mt-2">Find Friends</a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
