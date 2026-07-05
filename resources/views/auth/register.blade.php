@extends('layouts.app')

@section('title', 'Register - Dasmariñas Tourism')

@section('content')
    <section class="auth-section">
        <div class="container">
            <div class="row justify-content-center align-items-center min-vh-auth">
                <div class="col-lg-6 col-md-8">
                    <div class="auth-card">
                        <!-- Header -->
                        <div class="auth-header">
                            <div class="auth-icon">
                                <img src="{{ asset('assets/dasma-logo.png') }}" alt="Dasmariñas" class="auth-logo">
                            </div>
                            <h2 class="auth-title">Create Account</h2>
                            <p class="auth-subtitle">Join Dasmariñas Tourism Community</p>
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

                        <!-- Register Form -->
                        <form method="POST" action="{{ route('register') }}" class="auth-form">
                            @csrf

                            <!-- Name Field -->
                            <div class="form-group-auth">
                                <label for="name" class="form-label-auth">
                                    <i class='bx bx-user'></i> Full Name
                                </label>
                                <input type="text" class="form-control-auth @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name') }}"
                                    placeholder="Enter your full name" required autofocus>
                                @error('name')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email Field -->
                            <div class="form-group-auth">
                                <label for="email" class="form-label-auth">
                                    <i class='bx bx-envelope'></i> Email Address
                                </label>
                                <input type="email" class="form-control-auth @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email') }}"
                                    placeholder="your.email@example.com" required>
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
                                        id="password" name="password" placeholder="Create a strong password" required>
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
                                        name="password_confirmation" placeholder="Re-enter your password" required>
                                    <button type="button" class="toggle-password"
                                        onclick="togglePassword('password_confirmation', 'toggleIcon2')">
                                        <i class='bx bx-show' id="toggleIcon2"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Account Type -->
                            <div class="form-group-auth">
                                <label class="form-label-auth">
                                    <i class='bx bx-user-circle'></i> Account Type
                                </label>
                                <div class="role-selection-grid">
                                    <div class="role-card">
                                        <input class="role-radio" type="radio" name="role" id="roleUser"
                                            value="user" checked>
                                        <label class="role-label" for="roleUser">
                                            <div class="role-icon role-icon-user">
                                                <i class='bx bx-user'></i>
                                            </div>
                                            <div class="role-content">
                                                <strong class="role-title">Regular User</strong>
                                                <p class="role-description">Explore attractions and leave reviews</p>
                                            </div>
                                        </label>
                                    </div>
                                    <div class="role-card">
                                        <input class="role-radio" type="radio" name="role" id="roleBusinessOwner"
                                            value="business_owner">
                                        <label class="role-label" for="roleBusinessOwner">
                                            <div class="role-icon role-icon-business">
                                                <i class='bx bx-store-alt'></i>
                                            </div>
                                            <div class="role-content">
                                                <strong class="role-title">Business Owner</strong>
                                                <p class="role-description">List and promote your business</p>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Terms and Privacy Checkbox uses JS trigger and Bootstrap attributes -->
                            <div class="mb-4 form-check">
                                <input class="form-check-input @error('terms_agreement') is-invalid @enderror" 
                                    type="checkbox" name="terms_agreement" id="termsAgreement" required>
                                    
                                <label class="form-check-label" for="termsAgreement">
                                    I agree to the 
                                    <a href="#" onclick="showPolicyModal('privacy')" 
                                       data-bs-toggle="modal" data-bs-target="#policyModal" 
                                       class="text-primary fw-semibold">Privacy Policy</a>
                                    and the 
                                    <a href="#" onclick="showPolicyModal('terms')" 
                                       data-bs-toggle="modal" data-bs-target="#policyModal" 
                                       class="text-primary fw-semibold">Terms of Use</a>.
                                </label>
                                
                                @error('terms_agreement')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>You must agree to the terms and policies to register.</strong>
                                    </span>
                                @enderror
                            </div>

                            <!-- Register Button -->
                            <button type="submit" class="btn-auth-primary">
                                <i class='bx bx-user-plus me-2'></i>Create Account
                            </button>
                        </form>

                        <!-- Divider -->
                        <div class="auth-divider">
                            <span>or</span>
                        </div>

                        <!-- Login Link -->
                        <div class="auth-footer">
                            <p class="auth-footer-text">Already have an account?</p>
                            <a href="{{ route('login') }}" class="btn-auth-secondary">
                                <i class='bx bx-log-in me-2'></i>Login to Account
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
    
    <!-- Policy Modal HTML Structure -->
    <div class="modal fade" id="policyModal" tabindex="-1" aria-labelledby="policyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
            <div class="modal-content" style="border-radius: 12px;">
                <div class="modal-header bg-primary text-white" style="border-radius: 12px 12px 0 0;">
                    <h5 class="modal-title fw-bold" id="policyModalTitle">Loading...</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="policyModalBody" style="line-height: 1.7; color: #475569;">
                        <!-- Content will be injected here -->
                    </div>
                    
                    <!-- The actual hardcoded content, hidden by default -->
                    <!-- This content will be copied into policyModalBody by JavaScript -->
                    <div id="privacyContent" class="d-none"> 
                        @include('policy_content.privacy_policy')
                    </div>
                    <div id="termsContent" class="d-none"> 
                        @include('policy_content.terms_of_use')
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
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
        
        // ⬅️ FINAL FIX: JavaScript function to pull content directly from hidden Blade sections
        function showPolicyModal(type) {
            const titleElement = document.getElementById('policyModalTitle');
            const bodyContainer = document.getElementById('policyModalBody');
            
            let contentSourceElement;
            let titleText;

            // 1. Identify which content source element and title to use
            if (type === 'privacy') {
                contentSourceElement = document.getElementById('privacyContent');
                titleText = 'Privacy Policy';
            } else if (type === 'terms') {
                contentSourceElement = document.getElementById('termsContent');
                titleText = 'Terms of Use';
            } else {
                return;
            }
            
            // 2. Set the Title
            titleElement.textContent = titleText;
            
            // 3. Inject the content directly from the hidden HTML container
            if (contentSourceElement) {
                // Copy the inner HTML (the fully rendered policy content)
                bodyContainer.innerHTML = contentSourceElement.innerHTML; 
            } else {
                // Fallback error message (only if content injection fails)
                bodyContainer.innerHTML = '<div class="alert alert-danger">Policy content failed to load (Source element missing).</div>';
            }
            // Note: The modal is opened automatically by the data-bs-toggle attribute in the HTML.
        }
    </script>
@endpush