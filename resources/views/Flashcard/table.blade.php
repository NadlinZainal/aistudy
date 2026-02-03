@php
    $gradients = [
        ['start' => '#4f46e5', 'end' => '#818cf8', 'badge' => 'primary'],   // Indigo
        ['start' => '#059669', 'end' => '#34d399', 'badge' => 'success'],   // Emerald
        ['start' => '#d97706', 'end' => '#fbbf24', 'badge' => 'warning'],   // Amber
        ['start' => '#db2777', 'end' => '#f472b6', 'badge' => 'danger'],    // Pink
        ['start' => '#0891b2', 'end' => '#22d3ee', 'badge' => 'info'],      // Cyan
        ['start' => '#7c3aed', 'end' => '#a78bfa', 'badge' => 'primary']    // Violet
    ];
@endphp

<div class="container-fluid mt-4 fade-in">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-5">
        <div class="mb-3 mb-md-0">
            <h2 class="font-weight-bold ml-2 mb-0 tracking-tight" style="font-size: 2.2rem;">
                {{ isset($is_favorites_page) ? 'My Favourites' : 'My Library' }}
            </h2>
            @if(request('search'))
                <div class="ml-2 mt-2">
                    <span class="badge glass text-muted rounded-pill px-3 py-2">
                        <i class="fas fa-search mr-2 text-primary"></i> Results for: "<strong>{{ request('search') }}</strong>"
                        <a href="{{ url()->current() }}" class="ml-2 text-danger hover-opacity">
                            <i class="fas fa-times-circle"></i>
                        </a>
                    </span>
                </div>
            @endif
        </div>
        <div class="d-flex align-items-center">
            <div class="input-group glass rounded-pill overflow-hidden px-2 py-1 mr-3" style="width: 320px; transition: all 0.3s; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
                <div class="input-group-prepend">
                    <span class="input-group-text border-0 bg-transparent text-muted pl-3">
                        <i class="fas fa-search"></i>
                    </span>
                </div>
                <input type="text" id="liveSearch" class="form-control border-0 bg-transparent py-2" placeholder="Search your library..." style="box-shadow: none;">
            </div>
            <a href="{{ route('flashcard.create') }}" class="btn btn-primary btn-soft shadow-lg px-4 pulse-primary">
                <i class="fas fa-plus mr-2"></i> New Deck
            </a>
        </div>
    </div>

    @if($flashcards->isEmpty())
        <div class="text-center py-5">
            <div class="glass p-5 rounded-3xl d-inline-block">
                <i class="fas fa-layer-group fa-3x text-muted mb-4 opacity-50"></i>
                <h3 class="text-muted font-weight-bold">Your library is empty</h3>
                <p class="text-muted mb-4">Start your learning journey by creating your first flashcard deck.</p>
                <a href="{{ route('flashcard.create') }}" class="btn btn-primary btn-soft shadow-lg px-5">
                    <i class="fas fa-magic mr-2"></i> Create Deck
                </a>
            </div>
        </div>
    @else
        <div id="noResults" class="text-center py-5 w-100" style="display: none;">
            <div class="glass p-5 rounded-3xl d-inline-block">
                <i class="fas fa-search fa-3x text-muted mb-4"></i>
                <h3 class="text-muted">No decks found matching your search</h3>
                <p class="text-muted mb-0">Try a different keyword or create a new deck.</p>
            </div>
        </div>
        <div class="row px-2" id="flashcardGrid">
            @foreach($flashcards as $index => $flashcard)
                @php
                    $style = $gradients[$index % count($gradients)];
                @endphp
                <div class="col-xl-4 col-md-6 mb-5 flashcard-item" data-title="{{ strtolower($flashcard->title) }}" data-description="{{ strtolower($flashcard->description) }}">
                    <div class="card h-100 card-modern glass p-3 {{ (($flashcard->cards && count($flashcard->cards) > 0) || $flashcard->status === 'completed') ? 'cursor-pointer' : '' }}" 
                         style="border-top: 6px solid {{ $style['start'] }}; border-radius: 24px;"
                         @if(($flashcard->cards && count($flashcard->cards) > 0) || $flashcard->status === 'completed')
                            onclick="if(!event.target.closest('button, a, .dropdown-menu, i')) window.location.href='{{ route('flashcard.show', $flashcard->id) }}'"
                         @endif
                         >
                        <div class="card-body d-flex flex-column p-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h5 class="font-weight-bold tracking-tight mb-0" style="font-size: 1.25rem;">{{ $flashcard->title }}</h5>
                                @if($flashcard->status === 'processing')
                                    <span class="badge badge-warning text-white rounded-pill px-3 py-1 shimmer">Processing</span>
                                @elseif($flashcard->status === 'completed')
                                    <span class="badge glass rounded-pill px-3 py-1" 
                                          style="color: {{ $style['start'] }} !important; font-weight: 600;">
                                        {{ count($flashcard->cards ?? []) }} Cards
                                    </span>
                                @else
                                    <span class="badge badge-danger rounded-pill px-3 py-1">Failed</span>
                                @endif
                            </div>
                            
                            <p class="card-text text-muted mb-4" style="font-size: 0.95rem; line-height: 1.6; height: 3em; overflow: hidden;">
                                {{ Str::limit($flashcard->description, 75, '...') }}
                            </p>

                            <!-- Progress Bar -->
                            @php
                                $progress = $flashcard->studyProgress->where('user_id', auth()->id())->first();
                                $percent = $progress ? ($progress->studied_cards / max($progress->total_cards, 1)) * 100 : 0;
                            @endphp
                            
                            <div class="mt-auto">
                                @if($progress && $percent > 0)
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <small class="font-weight-bold text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px; color: {{ $style['start'] }};">Progress</small>
                                        <small class="font-weight-bold" style="font-size: 0.75rem;">{{ round($percent) }}%</small>
                                    </div>
                                    <div class="progress mb-4 rounded-pill" style="height: 8px; background-color: rgba(0,0,0,0.03);">
                                        <div class="progress-bar rounded-pill" role="progressbar" style="width: {{ $percent }}%; background: linear-gradient(90deg, {{ $style['start'] }}, {{ $style['end'] }}); box-shadow: 0 2px 4px {{ $style['start'] }}40;" 
                                             aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                @else
                                    <div class="text-muted mb-4 font-italic small" style="opacity: 0.5;">Experience this deck for the first time</div>
                                @endif
                                
                                <div class="d-flex justify-content-between align-items-center flex-wrap pt-2" style="gap: 10px;">
                                    <div class="d-flex align-items-center">
                                        <!-- Favorite Button -->
                                        <button class="btn btn-icon glass mr-2 {{ $flashcard->is_favorite ? 'text-danger' : 'text-muted' }} btn-favorite" data-id="{{ $flashcard->id }}" style="width: 38px; height: 38px; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                            <i class="{{ $flashcard->is_favorite ? 'fas' : 'far' }} fa-heart"></i>
                                        </button>
                                        
                                        <!-- Actions Dropdown -->
                                        <div class="dropdown">
                                            <button class="btn btn-icon glass text-muted" type="button" data-toggle="dropdown" style="width: 38px; height: 38px; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-ellipsis-h"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right glass shadow-xl border-0 p-2" style="border-radius: 16px; min-width: 200px;">
                                                <a class="dropdown-item rounded-lg py-2 mb-1" href="{{ route('flashcard.show', $flashcard->id) }}">
                                                    <i class="fas fa-eye text-primary mr-3"></i> View Deck
                                                </a>
                                                <a class="dropdown-item rounded-lg py-2 mb-1" href="{{ route('flashcard.edit', $flashcard->id) }}">
                                                    <i class="fas fa-pen text-info mr-3"></i> Edit Deck
                                                </a>
                                                <button class="dropdown-item rounded-lg py-2 mb-1 btn-summarize" data-id="{{ $flashcard->id }}">
                                                    <i class="fas fa-bolt text-warning mr-3"></i> Smart Summary
                                                </button>
                                                <button class="dropdown-item rounded-lg py-2 mb-1 btn-share-flashcard" data-title="{{ $flashcard->title }}" data-id="{{ $flashcard->id }}">
                                                    <i class="fas fa-paper-plane text-success mr-3"></i> Send to Friend
                                                </button>
                                                <div class="dropdown-divider mx-2"></div>
                                                <a class="dropdown-item rounded-lg py-2 mb-1" href="{{ route('flashcard.export.pdf', $flashcard->id) }}">
                                                    <i class="fas fa-file-pdf text-danger mr-3"></i> PDF Export
                                                </a>
                                                <div class="dropdown-divider mx-2"></div>
                                                <button class="dropdown-item text-danger rounded-lg py-2 btn-delete-deck" data-id="{{ $flashcard->id }}" data-title="{{ $flashcard->title }}">
                                                    <i class="fas fa-trash-alt mr-3"></i> Delete
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    @if($flashcard->status === 'completed' && !empty($flashcard->cards))
                                        <div class="d-flex align-items-center">
                                            <a href="{{ route('flashcard.quiz', $flashcard->id) }}" class="btn btn-info btn-soft px-3 py-1 rounded-pill font-weight-bold mr-2 btn-quiz-loading" style="font-size: 0.85rem;" title="Test your knowledge">
                                                Quiz
                                            </a>
                                            <a href="{{ route('flashcard.study', $flashcard->id) }}" class="btn btn-primary btn-soft px-3 py-1 rounded-pill font-weight-bold" style="font-size: 0.85rem;">
                                                Study
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<style>
    .rounded-24 { border-radius: 24px !important; }
    .btn-icon:hover { transform: scale(1.1); background: rgba(59, 130, 246, 0.1) !important; color: #3b82f6 !important; }
    .tracking-tight { letter-spacing: -0.02em; }
    .pulse-primary {
        animation: pulse-border 2s infinite;
    }
    @keyframes pulse-border {
        0% { box-shadow: 0 0 0 0 rgba(99, 102, 241, 0.4); }
        70% { box-shadow: 0 0 0 10px rgba(99, 102, 241, 0); }
        100% { box-shadow: 0 0 0 0 rgba(99, 102, 241, 0); }
    }
    .shimmer {
        background: linear-gradient(90deg, #f59e0b 25%, #fbbf24 50%, #f59e0b 75%);
        background-size: 200% 100%;
        animation: shimmer 1.5s infinite;
    }
    @keyframes shimmer {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }
    
    /* Dropdown Visibility Fix */
    .card-modern {
        overflow: visible !important;
        position: relative;
    }
    .card-modern:hover, .card-modern:focus-within {
        z-index: 50 !important;
    }
    .dropdown-menu {
        z-index: 1060 !important; /* Ensure it's above everything else */
    }
</style>
<style>
    body.dark-mode .table thead th { background-color: rgba(255,255,255,0.02) !important; color: #f9fafb !important; }
    body.dark-mode .text-dark, body.dark-mode h2, body.dark-mode h5 { color: #f9fafb !important; }
    body.dark-mode .progress { background-color: rgba(255,255,255,0.05) !important; }
    body.dark-mode .badge-light { background-color: rgba(255,255,255,0.05) !important; color: #f9fafb !important; border-color: var(--border-color) !important; }
</style>
@php
    // Re-check h2 in table.blade.php since it has hardcoded style
@endphp
<script>
    // Ensure titles are visible
    if(document.body.classList.contains('dark-mode')) {
        document.querySelectorAll('h2, h5').forEach(el => el.style.color = '#f9fafb');
    }
</script>
