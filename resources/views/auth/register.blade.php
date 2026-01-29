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
        max-width: 550px;
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
    
    .login-footer {
        text-align: center;
        margin-top: 2rem;
        color: #64748b;
        font-size: 0.9rem;
    }
    
    .login-footer a {
        color: #6366f1;
        font-weight: 600;
        text-decoration: none;
    }

    .form-row {
        display: flex;
        gap: 1rem;
        margin-bottom: 1rem;
    }
    .form-col { flex: 1; }

    @media (max-width: 576px) {
        .form-row { flex-direction: column; gap: 0; }
        .auth-card { padding: 2rem; }
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
            <h3 class="font-weight-bold tracking-tight text-dark mb-1">Create Account</h3>
            <p class="text-muted small">Begin your AI-powered learning experience</p>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="form-row">
                <div class="form-col mb-3">
                    <label class="form-label">Full Name</label>
                    <input id="name" type="text"
                           class="form-control @error('name') is-invalid @enderror"
                           name="name" value="{{ old('name') }}" required
                           placeholder="John Doe">
                    @error('name')
                        <small class="text-danger mt-1 d-block font-weight-bold">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-col mb-3">
                    <label class="form-label">Phone Number</label>
                    <input id="phone_number" type="text"
                           class="form-control @error('phone_number') is-invalid @enderror"
                           name="phone_number" value="{{ old('phone_number') }}" required
                           placeholder="012-3456789">
                    @error('phone_number')
                        <small class="text-danger mt-1 d-block font-weight-bold">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input id="email" type="email"
                       class="form-control @error('email') is-invalid @enderror"
                       name="email" value="{{ old('email') }}" required
                       placeholder="name@example.com">
                @error('email')
                    <small class="text-danger mt-1 d-block font-weight-bold">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Address</label>
                <input id="address" type="text"
                       class="form-control @error('address') is-invalid @enderror"
                       name="address" value="{{ old('address') }}" required
                       placeholder="123 Street Name, City">
                @error('address')
                    <small class="text-danger mt-1 d-block font-weight-bold">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-row">
                <div class="form-col mb-3">
                    <label class="form-label">Password</label>
                    <input id="password" type="password"
                           class="form-control @error('password') is-invalid @enderror"
                           name="password" required
                           placeholder="••••••••">
                    @error('password')
                        <small class="text-danger mt-1 d-block font-weight-bold">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-col mb-4">
                    <label class="form-label">Confirm Password</label>
                    <input id="password-confirm" type="password"
                           class="form-control"
                           name="password_confirmation" required
                           placeholder="••••••••">
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-3 mt-2">
                Create Account
            </button>
        </form>

        <div class="login-footer">
            Already have an account? <a href="{{ route('login') }}">Sign In</a>
        </div>
    </div>
</div>
@endsection

