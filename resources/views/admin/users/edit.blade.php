@extends('layouts.admin')

@section('page-title', 'Edit User')

@section('content')
    <!-- Enhanced Page Header -->
    <div class="admin-page-header mb-4">
        <div class="admin-page-header-content">
            <div>
                <div class="admin-breadcrumb mb-2">
                    <a href="{{ route('admin.users.index') }}" class="admin-breadcrumb-link">
                        <i class='bx bx-user'></i> Users
                    </a>
                    <i class='bx bx-chevron-right'></i>
                    <a href="{{ route('admin.users.show', $user) }}" class="admin-breadcrumb-link">
                        {{ $user->name }}
                    </a>
                    <i class='bx bx-chevron-right'></i>
                    <span class="admin-breadcrumb-current">Edit</span>
                </div>
                <h2 class="admin-page-title">Edit User</h2>
                <p class="admin-page-subtitle">Update user account information</p>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.users.update', $user) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row g-4">
            <!-- Main Form -->
            <div class="col-lg-8">
                <!-- Basic Information -->
                <div class="admin-form-section-card mb-4">
                    <div class="admin-form-section-header">
                        <div class="admin-form-section-icon primary">
                            <i class='bx bx-user-circle'></i>
                        </div>
                        <div>
                            <h5 class="admin-form-section-title">Basic Information</h5>
                            <p class="admin-form-section-subtitle">User account details</p>
                        </div>
                    </div>
                    <div class="admin-form-section-body">
                        <div class="admin-form-group">
                            <label class="admin-form-label">
                                <i class='bx bx-user'></i>
                                Full Name
                                <span class="admin-required">*</span>
                            </label>
                            <input type="text" class="admin-form-input @error('name') is-invalid @enderror"
                                name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="admin-form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="admin-form-group">
                            <label class="admin-form-label">
                                <i class='bx bx-envelope'></i>
                                Email Address
                                <span class="admin-required">*</span>
                            </label>
                            <input type="email" class="admin-form-input @error('email') is-invalid @enderror"
                                name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="admin-form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="admin-form-group">
                            <label class="admin-form-label">
                                <i class='bx bx-shield'></i>
                                User Role
                                <span class="admin-required">*</span>
                            </label>
                            <select class="admin-form-select @error('role') is-invalid @enderror" name="role" required>
                                <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>
                                    User
                                </option>
                                <option value="business_owner"
                                    {{ old('role', $user->role) === 'business_owner' ? 'selected' : '' }}>
                                    Business Owner
                                </option>
                                <option value="administrator"
                                    {{ old('role', $user->role) === 'administrator' ? 'selected' : '' }}>
                                    Administrator
                                </option>
                            </select>
                            @error('role')
                                <div class="admin-form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Password Change -->
                <div class="admin-form-section-card">
                    <div class="admin-form-section-header">
                        <div class="admin-form-section-icon warning">
                            <i class='bx bx-lock'></i>
                        </div>
                        <div>
                            <h5 class="admin-form-section-title">Change Password</h5>
                            <p class="admin-form-section-subtitle">Update user password (optional)</p>
                        </div>
                    </div>
                    <div class="admin-form-section-body">
                        <div class="admin-info-notice">
                            <i class='bx bx-info-circle'></i>
                            <span>Leave password fields empty to keep the current password</span>
                        </div>

                        <div class="admin-form-group">
                            <label class="admin-form-label">
                                <i class='bx bx-key'></i>
                                New Password
                            </label>
                            <input type="password" class="admin-form-input @error('password') is-invalid @enderror"
                                name="password">
                            <small class="admin-form-hint">Minimum 8 characters</small>
                            @error('password')
                                <div class="admin-form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="admin-form-group">
                            <label class="admin-form-label">
                                <i class='bx bx-check-shield'></i>
                                Confirm New Password
                            </label>
                            <input type="password" class="admin-form-input" name="password_confirmation">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- User Info -->
                <div class="admin-user-preview-card mb-4">
                    <div class="admin-user-preview-header">
                        <i class='bx bx-user-circle'></i>
                        <span>User Profile</span>
                    </div>
                    <div class="admin-user-preview-body">
                        <div class="admin-user-preview-avatar">
                            @if ($user->profile && $user->profile->profile_picture)
                                <img src="{{ asset('storage/' . $user->profile->profile_picture) }}"
                                    alt="{{ $user->name }}" class="admin-user-preview-img">
                            @else
                                <div class="admin-user-preview-placeholder">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <div class="admin-user-preview-info">
                            <strong class="admin-user-preview-name">{{ $user->name }}</strong>
                            <p class="admin-user-preview-email">{{ $user->email }}</p>
                            <span class="admin-user-preview-date">
                                <i class='bx bx-calendar'></i>
                                Joined {{ $user->created_at->format('M d, Y') }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Settings -->
                <div class="admin-settings-card mb-4">
                    <div class="admin-settings-card-header">
                        <i class='bx bx-cog'></i>
                        <span>Account Settings</span>
                    </div>
                    <div class="admin-settings-card-body">
                        <div class="admin-toggle-setting">
                            <div class="admin-toggle-info">
                                <strong>Active Account</strong>
                                <span>User can access the system</span>
                            </div>
                            <label class="admin-toggle-switch">
                                <input type="checkbox" name="is_active" value="1"
                                    {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                                <span class="admin-toggle-slider"></span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="admin-action-final-card">
                    <div class="admin-action-final-body">
                        <button type="submit" class="admin-submit-btn primary">
                            <i class='bx bx-save'></i>
                            <span>Update User</span>
                        </button>
                        <a href="{{ route('admin.users.show', $user) }}" class="admin-submit-btn secondary">
                            <i class='bx bx-x'></i>
                            <span>Cancel</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('styles')
    <style>
        /* Page Header */
        .admin-page-header {
            background: linear-gradient(135deg, #ffffff, #f8fafc);
            border-radius: 16px;
            padding: 2rem;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .admin-page-header-content {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 2rem;
        }

        .admin-breadcrumb {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            color: #64748b;
            margin-bottom: 0.5rem;
        }

        .admin-breadcrumb-link {
            display: flex;
            align-items: center;
            gap: 0.375rem;
            color: #1a7838;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .admin-breadcrumb-link:hover {
            color: #27a345;
        }

        .admin-breadcrumb-current {
            color: #94a3b8;
        }

        .admin-page-title {
            font-size: 1.75rem;
            font-weight: 800;
            color: #1e293b;
            margin-bottom: 0.375rem;
        }

        .admin-page-subtitle {
            color: #64748b;
            margin: 0;
            font-size: 0.9375rem;
        }

        /* Form Section Cards */
        .admin-form-section-card {
            background: white;
            border-radius: 14px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #f1f5f9;
            overflow: hidden;
        }

        .admin-form-section-header {
            padding: 1.5rem;
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .admin-form-section-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            flex-shrink: 0;
        }

        .admin-form-section-icon.primary {
            background: linear-gradient(135deg, #1a7838, #27a345);
        }

        .admin-form-section-icon.warning {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }

        .admin-form-section-title {
            font-size: 1.125rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }

        .admin-form-section-subtitle {
            font-size: 0.8125rem;
            color: #64748b;
            margin: 0.25rem 0 0 0;
        }

        .admin-form-section-body {
            padding: 1.5rem;
        }

        /* Form Elements */
        .admin-form-group {
            margin-bottom: 1.25rem;
        }

        .admin-form-group:last-child {
            margin-bottom: 0;
        }

        .admin-form-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 0.625rem;
        }

        .admin-form-label i {
            font-size: 1rem;
            color: #64748b;
        }

        .admin-required {
            color: #dc2626;
            font-weight: 700;
        }

        .admin-form-input,
        .admin-form-select {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 0.9375rem;
            transition: all 0.25s ease;
            background: white;
        }

        .admin-form-input:focus,
        .admin-form-select:focus {
            outline: none;
            border-color: #1a7838;
            box-shadow: 0 0 0 4px rgba(26, 120, 56, 0.1);
        }

        .admin-form-hint {
            display: block;
            margin-top: 0.5rem;
            font-size: 0.8125rem;
            color: #64748b;
        }

        .admin-form-error {
            margin-top: 0.5rem;
            font-size: 0.8125rem;
            color: #dc2626;
            display: flex;
            align-items: center;
            gap: 0.375rem;
        }

        /* Info Notice */
        .admin-info-notice {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem;
            background: #dbeafe;
            border-radius: 10px;
            margin-bottom: 1.25rem;
        }

        .admin-info-notice i {
            font-size: 1.25rem;
            color: #0284c7;
            flex-shrink: 0;
        }

        .admin-info-notice span {
            font-size: 0.875rem;
            color: #0c4a6e;
        }

        /* User Preview Card */
        .admin-user-preview-card {
            background: white;
            border-radius: 14px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #f1f5f9;
            overflow: hidden;
        }

        .admin-user-preview-header {
            padding: 1.25rem 1.5rem;
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 700;
            color: #1e293b;
        }

        .admin-user-preview-header i {
            font-size: 1.25rem;
            color: #1a7838;
        }

        .admin-user-preview-body {
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
        }

        .admin-user-preview-img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #e2e8f0;
        }

        .admin-user-preview-placeholder {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1a7838, #27a345);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 2rem;
            border: 3px solid #e2e8f0;
        }

        .admin-user-preview-info {
            text-align: center;
        }

        .admin-user-preview-name {
            display: block;
            font-size: 1.125rem;
            color: #1e293b;
            margin-bottom: 0.375rem;
        }

        .admin-user-preview-email {
            font-size: 0.9375rem;
            color: #64748b;
            margin: 0 0 0.5rem 0;
        }

        .admin-user-preview-date {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.375rem;
            font-size: 0.8125rem;
            color: #94a3b8;
        }

        /* Settings Card */
        .admin-settings-card {
            background: white;
            border-radius: 14px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #f1f5f9;
            overflow: hidden;
        }

        .admin-settings-card-header {
            padding: 1.25rem 1.5rem;
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 700;
            color: #1e293b;
        }

        .admin-settings-card-header i {
            font-size: 1.25rem;
            color: #1a7838;
        }

        .admin-settings-card-body {
            padding: 1.5rem;
        }

        /* Toggle Setting */
        .admin-toggle-setting {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }

        .admin-toggle-info {
            flex: 1;
        }

        .admin-toggle-info strong {
            display: block;
            font-size: 0.9375rem;
            color: #1e293b;
            margin-bottom: 0.25rem;
        }

        .admin-toggle-info span {
            display: block;
            font-size: 0.8125rem;
            color: #64748b;
        }

        .admin-toggle-switch {
            position: relative;
            display: inline-block;
            width: 52px;
            height: 28px;
            flex-shrink: 0;
        }

        .admin-toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .admin-toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #cbd5e1;
            transition: 0.3s;
            border-radius: 28px;
        }

        .admin-toggle-slider:before {
            position: absolute;
            content: "";
            height: 20px;
            width: 20px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: 0.3s;
            border-radius: 50%;
        }

        input:checked+.admin-toggle-slider {
            background: linear-gradient(135deg, #1a7838, #27a345);
        }

        input:checked+.admin-toggle-slider:before {
            transform: translateX(24px);
        }

        /* Action Final Card */
        .admin-action-final-card {
            background: white;
            border-radius: 14px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #f1f5f9;
        }

        .admin-action-final-body {
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        /* Submit Buttons */
        .admin-submit-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.875rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.9375rem;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            text-align: center;
        }

        .admin-submit-btn.primary {
            background: linear-gradient(135deg, #1a7838, #27a345);
            color: white;
            box-shadow: 0 4px 12px rgba(26, 120, 56, 0.25);
        }

        .admin-submit-btn.primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(26, 120, 56, 0.35);
            color: white;
        }

        .admin-submit-btn.secondary {
            background: #f1f5f9;
            color: #64748b;
        }

        .admin-submit-btn.secondary:hover {
            background: #e2e8f0;
            color: #64748b;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .admin-page-header {
                padding: 1.5rem;
            }

            .admin-form-section-header {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
@endpush
