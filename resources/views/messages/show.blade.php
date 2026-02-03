@extends('layouts.template')

@section('content')
<div class="container py-2 fade-in">
    <div class="card shadow-lg border-0 card-modern glass-container" style="height: 88vh; display: flex; flex-direction: column; overflow: hidden; background: var(--card-bg);">
        
        <!-- Header -->
        <div class="card-header bg-transparent border-bottom py-3 px-4 d-flex align-items-center justify-content-between">
             <div class="d-flex align-items-center">
                <a href="{{ route('messages.index') }}" class="btn btn-soft mr-3 p-0 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border-radius: 12px; background: rgba(99, 102, 241, 0.08);">
                    <i class="fas fa-arrow-left text-primary"></i>
                </a>
                <div class="d-flex align-items-center">
                    <div class="position-relative mr-3">
                        @if($user->profile_photo_path)
                             <img src="{{ asset('storage/' . $user->profile_photo_path) }}" class="rounded-circle shadow-sm" width="44" height="44" style="object-fit: cover; border: 2px solid var(--glass-border);">
                         @else
                             <div class="rounded-circle d-flex justify-content-center align-items-center shadow-sm" style="width: 44px; height: 44px; background: linear-gradient(135deg, var(--primary-color) 0%, #4f46e5 100%);">
                                 <span class="text-white font-weight-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                             </div>
                         @endif
                         <div class="position-absolute bg-success rounded-circle shadow-sm" style="width: 12px; height: 12px; bottom: 0; right: 0; border: 2px solid var(--card-bg);"></div>
                    </div>
                    <div>
                        <h6 class="mb-0 font-weight-bold" style="letter-spacing: -0.01em;">{{ $user->name }}</h6>
                        <small class="text-success font-weight-500 opacity-75">Online</small>
                    </div>
                </div>
             </div>
        </div>

        <!-- Messages Area -->
        <div class="card-body overflow-auto chat-container" id="chat-messages" style="flex: 1; padding: 25px; background-image: radial-gradient(var(--border-color) 0.5px, transparent 0.5px); background-size: 20px 20px;">
            @forelse($messages as $message)
                @php
                    $isMine = $message->sender_id === auth()->id();
                @endphp
                <div class="d-flex mb-4 {{ $isMine ? 'justify-content-end' : 'justify-content-start' }} animate-slide-in">
                    @if(!$isMine)
                        <div class="mr-2 mt-auto pb-2">
                             @if($user->profile_photo_path)
                                <img src="{{ asset('storage/' . $user->profile_photo_path) }}" class="rounded-circle shadow-sm" width="28" height="28" style="object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-secondary text-white d-flex justify-content-center align-items-center" style="width: 28px; height: 28px; font-size: 0.65rem;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                    @endif

                    <div class="message-bubble-wrapper" style="max-width: 80%;">
                        <div class="message-bubble shadow-sm {{ $isMine ? 'bubble-mine' : 'bubble-theirs' }}">
                            @if($message->content)
                                <p class="mb-0 message-text">{{ $message->content }}</p>
                            @endif

                            @if($message->flashcard)
                                <div class="shared-deck-card mt-3 animate-pop-in {{ $isMine ? 'deck-mine' : 'deck-theirs' }}">
                                    <div class="d-flex align-items-center p-3">
                                        <div class="deck-icon-wrapper mr-3 shadow-md">
                                            <i class="fas fa-layer-group"></i>
                                        </div>
                                        <div class="flex-grow-1 min-width-0">
                                            <strong class="d-block deck-title text-truncate">{{ $message->flashcard->title }}</strong>
                                            <span class="deck-meta">{{ is_array($message->flashcard->cards) ? count($message->flashcard->cards) : 0 }} Smart Cards</span>
                                        </div>
                                    </div>
                                    <div class="deck-footer p-2 d-flex gap-2">
                                        <a href="{{ route('flashcard.study', $message->flashcard->id) }}" class="btn btn-deck shadow-sm flex-grow-1">
                                            <i class="fas fa-play mr-1"></i> Study
                                        </a>
                                        @if($message->flashcard->user_id !== auth()->id())
                                            <button onclick="cloneDeck({{ $message->flashcard->id }}, this)" class="btn btn-deck-save shadow-sm">
                                                <i class="fas fa-download"></i>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                        <div class="d-flex align-items-center mt-1 px-1 {{ $isMine ? 'justify-content-end text-right' : 'text-left' }}">
                            <small class="text-time">{{ $message->created_at->diffForHumans() }}</small>
                            @if($isMine)
                                <i class="fas fa-check-double ml-1 text-primary" style="font-size: 0.6rem; opacity: 0.8;"></i>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <div class="empty-state-icon mb-4">
                        <i class="fas fa-paper-plane fa-3x text-primary opacity-25"></i>
                    </div>
                    <h5 class="font-weight-bold">No messages here yet</h5>
                    <p class="text-muted">Start the conversation by sending a message below.</p>
                </div>
            @endforelse
        </div>

        <!-- Input Area -->
        <div class="card-footer bg-transparent border-top p-4">
            <form action="{{ route('messages.store') }}" method="POST" class="message-form" id="messageInputForm">
                @csrf
                <input type="hidden" name="receiver_id" value="{{ $user->id }}">
                <div class="input-container glass shadow-md rounded-pill d-flex align-items-center pr-2">
                    <button type="button" class="btn btn-soft p-0 rounded-circle ml-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="fas fa-plus text-muted"></i>
                    </button>
                    <input type="text" name="content" class="form-control bg-transparent border-0 shadow-none px-3" placeholder="Type a message..." required autocomplete="off" style="height: 50px; font-size: 0.95rem;">
                    <button type="submit" class="btn btn-primary rounded-circle shadow-md d-flex justify-content-center align-items-center transition-all hover-scale" style="width: 42px; height: 42px; background: linear-gradient(135deg, var(--primary-color) 0%, #4f46e5 100%); border: none;">
                        <i class="fas fa-paper-plane" style="font-size: 0.9rem;"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* Premium Chat Styles */
    .glass-container {
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid var(--glass-border) !important;
    }

    .chat-container {
        scrollbar-width: thin;
        scrollbar-color: var(--primary-color) transparent;
    }
    
    .chat-container::-webkit-scrollbar {
        width: 4px;
    }
    .chat-container::-webkit-scrollbar-thumb {
        background-color: var(--primary-color);
        border-radius: 20px;
    }

    .message-bubble {
        padding: 12px 18px;
        position: relative;
        transition: transform 0.2s ease;
    }

    .bubble-mine {
        background: linear-gradient(135deg, var(--primary-color) 0%, #4f46e5 100%);
        color: white;
        border-radius: 22px 22px 4px 22px;
        box-shadow: 0 4px 15px rgba(99, 102, 241, 0.2);
    }

    .bubble-theirs {
        background: var(--card-bg);
        color: var(--text-main);
        border-radius: 22px 22px 22px 4px;
        border: 1px solid var(--border-color);
    }
    
    body.dark-mode .bubble-theirs {
        background: rgba(255, 255, 255, 0.05);
    }

    .message-text {
        font-size: 0.95rem;
        line-height: 1.5;
        letter-spacing: 0.01em;
    }

    .text-time {
        font-size: 0.68rem;
        opacity: 0.6;
        font-weight: 500;
        color: var(--text-muted);
    }

    /* Shared Deck Card - Rich UI */
    .shared-deck-card {
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.1);
        min-width: 240px;
    }
    
    .deck-mine {
        background: rgba(255, 255, 255, 0.12);
        color: white;
    }
    
    .deck-theirs {
        background: var(--bg-light);
        color: var(--text-main);
        border: 1px solid var(--border-color);
    }

    .deck-icon-wrapper {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: white;
        color: var(--primary-color);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
    }
    
    body.dark-mode .deck-icon-wrapper {
        background: var(--primary-color);
        color: white;
    }

    .deck-title {
        font-size: 0.9rem;
        font-weight: 700;
        margin-bottom: 2px;
    }

    .deck-meta {
        font-size: 0.75rem;
        opacity: 0.8;
    }

    .btn-deck {
        background: white;
        color: var(--primary-color);
        border-radius: 10px;
        font-size: 0.8rem;
        font-weight: 700;
        padding: 8px;
        border: none;
        transition: all 0.3s;
    }
    
    .btn-deck-save {
        background: rgba(255, 255, 255, 0.15);
        color: currentColor;
        border-radius: 10px;
        width: 36px;
        border: none;
        transition: all 0.3s;
    }

    .btn-deck:hover, .btn-deck-save:hover {
        transform: scale(1.05);
        opacity: 0.9;
    }

    /* Animations */
    .animate-slide-in {
        animation: slideUp 0.4s ease-out forwards;
    }
    
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .animate-pop-in {
        animation: pop 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
    }
    
    @keyframes pop {
        from { transform: scale(0.95); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }
    
    .hover-scale {
        transition: transform 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .hover-scale:hover {
        transform: scale(1.1);
    }

    .input-container {
        border: 1px solid var(--glass-border);
        transition: all 0.3s;
    }
    
    .input-container:focus-within {
        border-color: var(--primary-color);
        box-shadow: 0 4px 20px rgba(99, 102, 241, 0.1);
    }
</style>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const chatContainer = document.getElementById('chat-messages');
        chatContainer.scrollTop = chatContainer.scrollHeight;
        
        // Form submission animation
        const form = document.getElementById('messageInputForm');
        form.addEventListener('submit', function() {
            setTimeout(() => {
                chatContainer.scrollTop = chatContainer.scrollHeight;
            }, 100);
        });
    });

    function cloneDeck(flashcardId, btn) {
        if (!confirm('Add this deck to your library?')) return;
        
        const originalContent = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        btn.disabled = true;

        fetch(`/flashcard/${flashcardId}/clone`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { throw err; });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                if (window.Swal) {
                    Swal.fire({
                        title: 'Success!',
                        text: data.message,
                        icon: 'success',
                        showCancelButton: true,
                        confirmButtonText: 'View Library',
                        cancelButtonText: 'Stay Here'
                    }).then((result) => {
                        btn.innerHTML = '<i class="fas fa-check"></i>';
                        if (result.isConfirmed) {
                            window.location.href = data.redirect;
                        }
                    });
                } else {
                    alert(data.message);
                    window.location.href = data.redirect;
                }
            } else {
                throw data;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            const msg = error.message || 'Failed to add deck.';
            if (window.Swal) {
                Swal.fire('Error', msg, 'error');
            } else {
                alert(msg);
            }
            btn.innerHTML = originalContent;
            btn.disabled = false;
        });
    }
</script>
@endsection
@endsection
