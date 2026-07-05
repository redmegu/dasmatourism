@extends('layouts.app')

@section('title', 'Verify Your Email')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-lg border-0 mt-5">
                    <div class="card-header bg-gradient text-white text-center py-4"
                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <h3 class="mb-0">
                            <i class="fas fa-envelope-open-text me-2"></i>
                            Verify Your Email
                        </h3>
                    </div>
                    <div class="card-body p-4">
                        <!-- Warning Message for Unverified Login Attempt -->
                        @if (session('warning'))
                            <div class="alert alert-warning border-0 alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Verification Required!</strong> {{ session('warning') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <!-- Info Message -->
                        <div class="alert alert-info border-0" role="alert">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Almost there!</strong> We've sent a 6-digit verification code to your email address
                            @if ($email ?? (auth()->check() ? auth()->user()->email : null))
                                : <strong>{{ $email ?? auth()->user()->email }}</strong>
                            @endif
                        </div>

                        <!-- Verification Form -->
                        <form id="verifyForm">
                            @csrf
                            @if ($email ?? false)
                                <input type="hidden" name="email" id="userEmail" value="{{ $email }}">
                            @endif
                            <div class="mb-4">
                                <label for="otp" class="form-label fw-bold">
                                    <i class="fas fa-key me-2"></i>Enter Verification Code
                                </label>
                                <input type="text" class="form-control form-control-lg text-center" id="otp"
                                    name="otp" maxlength="6" pattern="[0-9]{6}" placeholder="000000"
                                    style="letter-spacing: 8px; font-size: 24px; font-family: 'Courier New', monospace;"
                                    required autocomplete="off">
                                <div class="invalid-feedback" id="otpError">
                                    Please enter a valid 6-digit code.
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-lg text-white"
                                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                    <i class="fas fa-check-circle me-2"></i>Verify Email
                                </button>
                            </div>
                        </form>

                        <div class="divider my-4">
                            <hr>
                        </div>

                        <!-- Resend Section -->
                        <div class="text-center">
                            <p class="text-muted mb-2">Didn't receive the code?</p>
                            <button id="resendBtn" class="btn btn-outline-primary">
                                <i class="fas fa-redo me-2"></i>Resend Verification Code
                            </button>
                            <p class="text-muted small mt-3">
                                <i class="fas fa-clock me-1"></i>
                                The verification code expires in <strong>15 minutes</strong>
                            </p>
                        </div>

                        <!-- Logout Option (only show if authenticated) -->
                        @auth
                            <div class="text-center mt-4">
                                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-link text-secondary text-decoration-none">
                                        <i class="fas fa-sign-out-alt me-1"></i>Logout
                                    </button>
                                </form>
                            </div>
                        @endauth

                        <!-- Login Option (only show if not authenticated) -->
                        @guest
                            <div class="text-center mt-4">
                                <a href="{{ route('login') }}" class="btn btn-link text-secondary text-decoration-none">
                                    <i class="fas fa-sign-in-alt me-1"></i>Already have an account? Login
                                </a>
                            </div>
                        @endguest
                    </div>
                </div>

                <!-- Help Section -->
                <div class="card border-0 mt-3">
                    <div class="card-body text-center">
                        <p class="text-muted mb-0">
                            <i class="fas fa-question-circle me-1"></i>
                            Need help? Contact us at
                            <a href="mailto:{{ config('mail.from.address') }}">{{ config('mail.from.address') }}</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            $(document).ready(function() {
                // Auto-format OTP input (numbers only)
                $('#otp').on('input', function() {
                    this.value = this.value.replace(/[^0-9]/g, '');
                });

                // Handle verification form submission
                $('#verifyForm').on('submit', function(e) {
                    e.preventDefault();

                    const otp = $('#otp').val();
                    const submitBtn = $(this).find('button[type="submit"]');
                    const originalBtnText = submitBtn.html();

                    // Validate OTP format
                    if (otp.length !== 6 || !/^\d+$/.test(otp)) {
                        $('#otp').addClass('is-invalid');
                        return;
                    }

                    $('#otp').removeClass('is-invalid');
                    submitBtn.prop('disabled', true).html(
                        '<i class="fas fa-spinner fa-spin me-2"></i>Verifying...');

                    // Get email if present (for non-authenticated users)
                    const email = $('#userEmail').val();
                    const postData = {
                        _token: '{{ csrf_token() }}',
                        otp: otp
                    };
                    if (email) {
                        postData.email = email;
                    }

                    $.ajax({
                        url: '{{ route('auth.verify.post') }}',
                        method: 'POST',
                        data: postData,
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Email Verified!',
                                    text: response.message,
                                    showConfirmButton: false,
                                    timer: 2000
                                }).then(() => {
                                    window.location.href = response.redirect;
                                });
                            }
                        },
                        error: function(xhr) {
                            submitBtn.prop('disabled', false).html(originalBtnText);

                            const message = xhr.responseJSON?.message ||
                                'An error occurred. Please try again.';

                            Swal.fire({
                                icon: 'error',
                                title: 'Verification Failed',
                                text: message,
                                confirmButtonColor: '#667eea'
                            });

                            $('#otp').addClass('is-invalid');
                            $('#otpError').text(message);
                        }
                    });
                });

                // Handle resend button
                $('#resendBtn').on('click', function() {
                    const btn = $(this);
                    const originalBtnText = btn.html();

                    btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Sending...');

                    // Get email if present (for non-authenticated users)
                    const email = $('#userEmail').val();
                    const postData = {
                        _token: '{{ csrf_token() }}'
                    };
                    if (email) {
                        postData.email = email;
                    }

                    $.ajax({
                        url: '{{ route('auth.verify.resend') }}',
                        method: 'POST',
                        data: postData,
                        success: function(response) {
                            btn.prop('disabled', false).html(originalBtnText);

                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Code Resent!',
                                    text: response.message,
                                    confirmButtonColor: '#667eea'
                                });

                                // Clear the OTP input
                                $('#otp').val('').removeClass('is-invalid');
                            }
                        },
                        error: function(xhr) {
                            btn.prop('disabled', false).html(originalBtnText);

                            const message = xhr.responseJSON?.message ||
                                'Failed to resend code. Please try again.';

                            Swal.fire({
                                icon: 'error',
                                title: 'Resend Failed',
                                text: message,
                                confirmButtonColor: '#667eea'
                            });
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection
