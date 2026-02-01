@extends('layouts.template')

@section('content')
<div class="container py-4 fade-in">
    <!-- Header/Breadcrumbs -->
    <div class="mb-5 px-2">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent p-0 mb-2">
                <li class="breadcrumb-item"><a href="{{ route('flashcard.index') }}" class="text-primary">Library</a></li>
                <li class="breadcrumb-item"><a href="{{ route('flashcard.show', $flashcard->id) }}" class="text-primary">{{ $flashcard->title }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Deck</li>
            </ol>
        </nav>
        <h2 class="font-weight-bold tracking-tight" style="font-size: 2.2rem;">Edit Deck Details</h2>
        <p class="text-muted">Update your flashcard deck information and settings</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card card-modern glass border-0 rounded-24 shadow-lg overflow-hidden animate-slide-up">
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('flashcard.update', $flashcard->id) }}" method="POST" enctype="multipart/form-data" class="modern-form">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group mb-4">
                            <label for="title" class="form-label-modern text-uppercase font-weight-bold text-muted mb-2" style="font-size: 0.75rem; letter-spacing: 1px;">Deck Title</label>
                            <div class="input-group-modern glass-input-wrapper">
                                <span class="input-icon"><i class="fas fa-heading text-primary"></i></span>
                                <input type="text" class="form-control-modern glass-input w-100" id="title" name="title" 
                                       value="{{ old('title', $flashcard->title) }}" required 
                                       placeholder="e.g., Advanced Mathematics - Group Theory">
                            </div>
                            @error('title')
                                <small class="text-danger mt-1 d-block">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label for="description" class="form-label-modern text-uppercase font-weight-bold text-muted mb-2" style="font-size: 0.75rem; letter-spacing: 1px;">Description</label>
                            <div class="input-group-modern glass-input-wrapper align-items-start">
                                <span class="input-icon mt-3"><i class="fas fa-align-left text-primary"></i></span>
                                <textarea class="form-control-modern glass-input w-100" id="description" name="description" 
                                          rows="4" placeholder="Briefly describe what this deck covers...">{{ old('description', $flashcard->description) }}</textarea>
                            </div>
                            @error('description')
                                <small class="text-danger mt-1 d-block">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="card bg-soft-primary border-0 rounded-xl mb-4 p-4 overflow-hidden position-relative">
                            <div class="position-absolute" style="top: -20px; right: -20px; opacity: 0.1;">
                                <i class="fas fa-file-alt fa-6x"></i>
                            </div>
                            <h6 class="font-weight-bold mb-3 d-flex align-items-center">
                                <i class="fas fa-paperclip mr-2 text-primary"></i> Source Document
                            </h6>
                            
                            @if($flashcard->document_path)
                                <div class="d-flex align-items-center bg-white dark-bg-dark border rounded-lg p-3 mb-3 shadow-sm">
                                    <div class="bg-soft-danger rounded-lg p-2 mr-3">
                                        <i class="fas fa-file-pdf text-danger fa-lg"></i>
                                    </div>
                                    <div class="overflow-hidden flex-grow-1">
                                        <div class="text-dark font-weight-bold text-truncate small">{{ basename($flashcard->document_path) }}</div>
                                        <small class="text-muted text-uppercase" style="font-size: 0.65rem;">Current File</small>
                                    </div>
                                    <a href="{{ asset('storage/' . $flashcard->document_path) }}" target="_blank" 
                                       class="btn btn-sm btn-outline-primary rounded-pill ml-3 px-3">
                                        View
                                    </a>
                                </div>
                            @endif

                            <div class="custom-file-modern">
                                <input type="file" class="custom-file-input-modern" id="document" name="document" hidden>
                                <label for="document" class="drop-zone-modern rounded-lg text-center p-3 cursor-pointer transition-all border-dashed">
                                    <i class="fas fa-cloud-upload-alt mb-2 text-primary fa-lg d-block"></i>
                                    <span class="small font-weight-bold text-muted">Click to replace document (optional)</span>
                                </label>
                            </div>
                        </div>

                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center pt-3 border-top mt-5">
                            <a href="{{ route('flashcard.show', $flashcard->id) }}" class="btn btn-light rounded-pill px-4 py-2 mb-3 mb-sm-0 transition-all font-weight-bold">
                                <i class="fas fa-times mr-2 font-weight-normal"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary rounded-pill px-5 py-2 shadow-lg transition-all hover-translate-y font-weight-bold">
                                <i class="fas fa-check-circle mr-2 font-weight-normal"></i> Save Deck Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .rounded-24 { border-radius: 24px !important; }
    .rounded-xl { border-radius: 16px !important; }
    .cursor-pointer { cursor: pointer; }
    
    .glass {
        background: rgba(255, 255, 255, 0.7) !important;
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    /* Modern Input Styling */
    .glass-input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
        border-radius: 14px;
        background: rgba(243, 244, 246, 0.5);
        border: 1px solid rgba(0, 0, 0, 0.05);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        padding: 0 15px;
    }
    
    .glass-input-wrapper:focus-within {
        background: #fff;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        transform: translateY(-1px);
    }
    
    .input-icon {
        font-size: 1rem;
        margin-right: 12px;
        opacity: 0.6;
    }
    
    .glass-input {
        background: transparent !important;
        border: none !important;
        box-shadow: none !important;
        padding: 12px 0 !important;
        height: auto !important;
        font-size: 0.95rem;
        color: var(--text-main);
    }

    .bg-soft-primary { background-color: rgba(79, 70, 229, 0.08) !important; }
    .bg-soft-danger { background-color: rgba(239, 68, 68, 0.08) !important; }
    
    .border-dashed { border: 2px dashed rgba(79, 70, 229, 0.2); }
    
    .drop-zone-modern:hover {
        background: rgba(79, 70, 229, 0.05);
        border-color: var(--primary-color);
    }

    .hover-translate-y:hover {
        transform: translateY(-2px);
    }
    
    .transition-all { transition: all 0.2s ease; }
    
    .animate-slide-up {
        animation: slideUp 0.6s cubic-bezier(0.2, 0.8, 0.2, 1);
    }
    
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Breadcrumbs Modernization */
    .breadcrumb-item + .breadcrumb-item::before { content: "•"; color: #cbd5e0; }
    
    /* Dark Mode Adjustments */
    body.dark-mode .dark-bg-dark { background-color: rgba(0,0,0,0.2) !important; }
    body.dark-mode .glass {
        background: rgba(30, 41, 59, 0.8) !important;
        border-color: rgba(255, 255, 255, 0.1);
    }
    body.dark-mode .glass-input-wrapper {
        background: rgba(0, 0, 0, 0.2);
        border-color: rgba(255, 255, 255, 0.1);
    }
    body.dark-mode .glass-input-wrapper:focus-within {
        background: rgba(0, 0, 0, 0.3);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('document');
        const dropZone = document.querySelector('.drop-zone-modern');
        
        fileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                dropZone.querySelector('span').textContent = 'Selected: ' + this.files[0].name;
                dropZone.classList.add('bg-soft-primary');
            }
        });
    });
</script>
@endsection
