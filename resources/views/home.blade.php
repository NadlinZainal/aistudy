@extends('layouts.template')

@section('content')
<div class="container-fluid py-4 fade-in">
    <!-- Welcome Header -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card-modern p-5 text-white" style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); position: relative; border: none; overflow: hidden;">
                <div style="position: absolute; right: -50px; top: -50px; opacity: 0.15; transform: rotate(15deg);">
                    <i class="fas fa-brain" style="font-size: 320px;"></i>
                </div>
                <div class="position-relative z-index-1">
                    <span class="badge badge-light text-primary font-weight-bold mb-3 px-3 py-2 rounded-pill" style="backdrop-filter: blur(4px); background: rgba(255,255,255,0.9);">
                        🚀 Your Personal AI Tutor
                    </span>
                    <h1 class="display-4 font-weight-bold mb-3 tracking-tight">Level up your learning</h1>
                    <p class="lead mb-5" style="opacity: 0.9; max-width: 550px; line-height: 1.6;">
                        Master any subject with AI-generated flashcards and interactive quizzes. Your journey to excellence starts here.
                    </p>
                    <div class="d-flex flex-wrap">
                        <a href="{{ route('flashcard.index') }}" class="btn btn-light btn-lg rounded-pill px-5 font-weight-bold shadow-lg mr-3 mb-2" style="color: #4f46e5; border: none;">
                            <i class="fas fa-play-circle mr-2"></i> Open Library
                        </a>
                        <a href="{{ route('flashcard.create') }}" class="btn btn-outline-light btn-lg rounded-pill px-5 font-weight-bold mb-2" style="border-width: 2px;">
                            <i class="fas fa-plus mr-2"></i> Create Deck
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bento Grid Section -->
    <div class="row g-4">
        <!-- Main Stats Card (Bento Large) -->
        <div class="col-lg-8 mb-4">
            <div class="row h-100">
                <div class="col-md-6 mb-4 mb-md-0">
                    <div class="card-modern h-100 p-4 d-flex flex-column justify-content-between glass">
                        <div>
                            <div class="rounded-lg bg-primary-soft p-3 mb-4 d-inline-block shadow-sm">
                                <i class="fas fa-layer-group fa-2x text-primary"></i>
                            </div>
                            <h3 class="font-weight-bold mb-1">{{ $totalFlashcards }}</h3>
                            <p class="text-muted font-weight-medium mb-0">Total Study Decks</p>
                        </div>
                        <div class="mt-4 pt-4 border-top">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted small">Completion rate</span>
                                <span class="text-primary font-weight-bold small">85%</span>
                            </div>
                            <div class="progress rounded-pill" style="height: 6px; background-color: rgba(99, 102, 241, 0.1);">
                                <div class="progress-bar rounded-pill" style="width: 85%; background: var(--primary-color);"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card-modern h-100 p-4 d-flex flex-column justify-content-between glass">
                        <div>
                            <div class="rounded-lg bg-success-soft p-3 mb-4 d-inline-block shadow-sm">
                                <i class="fas fa-clock fa-2x text-success" style="color: #22c55e !important;"></i>
                            </div>
                            <h3 class="font-weight-bold mb-1">{{ $averageStudyTime }} <span class="h5 text-muted">min</span></h3>
                            <p class="text-muted font-weight-medium mb-0">Avg. Session Time</p>
                        </div>
                        <div class="mt-4">
                            <span class="badge bg-success-soft text-success rounded-pill px-3 py-1 small">
                                <i class="fas fa-arrow-up mr-1"></i> +12% from last week
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions Sidebar (Bento Tall) -->
        <div class="col-lg-4 mb-4">
            <div class="card-modern h-100 p-4 glass">
                <h5 class="font-weight-bold mb-4">Quick Shortcuts</h5>
                <div class="list-group list-group-flush border-0">
                    <a href="{{ route('flashcard.create') }}" class="list-group-item list-group-item-action border-0 px-0 py-3 bg-transparent d-flex align-items-center">
                        <div class="rounded-circle bg-primary-soft p-2 mr-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-plus text-primary"></i>
                        </div>
                        <span class="font-weight-bold">Create New Deck</span>
                        <i class="fas fa-chevron-right ml-auto text-muted small"></i>
                    </a>
                    <a href="{{ route('flashcard.index') }}" class="list-group-item list-group-item-action border-0 px-0 py-3 bg-transparent d-flex align-items-center">
                        <div class="rounded-circle bg-info-soft p-2 mr-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; background-color: #e0f2fe; color: #0284c7;">
                            <i class="fas fa-tasks text-info" style="color: #0ea5e9 !important;"></i>
                        </div>
                        <span class="font-weight-bold">Take a Quiz</span>
                        <i class="fas fa-chevron-right ml-auto text-muted small"></i>
                    </a>
                    <a href="{{ route('flashcard.favorites') }}" class="list-group-item list-group-item-action border-0 px-0 py-3 bg-transparent d-flex align-items-center">
                        <div class="rounded-circle bg-danger-soft p-2 mr-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; background-color: #fee2e2; color: #dc2626;">
                            <i class="fas fa-heart text-danger"></i>
                        </div>
                        <span class="font-weight-bold">My Favourites</span>
                        <i class="fas fa-chevron-right ml-auto text-muted small"></i>
                    </a>
                    <a href="{{ route('flashcard.quiz-history') }}" class="list-group-item list-group-item-action border-0 px-0 py-3 bg-transparent d-flex align-items-center">
                        <div class="rounded-circle bg-warning-soft p-2 mr-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; background-color: #fef3c7; color: #d97706;">
                            <i class="fas fa-history text-warning" style="color: #f59e0b !important;"></i>
                        </div>
                        <span class="font-weight-bold">Quiz History</span>
                        <i class="fas fa-chevron-right ml-auto text-muted small"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- AI Study Plan Section -->
    <div class="row pt-2">
        <div class="col-12">
            <div class="card-modern p-4 glass h-100 overflow-hidden" style="border-left: 6px solid #6366f1;">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="font-weight-bold mb-1">AI-Generated Study Plan</h4>
                        <p class="text-muted small mb-0">Personalized schedule based on your progress and performance</p>
                    </div>
                    <span class="badge badge-primary rounded-pill px-3 py-2">Daily Goal: 3 Decks</span>
                </div>

                <div class="row">
                    @forelse($studyPlan as $deck)
                        @php 
                            $progress = $deck->studyProgress->first();
                            $percent = $progress ? ($progress->studied_cards / max($progress->total_cards, 1)) * 100 : 0;
                        @endphp
                        <div class="col-md-4 mb-3">
                            <div class="p-3 bg-white rounded-xl shadow-sm border h-100 hover-up transition-all cursor-pointer" onclick="window.location.href='{{ route('flashcard.study', $deck->id) }}'">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="rounded-circle bg-primary-soft p-2 mr-3" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-book-reader text-primary small"></i>
                                    </div>
                                    <h6 class="font-weight-bold mb-0 text-truncate">{{ $deck->title }}</h6>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-muted extra-small">Completion</span>
                                    <span class="font-weight-bold extra-small">{{ round($percent) }}%</span>
                                </div>
                                <div class="progress rounded-pill" style="height: 5px; background-color: rgba(99, 102, 241, 0.1);">
                                    <div class="progress-bar rounded-pill" style="width: {{ $percent }}%; background: #6366f1;"></div>
                                </div>
                                <div class="mt-3">
                                    <button class="btn btn-sm btn-soft-primary btn-block rounded-lg py-2">
                                        {{ $percent == 0 ? 'Start Learning' : 'Continue' }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 py-4 text-center">
                            <i class="fas fa-hourglass-start fa-2x text-muted mb-3 opacity-25"></i>
                            <p class="text-muted">Create some decks to generate your AI study plan!</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div></div>

<style>
    .bg-primary-soft { background-color: #e0e7ff; color: #6366f1; }
    .bg-success-soft { background-color: #dcfce7; color: #166534; }
    .bg-warning-soft { background-color: #fef3c7; color: #92400e; }

    body.dark-mode .bg-primary-soft { background-color: rgba(99, 102, 241, 0.2); color: #a5b4fc; }
    body.dark-mode .bg-success-soft { background-color: rgba(34, 197, 94, 0.2); color: #86efac; }
    body.dark-mode .bg-warning-soft { background-color: rgba(245, 158, 11, 0.2); color: #fcd34d; }

    .border-left-primary { border-left: 5px solid #6366f1; }
    .border-left-success { border-left: 5px solid #22c55e; }
    .border-left-warning { border-left: 5px solid #f59e0b; }
    .hover-up:hover { transform: translateY(-5px); }
    .tracking-wider { letter-spacing: 0.05em; }

    .extra-small { font-size: 0.7rem; }
    .rounded-xl { border-radius: 16px; }
    .btn-soft-primary { background-color: #eef2ff; color: #4f46e5; border: none; }
    .btn-soft-primary:hover { background-color: #e0e7ff; color: #4338ca; }
    .transition-all { transition: all 0.3s ease; }
    .cursor-pointer { cursor: pointer; }
    
    body.dark-mode .text-dark { color: #f9fafb !important; }
    body.dark-mode h4, body.dark-mode h2 { color: #f9fafb !important; }
</style>
@endsection
