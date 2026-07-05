@extends('layouts.app')

@section('title', 'Forgot Password - Dasmariñas Tourism')

@section('content')
    <section class="auth-section">
        <div class="container">
            <div class="row justify-content-center align-items-center min-vh-auth">
                <div class="col-lg-5 col-md-7">
                    <!-- Auth Card -->
                    <div class="auth-card">
                        <!-- Header -->
                        <div class="auth-header">
                            <div class="auth-icon">
                                <img src="{{ asset('assets/dasma-logo.png') }}" alt="Dasmariñas" class="auth-logo">
                            </div>
                            <h2 class="auth-title">Forgot Password?</h2>
                            <p class="auth-subtitle">
                                No problem. Just enter your email address and we'll send you a password reset link.
                            </p>
                        </div>

                        <!-- Success Message -->
                        @if (session('status'))
                            <div class="alert alert-success auth-alert-success">
                                <i class='bx bx-check-circle'></i>
                                <div>
                                    <strong>Email Sent!</strong>
                                    <p class="mb-0 mt-1">{{ session('status') }}</p>
                                </div>
                            </div>
                        @endif

                        <!-- Error Messages -->
                        @if ($errors->any())
                            <div class="alert alert-danger auth-alert">
                                <i class='bx bx-error-circle'></i>
                                <div>
                                    <strong>Oops! Something went wrong.</strong>
                                    <ul class="mb-0 mt-2">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        <!-- Reset Form -->
                        <form method="POST" action="{{ route('password.email') }}" class="auth-form">
                            @csrf

                            <!-- Email Field -->
                            <div class="form-group-auth">
                                <label for="email" class="form-label-auth">
                                    <i class='bx bx-envelope'></i> Email Address
                                </label>
                                <input type="email" class="form-control-auth @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email') }}"
                                    placeholder="your.email@example.com" required autofocus>
                                @error('email')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn-auth-primary">
                                <i class='bx bx-mail-send me-2'></i>Send Reset Link
                            </button>
                        </form>

                        <!-- Divider -->
                        <div class="auth-divider">
                            <span>or</span>
                        </div>

                        <!-- Back to Login -->
                        <div class="text-center">
                            <a href="{{ route('login') }}" class="btn-auth-secondary">
                                <i class='bx bx-arrow-back me-2'></i>Back to Login
                            </a>
                        </div>

                        <!-- Additional Links -->
                        <div class="text-center mt-4">
                            <p class="auth-footer-text mb-2">Don't have an account?</p>
                            <a href="{{ route('register') }}" class="forgot-link">
                                Create New Account
                            </a>
                        </div>

                        <!-- Back to Home -->
                        <div class="text-center mt-3">
                            <a href="{{ route('home') }}" class="back-home-link">
                                <i class='bx bx-arrow-back me-1'></i>Back to Home
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
