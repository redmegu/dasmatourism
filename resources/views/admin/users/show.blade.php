@extends('layouts.admin')

@section('page-title', 'User Details')

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
                    <span class="admin-breadcrumb-current">{{ $user->name }}</span>
                </div>
                <h2 class="admin-page-title">User Details</h2>
                <div class="admin-header-badges">
                    @if ($user->role === 'administrator')
                        <span class="admin-badge admin">
                            <i class='bx bx-shield'></i> Administrator
                        </span>
                    @elseif($user->role === 'business_owner')
                        <span class="admin-badge business">
                            <i class='bx bx-store'></i> Business Owner
                        </span>
                    @else
                        <span class="admin-badge user">
                            <i class='bx bx-user'></i> User
                        </span>
                    @endif
                    <span class="admin-badge {{ $user->is_active ? 'active' : 'inactive' }}">
                        <i class='bx {{ $user->is_active ? 'bx-check-circle' : 'bx-x-circle' }}'></i>
                        {{ $user->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- User Profile -->
            <div class="admin-detail-card mb-4">
                <div class="admin-detail-card-header">
                    <div class="admin-detail-card-icon primary">
                        <i class='bx bx-user-circle'></i>
                    </div>
                    <div>
                        <h5 class="admin-detail-card-title">User Profile</h5>
                        <p class="admin-detail-card-subtitle">Personal information and details</p>
                    </div>
                </div>
                <div class="admin-detail-card-body">
                    <div class="admin-profile-header">
                        <div class="admin-profile-avatar">
                            @if ($user->profile && $user->profile->profile_picture)
                                <img src="{{ asset('storage/' . $user->profile->profile_picture) }}"
                                    alt="{{ $user->name }}" class="admin-profile-avatar-img">
                            @else
                                <div class="admin-profile-avatar-placeholder">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <div class="admin-profile-info">
                            <h4 class="admin-profile-name">{{ $user->name }}</h4>
                            <p class="admin-profile-email">{{ $user->email }}</p>
                            @if ($user->profile && $user->profile->phone)
                                <p class="admin-profile-phone">
                                    <i class='bx bx-phone'></i> {{ $user->profile->phone }}
                                </p>
                            @endif
                        </div>
                    </div>

                    @if ($user->profile)
                        <div class="admin-profile-details">
                            @if ($user->profile->bio)
                                <div class="admin-profile-detail-item full">
                                    <div class="admin-profile-detail-label">
                                        <i class='bx bx-message-square-detail'></i>
                                        <span>Bio</span>
                                    </div>
                                    <div class="admin-profile-detail-value">{{ $user->profile->bio }}</div>
                                </div>
                            @endif

                            @if ($user->profile->address)
                                <div class="admin-profile-detail-item full">
                                    <div class="admin-profile-detail-label">
                                        <i class='bx bx-map-pin'></i>
                                        <span>Address</span>
                                    </div>
                                    <div class="admin-profile-detail-value">{{ $user->profile->address }}</div>
                                </div>
                            @endif

                            @if ($user->profile->date_of_birth)
                                <div class="admin-profile-detail-item">
                                    <div class="admin-profile-detail-label">
                                        <i class='bx bx-cake'></i>
                                        <span>Date of Birth</span>
                                    </div>
                                    <div class="admin-profile-detail-value">
                                        {{ $user->profile->date_of_birth->format('M d, Y') }}</div>
                                </div>
                            @endif

                            @if ($user->profile->gender)
                                <div class="admin-profile-detail-item">
                                    <div class="admin-profile-detail-label">
                                        <i class='bx bx-user'></i>
                                        <span>Gender</span>
                                    </div>
                                    <div class="admin-profile-detail-value">{{ ucfirst($user->profile->gender) }}</div>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Business (if business owner) -->
            @if ($user->role === 'business_owner' && $user->business)
                <div class="admin-detail-card mb-4">
                    <div class="admin-detail-card-header">
                        <div class="admin-detail-card-icon info">
                            <i class='bx bx-store'></i>
                        </div>
                        <div>
                            <h5 class="admin-detail-card-title">Business Information</h5>
                            <p class="admin-detail-card-subtitle">Registered business profile</p>
                        </div>
                    </div>
                    <div class="admin-detail-card-body">
                        <div class="admin-business-info">
                            <div class="admin-business-header">
                                <div>
                                    <h5 class="admin-business-name">{{ $user->business->name }}</h5>
                                    <div class="admin-business-badges">
                                        <span class="admin-business-badge {{ $user->business->status }}">
                                            {{ ucfirst($user->business->status) }}
                                        </span>
                                        @if ($user->business->is_verified)
                                            <span class="admin-business-badge verified">
                                                <i class='bx bxs-badge-check'></i> Verified
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <a href="{{ route('admin.businesses.show', $user->business) }}"
                                    class="admin-business-link-btn">
                                    <i class='bx bx-link-external'></i>
                                    <span>View Business</span>
                                </a>
                            </div>
                            <p class="admin-business-description">{{ Str::limit($user->business->description, 150) }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Reviews -->
            @if ($user->reviews->count() > 0)
                <div class="admin-detail-card mb-4">
                    <div class="admin-detail-card-header">
                        <div class="admin-detail-card-icon success">
                            <i class='bx bx-message-square-detail'></i>
                        </div>
                        <div>
                            <h5 class="admin-detail-card-title">Reviews</h5>
                            <p class="admin-detail-card-subtitle">{{ $user->reviews->count() }} reviews posted</p>
                        </div>
                    </div>
                    <div class="admin-detail-card-body-list">
                        @foreach ($user->reviews->take(5) as $review)
                            <div class="admin-review-item-modern">
                                <div class="admin-review-item-header">
                                    <div>
                                        <strong
                                            class="admin-review-item-target">{{ $review->reviewable->name ?? 'N/A' }}</strong>
                                        <div class="admin-review-stars">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i class='bx {{ $i <= $review->rating ? 'bxs-star' : 'bx-star' }}'></i>
                                            @endfor
                                        </div>
                                    </div>
                                    <span class="admin-review-time">{{ $review->created_at->diffForHumans() }}</span>
                                </div>
                                @if ($review->comment)
                                    <p class="admin-review-comment">{{ Str::limit($review->comment, 100) }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Landmark Suggestions -->
            @if ($user->landmarkSuggestions->count() > 0)
                <div class="admin-detail-card">
                    <div class="admin-detail-card-header">
                        <div class="admin-detail-card-icon warning">
                            <i class='bx bx-bulb'></i>
                        </div>
                        <div>
                            <h5 class="admin-detail-card-title">Landmark Suggestions</h5>
                            <p class="admin-detail-card-subtitle">{{ $user->landmarkSuggestions->count() }} suggestions
                                submitted</p>
                        </div>
                    </div>
                    <div class="admin-table-wrapper-modern">
                        <table class="admin-suggestions-table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($user->landmarkSuggestions->take(5) as $suggestion)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.suggestions.show', $suggestion) }}"
                                                class="admin-table-link">
                                                {{ $suggestion->name }}
                                            </a>
                                        </td>
                                        <td>{{ $suggestion->category }}</td>
                                        <td>
                                            <span class="admin-table-status-badge {{ $suggestion->status }}">
                                                {{ ucfirst($suggestion->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $suggestion->created_at->format('M d, Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Account Info -->
            <div class="admin-info-card mb-4">
                <div class="admin-info-card-header">
                    <i class='bx bx-info-circle'></i>
                    <span>Account Information</span>
                </div>
                <div class="admin-info-card-body">
                    <div class="admin-info-item-modern">
                        <div class="admin-info-label">User ID</div>
                        <div class="admin-info-value">#{{ $user->id }}</div>
                    </div>
                    <div class="admin-info-item-modern">
                        <div class="admin-info-label">Email Verified</div>
                        <div class="admin-info-value">
                            @if ($user->email_verified_at)
                                <span class="admin-verify-badge verified">
                                    <i class='bx bx-check-circle'></i> Verified
                                </span>
                            @else
                                <span class="admin-verify-badge unverified">
                                    <i class='bx bx-x-circle'></i> Not Verified
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="admin-info-item-modern">
                        <div class="admin-info-label">Joined</div>
                        <div class="admin-info-value">{{ $user->created_at->format('M d, Y') }}</div>
                    </div>
                    <div class="admin-info-item-modern">
                        <div class="admin-info-label">Last Updated</div>
                        <div class="admin-info-value">{{ $user->updated_at->diffForHumans() }}</div>
                    </div>
                </div>
            </div>

            <!-- Activity Stats -->
            <div class="admin-activity-card mb-4">
                <div class="admin-activity-card-header">
                    <i class='bx bx-bar-chart'></i>
                    <span>Activity Statistics</span>
                </div>
                <div class="admin-activity-card-body">
                    <div class="admin-activity-stat">
                        <div class="admin-activity-stat-icon primary">
                            <i class='bx bx-message-square-detail'></i>
                        </div>
                        <div class="admin-activity-stat-content">
                            <div class="admin-activity-stat-label">Reviews Posted</div>
                            <div class="admin-activity-stat-value">{{ $user->reviews->count() }}</div>
                        </div>
                    </div>

                    <div class="admin-activity-stat">
                        <div class="admin-activity-stat-icon success">
                            <i class='bx bx-bulb'></i>
                        </div>
                        <div class="admin-activity-stat-content">
                            <div class="admin-activity-stat-label">Suggestions</div>
                            <div class="admin-activity-stat-value">{{ $user->landmarkSuggestions->count() }}</div>
                        </div>
                    </div>

                    @if ($user->role === 'business_owner' && $user->business)
                        <div class="admin-activity-stat">
                            <div class="admin-activity-stat-icon info">
                                <i class='bx bx-show'></i>
                            </div>
                            <div class="admin-activity-stat-content">
                                <div class="admin-activity-stat-label">Business Views</div>
                                <div class="admin-activity-stat-value">{{ number_format($user->business->views) }}</div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="admin-actions-card">
                <div class="admin-actions-card-header">
                    <i class='bx bx-cog'></i>
                    <span>Actions</span>
                </div>
                <div class="admin-actions-card-body">
                    <a href="{{ route('admin.users.edit', $user) }}" class="admin-action-btn-modern edit">
                        <i class='bx bx-edit'></i>
                        <span>Edit User</span>
                    </a>

                    @if ($user->id !== auth()->id())
                        <button type="button" class="admin-action-btn-modern delete" onclick="confirmDelete()">
                            <i class='bx bx-trash'></i>
                            <span>Delete User</span>
                        </button>

                        <form id="delete-form" action="{{ route('admin.users.destroy', $user) }}" method="POST"
                            class="d-none">
                            @csrf
                            @method('DELETE')
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
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
            margin-bottom: 0.5rem;
        }

        .admin-header-badges {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .admin-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.8125rem;
            font-weight: 600;
        }

        .admin-badge.admin {
            background: #fee2e2;
            color: #dc2626;
        }

        .admin-badge.business {
            background: #dbeafe;
            color: #0284c7;
        }

        .admin-badge.user {
            background: #f1f5f9;
            color: #64748b;
        }

        .admin-badge.active {
            background: #d1fae5;
            color: #059669;
        }

        .admin-badge.inactive {
            background: #f1f5f9;
            color: #64748b;
        }

        /* Detail Cards */
        .admin-detail-card {
            background: white;
            border-radius: 14px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #f1f5f9;
            overflow: hidden;
        }

        .admin-detail-card-header {
            padding: 1.5rem;
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .admin-detail-card-icon {
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

        .admin-detail-card-icon.primary {
            background: linear-gradient(135deg, #1a7838, #27a345);
        }

        .admin-detail-card-icon.info {
            background: linear-gradient(135deg, #00A8E8, #0284c7);
        }

        .admin-detail-card-icon.success {
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .admin-detail-card-icon.warning {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }

        .admin-detail-card-title {
            font-size: 1.125rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }

        .admin-detail-card-subtitle {
            font-size: 0.8125rem;
            color: #64748b;
            margin: 0.25rem 0 0 0;
        }

        .admin-detail-card-body {
            padding: 1.5rem;
        }

        .admin-detail-card-body-list {
            padding: 0;
        }

        /* Profile Header */
        .admin-profile-header {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 2px solid #f1f5f9;
            margin-bottom: 1.5rem;
        }

        .admin-profile-avatar {
            flex-shrink: 0;
        }

        .admin-profile-avatar-img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #e2e8f0;
        }

        .admin-profile-avatar-placeholder {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1a7838, #27a345);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 2.5rem;
            border: 4px solid #e2e8f0;
        }

        .admin-profile-name {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0 0 0.375rem 0;
        }

        .admin-profile-email {
            font-size: 1rem;
            color: #64748b;
            margin: 0 0 0.25rem 0;
        }

        .admin-profile-phone {
            font-size: 0.9375rem;
            color: #64748b;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.375rem;
        }

        /* Profile Details */
        .admin-profile-details {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
        }

        .admin-profile-detail-item {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .admin-profile-detail-item.full {
            grid-column: 1 / -1;
        }

        .admin-profile-detail-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.8125rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .admin-profile-detail-value {
            font-size: 1rem;
            color: #1e293b;
            line-height: 1.6;
        }

        /* Business Info */
        .admin-business-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .admin-business-name {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0 0 0.5rem 0;
        }

        .admin-business-badges {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .admin-business-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.375rem 0.75rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .admin-business-badge.approved {
            background: #d1fae5;
            color: #059669;
        }

        .admin-business-badge.pending {
            background: #fef3c7;
            color: #d97706;
        }

        .admin-business-badge.rejected {
            background: #fee2e2;
            color: #dc2626;
        }

        .admin-business-badge.verified {
            background: #dbeafe;
            color: #0284c7;
        }

        .admin-business-link-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.625rem 1rem;
            background: #f1f5f9;
            color: #1a7838;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.2s ease;
        }

        .admin-business-link-btn:hover {
            background: #1a7838;
            color: white;
        }

        .admin-business-description {
            font-size: 0.9375rem;
            color: #64748b;
            line-height: 1.6;
            margin: 0;
        }

        /* Review Items */
        .admin-review-item-modern {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #f1f5f9;
        }

        .admin-review-item-modern:last-child {
            border-bottom: none;
        }

        .admin-review-item-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 0.75rem;
        }

        .admin-review-item-target {
            font-size: 1rem;
            color: #1e293b;
            display: block;
            margin-bottom: 0.375rem;
        }

        .admin-review-stars {
            color: #fbbf24;
            font-size: 1.125rem;
        }

        .admin-review-time {
            font-size: 0.8125rem;
            color: #94a3b8;
        }

        .admin-review-comment {
            font-size: 0.9375rem;
            color: #64748b;
            line-height: 1.6;
            margin: 0;
        }

        /* Suggestions Table */
        .admin-table-wrapper-modern {
            overflow-x: auto;
        }

        .admin-suggestions-table {
            width: 100%;
            border-collapse: collapse;
        }

        .admin-suggestions-table thead {
            background: #f8fafc;
        }

        .admin-suggestions-table th {
            padding: 1rem 1.5rem;
            text-align: left;
            font-size: 0.8125rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 2px solid #e2e8f0;
        }

        .admin-suggestions-table td {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.9375rem;
        }

        .admin-table-link {
            color: #1a7838;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s ease;
        }

        .admin-table-link:hover {
            color: #27a345;
        }

        .admin-table-status-badge {
            display: inline-block;
            padding: 0.375rem 0.75rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .admin-table-status-badge.approved {
            background: #d1fae5;
            color: #059669;
        }

        .admin-table-status-badge.pending {
            background: #fef3c7;
            color: #d97706;
        }

        .admin-table-status-badge.rejected {
            background: #fee2e2;
            color: #dc2626;
        }

        /* Info Card */
        .admin-info-card {
            background: white;
            border-radius: 14px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #f1f5f9;
            overflow: hidden;
        }

        .admin-info-card-header {
            padding: 1.25rem 1.5rem;
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 700;
            color: #1e293b;
        }

        .admin-info-card-header i {
            font-size: 1.25rem;
            color: #1a7838;
        }

        .admin-info-card-body {
            padding: 1rem;
        }

        .admin-info-item-modern {
            padding: 1rem;
            border-bottom: 1px solid #f1f5f9;
        }

        .admin-info-item-modern:last-child {
            border-bottom: none;
        }

        .admin-info-label {
            font-size: 0.75rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.5rem;
        }

        .admin-info-value {
            font-size: 0.9375rem;
            font-weight: 600;
            color: #1e293b;
        }

        .admin-verify-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.375rem 0.75rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .admin-verify-badge.verified {
            background: #d1fae5;
            color: #059669;
        }

        .admin-verify-badge.unverified {
            background: #fef3c7;
            color: #d97706;
        }

        /* Activity Card */
        .admin-activity-card {
            background: white;
            border-radius: 14px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #f1f5f9;
            overflow: hidden;
        }

        .admin-activity-card-header {
            padding: 1.25rem 1.5rem;
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 700;
            color: #1e293b;
        }

        .admin-activity-card-header i {
            font-size: 1.25rem;
            color: #1a7838;
        }

        .admin-activity-card-body {
            padding: 1rem;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .admin-activity-stat {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: #f8fafc;
            border-radius: 10px;
        }

        .admin-activity-stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: white;
            flex-shrink: 0;
        }

        .admin-activity-stat-icon.primary {
            background: linear-gradient(135deg, #1a7838, #27a345);
        }

        .admin-activity-stat-icon.success {
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .admin-activity-stat-icon.info {
            background: linear-gradient(135deg, #00A8E8, #0284c7);
        }

        .admin-activity-stat-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.25rem;
        }

        .admin-activity-stat-value {
            font-size: 1.5rem;
            font-weight: 800;
            color: #1e293b;
            margin: 0;
        }

        /* Actions Card */
        .admin-actions-card {
            background: white;
            border-radius: 14px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #f1f5f9;
            overflow: hidden;
        }

        .admin-actions-card-header {
            padding: 1.25rem 1.5rem;
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 700;
            color: #1e293b;
        }

        .admin-actions-card-header i {
            font-size: 1.25rem;
            color: #1a7838;
        }

        .admin-actions-card-body {
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .admin-action-btn-modern {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.875rem 1.25rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.9375rem;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .admin-action-btn-modern.edit {
            background: linear-gradient(135deg, #1a7838, #27a345);
            color: white;
            box-shadow: 0 4px 12px rgba(26, 120, 56, 0.25);
        }

        .admin-action-btn-modern.edit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(26, 120, 56, 0.35);
            color: white;
        }

        .admin-action-btn-modern.delete {
            background: #fee2e2;
            color: #dc2626;
        }

        .admin-action-btn-modern.delete:hover {
            background: #dc2626;
            color: white;
            transform: translateY(-2px);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .admin-page-header {
                padding: 1.5rem;
            }

            .admin-profile-header {
                flex-direction: column;
                text-align: center;
            }

            .admin-profile-details {
                grid-template-columns: 1fr;
            }

            .admin-business-header {
                flex-direction: column;
                align-items: stretch;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        function confirmDelete() {
            if (confirm(
                    'Are you sure you want to delete this user?\n\nThis action cannot be undone and will remove all their data.'
                    )) {
                document.getElementById('delete-form').submit();
            }
        }
    </script>
@endpush
