@extends('layouts.template')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');

    .create-page {
        font-family: 'Poppins', sans-serif;
        min-height: calc(100vh - 100px);
        background: linear-gradient(135deg, #f8faff 0%, #eef2ff 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 0;
    }

    .glass-card-container {
        width: 100%;
        max-width: 800px;
    }

    .glass-card {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.4);
        border-radius: 32px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1);
        padding: 50px;
    }

    .form-section-title {
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 2px;
        color: #6366f1;
        font-weight: 700;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
    }

    .form-section-title i {
        margin-right: 10px;
        font-size: 1rem;
    }

    .input-field-custom {
        background: rgba(255, 255, 255, 0.5);
        border: 2px solid transparent;
        border-radius: 18px;
        padding: 15px 20px;
        transition: all 0.3s ease;
        color: #1e293b;
        height: auto !important;
    }

    .input-field-custom:focus {
        background: #fff;
        border-color: #6366f1;
        box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.1);
        outline: none;
    }

    .nav-pills-modern {
        background: rgba(0, 0, 0, 0.03);
        padding: 6px;
        border-radius: 20px;
    }

    .nav-pills-modern .nav-link {
        border-radius: 16px;
        padding: 12px 20px;
        font-weight: 600;
        color: #64748b;
        transition: all 0.3s ease;
        border: none;
    }

    .nav-pills-modern .nav-link.active {
        background: #fff;
        color: #6366f1;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    .upload-zone-modern {
        border: 2px dashed #cbd5e1;
        border-radius: 24px;
        padding: 40px 20px;
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.3);
        cursor: pointer;
    }

    .upload-zone-modern:hover, .upload-zone-modern.dragover {
        border-color: #6366f1;
        background: rgba(99, 102, 241, 0.05);
    }

    .file-item-modern {
        background: #fff;
        border-radius: 16px;
        padding: 12px 16px;
        margin-bottom: 10px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
        border: 1px solid rgba(0, 0, 0, 0.05);
        display: flex;
        align-items: center;
        animation: slideIn 0.3s ease-out;
    }

    @keyframes slideIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .btn-generate-modern {
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        color: #fff;
        border: none;
        border-radius: 20px;
        padding: 18px 40px;
        font-weight: 700;
        font-size: 1.1rem;
        box-shadow: 0 10px 25px -5px rgba(99, 102, 241, 0.4);
        transition: all 0.3s ease;
        width: 100%;
        cursor: pointer;
    }

    .btn-generate-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 30px -5px rgba(99, 102, 241, 0.5);
        color: #fff;
    }

    .btn-generate-modern:disabled {
        background: #cbd5e1;
        box-shadow: none;
        cursor: not-allowed;
    }

    .bg-primary-soft { background-color: #eef2ff; color: #6366f1; }
    .bg-success-soft { background-color: #f0fdf4; color: #22c55e; }
    .rounded-3xl { border-radius: 24px; }
</style>

<div class="create-page">
    <div class="glass-card-container fade-in">
        <div class="text-center mb-5">
            <h1 class="font-weight-bold tracking-tight" style="font-size: 2.8rem; color: #1e293b;">AI Deck Builder</h1>
            <p class="text-muted" style="font-size: 1.1rem;">Instant study materials created by AI from your files or links</p>
        </div>

        <div class="glass-card">
            <form action="{{ route('flashcard.store') }}" method="POST" enctype="multipart/form-data" id="createForm">
                @csrf
                
                <div class="form-section">
                    <div class="form-section-title">
                        <i class="fas fa-pen-nib"></i> 1. Setup Your Deck
                    </div>
                    <div class="form-group mb-4">
                        <input type="text" class="form-control input-field-custom w-100" id="title" name="title" 
                               placeholder="Deck Title (e.g. Data Structures Finale)" autocomplete="off">
                    </div>
                    <div class="form-group mb-5">
                        <textarea class="form-control input-field-custom w-100" id="description" name="description" 
                                  rows="2" placeholder="Deck Description (Optional)"></textarea>
                    </div>
                </div>

                <div class="form-section">
                    <div class="form-section-title">
                        <i class="fas fa-file-import"></i> 2. Select Source
                    </div>
                    
                    <ul class="nav nav-pills nav-fill nav-pills-modern mb-4" id="sourceTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="file-tab" data-toggle="pill" href="#file-upload" role="tab">
                                <i class="fas fa-copy mr-2"></i> Documents
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="url-tab" data-toggle="pill" href="#url-import" role="tab">
                                <i class="fas fa-link mr-2"></i> Web URL
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content pt-2">
                        <!-- File Upload -->
                        <div class="tab-pane fade show active" id="file-upload" role="tabpanel">
                            <div class="upload-zone-modern text-center position-relative" id="drop-zone">
                                <input type="file" id="document" name="document[]" multiple 
                                       class="position-absolute h-100 w-100 top-0 start-0 opacity-0 cursor-pointer" 
                                       onchange="handleFileSelect(this)" style="z-index: 10;">
                                
                                <div id="upload-placeholder">
                                    <div class="bg-primary-soft rounded-circle d-inline-flex p-4 mb-4">
                                        <i class="fas fa-cloud-upload-alt fa-3x text-primary"></i>
                                    </div>
                                    <h4 class="font-weight-bold">Drop files or click here</h4>
                                    <p class="text-muted small">Supports PDF, JPG, PNG, TXT</p>
                                </div>

                                <div id="file-chosen" class="d-none">
                                    <div class="bg-success-soft rounded-circle d-inline-flex p-3 mb-3">
                                        <i class="fas fa-check text-success fa-2x"></i>
                                    </div>
                                    <h5 class="font-weight-bold text-success" id="file-name-display">Files Ready!</h5>
                                    <div id="file-list" class="mt-4 px-3" style="max-height: 250px; overflow-y: auto;"></div>
                                    <button type="button" class="btn btn-link text-primary font-weight-bold mt-2" onclick="resetFile()" style="position: relative; z-index: 20;">
                                        <i class="fas fa-undo mr-2"></i>Choose different files
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- URL Import -->
                        <div class="tab-pane fade" id="url-import" role="tabpanel">
                            <div class="p-4 rounded-3xl border-2" style="background: rgba(255,255,255,0.4); border: 2px dashed #cbd5e1;">
                                <div class="input-group input-group-lg" id="urlGroup" style="background: #fff; border-radius: 18px; overflow: hidden; border: 2px solid #e2e8f0; transition: all 0.3s ease;">
                                    <span class="input-group-text bg-white border-0 pl-4">
                                        <i class="fas fa-globe text-muted"></i>
                                    </span>
                                    <input type="url" class="form-control border-0 bg-transparent py-4" id="url" name="url" 
                                           placeholder="Paste article or Wikipedia link..." onfocus="$('#urlGroup').css('border-color', '#6366f1')">
                                </div>
                                <div class="text-center mt-4">
                                    <small class="text-muted">
                                        <i class="fas fa-bolt mr-1 text-warning"></i> AI will scan the link and extract the most relevant facts.
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-5 pt-3">
                    <button type="submit" id="generate-btn" class="btn-generate-modern">
                        <span class="normal-state">Generate My Deck <i class="fas fa-magic ml-2"></i></span>
                        <span class="loading-state d-none">
                            <i class="fas fa-spinner fa-spin mr-3"></i> AI is digesting your content...
                        </span>
                    </button>
                    
                    <div class="text-center mt-4">
                        <a href="{{ route('flashcard.index') }}" class="text-muted text-decoration-none small font-weight-600">
                            <i class="fas fa-times-circle mr-2"></i> Discard and go back
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function handleFileSelect(input) {
        if (input.files && input.files.length > 0) {
            $('#upload-placeholder').addClass('d-none');
            $('#file-chosen').removeClass('d-none');
            
            const fileList = $('#file-list');
            fileList.empty();
            
            let filesArray = Array.from(input.files);
            $('#file-name-display').text(`${filesArray.length} Source(s) Added`);
            
            filesArray.forEach((file) => {
                const fileSize = (file.size / 1024 / 1024).toFixed(2);
                fileList.append(`
                    <div class="file-item-modern">
                        <div class="bg-primary-soft rounded p-2 mr-3" style="width: 35px; text-align: center;">
                            <i class="fas fa-file-alt text-primary small"></i>
                        </div>
                        <div class="flex-grow-1 text-left overflow-hidden">
                            <div class="font-weight-600 text-truncate" style="font-size: 0.85rem; color: #1e293b;">${file.name}</div>
                            <div class="text-muted" style="font-size: 0.7rem;">${fileSize} MB</div>
                        </div>
                        <div class="ml-2 text-success small">
                            <i class="fas fa-check"></i>
                        </div>
                    </div>
                `);
            });

            if (filesArray.length === 1 && !$('#title').val()) {
                let name = filesArray[0].name.replace(/\.[^/.]+$/, "");
                $('#title').val(name);
            }
            $('#url').val('');
        }
    }

    const dropZone = document.getElementById('drop-zone');
    if (dropZone) {
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, (e) => { e.preventDefault(); e.stopPropagation(); }, false);
        });
        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => dropZone.classList.add('dragover'), false);
        });
        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => dropZone.classList.remove('dragover'), false);
        });
        dropZone.addEventListener('drop', (e) => {
            let dt = e.dataTransfer;
            document.getElementById('document').files = dt.files;
            handleFileSelect(document.getElementById('document'));
        }, false);
    }

    function resetFile() {
        const input = document.getElementById('document');
        input.value = '';
        $('#upload-placeholder').removeClass('d-none');
        $('#file-chosen').addClass('d-none');
        $('#file-list').empty();
    }

    $(document).ready(function() {
        $('#document').prop('required', true);
        $('#url').prop('required', false);

        $('#createForm').on('submit', function() {
            const btn = $('#generate-btn');
            btn.prop('disabled', true);
            btn.find('.normal-state').addClass('d-none');
            btn.find('.loading-state').removeClass('d-none');
        });

        $('a[data-toggle="pill"]').on('shown.bs.tab', function (e) {
            const targetId = $(e.target).attr('id');
            if (targetId === 'url-tab') {
                $('#document').prop('required', false);
                $('#url').prop('required', true);
                resetFile();
            } else {
                $('#document').prop('required', true);
                $('#url').prop('required', false);
                $('#url').val('');
            }
        });
    });
</script>
@endsection
