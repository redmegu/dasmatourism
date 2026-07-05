@extends('layouts.app')

@section('title', 'Edit Profile - Dasmariñas Tourism')

@section('content')
    <section class="profile-section">
        <div class="container">
            <div class="row">
                <!-- Sidebar -->
                <div class="col-lg-3 mb-4">
                    <div class="profile-sidebar">
                        <div class="profile-avatar">
                            @if ($user->profile && $user->profile->profile_picture)
                                <img src="{{ asset('storage/' . $user->profile->profile_picture) }}" alt="{{ $user->name }}"
                                    class="avatar-image">
                            @else
                                <div class="avatar-circle">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <h5 class="profile-name">{{ $user->name }}</h5>
                        <p class="profile-email">{{ $user->email }}</p>
                        <span class="profile-badge">{{ ucfirst(str_replace('_', ' ', $user->role)) }}</span>

                        <div class="profile-menu">
                            <a href="{{ route('user.profile.show') }}" class="profile-menu-item">
                                <i class='bx bx-user'></i> Profile
                            </a>
                            <a href="{{ route('user.profile.edit') }}" class="profile-menu-item active">
                                <i class='bx bx-edit'></i> Edit Profile
                            </a>
                            @if (Route::has('user.bookmarks.index'))
                                <a href="{{ route('user.bookmarks.index') }}" class="profile-menu-item">
                                    <i class='bx bx-bookmark'></i> My Bookmarks
                                </a>
                            @endif
                            @if (Route::has('user.reviews.index'))
                                <a href="{{ route('user.reviews.index') }}" class="profile-menu-item">
                                    <i class='bx bx-message-square-detail'></i> My Reviews
                                </a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}" class="m-0">
                                @csrf
                                <button type="submit" class="profile-menu-item profile-logout">
                                    <i class='bx bx-log-out'></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="col-lg-9">
                    <!-- Profile Header -->
                    <div class="profile-header-card">
                        <h2 class="profile-header-title">
                            <i class='bx bx-edit'></i> Edit Profile
                        </h2>
                        <p class="profile-header-subtitle">Update your account information</p>
                    </div>

                    <!-- Success Message -->
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class='bx bx-check-circle me-2'></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Error Messages -->
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class='bx bx-error-circle me-2'></i>
                            <strong>Please fix the following errors:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('user.profile.update') }}" enctype="multipart/form-data"
                        id="profileForm">
                        @csrf
                        @method('PUT')

                        <!-- Profile Picture -->
                        <div class="profile-card">
                            <div class="profile-card-header">
                                <h5 class="profile-card-title">
                                    <i class='bx bx-image'></i> Profile Picture
                                </h5>
                            </div>
                            <div class="profile-card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-4 text-center mb-3 mb-md-0">
                                        <div class="profile-picture-preview">
                                            @if ($user->profile && $user->profile->profile_picture)
                                                <img src="{{ asset('storage/' . $user->profile->profile_picture) }}"
                                                    alt="{{ $user->name }}" id="preview-image"
                                                    class="profile-preview-img">
                                            @else
                                                <div class="profile-preview-placeholder" id="preview-placeholder">
                                                    <i class='bx bx-user'></i>
                                                </div>
                                                <img src="" alt="Preview" id="preview-image"
                                                    class="profile-preview-img" style="display: none;">
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <label for="profile_picture" class="form-label fw-semibold">
                                            <i class='bx bx-upload'></i> Upload New Picture
                                        </label>
                                        <input type="file"
                                            class="form-control @error('profile_picture') is-invalid @enderror"
                                            id="profile_picture" name="profile_picture"
                                            accept="image/jpeg,image/png,image/jpg" onchange="previewImage(event)">
                                        <small class="text-muted d-block mt-2">
                                            <i class='bx bx-info-circle'></i> JPG, PNG only. Max size: 2MB
                                        </small>
                                        @error('profile_picture')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Basic Information -->
                        <div class="profile-card">
                            <div class="profile-card-header">
                                <h5 class="profile-card-title">
                                    <i class='bx bx-user'></i> Basic Information
                                </h5>
                            </div>
                            <div class="profile-card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="name" class="form-label fw-semibold">
                                            <i class='bx bx-user'></i> Full Name
                                        </label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name', $user->name) }}"
                                            required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="email" class="form-label fw-semibold">
                                            <i class='bx bx-envelope'></i> Email Address
                                        </label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email', $user->email) }}"
                                            required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="phone" class="form-label fw-semibold">
                                            <i class='bx bx-phone'></i> Phone Number
                                        </label>
                                        <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                            id="phone" name="phone"
                                            value="{{ old('phone', $user->profile->phone ?? '') }}">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="birth_date" class="form-label fw-semibold">
                                            <i class='bx bx-calendar'></i> Birth Date
                                        </label>
                                        <input type="date"
                                            class="form-control @error('birth_date') is-invalid @enderror" id="birth_date"
                                            name="birth_date"
                                            value="{{ old('birth_date', $user->profile && $user->profile->birth_date ? $user->profile->birth_date->format('Y-m-d') : '') }}">
                                        @error('birth_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="gender" class="form-label fw-semibold">
                                            <i class='bx bx-user-circle'></i> Gender
                                        </label>
                                        <select class="form-select @error('gender') is-invalid @enderror" id="gender"
                                            name="gender">
                                            <option value="">Select Gender</option>
                                            <option value="male"
                                                {{ old('gender', $user->profile->gender ?? '') == 'male' ? 'selected' : '' }}>
                                                Male</option>
                                            <option value="female"
                                                {{ old('gender', $user->profile->gender ?? '') == 'female' ? 'selected' : '' }}>
                                                Female</option>
                                            <option value="other"
                                                {{ old('gender', $user->profile->gender ?? '') == 'other' ? 'selected' : '' }}>
                                                Other</option>
                                        </select>
                                        @error('gender')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label for="address" class="form-label fw-semibold">
                                            <i class='bx bx-map-pin'></i> Address
                                        </label>
                                        <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3">{{ old('address', $user->profile->address ?? '') }}</textarea>
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Change Password -->
                        <div class="profile-card">
                            <div class="profile-card-header">
                                <h5 class="profile-card-title">
                                    <i class='bx bx-lock-alt'></i> Change Password
                                </h5>
                            </div>
                            <div class="profile-card-body">
                                <p class="text-muted mb-3">Leave blank if you don't want to change your password</p>
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <label for="current_password" class="form-label fw-semibold">
                                            <i class='bx bx-key'></i> Current Password
                                        </label>
                                        <input type="password"
                                            class="form-control @error('current_password') is-invalid @enderror"
                                            id="current_password" name="current_password">
                                        @error('current_password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="new_password" class="form-label fw-semibold">
                                            <i class='bx bx-lock-alt'></i> New Password
                                        </label>
                                        <input type="password"
                                            class="form-control @error('new_password') is-invalid @enderror"
                                            id="new_password" name="new_password">
                                        @error('new_password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="new_password_confirmation" class="form-label fw-semibold">
                                            <i class='bx bx-lock-alt'></i> Confirm New Password
                                        </label>
                                        <input type="password" class="form-control" id="new_password_confirmation"
                                            name="new_password_confirmation">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('user.profile.show') }}" class="btn btn-outline-secondary btn-lg">
                                <i class='bx bx-x me-2'></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class='bx bx-save me-2'></i>Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        function previewImage(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('preview-image');
            const placeholder = document.getElementById('preview-placeholder');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    if (placeholder) {
                        placeholder.style.display = 'none';
                    }
                };
                reader.readAsDataURL(file);
            }
        }
    </script>
@endpush
