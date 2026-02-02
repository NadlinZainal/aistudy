@extends('layouts.template')

@section('content')
<div class="container-fluid py-3 fade-in">
    <div class="row justify-content-center">
        <div class="col-xl-9 col-lg-11">
            <!-- Compact Header -->
            <div class="d-flex align-items-center mb-4 ml-2">
                <div class="p-2 rounded-16 glass mr-3 shadow-sm">
                    <i class="fas fa-user-gear fa-lg text-primary"></i>
                </div>
                <div>
                    <h2 class="font-weight-bold mb-0 tracking-tight">Account Settings</h2>
                    <p class="text-muted small mb-0">Manage your profile and security</p>
                </div>
            </div>

            <div class="card card-modern glass shadow-2xl border-0 overflow-hidden" style="border-radius: 24px;">
                <div class="card-body p-0">
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="modern-form">
                        @csrf
                        @method('PUT')

                        <div class="row no-gutters">
                            <!-- Sidebar Profile Preview - Compact -->
                            <div class="col-md-3 bg-primary-soft p-4 d-flex flex-column align-items-center text-center border-right" style="background: linear-gradient(180deg, rgba(99, 102, 241, 0.05) 0%, rgba(99, 102, 241, 0) 100%); min-height: 400px;">
                                <div class="position-relative mb-4 hover-up">
                                    @if($user->profile_photo_path)
                                        <img src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="Profile Photo" 
                                             class="rounded-circle shadow-24 border border-white" 
                                             style="width: 120px; height: 120px; object-fit: cover; border-width: 4px !important;">
                                    @else
                                        <div class="rounded-circle glass d-flex justify-content-center align-items-center shadow-24" 
                                             style="width: 120px; height: 120px; background: white;">
                                            <i class="fas fa-user fa-3x text-primary-soft" style="opacity: 0.5;"></i>
                                        </div>
                                    @endif
                                    <label for="profile_photo" class="position-absolute btn btn-primary btn-icon rounded-circle shadow-lg pulse-primary" style="width: 38px; height: 38px; bottom: 5px; right: 5px; display: flex; align-items: center; justify-content: center; cursor: pointer; z-index: 10;">
                                        <i class="fas fa-camera fa-xs"></i>
                                    </label>
                                    <input type="file" name="profile_photo" id="profile_photo" class="d-none">
                                </div>
                                
                                <h5 class="font-weight-bold mb-2 mt-1">{{ $user->name }}</h5>
                                <p class="text-muted small mb-3">{{ $user->email }}</p>
                                
                                <div class="w-100 mt-auto">
                                    <div class="p-3 rounded-xl glass border-0 text-left">
                                        <small class="text-muted d-block text-uppercase font-weight-bold mb-1" style="font-size: 0.6rem; letter-spacing: 1px;">Member Since</small>
                                        <span class="font-weight-medium small">{{ $user->created_at->format('M Y') }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Form Sections - Compact -->
                            <div class="col-md-9 p-4">
                                @if(session('success'))
                                    <div class="alert alert-success border-0 shadow-sm rounded-16 mb-4 d-flex align-items-center fade-in">
                                        <i class="fas fa-check-circle mr-3 fa-lg"></i>
                                        <div>
                                            <span class="font-weight-bold">Success!</span><br>
                                            {{ session('success') }}
                                        </div>
                                        <button type="button" class="close ml-auto" data-dismiss="alert">&times;</button>
                                    </div>
                                @endif

                                <!-- Information Section -->
                                <div class="mb-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="bg-primary text-white p-2 rounded-lg mr-2 shadow-sm" style="width: 28px; height: 28px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-info-circle fa-xs"></i>
                                        </div>
                                        <h6 class="font-weight-bold mb-0 text-uppercase tracking-wider">Personal Information</h6>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label font-weight-bold small text-muted text-uppercase mb-1">Full Name</label>
                                            <input type="text" name="name" class="form-control-modern" value="{{ old('name', $user->name) }}" placeholder="Your name" required>
                                            @error('name') <div class="error-msg fade-in"><i class="fas fa-times-circle mr-1"></i> {{ $message }}</div> @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label font-weight-bold small text-muted text-uppercase mb-1">Email Address</label>
                                            <input type="email" name="email" class="form-control-modern" value="{{ old('email', $user->email) }}" placeholder="mail@example.com" required>
                                            @error('email') <div class="error-msg fade-in"><i class="fas fa-times-circle mr-1"></i> {{ $message }}</div> @enderror
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label font-weight-bold small text-muted text-uppercase mb-1">Phone Number</label>
                                            <input type="text" name="phone_number" class="form-control-modern py-2" value="{{ old('phone_number', $user->phone_number) }}" placeholder="+1 234 567 890">
                                            @error('phone_number') <div class="error-msg fade-in"><i class="fas fa-times-circle mr-1"></i> {{ $message }}</div> @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label font-weight-bold small text-muted text-uppercase mb-1">Age</label>
                                            <input type="number" name="age" class="form-control-modern py-2" value="{{ old('age', $user->age) }}" placeholder="Your age">
                                            @error('age') <div class="error-msg fade-in"><i class="fas fa-times-circle mr-1"></i> {{ $message }}</div> @enderror
                                        </div>
                                    </div>

                                    <div class="form-group mb-0">
                                        <label class="form-label font-weight-bold small text-muted text-uppercase mb-1">Residential Address</label>
                                        <textarea name="address" class="form-control-modern py-2" rows="2" placeholder="Street, City, Postal Code">{{ old('address', $user->address) }}</textarea>
                                        @error('address') <div class="error-msg fade-in"><i class="fas fa-times-circle mr-1"></i> {{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <!-- Security Section -->
                                <div class="mb-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="bg-danger text-white p-2 rounded-lg mr-2 shadow-sm" style="width: 28px; height: 28px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-shield-alt fa-xs"></i>
                                        </div>
                                        <h6 class="font-weight-bold mb-0 text-uppercase tracking-wider">Security</h6>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label font-weight-bold small text-muted text-uppercase mb-1">New Password</label>
                                            <input type="password" name="password" class="form-control-modern py-2 border-danger-focus" placeholder="••••••••">
                                            @error('password') <div class="error-msg fade-in"><i class="fas fa-times-circle mr-1"></i> {{ $message }}</div> @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label font-weight-bold small text-muted text-uppercase mb-1">Confirm Password</label>
                                            <input type="password" name="password_confirmation" class="form-control-modern py-2 border-danger-focus" placeholder="••••••••">
                                        </div>
                                    </div>
                                </div>

                                <!-- Telegram Integration Section -->
                                <div class="mb-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="bg-info text-white p-2 rounded-lg mr-2 shadow-sm" style="width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; background-color: #0088cc !important;">
                                            <i class="fab fa-telegram-plane fa-xs"></i>
                                        </div>
                                        <h6 class="font-weight-bold mb-0 text-uppercase tracking-wider">Telegram Integration</h6>
                                    </div>

                                    <div class="glass p-3 rounded-16 border-0">
                                        @if(auth()->user()->telegram_chat_id)
                                            <div class="d-flex align-items-center text-success">
                                                <i class="fas fa-check-circle mr-2"></i>
                                                <span class="font-weight-bold">Connected to Telegram</span>
                                            </div>
                                            <p class="text-muted small mt-2 mb-0">You're all set! You can now use the AIStudy bot to create decks and practice daily.</p>
                                        @else
                                            <div id="telegram-link-container">
                                                <p class="text-muted small mb-3">Link your Telegram account to create decks instantly via chat and receive daily practice cards.</p>
                                                
                                                <div id="token-display" class="mb-3 d-none">
                                                    <label class="form-label font-weight-bold small text-muted text-uppercase mb-1">Your Linking Token</label>
                                                    <div class="input-group">
                                                        <input type="text" id="telegram-token-input" class="form-control-modern py-2 bg-light" readonly>
                                                        <div class="input-group-append">
                                                            <button class="btn btn-outline-primary border-2 rounded-right-12" type="button" onclick="copyToken()">
                                                                <i class="fas fa-copy"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="mt-2 small text-primary">
                                                        <i class="fas fa-info-circle mr-1"></i> Send <code>/start [TOKEN]</code> to <a href="https://t.me/{{ env('TELEGRAM_BOT_USERNAME', 'AIStudyBot') }}" target="_blank" class="font-weight-bold">@ {{ env('TELEGRAM_BOT_USERNAME', 'AIStudyBot') }}</a>
                                                    </div>
                                                </div>

                                                <button type="button" id="btn-generate-token" class="btn btn-info btn-soft rounded-pill px-4 py-2 font-weight-bold w-100" onclick="generateToken()">
                                                    <i class="fas fa-link mr-2"></i> Get Linking Token
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="pt-3 border-top d-flex align-items-center justify-content-between">
                                    <a href="{{ route('home') }}" class="btn btn-link text-muted font-weight-bold px-0 small">Discard Changes</a>
                                    <button type="submit" class="btn btn-primary btn-soft rounded-pill px-4 py-2 font-weight-bold shadow-lg hover-up">
                                        <i class="fas fa-save mr-2"></i> Update Profile
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .rounded-24 { border-radius: 24px !important; }
    .rounded-16 { border-radius: 16px !important; }
    
    .form-control-modern {
        display: block;
        width: 100%;
        padding: 0.6rem 1rem;
        font-size: 0.95rem;
        font-weight: 500;
        line-height: 1.5;
        color: var(--text-main);
        background-color: var(--card-bg);
        background-clip: padding-box;
        border: 2px solid var(--border-color);
        border-radius: 12px;
        transition: all 0.2s ease-in-out;
    }

    .form-control-modern:focus {
        border-color: var(--primary-color);
        outline: 0;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15);
        transform: translateY(-1px);
    }
    
    .border-danger-focus:focus {
        border-color: #ef4444;
        box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.15);
    }

    .error-msg {
        color: #ef4444;
        font-size: 0.8rem;
        font-weight: 600;
        margin-top: 6px;
        display: flex;
        align-items: center;
    }

    .tracking-tight { letter-spacing: -0.025em; }
    .tracking-wider { letter-spacing: 0.05em; }
    
    body.dark-mode .form-control-modern {
        background-color: rgba(255, 255, 255, 0.03);
    }
    
    body.dark-mode .bg-primary-soft {
        background: linear-gradient(180deg, rgba(99, 102, 241, 0.1) 0%, rgba(99, 102, 241, 0) 100%) !important;
    }
</style>

@section('scripts')
<script>
    // Preview image on selection
    document.getElementById('profile_photo').addEventListener('change', function(e) {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            // Selector matched to the profile sidebar (col-md-3)
            const img = document.querySelector('.col-md-3 img');
            const fallback = document.querySelector('.col-md-3 .glass i'); // Icon inside fallback
            
            reader.onload = function(e) {
                if (img) {
                    img.src = e.target.result;
                } else if (fallback) {
                    // Replace fallback icon with new image
                    const newImg = document.createElement('img');
                    newImg.src = e.target.result;
                    newImg.className = 'rounded-circle shadow-24 border border-white';
                    newImg.style.width = '120px';
                    newImg.style.height = '120px';
                    newImg.style.objectFit = 'cover';
                    newImg.style.borderWidth = '4px !important';
                    fallback.parentNode.replaceChild(newImg, fallback);
                }
            }
            reader.readAsDataURL(this.files[0]);
        }
    });

    function generateToken() {
        const btn = document.getElementById('btn-generate-token');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Generating...';

        fetch('{{ route("telegram.link") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('telegram-token-input').value = data.token;
                document.getElementById('token-display').classList.remove('d-none');
                btn.classList.add('d-none');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-link mr-2"></i> Get Linking Token';
        });
    }

    function copyToken() {
        const input = document.getElementById('telegram-token-input');
        input.select();
        document.execCommand('copy');
        
        const btn = event.currentTarget;
        const originalIcon = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check"></i>';
        setTimeout(() => {
            btn.innerHTML = originalIcon;
        }, 2000);
    }
</script>
@endsection
@endsection

