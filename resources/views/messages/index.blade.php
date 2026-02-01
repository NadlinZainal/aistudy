@extends('layouts.template')

@section('content')
<div class="container py-4 fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="font-weight-bold mb-1" style="letter-spacing: -0.02em;">Messages</h2>
            <p class="text-muted mb-0">Connect and share knowledge with your friends</p>
        </div>
        <a href="{{ route('friends.search') }}" class="btn btn-primary rounded-pill px-4 shadow-sm font-weight-bold transition-all hover-translate-y">
            <i class="fas fa-plus mr-2"></i> New Chat
        </a>
    </div>

    <div class="card card-modern border-0 overflow-hidden shadow-sm">
        <div class="card-header bg-transparent border-bottom p-3">
            <div class="input-group bg-light rounded-pill px-3 py-1 border-0">
                <div class="input-group-prepend">
                    <span class="input-group-text bg-transparent border-0 text-muted">
                        <i class="fas fa-search"></i>
                    </span>
                </div>
                <input type="text" id="conversationSearch" class="form-control bg-transparent border-0 shadow-none text-sm" placeholder="Search conversations..." style="height: 40px;">
            </div>
        </div>
        
        <div class="card-body p-0">
            <div class="list-group list-group-flush" id="conversationList">
                @forelse($users as $user)
                    <a href="{{ route('messages.show', $user->id) }}" class="list-group-item list-group-item-action d-flex align-items-center p-3 border-0 conversation-item transition-all" style="border-bottom: 1px solid var(--border-color) !important;">
                        <div class="position-relative mr-3">
                            @if($user->profile_photo_path)
                                <img src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="{{ $user->name }}" class="rounded-circle shadow-sm" width="56" height="56" style="object-fit: cover; border: 2px solid var(--glass-border);">
                            @else
                                <div class="rounded-circle shadow-sm d-flex justify-content-center align-items-center" style="width: 56px; height: 56px; background: linear-gradient(135deg, var(--primary-color) 0%, #4f46e5 100%); border: 2px solid var(--glass-border);">
                                    <span class="text-white font-weight-bold" style="font-size: 1.2rem;">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                </div>
                            @endif
                            <div class="position-absolute bg-success rounded-circle" style="width: 12px; height: 12px; bottom: 2px; right: 2px; border: 2px solid var(--card-bg);"></div>
                        </div>
                        
                        <div class="flex-grow-1 min-width-0">
                            <div class="d-flex justify-content-between align-items-baseline">
                                <h6 class="mb-0 font-weight-bold text-truncate userName">{{ $user->name }}</h6>
                                <small class="text-muted ml-2">Chat Now</small>
                            </div>
                            <p class="mb-0 text-muted text-truncate small mt-1 opacity-75">Click to view message history</p>
                        </div>
                        
                        <div class="ml-3 d-flex align-items-center">
                            @if(isset($user->friendship_id))
                                <form action="{{ route('friends.destroy', $user->friendship_id) }}" method="POST" class="unfriend-form mr-2" onsubmit="return confirmUnfriend(event, '{{ $user->name }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-soft-danger btn-sm rounded-circle p-0 d-flex align-items-center justify-content-center transition-all unfriend-btn" style="width: 32px; height: 32px; opacity: 0;" title="Unfriend">
                                        <i class="fas fa-user-minus shadow-none" style="font-size: 0.8rem;"></i>
                                    </button>
                                </form>
                            @endif
                            <div class="opacity-0 item-arrow transition-all">
                                <i class="fas fa-chevron-right text-primary"></i>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="text-center py-5 px-3">
                        <div class="mb-4">
                            <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                <i class="fas fa-comments-alt fa-2x text-muted opacity-50"></i>
                            </div>
                        </div>
                        <h5 class="font-weight-bold">No active chats</h5>
                        <p class="text-muted mx-auto" style="max-width: 300px;">Start a conversation with your friends to see them here.</p>
                        <a href="{{ route('friends.search') }}" class="btn btn-outline-primary rounded-pill px-4 mt-2 font-weight-bold">
                            Find Friends
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<style>
    .conversation-item:hover {
        background-color: rgba(99, 102, 241, 0.04) !important;
        padding-left: 2rem !important;
    }
    
    .conversation-item:hover .item-arrow {
        opacity: 1;
        transform: translateX(5px);
    }
    
    .transition-all {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .hover-translate-y:hover {
        transform: translateY(-2px);
    }
    
    body.dark-mode .bg-light {
        background-color: rgba(255, 255, 255, 0.05) !important;
    }
    
    body.dark-mode .conversation-item:hover {
        background-color: rgba(255, 255, 255, 0.02) !important;
    }

    .conversation-item:hover .unfriend-btn {
        opacity: 1 !important;
    }
    
    .btn-soft-danger {
        background-color: rgba(220, 53, 69, 0.1);
        color: #dc3545;
        border: none;
    }
    .btn-soft-danger:hover {
        background-color: #dc3545;
        color: white;
    }
</style>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('conversationSearch');
        const listItems = document.querySelectorAll('.conversation-item');
        
        searchInput.addEventListener('keyup', function() {
            const query = searchInput.value.toLowerCase();
            
            listItems.forEach(item => {
                const userName = item.querySelector('.userName').textContent.toLowerCase();
                if (userName.includes(query)) {
                    item.classList.remove('d-none');
                    item.classList.add('d-flex');
                } else {
                    item.classList.add('d-none');
                    item.classList.remove('d-flex');
                }
            });
        });
    });

    function confirmUnfriend(event, name) {
        event.preventDefault();
        event.stopPropagation();
        
        const form = event.currentTarget;
        
        if (window.Swal) {
            Swal.fire({
                title: `Unfriend ${name}?`,
                text: "You will no longer be friends with this user.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, unfriend',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        } else {
            if (confirm(`Are you sure you want to unfriend ${name}?`)) {
                form.submit();
            }
        }
        return false;
    }
</script>
@endsection
@endsection
