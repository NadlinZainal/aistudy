@extends('layouts.template')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm border-0" style="height: 85vh; display: flex; flex-direction: column;">
        
        <!-- Header -->
        <div class="card-header bg-white border-bottom py-3 d-flex align-items-center">
             <a href="{{ route('messages.index') }}" class="btn btn-outline-secondary btn-sm mr-3 rounded-circle" style="width: 35px; height: 35px; padding: 0; line-height: 35px;">
                <i class="fas fa-arrow-left"></i>
             </a>
             <div class="d-flex align-items-center">
                <div class="mr-3">
                    @if($user->profile_photo_path)
                         <img src="{{ asset('storage/' . $user->profile_photo_path) }}" class="rounded-circle" width="40" height="40" style="object-fit: cover;">
                     @else
                         <div class="rounded-circle bg-primary text-white d-flex justify-content-center align-items-center" style="width: 40px; height: 40px;">
                             {{ strtoupper(substr($user->name, 0, 1)) }}
                         </div>
                     @endif
                </div>
                <h5 class="mb-0 font-weight-bold">{{ $user->name }}</h5>
             </div>
        </div>

        <!-- Messages Area -->
        <div class="card-body overflow-auto bg-light" id="chat-messages" style="flex: 1; padding: 20px;">
            @forelse($messages as $message)
                <div class="d-flex mb-3 {{ $message->sender_id === auth()->id() ? 'justify-content-end' : 'justify-content-start' }}">
                    @if($message->sender_id !== auth()->id())
                        <div class="mr-2 mt-1">
                             @if($user->profile_photo_path)
                                <img src="{{ asset('storage/' . $user->profile_photo_path) }}" class="rounded-circle" width="30" height="30" style="object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-secondary text-white d-flex justify-content-center align-items-center" style="width: 30px; height: 30px; font-size: 0.7rem;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                    @endif

                    <div class="card border-0 shadow-sm {{ $message->sender_id === auth()->id() ? 'bg-primary text-white' : 'bg-white text-dark' }}" 
                         style="max-width: 75%; border-radius: 18px; {{ $message->sender_id === auth()->id() ? 'border-bottom-right-radius: 4px;' : 'border-bottom-left-radius: 4px;' }}">
                        <div class="card-body py-2 px-3">
                            <p class="mb-1" style="font-size: 0.95rem; line-height: 1.4;">{{ $message->content }}</p>

                            @if($message->flashcard)
                                <div class="mt-2 mb-1 p-2 bg-light rounded shadow-sm border" style="max-width: 250px;">
                                    <div class="d-flex align-items-center mb-1">
                                        <div class="rounded bg-primary text-white d-flex align-items-center justify-content-center mr-2" style="width: 30px; height: 30px;">
                                            <i class="fas fa-layer-group" style="font-size: 0.8rem;"></i>
                                        </div>
                                        <div class="text-truncate">
                                            <strong class="d-block text-dark text-truncate" style="font-size: 0.85rem;">{{ $message->flashcard->title }}</strong>
                                            <small class="text-muted text-dark">{{ is_array($message->flashcard->cards) ? count($message->flashcard->cards) : 0 }} Cards</small>
                                        </div>
                                    </div>
                                    <a href="{{ route('flashcard.study', $message->flashcard->id) }}" class="btn btn-primary btn-sm btn-block rounded-pill" style="font-size: 0.75rem;">
                                        Start Studying
                                    </a>
                                </div>
                            @endif

                            <div class="d-flex align-items-center {{ $message->sender_id === auth()->id() ? 'justify-content-end text-white-50' : 'text-muted' }}">
                                <small style="font-size: 0.7rem;" title="{{ $message->created_at->format('d M Y H:i') }}">
                                    {{ $message->created_at->diffForHumans() }}
                                </small>
                                @if($message->sender_id === auth()->id())
                                    <i class="fas fa-check-double ml-1" style="font-size: 0.6rem;"></i>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5 text-muted">
                    <div class="mb-3">
                        <i class="fas fa-comments fa-3x text-muted opacity-50"></i>
                    </div>
                    <p>No messages yet. Say hello!</p>
                </div>
            @endforelse
        </div>

        <!-- Input Area -->
        <div class="card-footer bg-white border-top p-3">
            <form action="{{ route('messages.store') }}" method="POST" class="d-flex align-items-center">
                @csrf
                <input type="hidden" name="receiver_id" value="{{ $user->id }}">
                <div class="input-group">
                    <input type="text" name="content" class="form-control rounded-pill border-0 bg-light py-2 px-3" placeholder="Message..." required autocomplete="off" style="box-shadow: none;">
                    <div class="input-group-append ml-2">
                        <button type="submit" class="btn btn-primary rounded-circle d-flex justify-content-center align-items-center" style="width: 40px; height: 40px;">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const chatContainer = document.getElementById('chat-messages');
        chatContainer.scrollTop = chatContainer.scrollHeight;
    });
</script>
@endsection
