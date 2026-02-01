@extends('layouts.template')

@section('content')
<div class="container py-4">
    <div class="card border-0 shadow-sm" style="min-height: 400px;">
        <div class="card-body">
            <h3 class="font-weight-bold mb-4">Find New Friends</h3>
            
            <form action="{{ route('friends.search') }}" method="GET" class="mb-5">
                <div class="input-group input-group-lg">
                    <input type="text" name="query" class="form-control rounded-pill border-0 bg-light pl-4" placeholder="Search by name..." value="{{ request('query') }}" style="box-shadow: none;">
                    <div class="input-group-append">
                        <button class="btn btn-primary rounded-pill px-4 ml-2" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>

            @if(request('query'))
                <h5 class="mb-3 text-muted">Results for "{{ request('query') }}"</h5>
                <div class="row">
                    @forelse($users as $user)
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center p-3 glass border rounded-lg shadow-sm">
                                <div class="mr-3">
                                     @if($user->profile_photo_path)
                                        <img src="{{ asset('storage/' . $user->profile_photo_path) }}" class="rounded-circle" width="50" height="50" style="object-fit: cover;">
                                    @else
                                        <div class="rounded-circle bg-secondary text-white d-flex justify-content-center align-items-center" style="width: 50px; height: 50px;">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="mb-1 font-weight-bold">{{ $user->name }}</h5>
                                </div>
                                <div>
                                    @if(auth()->user()->isFriendWith($user->id))
                                        <button class="btn btn-light btn-sm rounded-pill text-success" disabled>
                                            <i class="fas fa-check mr-1"></i> Friend
                                        </button>
                                    @elseif(auth()->user()->hasPendingRequestWith($user->id))
                                        <button class="btn btn-light btn-sm rounded-pill text-muted" disabled>
                                            <i class="fas fa-clock mr-1"></i> Pending
                                        </button>
                                    @else
                                        <form action="{{ route('friends.store') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="addressee_id" value="{{ $user->id }}">
                                            <button type="submit" class="btn btn-primary btn-sm rounded-pill px-3">
                                                <i class="fas fa-user-plus mr-1"></i> Add
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-4">
                            <p class="text-muted">No users found.</p>
                        </div>
                    @endforelse
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
