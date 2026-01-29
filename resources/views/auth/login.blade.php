@extends('layouts.app')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    body {
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    #app { width: 100%; }
    nav.navbar { display: none; }
    
    .auth-container {
        max-width: 450px;
        width: 100%;
        margin: 2rem auto;
    }
    
    .auth-card {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(10px);
        border-radius: 24px;
        border: 1px solid rgba(255, 255, 255, 0.5);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.05);
        padding: 3rem;
        transition: transform 0.3s ease;
    }
    
    .brand-logo {
        width: 64px;
        height: 64px;
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        box-shadow: 0 10px 20px rgba(79, 70, 229, 0.2);
    }
    
    .form-label {
        font-weight: 600;
        font-size: 0.85rem;
        color: #475569;
        margin-bottom: 0.5rem;
    }
    
    .form-control {
        border-radius: 12px;
        padding: 0.75rem 1rem;
        border: 2px solid #e2e8f0;
        transition: all 0.2s;
        font-size: 0.95rem;
    }
    
    .form-control:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        border: none;
        border-radius: 12px;
        padding: 0.8rem;
        font-weight: 600;
        letter-spacing: 0.02em;
        transition: all 0.3s;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.2);
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(99, 102, 241, 0.3);
        background: linear-gradient(135deg, #4f46e5, #4338ca);
    }
    
    .remember-check {
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #64748b;
        font-size: 0.9rem;
    }
    
    .remember-check input {
        width: 18px;
        height: 18px;
        border-radius: 6px;
        cursor: pointer;
    }
    
    .forgot-link {
        color: #6366f1;
        font-weight: 500;
        font-size: 0.9rem;
        text-decoration: none;
    }
    
    .forgot-link:hover { text-decoration: underline; }
    
    .register-footer {
        text-align: center;
        margin-top: 2rem;
        color: #64748b;
        font-size: 0.9rem;
    }
    
    .register-footer a {
        color: #6366f1;
        font-weight: 600;
        text-decoration: none;
    }
</style>
@endsection

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <div class="text-center mb-5">
            <div class="brand-logo">
                <i class="fas fa-graduation-cap fa-2x text-white"></i>
            </div>
            <h3 class="font-weight-bold tracking-tight text-dark mb-1">Welcome back!</h3>
            <p class="text-muted small">Continue your learning journey with AIStudy</p>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-4">
                <label class="form-label">Email Address</label>
                <div class="input-group">
                    <input id="email" type="email"
                           class="form-control @error('email') is-invalid @enderror"
                           name="email" value="{{ old('email') }}" required autofocus
                           placeholder="name@example.com">
                </div>
                @error('email')
                    <small class="text-danger mt-1 d-block font-weight-bold">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-4">
                <label class="form-label d-flex justify-content-between">
                    Password
                    @if (Route::has('password.request'))
                        <a class="forgot-link" href="{{ route('password.request') }}">
                            Forgot?
                        </a>
                    @endif
                </label>
                <input id="password" type="password"
                       class="form-control @error('password') is-invalid @enderror"
                       name="password" required
                       placeholder="••••••••">
                @error('password')
                    <small class="text-danger mt-1 d-block font-weight-bold">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-4">
                <label class="remember-check">
                    <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <span>Keep me logged in</span>
                </label>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-3 mt-2">
                Sign In
            </button>
        </form>

        <div class="register-footer">
            Don't have an account? <a href="{{ route('register') }}">Create account</a>
        </div>
    </div>
</div>
@endsection
