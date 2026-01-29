@extends('layouts.template') 
@section('content') 
@include('flashcard.table')

<!-- Share Modal -->
<div class="modal fade" id="shareModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-share-alt mr-2 text-primary"></i> Send Deck</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('messages.store') }}" method="POST">
                @csrf
                <input type="hidden" name="flashcard_id" id="shareFlashcardId">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="font-weight-bold">Select User</label>
                        <select name="receiver_id" class="form-control" required style="border-radius: 10px; height: 50px;">
                            <option value="">Choose a friend...</option>
                            @if(isset($users))
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Message</label>
                        <textarea name="content" id="shareMessagePayload" class="form-control" rows="4" required style="border-radius: 10px; border: 1px solid #e2e8f0;"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light" style="border-bottom-left-radius: 20px; border-bottom-right-radius: 20px;">
                    <button type="button" class="btn btn-light font-weight-bold" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm font-weight-bold">
                        <i class="fas fa-paper-plane mr-2"></i> Send
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Summary Modal -->
<div class="modal fade" id="summaryModal" tabindex="-1" role="dialog" aria-labelledby="summaryModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content card-modern" style="border-radius: 20px; border: none;">
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
        <button type="button" class="btn btn-light btn-soft" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Share Flashcard
    $(document).on('click', '.btn-share-flashcard', function(e) {
        e.preventDefault();
        const flashcardId = $(this).data('id');
        const title = $(this).data('title');
        
        $('#shareFlashcardId').val(flashcardId);
        
        // Capitalize title
        const formattedTitle = title.charAt(0).toUpperCase() + title.slice(1);
        $('#shareMessagePayload').val(`Check out this flashcard deck: ${formattedTitle}`);
        $('#shareModal').modal('show');
    });

    $('.btn-summarize').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
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
                    // Simple markdown-ish conversion for basic formatting
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

    // Toggle Favorite
    $(document).on('click', '.btn-favorite', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const btn = $(this);
        const icon = btn.find('i');
        const id = btn.data('id');
        
        btn.prop('disabled', true);
        
        const url = "{{ route('flashcard.toggle-favorite', ':id') }}".replace(':id', id);
        
        $.ajax({
            url: url,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    if (response.is_favorite) {
                        icon.removeClass('far').addClass('fas');
                        btn.attr('title', 'Unfavorite');
                    } else {
                        icon.removeClass('fas').addClass('far');
                        btn.attr('title', 'Favorite');
                        
                        // If we are on the favorites page, remove the card
                        if (window.location.pathname.includes('favorites')) {
                            btn.closest('.col-md-4').fadeOut(300, function() {
                                $(this).remove();
                                // If list is empty, refresh to show empty state
                                if ($('.row.px-2').children(':visible').length === 0) {
                                    location.reload();
                                }
                            });
                        }
                    }
                }
            },
            error: function() {
                alert('Something went wrong. Please try again.');
            },
            complete: function() {
                btn.prop('disabled', false);
            }
        });
    });
    // Quiz Button Loading State
    $('.btn-quiz-loading').on('click', function() {
        const btn = $(this);
        // We use a shorter text to prevent the button from overflowing the card layout
        btn.addClass('disabled').css('pointer-events', 'none').html('<i class="fas fa-spinner fa-spin"></i> Wait');
        
        // Link will naturally navigate, but we show the state in case of slow response
    });

    // Live Search Logic
    $('#liveSearch').on('keyup', function() {
        const query = $(this).val().toLowerCase();
        let visibleCount = 0;
        
        $('.flashcard-item').each(function() {
            const title = $(this).data('title') || '';
            const description = $(this).data('description') || '';
            
            if (title.includes(query) || description.includes(query)) {
                $(this).fadeIn(200);
                visibleCount++;
            } else {
                $(this).fadeOut(200);
            }
        });

        if (visibleCount === 0) {
            $('#noResults').fadeIn(200);
            $('#flashcardGrid').fadeOut(200);
        } else {
            $('#noResults').hide();
            $('#flashcardGrid').show();
        }
    });
});
</script>
@endsection