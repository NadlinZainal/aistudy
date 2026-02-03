@extends('layouts.template')

@section('content')
<div class="container-fluid mt-4 fade-in">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-5 px-3">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 mb-2">
                    <li class="breadcrumb-item"><a href="{{ route('flashcard.index') }}" class="text-primary">Library</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Deck Details</li>
                </ol>
            </nav>
            <h2 class="font-weight-bold tracking-tight mb-0" style="font-size: 2.5rem;">
                {{ $flashcard->title }}
            </h2>
            <p class="text-muted mt-2" style="font-size: 1.1rem; max-width: 600px;">
                {{ $flashcard->description }}
            </p>
        </div>
        <div class="d-flex align-items-center mt-3 mt-md-0">
            @if($flashcard->user_id === auth()->id())
            <a href="{{ route('flashcard.edit', $flashcard->id) }}" class="btn btn-soft-info px-4 py-2 rounded-pill mr-2">
                <i class="fas fa-edit mr-2"></i> Edit Deck Info
            </a>
            @else
            <form action="{{ route('flashcard.clone', $flashcard->id) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-soft-success px-4 py-2 rounded-pill mr-2">
                    <i class="fas fa-plus-circle mr-2"></i> Add to Library
                </button>
            </form>
            @endif
            @if($flashcard->status === 'completed' && !empty($flashcard->cards))
                <button class="btn btn-soft-warning px-4 py-2 rounded-pill mr-2 btn-summarize" data-id="{{ $flashcard->id }}">
                    <i class="fas fa-bolt mr-2"></i> Summary
                </button>
                <a href="{{ route('flashcard.study', $flashcard->id) }}" class="btn btn-primary shadow-lg px-4 py-2 rounded-pill">
                    <i class="fas fa-play mr-2"></i> Start Studying
                </a>
            @endif
        </div>
    </div>

    <div class="row px-3">
        <!-- Deck Sidebar Info -->
        <div class="col-lg-3 mb-4">
            <div class="card glass border-0 rounded-24 shadow-sm h-100 overflow-hidden">
                <div class="card-body p-4">
                    <h6 class="text-uppercase font-weight-bold text-muted mb-4" style="font-size: 0.75rem; letter-spacing: 1px;">Statistics</h6>
                    
                    <div class="d-flex align-items-center mb-4">
                        <div class="stats-icon bg-soft-primary mr-3">
                            <i class="fas fa-layer-group text-primary"></i>
                        </div>
                        <div>
                            <div class="h5 mb-0 font-weight-bold" id="total-cards-count">{{ is_array($flashcard->cards) ? count($flashcard->cards) : 0 }}</div>
                            <small class="text-muted">Total Cards</small>
                        </div>
                    </div>

                    <div class="d-flex align-items-center mb-4">
                        <div class="stats-icon bg-soft-success mr-3">
                            <i class="fas fa-check-circle text-success"></i>
                        </div>
                        <div>
                            <div class="h5 mb-0 font-weight-bold">{{ ucfirst($flashcard->status) }}</div>
                            <small class="text-muted">Status</small>
                        </div>
                    </div>

                    @php
                        $progress = $flashcard->studyProgress->where('user_id', auth()->id())->first();
                        $percent = $progress ? ($progress->studied_cards / max($progress->total_cards, 1)) * 100 : 0;
                    @endphp

                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <small class="font-weight-bold text-muted">Completion</small>
                            <small class="font-weight-bold">{{ round($percent) }}%</small>
                        </div>
                        <div class="progress rounded-pill" style="height: 10px; background-color: rgba(0,0,0,0.05);">
                            <div class="progress-bar rounded-pill bg-primary" style="width: {{ $percent }}%;"></div>
                        </div>
                    </div>

                    <hr class="my-4 opacity-50">

                    <h6 class="text-uppercase font-weight-bold text-muted mb-3" style="font-size: 0.75rem; letter-spacing: 1px;">Source File</h6>
                    @if($flashcard->document_path)
                        <a href="{{ asset('storage/' . $flashcard->document_path) }}" target="_blank" class="d-flex align-items-center p-2 rounded-lg bg-light text-decoration-none hover-shadow transition-all">
                            <i class="fas fa-file-pdf fa-2x text-danger mr-3"></i>
                            <div class="overflow-hidden">
                                <div class="text-dark font-weight-bold text-truncate" style="font-size: 0.85rem;">{{ basename($flashcard->document_path) }}</div>
                                <small class="text-muted text-uppercase">{{ $flashcard->source_type }}</small>
                            </div>
                        </a>
                    @else
                        <p class="text-muted italic small">No document attached.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Cards List -->
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4 px-2">
                <h4 class="font-weight-bold mb-0">Deck Cards</h4>
                <div class="text-muted small">Edit or delete cards to refine your deck</div>
            </div>

            <div id="cards-container">
                @if(is_array($flashcard->cards) && count($flashcard->cards))
                    @foreach($flashcard->cards as $index => $card)
                        <div class="card glass border-0 rounded-24 mb-3 shadow-sm card-hover card-item" data-index="{{ $index }}">
                            <div class="card-body p-4">
                                <div class="row align-items-center">
                                    <div class="col-md-1 d-none d-md-block text-center">
                                        <span class="badge badge-light rounded-circle p-3 text-muted font-weight-bold card-index-badge" style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;">
                                            {{ $index + 1 }}
                                        </span>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="mb-2">
                                            <span class="text-uppercase font-weight-bold text-primary mr-2" style="font-size: 0.7rem; letter-spacing: 1px;">Question</span>
                                            <div class="h5 font-weight-bold card-question mb-3">{{ $card['question'] ?? '' }}</div>
                                        </div>
                                        <div>
                                            <span class="text-uppercase font-weight-bold text-success mr-2" style="font-size: 0.7rem; letter-spacing: 1px;">Answer</span>
                                            <div class="text-muted card-answer">{{ $card['answer'] ?? '' }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 text-right mt-3 mt-md-0">
                                        <button class="btn btn-icon bg-soft-info mr-1 btn-edit-card" title="Edit Card">
                                            <i class="fas fa-pen text-info"></i>
                                        </button>
                                        <button class="btn btn-icon bg-soft-danger btn-delete-card" title="Delete Card">
                                            <i class="fas fa-trash-alt text-danger"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-5 glass rounded-24">
                        <i class="fas fa-clone fa-3x text-muted mb-3 opacity-30"></i>
                        <h5 class="text-muted">No cards found in this deck</h5>
                        @if($flashcard->status === 'processing')
                            <p class="text-muted">Please wait while we generate your flashcards...</p>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Edit Card Modal -->
<div class="modal fade" id="editCardModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content glass border-0 rounded-24">
            <div class="modal-header border-0 p-4">
                <h5 class="modal-title font-weight-bold">Edit Flashcard</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editCardForm">
                <div class="modal-body p-4 pt-0">
                    <input type="hidden" id="edit-card-index">
                    <div class="form-group mb-4">
                        <label class="font-weight-bold text-muted small text-uppercase mb-2">Question</label>
                        <textarea class="form-control glass-input rounded-xl p-3" id="edit-card-question" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold text-muted small text-uppercase mb-2">Answer</label>
                        <textarea class="form-control glass-input rounded-xl p-3" id="edit-card-answer" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4">
                    <button type="button" class="btn btn-light px-4 rounded-pill" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4 rounded-pill shadow-lg">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Summary Modal -->
<div class="modal fade" id="summaryModal" tabindex="-1" role="dialog" aria-labelledby="summaryModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content glass border-0" style="border-radius: 24px;">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title font-weight-bold" id="summaryModalLabel">
            <i class="fas fa-bolt text-warning mr-2"></i> Smart Summary
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body p-4">
        <div id="summaryContent" class="text-muted" style="line-height: 1.6; white-space: pre-wrap;">
            Generating summary... Please wait.
        </div>
      </div>
      <div class="modal-footer border-0">
        <button type="button" class="btn btn-light rounded-pill px-4" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<style>
    .glass {
        background: rgba(255, 255, 255, 0.7) !important;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }
    .rounded-24 { border-radius: 24px !important; }
    .rounded-xl { border-radius: 12px !important; }
    .bg-soft-primary { background-color: rgba(79, 70, 229, 0.1) !important; }
    .bg-soft-success { background-color: rgba(5, 150, 105, 0.1) !important; }
    .bg-soft-info { background-color: rgba(8, 145, 178, 0.1) !important; }
    .bg-soft-danger { background-color: rgba(220, 38, 38, 0.1) !important; }
    .btn-soft-info { background-color: rgba(8, 145, 178, 0.1); color: #0891b2; border: none; }
    .btn-soft-info:hover { background-color: rgba(8, 145, 178, 0.2); }
    .btn-soft-warning { background-color: rgba(245, 158, 11, 0.1); color: #d97706; border: none; }
    .btn-soft-warning:hover { background-color: rgba(245, 158, 11, 0.2); }
    
    .stats-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }
    
    .card-hover {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1) !important;
    }
    
    .btn-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        border: none;
    }
    .btn-icon:hover { transform: scale(1.1); }
    
    .glass-input {
        background: rgba(255, 255, 255, 0.5);
        border: 1px solid rgba(0, 0, 0, 0.05);
        transition: all 0.3s;
    }
    .glass-input:focus {
        background: #fff;
        border-color: #4f46e5;
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
    }
    
    .breadcrumb-item + .breadcrumb-item::before { content: "•"; color: #cbd5e0; }
    
    .transition-all { transition: all 0.3s; }
    .hover-shadow:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.05); }

    /* Dark mode adjustments */
    body.dark-mode .glass {
        background: rgba(31, 41, 55, 0.8) !important;
        border-color: rgba(255, 255, 255, 0.1);
    }
    body.dark-mode .text-dark { color: #f9fafb !important; }
    body.dark-mode .bg-light { background-color: rgba(255,255,255,0.05) !important; }
    body.dark-mode .glass-input {
        background: rgba(0, 0, 0, 0.2);
        color: #fff;
        border-color: rgba(255, 255, 255, 0.1);
    }
</style>

@endsection

@section('scripts')
<script>
$(function() {
    const flashcardId = "{{ $flashcard->id }}";

    // Edit Card Button Click
    $(document).on('click', '.btn-edit-card', function() {
        const row = $(this).closest('.card-item');
        const index = row.data('index');
        const question = row.find('.card-question').text();
        const answer = row.find('.card-answer').text();

        $('#edit-card-index').val(index);
        $('#edit-card-question').val(question);
        $('#edit-card-answer').val(answer);
        $('#editCardModal').modal('show');
    });

    // Handle Edit Card Form Submit
    $('#editCardForm').on('submit', function(e) {
        e.preventDefault();
        const index = $('#edit-card-index').val();
        const question = $('#edit-card-question').val();
        const answer = $('#edit-card-answer').val();
        const btn = $(this).find('button[type="submit"]');

        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status"></span> Saving...');

        $.ajax({
            url: `/flashcard/${flashcardId}/card/${index}`,
            method: 'PATCH',
            data: {
                _token: "{{ csrf_token() }}",
                question: question,
                answer: answer
            },
            success: function(response) {
                if (response.success) {
                    const row = $(`.card-item[data-index="${index}"]`);
                    row.find('.card-question').text(question);
                    row.find('.card-answer').text(answer);
                    $('#editCardModal').modal('hide');
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Card Updated',
                        text: 'Your flashcard has been updated successfully.',
                        timer: 2000,
                        showConfirmButton: false,
                        position: 'top-end',
                        toast: true
                    });
                }
            },
            error: function() {
                Swal.fire('Error', 'Failed to update card. Please try again.', 'error');
            },
            complete: function() {
                btn.prop('disabled', false).text('Save Changes');
            }
        });
    });

    // Delete Card Button Click
    $(document).on('click', '.btn-delete-card', function() {
        const row = $(this).closest('.card-item');
        const index = row.data('index');

        Swal.fire({
            title: 'Delete this card?',
            text: "This action cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/flashcard/${flashcardId}/card/${index}`,
                    method: 'DELETE',
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if (response.success) {
                            row.css('transform', 'translateX(100px)');
                            row.css('opacity', '0');
                            
                            setTimeout(function() {
                                row.remove();
                                
                                // Update Total Count
                                const countEl = $('#total-cards-count');
                                let newCount = parseInt(countEl.text()) - 1;
                                countEl.text(newCount);

                                // Update Index Badges
                                $('.card-item').each(function(i) {
                                    $(this).attr('data-index', i);
                                    $(this).find('.card-index-badge').text(i + 1);
                                });

                                // Check if empty
                                if (newCount === 0) {
                                    $('#cards-container').html(`
                                        <div class="text-center py-5 glass rounded-24 fade-in">
                                            <i class="fas fa-clone fa-3x text-muted mb-3 opacity-30"></i>
                                            <h5 class="text-muted">No cards found in this deck</h5>
                                        </div>
                                    `);
                                }
                            }, 300);

                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: 'Card has been removed from the deck.',
                                timer: 1500,
                                showConfirmButton: false,
                                position: 'top-end',
                                toast: true
                            });
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Failed to delete card.', 'error');
                    }
                });
            }
        });
    });

    // Summarize Action
    $('.btn-summarize').on('click', function(e) {
        e.preventDefault();
        const id = $(this).data('id');
        const modal = $('#summaryModal');
        const content = $('#summaryContent');
        
        content.html('<div class="text-center p-5"><i class="fas fa-spinner fa-spin fa-2x text-primary mb-3"></i><p>AI is analyzing your document and generating a summary...</p></div>');
        modal.modal('show');
        
        $.ajax({
            url: `/flashcard/${id}/summarize`,
            method: 'GET',
            success: function(response) {
                if (response.summary) {
                    let html = response.summary
                        .replace(/^### (.*)$/gm, '<h4 class="mt-4 font-weight-bold">$1</h4>')
                        .replace(/^## (.*)$/gm, '<h3 class="mt-4 font-weight-bold">$1</h3>')
                        .replace(/^\- (.*)$/gm, '<li class="ml-3">$1</li>')
                        .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
                    
                    content.html(html);
                } else {
                    content.html('<p class="text-danger">Failed to generate summary.</p>');
                }
            },
            error: function(xhr) {
                content.html('<p class="text-danger">Error: ' + (xhr.responseJSON?.error || 'Something went wrong.') + '</p>');
            }
        });
    });
});
</script>
@endsection
