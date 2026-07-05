@extends('layouts.app')

@section('title', 'My Profile - Dasmariñas Tourism')

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
                            <a href="{{ route('user.profile.show') }}" class="profile-menu-item active">
                                <i class='bx bx-user'></i> Profile
                            </a>
                            @if (Route::has('user.profile.edit'))
                                <a href="{{ route('user.profile.edit') }}" class="profile-menu-item">
                                    <i class='bx bx-edit'></i> Edit Profile
                                </a>
                            @endif
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
                            <i class='bx bx-user-circle'></i> My Profile
                        </h2>
                        <p class="profile-header-subtitle">View and manage your account information</p>
                    </div>

                    <!-- Profile Information -->
                    <div class="profile-card">
                        <div class="profile-card-header">
                            <h5 class="profile-card-title">
                                <i class='bx bx-info-circle'></i> Personal Information
                            </h5>
                            @if (Route::has('user.profile.edit'))
                                <a href="{{ route('user.profile.edit') }}" class="btn btn-outline-primary btn-sm">
                                    <i class='bx bx-edit me-1'></i>Edit Profile
                                </a>
                            @endif
                        </div>
                        <div class="profile-card-body">
                            <div class="info-row">
                                <div class="info-label">
                                    <i class='bx bx-user'></i> Full Name
                                </div>
                                <div class="info-value">{{ $user->name }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">
                                    <i class='bx bx-envelope'></i> Email Address
                                </div>
                                <div class="info-value">{{ $user->email }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">
                                    <i class='bx bx-shield'></i> Account Type
                                </div>
                                <div class="info-value">
                                    <span class="badge badge-role">{{ ucfirst(str_replace('_', ' ', $user->role)) }}</span>
                                </div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">
                                    <i class='bx bx-calendar'></i> Member Since
                                </div>
                                <div class="info-value">{{ $user->created_at->format('F d, Y') }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Profile Info -->
                    @if (
                        $user->profile &&
                            ($user->profile->phone || $user->profile->address || $user->profile->birth_date || $user->profile->gender))
                        <div class="profile-card">
                            <div class="profile-card-header">
                                <h5 class="profile-card-title">
                                    <i class='bx bx-detail'></i> Additional Details
                                </h5>
                            </div>
                            <div class="profile-card-body">
                                @if ($user->profile->phone)
                                    <div class="info-row">
                                        <div class="info-label">
                                            <i class='bx bx-phone'></i> Phone Number
                                        </div>
                                        <div class="info-value">{{ $user->profile->phone }}</div>
                                    </div>
                                @endif
                                @if ($user->profile->birth_date)
                                    <div class="info-row">
                                        <div class="info-label">
                                            <i class='bx bx-cake'></i> Birth Date
                                        </div>
                                        <div class="info-value">
                                            {{ \Carbon\Carbon::parse($user->profile->birth_date)->format('F d, Y') }}</div>
                                    </div>
                                @endif
                                @if ($user->profile->gender)
                                    <div class="info-row">
                                        <div class="info-label">
                                            <i class='bx bx-user-circle'></i> Gender
                                        </div>
                                        <div class="info-value">{{ ucfirst($user->profile->gender) }}</div>
                                    </div>
                                @endif
                                @if ($user->profile->address)
                                    <div class="info-row">
                                        <div class="info-label">
                                            <i class='bx bx-map-pin'></i> Address
                                        </div>
                                        <div class="info-value">{{ $user->profile->address }}</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="profile-card">
                            <div class="profile-card-body text-center py-4">
                                <i class='bx bx-user-plus' style="font-size: 3rem; color: #cbd5e1;"></i>
                                <h6 class="mt-3 mb-2">Complete Your Profile</h6>
                                <p class="text-muted mb-3">Add more information to personalize your experience</p>
                                @if (Route::has('user.profile.edit'))
                                    <a href="{{ route('user.profile.edit') }}" class="btn btn-primary">
                                        <i class='bx bx-edit me-2'></i>Update Profile
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Activity Stats -->
                    <div class="row g-4 mt-2">
                        <div class="col-md-4">
                            <div class="stat-card">
                                <div class="stat-icon stat-icon-primary">
                                    <i class='bx bx-bookmark'></i>
                                </div>
                                <div class="stat-content">
                                    <h3 class="stat-number">{{ $user->bookmarks ? $user->bookmarks->count() : 0 }}</h3>
                                    <p class="stat-label">Bookmarks</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stat-card">
                                <div class="stat-icon stat-icon-success">
                                    <i class='bx bx-message-square-detail'></i>
                                </div>
                                <div class="stat-content">
                                    <h3 class="stat-number">{{ $user->reviews ? $user->reviews->count() : 0 }}</h3>
                                    <p class="stat-label">Reviews</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stat-card">
                                <div class="stat-icon stat-icon-warning">
                                    <i class='bx bx-map'></i>
                                </div>
                                <div class="stat-content">
                                    <h3 class="stat-number">
                                        {{ $user->interactions ? $user->interactions->where('interaction_type', 'view')->count() : 0 }}
                                    </h3>
                                    <p class="stat-label">Places Visited</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    @if ($user->reviews && $user->reviews->count() > 0)
                        <div class="profile-card mt-4">
                            <div class="profile-card-header">
                                <h5 class="profile-card-title">
                                    <i class='bx bx-time-five'></i> Recent Reviews
                                </h5>
                                @if (Route::has('user.reviews.index'))
                                    <a href="{{ route('user.reviews.index') }}" class="btn btn-outline-primary btn-sm">
                                        <i class='bx bx-show me-1'></i>View All
                                    </a>
                                @endif
                            </div>
                            <div class="profile-card-body">
                                @foreach ($user->reviews->take(3) as $review)
                                    <div class="activity-item">
                                        <div class="activity-icon">
                                            <i class='bx bx-message-square-detail'></i>
                                        </div>
                                        <div class="activity-content">
                                            <p class="activity-title">
                                            Reviewed {{ $review->reviewable?->name ?? '[Deleted Attraction]' }}
                                            </p>

                                            <div class="activity-meta">
                                                <div class="rating-display-small">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <i
                                                            class='bx {{ $i <= $review->rating ? 'bxs-star' : 'bx-star' }}'></i>
                                                    @endfor
                                                </div>
                                                <span
                                                    class="activity-time">{{ $review->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
