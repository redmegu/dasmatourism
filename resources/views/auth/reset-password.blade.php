@extends('layouts.app')

@section('title', 'Reset Password - Dasmariñas Tourism')

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
                            <h2 class="auth-title">Reset Password</h2>
                            <p class="auth-subtitle">
                                Enter your new password below to reset your account password.
                            </p>
                        </div>

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
                        <form method="POST" action="{{ route('password.update') }}" class="auth-form">
                            @csrf
                            <input type="hidden" name="token" value="{{ $request->route('token') }}">

                            <!-- Email Field -->
                            <div class="form-group-auth">
                                <label for="email" class="form-label-auth">
                                    <i class='bx bx-envelope'></i> Email Address
                                </label>
                                <input type="email" class="form-control-auth @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email', $request->email) }}"
                                    placeholder="your.email@example.com" required readonly
                                    style="background-color: #f5f5f5; cursor: not-allowed;">
                                @error('email')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Password Field -->
                            <div class="form-group-auth">
                                <label for="password" class="form-label-auth">
                                    <i class='bx bx-lock-alt'></i> New Password
                                </label>
                                <div class="password-input-wrapper">
                                    <input type="password" class="form-control-auth @error('password') is-invalid @enderror"
                                        id="password" name="password" placeholder="Enter new password" required>
                                    <button type="button" class="toggle-password"
                                        onclick="togglePassword('password', 'toggleIcon1')">
                                        <i class='bx bx-show' id="toggleIcon1"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Confirm Password Field -->
                            <div class="form-group-auth">
                                <label for="password_confirmation" class="form-label-auth">
                                    <i class='bx bx-lock-alt'></i> Confirm Password
                                </label>
                                <div class="password-input-wrapper">
                                    <input type="password" class="form-control-auth" id="password_confirmation"
                                        name="password_confirmation" placeholder="Confirm new password" required>
                                    <button type="button" class="toggle-password"
                                        onclick="togglePassword('password_confirmation', 'toggleIcon2')">
                                        <i class='bx bx-show' id="toggleIcon2"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn-auth-primary">
                                <i class='bx bx-check-circle me-2'></i>Reset Password
                            </button>
                        </form>
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
        function togglePassword(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const toggleIcon = document.getElementById(iconId);

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

        // Check for success message from session
        @if (session('status'))
            Swal.fire({
                icon: 'success',
                title: 'Password Reset Successfully!',
                text: 'Your password has been successfully updated. You can now log in with your new password.',
                confirmButtonText: 'Go to Login',
                confirmButtonColor: '#667eea',
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '{{ route('login') }}';
                }
            });
        @endif
    </script>
@endpush
