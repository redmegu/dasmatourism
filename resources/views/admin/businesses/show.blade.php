@extends('layouts.admin')

@section('page-title', 'Business Details')

@section('content')
    <!-- Enhanced Page Header -->
    <div class="admin-page-header mb-4">
        <div class="admin-page-header-content">
            <div>
                <div class="admin-breadcrumb mb-2">
                    <a href="{{ route('admin.businesses.index') }}" class="admin-breadcrumb-link">
                        <i class='bx bx-store'></i> Businesses
                    </a>
                    <i class='bx bx-chevron-right'></i>
                    <span class="admin-breadcrumb-current">{{ $business->name }}</span>
                </div>
                <h2 class="admin-page-title">{{ $business->name }}</h2>
                <p class="admin-page-subtitle">Detailed information and management</p>
            </div>
            <div class="admin-header-actions">
                @if($business->slug)
                <a href="{{ route('businesses.show', $business->slug) }}" class="admin-header-action-btn secondary"
                    target="_blank">
                    <i class='bx bx-link-external'></i>
                    <span>View Public</span>
                </a>
                @endif
                <button type="button" class="admin-header-action-btn danger" onclick="confirmDelete()">
                    <i class='bx bx-trash'></i>
                    <span>Delete</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Status Badges Row -->
    <div class="admin-status-row mb-4">
        <div class="admin-status-badge-modern {{ $business->status }}">
            @if ($business->status === 'approved')
                <i class='bx bx-check-circle'></i>
            @elseif ($business->status === 'pending')
                <i class='bx bx-time'></i>
            @else
                <i class='bx bx-x-circle'></i>
            @endif
            <span>{{ ucfirst($business->status) }}</span>
        </div>

        @if ($business->is_verified)
            <div class="admin-status-badge-modern verified">
                <i class='bx bxs-badge-check'></i>
                <span>Verified Business</span>
            </div>
        @endif

        <div class="admin-status-badge-modern info">
            <i class='bx bx-calendar'></i>
            <span>Registered {{ $business->created_at->format('M d, Y') }}</span>
        </div>
    </div>

    <div class="row g-4">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Business Info -->
            <div class="admin-detail-card mb-4">
                <div class="admin-detail-card-header">
                    <div class="admin-detail-card-icon primary">
                        <i class='bx bx-info-circle'></i>
                    </div>
                    <div>
                        <h5 class="admin-detail-card-title">Business Information</h5>
                        <p class="admin-detail-card-subtitle">Core business details and information</p>
                    </div>
                </div>
                <div class="admin-detail-card-body">
                    <div class="admin-info-grid">
                        <div class="admin-info-item">
                            <div class="admin-info-label">
                                <i class='bx bx-category'></i>
                                <span>Category</span>
                            </div>
                            <div class="admin-info-value">{{ $business->category->name }}</div>
                        </div>

                        <div class="admin-info-item">
                            <div class="admin-info-label">
                                <i class='bx bx-user'></i>
                                <span>Business Owner</span>
                            </div>
                            <div class="admin-info-value">{{ $business->owner->name }}</div>
                        </div>

                        <div class="admin-info-item">
                            <div class="admin-info-label">
                                <i class='bx bx-show'></i>
                                <span>Total Views</span>
                            </div>
                            <div class="admin-info-value">{{ number_format($business->views) }}</div>
                        </div>

                        <div class="admin-info-item">
                            <div class="admin-info-label">
                                <i class='bx bxs-star'></i>
                                <span>Average Rating</span>
                            </div>
                            <div class="admin-info-value">{{ number_format($business->getAverageRating(), 1) }} / 5.0</div>
                        </div>
                    </div>

                    <div class="admin-info-section mt-4">
                        <div class="admin-info-label">
                            <i class='bx bx-message-square-detail'></i>
                            <span>Description</span>
                        </div>
                        <div class="admin-info-description">{{ $business->description }}</div>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="admin-detail-card mb-4">
                <div class="admin-detail-card-header">
                    <div class="admin-detail-card-icon success">
                        <i class='bx bx-phone'></i>
                    </div>
                    <div>
                        <h5 class="admin-detail-card-title">Contact Information</h5>
                        <p class="admin-detail-card-subtitle">How to reach this business</p>
                    </div>
                </div>
                <div class="admin-detail-card-body">
                    <div class="admin-contact-list">
                        <div class="admin-contact-item">
                            <div class="admin-contact-icon">
                                <i class='bx bx-map-pin'></i>
                            </div>
                            <div class="admin-contact-content">
                                <span class="admin-contact-label">Address</span>
                                <span class="admin-contact-value">{{ $business->address }}</span>
                            </div>
                        </div>

                        @if ($business->phone)
                            <div class="admin-contact-item">
                                <div class="admin-contact-icon">
                                    <i class='bx bx-phone'></i>
                                </div>
                                <div class="admin-contact-content">
                                    <span class="admin-contact-label">Phone Number</span>
                                    <a href="tel:{{ $business->phone }}"
                                        class="admin-contact-value link">{{ $business->phone }}</a>
                                </div>
                            </div>
                        @endif

                        @if ($business->email)
                            <div class="admin-contact-item">
                                <div class="admin-contact-icon">
                                    <i class='bx bx-envelope'></i>
                                </div>
                                <div class="admin-contact-content">
                                    <span class="admin-contact-label">Email Address</span>
                                    <a href="mailto:{{ $business->email }}"
                                        class="admin-contact-value link">{{ $business->email }}</a>
                                </div>
                            </div>
                        @endif

                        @if ($business->website)
                            <div class="admin-contact-item">
                                <div class="admin-contact-icon">
                                    <i class='bx bx-globe'></i>
                                </div>
                                <div class="admin-contact-content">
                                    <span class="admin-contact-label">Website</span>
                                    <a href="{{ $business->website }}" target="_blank"
                                        class="admin-contact-value link">{{ $business->website }}</a>
                                </div>
                            </div>
                        @endif

                        @if ($business->google_maps_link)
                            <div class="admin-contact-item">
                                <div class="admin-contact-icon">
                                    <i class='bx bx-map text-primary'></i>
                                </div>
                                <div class="admin-contact-content">
                                    <span class="admin-contact-label">Google Maps</span>
                                    <a href="{{ $business->google_maps_link }}" target="_blank"
                                        class="admin-contact-value link text-primary">
                                        <i class='bx bx-navigation'></i> Open Directions</a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Business Permit Section (ADD THIS) -->
            @if ($business->business_permit)
                <div class="admin-detail-card mb-4">
                    <div class="admin-detail-card-header">
                        <div class="admin-detail-card-icon warning">
                            <i class='bx bx-file-blank'></i>
                        </div>
                        <div>
                            <h5 class="admin-detail-card-title">Business Permit</h5>
                            <p class="admin-detail-card-subtitle">Submitted document for verification</p>
                        </div>
                        @if ($business->hasVerifiedPermit())
                            <span class="admin-permit-badge verified ms-auto">
                                <i class='bx bx-check-circle'></i> Verified
                            </span>
                        @else
                            <span class="admin-permit-badge pending ms-auto">
                                <i class='bx bx-time'></i> Pending
                            </span>
                        @endif
                    </div>
                    <div class="admin-detail-card-body">
                        <div class="admin-permit-display">
                            <div class="admin-permit-info-section">
                                <div class="admin-permit-file-card">
                                    <div class="admin-permit-file-icon">
                                        @if (Str::endsWith($business->business_permit, '.pdf'))
                                            <i class='bx bxs-file-pdf'></i>
                                        @else
                                            <i class='bx bxs-file-image'></i>
                                        @endif
                                    </div>
                                    <div class="admin-permit-file-details">
                                        <h6 class="admin-permit-file-name">Business Permit Document</h6>
                                        <p class="admin-permit-file-meta">
                                            <i class='bx bx-calendar'></i>
                                            Submitted: {{ $business->created_at->format('M d, Y') }}
                                        </p>
                                        @if ($business->hasVerifiedPermit())
                                            <p class="admin-permit-file-verified">
                                                <i class='bx bx-check-circle'></i>
                                                Verified on
                                                {{ $business->permit_verified_at->format('M d, Y \a\t h:i A') }}
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                <div class="admin-permit-actions">
                                    <a href="{{ asset('storage/' . $business->business_permit) }}" target="_blank"
                                        class="admin-permit-btn view">
                                        <i class='bx bx-show'></i>
                                        <span>View Document</span>
                                    </a>
                                    <a href="{{ asset('storage/' . $business->business_permit) }}" download
                                        class="admin-permit-btn download">
                                        <i class='bx bx-download'></i>
                                        <span>Download</span>
                                    </a>
                                </div>
                            </div>

                            <!-- Image Preview for JPG/PNG -->
                            @if (!Str::endsWith($business->business_permit, '.pdf'))
                                <div class="admin-permit-preview-section">
                                    <h6 class="admin-permit-preview-title">Document Preview</h6>
                                    <div class="admin-permit-preview-wrapper">
                                        <img src="{{ asset('storage/' . $business->business_permit) }}"
                                            alt="Business Permit" class="admin-permit-preview-img"
                                            onclick="viewPermitModal('{{ asset('storage/' . $business->business_permit) }}')">
                                    </div>
                                    <p class="text-center text-muted small mt-2">Click to view full size</p>
                                </div>
                            @endif

                            <!-- Verification Actions -->
                            <div class="admin-permit-verification-actions">
                                @if (!$business->hasVerifiedPermit())
                                    <form action="{{ route('admin.businesses.permit.verify', $business) }}"
                                        method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="admin-permit-action-btn verify"
                                            onclick="return confirm('Verify this business permit?')">
                                            <i class='bx bx-check-circle'></i>
                                            <span>Verify Permit</span>
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.businesses.permit.reject', $business) }}"
                                        method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="admin-permit-action-btn reject"
                                            onclick="return confirm('Remove permit verification? This will require re-verification.')">
                                            <i class='bx bx-x-circle'></i>
                                            <span>Remove Verification</span>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- No Permit Warning -->
                <div class="admin-detail-card mb-4 admin-permit-warning-card">
                    <div class="admin-detail-card-header">
                        <div class="admin-detail-card-icon danger">
                            <i class='bx bx-error'></i>
                        </div>
                        <div>
                            <h5 class="admin-detail-card-title text-danger">Business Permit Missing</h5>
                            <p class="admin-detail-card-subtitle">No permit document uploaded</p>
                        </div>
                    </div>
                    <div class="admin-detail-card-body">
                        <div class="alert alert-warning mb-0">
                            <i class='bx bx-info-circle'></i>
                            <strong>Warning:</strong> This business has not uploaded their business permit yet.
                            The owner must upload a valid business permit before the business can be fully approved.
                        </div>
                    </div>
                </div>
            @endif


            <!-- Business Hours -->
            @if ($business->business_hours)
                <div class="admin-detail-card mb-4">
                    <div class="admin-detail-card-header">
                        <div class="admin-detail-card-icon warning">
                            <i class='bx bx-time'></i>
                        </div>
                        <div>
                            <h5 class="admin-detail-card-title">Business Hours</h5>
                            <p class="admin-detail-card-subtitle">Operating schedule</p>
                        </div>
                    </div>
                    <div class="admin-detail-card-body">
                        <div class="admin-hours-display">{{ $business->business_hours }}</div>
                    </div>
                </div>
            @endif

            <!-- Images Gallery -->
            @if ($business->images->count() > 0)
                <div class="admin-detail-card mb-4">
                    <div class="admin-detail-card-header">
                        <div class="admin-detail-card-icon info">
                            <i class='bx bx-image'></i>
                        </div>
                        <div>
                            <h5 class="admin-detail-card-title">Image Gallery</h5>
                            <p class="admin-detail-card-subtitle">{{ $business->images->count() }} images uploaded</p>
                        </div>
                    </div>
                    <div class="admin-detail-card-body">
                        <div class="admin-modern-gallery">
                            @foreach ($business->images as $image)
                                <div class="admin-modern-gallery-item">
                                    <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $business->name }}">
                                    @if ($image->is_primary)
                                        <div class="admin-primary-tag">
                                            <i class='bx bxs-star'></i>
                                            <span>Primary</span>
                                        </div>
                                    @endif>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Reviews -->
            @if ($business->reviews->count() > 0)
                <div class="admin-detail-card">
                    <div class="admin-detail-card-header">
                        <div class="admin-detail-card-icon success">
                            <i class='bx bx-message-square-detail'></i>
                        </div>
                        <div>
                            <h5 class="admin-detail-card-title">Customer Reviews</h5>
                            <p class="admin-detail-card-subtitle">{{ $business->reviews->count() }} reviews total</p>
                        </div>
                    </div>
                    <div class="admin-detail-card-body p-0">
                        @foreach ($business->reviews->take(5) as $review)
                            <div class="admin-modern-review">
                                <div class="admin-modern-review-header">
                                    <div class="admin-modern-review-user">
                                        @if ($review->user->profile && $review->user->profile->profile_picture)
                                            <img src="{{ asset('storage/' . $review->user->profile->profile_picture) }}"
                                                alt="{{ $review->user->name }}" class="admin-modern-review-avatar">
                                        @else
                                            <div class="admin-modern-review-avatar-placeholder">
                                                {{ substr($review->user->name, 0, 1) }}
                                            </div>
                                        @endif
                                        <div>
                                            <strong class="admin-modern-review-name">{{ $review->user->name }}</strong>
                                            <div class="admin-modern-review-stars">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <i
                                                        class='bx {{ $i <= $review->rating ? 'bxs-star' : 'bx-star' }}'></i>
                                                @endfor
                                            </div>
                                        </div>
                                    </div>
                                    <span
                                        class="admin-modern-review-date">{{ $review->created_at->diffForHumans() }}</span>
                                </div>
                                @if ($review->comment)
                                    <p class="admin-modern-review-comment">{{ $review->comment }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Status Management -->
            <div class="admin-action-card mb-4">
                <div class="admin-action-card-header">
                    <i class='bx bx-check-circle'></i>
                    <span>Status Management</span>
                </div>
                <div class="admin-action-card-body">
                    <form action="{{ route('admin.businesses.status', $business) }}" method="POST">
                        @csrf
                        <div class="admin-form-group">
                            <label class="admin-form-label">
                                <i class='bx bx-check-shield'></i>
                                Approval Status
                            </label>
                            <select class="admin-form-select" name="status" required>
                                <option value="pending" {{ $business->status === 'pending' ? 'selected' : '' }}>Pending
                                    Review</option>
                                <option value="approved" {{ $business->status === 'approved' ? 'selected' : '' }}>Approved
                                </option>
                                <option value="rejected" {{ $business->status === 'rejected' ? 'selected' : '' }}>Rejected
                                </option>
                            </select>
                        </div>
                        <button type="submit" class="admin-action-btn primary">
                            <i class='bx bx-save'></i>
                            <span>Update Status</span>
                        </button>
                    </form>

                    <div class="admin-divider"></div>

                    <form action="{{ route('admin.businesses.verify', $business) }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="admin-action-btn {{ $business->is_verified ? 'warning' : 'success' }}">
                            <i class='bx {{ $business->is_verified ? 'bx-x-circle' : 'bxs-badge-check' }}'></i>
                            <span>{{ $business->is_verified ? 'Remove Verification' : 'Verify Business' }}</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Statistics -->
            <div class="admin-stat-sidebar-card mb-4">
                <div class="admin-stat-sidebar-header">
                    <i class='bx bx-bar-chart'></i>
                    <span>Performance Stats</span>
                </div>
                <div class="admin-stat-sidebar-body">
                    <div class="admin-stat-sidebar-item primary">
                        <div class="admin-stat-sidebar-icon">
                            <i class='bx bx-show'></i>
                        </div>
                        <div class="admin-stat-sidebar-content">
                            <span class="admin-stat-sidebar-label">Total Views</span>
                            <span class="admin-stat-sidebar-value">{{ number_format($business->views) }}</span>
                        </div>
                    </div>

                    <div class="admin-stat-sidebar-item warning">
                        <div class="admin-stat-sidebar-icon">
                            <i class='bx bxs-star'></i>
                        </div>
                        <div class="admin-stat-sidebar-content">
                            <span class="admin-stat-sidebar-label">Avg Rating</span>
                            <span
                                class="admin-stat-sidebar-value">{{ number_format($business->getAverageRating(), 1) }}</span>
                        </div>
                    </div>

                    <div class="admin-stat-sidebar-item success">
                        <div class="admin-stat-sidebar-icon">
                            <i class='bx bx-message-square-detail'></i>
                        </div>
                        <div class="admin-stat-sidebar-content">
                            <span class="admin-stat-sidebar-label">Reviews</span>
                            <span class="admin-stat-sidebar-value">{{ $business->reviews->count() }}</span>
                        </div>
                    </div>

                    <div class="admin-stat-sidebar-item info">
                        <div class="admin-stat-sidebar-icon">
                            <i class='bx bx-image'></i>
                        </div>
                        <div class="admin-stat-sidebar-content">
                            <span class="admin-stat-sidebar-label">Images</span>
                            <span class="admin-stat-sidebar-value">{{ $business->images->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="admin-quick-actions-card">
                <div class="admin-quick-actions-header">
                    <i class='bx bx-bolt'></i>
                    <span>Quick Actions</span>
                </div>
                <div class="admin-quick-actions-body">
                    @if($business->slug)
                    <a href="{{ route('businesses.show', $business->slug) }}" class="admin-quick-action-item"
                        target="_blank">
                        <i class='bx bx-link-external'></i>
                        <span>View on Website</span>
                        <i class='bx bx-chevron-right'></i>
                    </a>
                    @endif

                    <button type="button" class="admin-quick-action-item danger" onclick="confirmDelete()">
                        <i class='bx bx-trash'></i>
                        <span>Delete Business</span>
                        <i class='bx bx-chevron-right'></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Form -->
    <form id="delete-form" action="{{ route('admin.businesses.destroy', $business) }}" method="POST" class="d-none">
        @csrf
        @method('DELETE')
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
            flex-wrap: wrap;
        }

        .admin-breadcrumb {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            color: #64748b;
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
            margin: 0.25rem 0;
        }

        .admin-page-subtitle {
            color: #64748b;
            margin: 0;
            font-size: 0.9375rem;
        }

        .admin-header-actions {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .admin-header-action-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.25rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.9375rem;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            white-space: nowrap;
        }

        .admin-header-action-btn.secondary {
            background: #f1f5f9;
            color: #475569;
        }

        .admin-header-action-btn.secondary:hover {
            background: #e2e8f0;
            transform: translateY(-2px);
        }

        .admin-header-action-btn.danger {
            background: #fee2e2;
            color: #dc2626;
        }

        .admin-header-action-btn.danger:hover {
            background: #dc2626;
            color: white;
            transform: translateY(-2px);
        }

        /* Status Badges Row */
        .admin-status-row {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .admin-status-badge-modern {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.625rem 1.25rem;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.875rem;
        }

        .admin-status-badge-modern.approved {
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
            color: #059669;
        }

        .admin-status-badge-modern.pending {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            color: #d97706;
        }

        .admin-status-badge-modern.rejected {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            color: #dc2626;
        }

        .admin-status-badge-modern.verified {
            background: linear-gradient(135deg, #dbeafe, #bfdbfe);
            color: #0284c7;
        }

        .admin-status-badge-modern.info {
            background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
            color: #475569;
        }

        .admin-status-badge-modern i {
            font-size: 1.125rem;
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
            border-bottom: 1px solid #f1f5f9;
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

        .admin-detail-card-icon.success {
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .admin-detail-card-icon.warning {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }

        .admin-detail-card-icon.info {
            background: linear-gradient(135deg, #00A8E8, #0284c7);
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

        /* Info Grid */
        .admin-info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
        }

        .admin-info-item {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .admin-info-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.8125rem;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .admin-info-label i {
            font-size: 1rem;
        }

        .admin-info-value {
            font-size: 1rem;
            font-weight: 600;
            color: #1e293b;
        }

        .admin-info-section {
            border-top: 1px solid #f1f5f9;
            padding-top: 1.5rem;
        }

        .admin-info-description {
            font-size: 0.9375rem;
            color: #64748b;
            line-height: 1.6;
            margin-top: 0.75rem;
        }

        /* Contact List */
        .admin-contact-list {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        .admin-contact-item {
            display: flex;
            gap: 1rem;
            padding: 1rem;
            background: #f8fafc;
            border-radius: 10px;
            transition: all 0.2s ease;
        }

        .admin-contact-item:hover {
            background: #f1f5f9;
        }

        .admin-contact-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background: linear-gradient(135deg, #1a7838, #27a345);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        .admin-contact-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .admin-contact-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .admin-contact-value {
            font-size: 0.9375rem;
            font-weight: 600;
            color: #1e293b;
        }

        .admin-contact-value.link {
            color: #1a7838;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .admin-contact-value.link:hover {
            color: #27a345;
            text-decoration: underline;
        }

        /* Hours Display */
        .admin-hours-display {
            padding: 1.25rem;
            background: #f8fafc;
            border-radius: 10px;
            font-size: 0.9375rem;
            color: #1e293b;
            white-space: pre-wrap;
            line-height: 1.8;
            border-left: 4px solid #f59e0b;
        }

        /* Modern Gallery */
        .admin-modern-gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
        }

        .admin-modern-gallery-item {
            position: relative;
            aspect-ratio: 1;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .admin-modern-gallery-item:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .admin-modern-gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .admin-primary-tag {
            position: absolute;
            top: 0.75rem;
            right: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.375rem 0.75rem;
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 700;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        /* Modern Reviews */
        .admin-modern-review {
            padding: 1.5rem;
            border-bottom: 1px solid #f1f5f9;
            transition: all 0.2s ease;
        }

        .admin-modern-review:last-child {
            border-bottom: none;
        }

        .admin-modern-review:hover {
            background: #f8fafc;
        }

        .admin-modern-review-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
            gap: 1rem;
        }

        .admin-modern-review-user {
            display: flex;
            gap: 0.875rem;
            align-items: center;
        }

        .admin-modern-review-avatar,
        .admin-modern-review-avatar-placeholder {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .admin-modern-review-avatar {
            object-fit: cover;
            border: 2px solid #e2e8f0;
        }

        .admin-modern-review-avatar-placeholder {
            background: linear-gradient(135deg, #1a7838, #27a345);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            font-weight: 700;
        }

        .admin-modern-review-name {
            font-size: 0.9375rem;
            color: #1e293b;
            display: block;
            margin-bottom: 0.25rem;
        }

        .admin-modern-review-stars {
            display: flex;
            gap: 0.125rem;
        }

        .admin-modern-review-stars i {
            font-size: 1rem;
            color: #f59e0b;
        }

        .admin-modern-review-date {
            font-size: 0.8125rem;
            color: #94a3b8;
            white-space: nowrap;
        }

        .admin-modern-review-comment {
            font-size: 0.9375rem;
            color: #64748b;
            line-height: 1.6;
            margin: 0;
        }

        /* Action Card */
        .admin-action-card {
            background: white;
            border-radius: 14px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #f1f5f9;
            overflow: hidden;
        }

        .admin-action-card-header {
            padding: 1.25rem 1.5rem;
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 700;
            color: #1e293b;
        }

        .admin-action-card-header i {
            font-size: 1.25rem;
            color: #1a7838;
        }

        .admin-action-card-body {
            padding: 1.5rem;
        }

        .admin-form-group {
            margin-bottom: 1.25rem;
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

        .admin-form-select {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 0.9375rem;
            transition: all 0.25s ease;
        }

        .admin-form-select:focus {
            outline: none;
            border-color: #1a7838;
            box-shadow: 0 0 0 4px rgba(26, 120, 56, 0.1);
        }

        .admin-action-btn {
            width: 100%;
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
        }

        .admin-action-btn.primary {
            background: linear-gradient(135deg, #1a7838, #27a345);
            color: white;
            box-shadow: 0 4px 12px rgba(26, 120, 56, 0.25);
        }

        .admin-action-btn.primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(26, 120, 56, 0.35);
        }

        .admin-action-btn.success {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25);
        }

        .admin-action-btn.success:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.35);
        }

        .admin-action-btn.warning {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.25);
        }

        .admin-action-btn.warning:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(245, 158, 11, 0.35);
        }

        .admin-divider {
            height: 1px;
            background: #e2e8f0;
            margin: 1.25rem 0;
        }

        /* Stat Sidebar Card */
        .admin-stat-sidebar-card {
            background: white;
            border-radius: 14px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #f1f5f9;
            overflow: hidden;
        }

        .admin-stat-sidebar-header {
            padding: 1.25rem 1.5rem;
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 700;
            color: #1e293b;
        }

        .admin-stat-sidebar-header i {
            font-size: 1.25rem;
            color: #1a7838;
        }

        .admin-stat-sidebar-body {
            padding: 1rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .admin-stat-sidebar-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            border-radius: 10px;
            transition: all 0.2s ease;
        }

        .admin-stat-sidebar-item.primary {
            background: linear-gradient(135deg, #e0f2e9, #d1fae5);
        }

        .admin-stat-sidebar-item.warning {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
        }

        .admin-stat-sidebar-item.success {
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        }

        .admin-stat-sidebar-item.info {
            background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        }

        .admin-stat-sidebar-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: white;
            flex-shrink: 0;
        }

        .admin-stat-sidebar-item.primary .admin-stat-sidebar-icon {
            background: linear-gradient(135deg, #1a7838, #27a345);
        }

        .admin-stat-sidebar-item.warning .admin-stat-sidebar-icon {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }

        .admin-stat-sidebar-item.success .admin-stat-sidebar-icon {
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .admin-stat-sidebar-item.info .admin-stat-sidebar-icon {
            background: linear-gradient(135deg, #00A8E8, #0284c7);
        }

        .admin-stat-sidebar-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .admin-stat-sidebar-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .admin-stat-sidebar-value {
            font-size: 1.5rem;
            font-weight: 800;
            color: #1e293b;
        }

        /* Quick Actions Card */
        .admin-quick-actions-card {
            background: white;
            border-radius: 14px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #f1f5f9;
            overflow: hidden;
        }

        .admin-quick-actions-header {
            padding: 1.25rem 1.5rem;
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 700;
            color: #1e293b;
        }

        .admin-quick-actions-header i {
            font-size: 1.25rem;
            color: #1a7838;
        }

        .admin-quick-actions-body {
            padding: 0.5rem;
        }

        .admin-quick-action-item {
            display: flex;
            align-items: center;
            gap: 0.875rem;
            padding: 1rem 1.25rem;
            border-radius: 10px;
            font-weight: 600;
            color: #1e293b;
            text-decoration: none;
            transition: all 0.2s ease;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
            cursor: pointer;
        }

        .admin-quick-action-item:hover {
            background: #f8fafc;
        }

        .admin-quick-action-item.danger {
            color: #dc2626;
        }

        .admin-quick-action-item.danger:hover {
            background: #fee2e2;
        }

        .admin-quick-action-item i:first-child {
            font-size: 1.25rem;
        }

        .admin-quick-action-item i:last-child {
            margin-left: auto;
            font-size: 1.125rem;
            color: #cbd5e1;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .admin-info-grid {
                grid-template-columns: 1fr;
            }

            .admin-modern-gallery {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            }
        }

        @media (max-width: 768px) {
            .admin-page-header-content {
                flex-direction: column;
                align-items: stretch;
            }

            .admin-header-actions {
                width: 100%;
            }

            .admin-header-action-btn {
                flex: 1;
                justify-content: center;
            }
        }

        /* Business Permit Styles */
        .admin-permit-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 700;
        }

        .admin-permit-badge.verified {
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
            color: #059669;
        }

        .admin-permit-badge.pending {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            color: #d97706;
        }

        .admin-permit-display {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        .admin-permit-info-section {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .admin-permit-file-card {
            display: flex;
            gap: 1.5rem;
            padding: 1.5rem;
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            border-radius: 12px;
            border: 2px solid #fbbf24;
        }

        .admin-permit-file-icon {
            width: 80px;
            height: 80px;
            border-radius: 12px;
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(251, 191, 36, 0.3);
        }

        .admin-permit-file-details {
            flex: 1;
        }

        .admin-permit-file-name {
            font-size: 1.25rem;
            font-weight: 700;
            color: #78350f;
            margin-bottom: 0.75rem;
        }

        .admin-permit-file-meta {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            color: #92400e;
            margin-bottom: 0.5rem;
        }

        .admin-permit-file-verified {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            font-weight: 600;
            color: #059669;
        }

        .admin-permit-actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .admin-permit-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.625rem;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.9375rem;
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .admin-permit-btn.view {
            background: linear-gradient(135deg, #1a7838, #27a345);
            color: white;
            box-shadow: 0 4px 12px rgba(26, 120, 56, 0.25);
        }

        .admin-permit-btn.view:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(26, 120, 56, 0.35);
            color: white;
        }

        .admin-permit-btn.download {
            background: #f1f5f9;
            color: #475569;
        }

        .admin-permit-btn.download:hover {
            background: #e2e8f0;
            transform: translateY(-2px);
        }

        .admin-permit-preview-section {
            padding-top: 1.5rem;
            border-top: 2px solid #fbbf24;
        }

        .admin-permit-preview-title {
            font-size: 1rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 1rem;
        }

        .admin-permit-preview-wrapper {
            border: 2px solid #fbbf24;
            border-radius: 12px;
            padding: 1rem;
            background: white;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .admin-permit-preview-wrapper:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(251, 191, 36, 0.3);
        }

        .admin-permit-preview-img {
            width: 100%;
            max-height: 500px;
            object-fit: contain;
            border-radius: 8px;
        }

        .admin-permit-verification-actions {
            padding-top: 1.5rem;
            border-top: 2px solid #e2e8f0;
        }

        .admin-permit-action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            padding: 1rem 2rem;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1rem;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
        }

        .admin-permit-action-btn.verify {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25);
        }

        .admin-permit-action-btn.verify:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.35);
        }

        .admin-permit-action-btn.reject {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.25);
        }

        .admin-permit-action-btn.reject:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(245, 158, 11, 0.35);
        }

        .admin-permit-warning-card {
            border: 2px solid #ef4444;
        }

        .admin-detail-card-icon.danger {
            background: linear-gradient(135deg, #ef4444, #dc2626);
        }
    </style>
@endpush

@push('scripts')
    <script>
        function confirmDelete() {
            if (confirm(
                    'Are you sure you want to delete this business?\n\nThis action cannot be undone and will permanently remove:\n• Business information\n• All images\n• All reviews\n• All associated data'
                )) {
                document.getElementById('delete-form').submit();
            }
        }

        function viewPermitModal(src) {
            // Create modal HTML if it doesn't exist
            if (!document.getElementById('permitModal')) {
                const modalHTML = `
            <div class="modal fade" id="permitModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered modal-xl">
                    <div class="modal-content">
                        <div class="modal-header border-0">
                            <h5 class="modal-title">Business Permit Document</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body p-0">
                            <img src="" alt="Business Permit" id="modalPermitImage" class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>
        `;
                document.body.insertAdjacentHTML('beforeend', modalHTML);
            }

            document.getElementById('modalPermitImage').src = src;
            const modal = new bootstrap.Modal(document.getElementById('permitModal'));
            modal.show();
        }
    </script>
@endpush
