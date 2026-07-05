@extends('layouts.app')

@section('title', 'Login - Dasmariñas Tourism')

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
                            <h2 class="auth-title">Welcome Back</h2>
                            <p class="auth-subtitle">Login to continue your journey</p>
                        </div>

                        <!-- Success Messages -->
                        @if (session('status'))
                            <div class="alert alert-success auth-alert">
                                <i class='bx bx-check-circle'></i>
                                <div>
                                    <strong>Success!</strong>
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

                        <!-- Login Form -->
                        <form method="POST" action="{{ route('login') }}" class="auth-form">
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

                            <!-- Password Field -->
                            <div class="form-group-auth">
                                <label for="password" class="form-label-auth">
                                    <i class='bx bx-lock-alt'></i> Password
                                </label>
                                <div class="password-input-wrapper">
                                    <input type="password" class="form-control-auth @error('password') is-invalid @enderror"
                                        id="password" name="password" placeholder="Enter your password" required>
                                    <button type="button" class="toggle-password" onclick="togglePassword()">
                                        <i class='bx bx-show' id="toggleIcon"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Remember Me & Forgot Password -->
                            <div class="auth-options">
                                <div class="form-check-auth">
                                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                    <label class="form-check-label" for="remember">
                                        Remember me
                                    </label>
                                </div>
                                <a href="{{ route('password.request') }}" class="forgot-link">
                                    Forgot password?
                                </a>
                            </div>

                            <!-- Login Button -->
                            <button type="submit" class="btn-auth-primary">
                                <i class='bx bx-log-in me-2'></i>Login to Account
                            </button>
                        </form>

                        <!-- Divider -->
                        <div class="auth-divider">
                            <span>or</span>
                        </div>

                        <!-- Register Link -->
                        <div class="auth-footer">
                            <p class="auth-footer-text">Don't have an account?</p>
                            <a href="{{ route('register') }}" class="btn-auth-secondary">
                                <i class='bx bx-user-plus me-2'></i>Create New Account
                            </a>
                        </div>

                        <!-- Back to Home -->
                        <div class="text-center mt-4">
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

@push('scripts')
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('bx-show');
                toggleIcon.classList.add('bx-hide');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('bx-hide');
                toggleIcon.classList.add('bx-show');
            }
        }

        // Show success dialog for password reset
        @if (session('status'))
            Swal.fire({
                icon: 'success',
                title: 'Password Reset Successfully!',
                text: '{{ session('status') }}',
                confirmButtonText: 'Login Now',
                confirmButtonColor: '#667eea',
                allowOutsideClick: true
            });
        @endif

        // Check if user tried to login with unverified email
        @if (session('unverified'))
            Swal.fire({
                icon: 'warning',
                title: 'Email Not Verified',
                html: '<p>Your email address <strong>{{ session('email') }}</strong> has not been verified yet.</p>' +
                    '<p>Please verify your email before logging in.</p>' +
                    '<p class="mt-3">Need to verify your account?</p>',
                showCancelButton: true,
                confirmButtonText: '<i class="fas fa-envelope me-2"></i>Go to Verification',
                cancelButtonText: '<i class="fas fa-redo me-2"></i>Resend Code',
                confirmButtonColor: '#667eea',
                cancelButtonColor: '#6c757d',
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect to login first (to authenticate) then to verification
                    window.location.href = '{{ route('auth.verify.show') }}';
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    // Show resend form
                    Swal.fire({
                        title: 'Resend Verification Code',
                        html: '<p>Enter your email to receive a new verification code:</p>' +
                            '<input type="email" id="resendEmail" class="swal2-input" placeholder="Email Address" value="{{ session('email') ?? '' }}">',
                        showCancelButton: true,
                        confirmButtonText: 'Send Code',
                        confirmButtonColor: '#667eea',
                        preConfirm: () => {
                            const email = document.getElementById('resendEmail').value;
                            if (!email) {
                                Swal.showValidationMessage('Please enter your email address');
                                return false;
                            }
                            return email;
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // In a real scenario, you'd need to authenticate first to resend
                            // For now, just show a message
                            Swal.fire({
                                icon: 'info',
                                title: 'Login Required',
                                text: 'Please login first, then you can resend the verification code from the verification page.',
                                confirmButtonColor: '#667eea'
                            });
                        }
                    });
                }
            });
        @endif
    </script>
@endpush
