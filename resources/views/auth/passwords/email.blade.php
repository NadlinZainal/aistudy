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
        font-size: 0.95rem;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        border: none;
        border-radius: 12px;
        padding: 0.8rem;
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.2);
    }
    
    .back-link {
        text-align: center;
        margin-top: 2rem;
        color: #64748b;
        font-size: 0.9rem;
    }
    
    .back-link a {
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
                <i class="fas fa-key fa-2x text-white"></i>
            </div>
            <h3 class="font-weight-bold text-dark mb-1">Reset Password</h3>
            <p class="text-muted small">Enter your email to receive a reset link</p>
        </div>

        @if (session('status'))
            <div class="alert alert-success border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px;">
                <i class="fas fa-check-circle mr-2"></i> {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="mb-4">
                <label class="form-label">Email Address</label>
                <input id="email" type="email"
                       class="form-control @error('email') is-invalid @enderror"
                       name="email" value="{{ old('email') }}" required autofocus
                       placeholder="name@example.com">
                @error('email')
                    <small class="text-danger mt-1 d-block font-weight-bold">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary w-100 py-3 mt-2">
                Send Reset Link
            </button>
        </form>

        <div class="back-link">
            Wait, I remember it! <a href="{{ route('login') }}">Back to Login</a>
        </div>
    </div>
</div>
@endsection
