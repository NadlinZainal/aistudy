@extends('layouts.template')

@section('content')
<div class="container-fluid mt-4 fade-in">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h2 class="font-weight-bold ml-2 mb-0 tracking-tight" style="font-size: 2.2rem;">Quiz History</h2>
            <p class="text-muted ml-2 mt-1">Track your progress and review previous attempts</p>
        </div>
        <a href="{{ route('flashcard.index') }}" class="btn btn-primary btn-soft shadow-lg px-4 pulse-primary">
            <i class="fas fa-play mr-2"></i> New Quiz
        </a>
    </div>

    <div class="card card-modern glass shadow-2xl overflow-hidden" style="border-radius: 24px;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="glass" style="border-bottom: 2px solid var(--border-color);">
                        <tr>
                            <th class="border-0 px-4 py-4 text-uppercase small font-weight-bold tracking-wider">Deck Title</th>
                            <th class="border-0 px-4 py-4 text-center text-uppercase small font-weight-bold tracking-wider">Score</th>
                            <th class="border-0 px-4 py-4 text-center text-uppercase small font-weight-bold tracking-wider">Correct Answers</th>
                            <th class="border-0 px-4 py-4 text-center text-uppercase small font-weight-bold tracking-wider">Date</th>
                            <th class="border-0 px-4 py-4 text-right text-uppercase small font-weight-bold tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($results as $result)
                            <tr style="transition: all 0.3s;">
                                <td class="px-4 py-4 align-middle border-0">
                                    <div class="d-flex align-items-center">
                                        <div class="mr-3 p-3 rounded-lg bg-primary-soft shadow-sm">
                                            <i class="fas fa-graduation-cap text-primary"></i>
                                        </div>
                                        <div>
                                            <span class="font-weight-bold h6 mb-0 d-block">{{ $result->flashcard->title }}</span>
                                            <small class="text-muted">{{ count($result->flashcard->cards ?? []) }} cards</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4 align-middle text-center border-0">
                                    <div class="d-inline-flex align-items-center">
                                        <div class="rounded-pill px-4 py-2 font-weight-bold" 
                                             style="background-color: {{ $result->score >= 80 ? 'rgba(34, 197, 94, 0.1)' : ($result->score >= 60 ? 'rgba(245, 158, 11, 0.1)' : 'rgba(239, 68, 68, 0.1)') }}; 
                                                    color: {{ $result->score >= 80 ? '#16a34a' : ($result->score >= 60 ? '#d97706' : '#dc2626') }};
                                                    font-size: 0.9rem;">
                                            {{ round($result->score) }}%
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4 align-middle text-center border-0">
                                    <span class="font-weight-bold h6 mb-0" style="opacity: 0.8;">{{ $result->correct_count }}</span>
                                    <span class="text-muted small"> / {{ $result->total_questions }}</span>
                                </td>
                                <td class="px-4 py-4 align-middle text-center border-0">
                                    <div class="d-flex flex-column align-items-center">
                                        <span class="font-weight-medium small">{{ $result->created_at->format('M d, Y') }}</span>
                                        <small class="text-muted">{{ $result->created_at->format('h:i A') }}</small>
                                    </div>
                                </td>
                                <td class="px-4 py-4 align-middle text-right border-0">
                                    <a href="{{ route('flashcard.quiz', $result->flashcard_id) }}" class="btn glass btn-icon rounded-pill px-4 font-weight-bold text-primary">
                                        <i class="fas fa-redo-alt mr-2"></i> Retake
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-5 text-center">
                                    <div class="mb-4">
                                        <div class="d-inline-block p-4 rounded-circle glass mb-3">
                                            <i class="fas fa-history fa-4x text-muted" style="opacity: 0.2;"></i>
                                        </div>
                                    </div>
                                    <h4 class="font-weight-bold">No history available</h4>
                                    <p class="text-muted mx-auto" style="max-width: 400px;">Review your quiz performance here after you've completed a session.</p>
                                    <a href="{{ route('flashcard.index') }}" class="btn btn-primary btn-soft rounded-pill px-5 mt-3">Start Studying</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .tracking-wider { letter-spacing: 0.05em; }
    .btn-icon:hover { background-color: var(--primary-color) !important; color: white !important; transform: translateY(-2px); }
</style>
@endsection
