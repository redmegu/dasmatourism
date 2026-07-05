@extends('layouts.business-owner')

@section('title', 'My Business Profile')
@section('page-title', 'My Business Profile')

@section('content')
    <!-- Business Profile Header -->
    <div class="business-profile-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="d-flex align-items-center gap-4">
                    <div class="business-logo-large">
                        @if ($business->logo)
                            <img src="{{ asset('storage/' . $business->logo) }}" alt="{{ $business->name }}"
                                class="business-logo-img">
                        @else
                            <div class="business-logo-placeholder-large">
                                <i class='bx bx-store'></i>
                            </div>
                        @endif
                    </div>
                    <div>
                        <h2 class="business-profile-name">{{ $business->name }}</h2>
                        <p class="business-profile-category">
                            <i class='bx bx-category'></i>
                            {{ $business->category->name }}
                        </p>
                        <div class="business-profile-badges">
                            <span class="status-badge status-badge-{{ $business->status }}">
                                <i
                                    class='bx {{ $business->status === 'approved' ? 'bx-check-circle' : ($business->status === 'pending' ? 'bx-time-five' : 'bx-x-circle') }}'></i>
                                {{ ucfirst($business->status) }}
                            </span>
                            @if ($business->is_verified)
                                <span class="status-badge status-badge-verified">
                                    <i class='bx bxs-badge-check'></i>
                                    Verified
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-end">
                <a href="{{ route('business-owner.profile.edit') }}" class="btn btn-primary">
                    <i class='bx bx-edit'></i> Edit Profile
                </a>
                <a href="{{ route('business-owner.profile.preview') }}" class="btn btn-outline-secondary mt-2"
                    target="_blank">
                    <i class='bx bx-link-external'></i> View Public Profile
                </a>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Main Information -->
        <div class="col-lg-8">
            <!-- Basic Information -->
            <div class="dashboard-card">
                <div class="dashboard-card-header">
                    <h5 class="dashboard-card-title">
                        <i class='bx bx-info-circle'></i> Basic Information
                    </h5>
                </div>
                <div class="dashboard-card-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <label><i class='bx bx-store'></i> Business Name</label>
                            <p>{{ $business->name }}</p>
                        </div>
                        <div class="info-item">
                            <label><i class='bx bx-category'></i> Category</label>
                            <p>{{ $business->category->name }}</p>
                        </div>
                        <div class="info-item">
                            <label><i class='bx bx-phone'></i> Contact Number</label>
                            <p>{{ $business->contact_number }}</p>
                        </div>
                        @if ($business->email)
                            <div class="info-item">
                                <label><i class='bx bx-envelope'></i> Email</label>
                                <p><a href="mailto:{{ $business->email }}">{{ $business->email }}</a></p>
                            </div>
                        @endif
                        @if ($business->website)
                            <div class="info-item">
                                <label><i class='bx bx-globe'></i> Website</label>
                                <p><a href="{{ $business->website }}" target="_blank">{{ $business->website }}</a></p>
                            </div>
                        @endif
                        @if ($business->google_maps_link)
                            <div class="info-item">
                                <label><i class='bx bx-map text-primary'></i> Google Maps</label>
                                <p><a href="{{ $business->google_maps_link }}" target="_blank" class="text-primary">
                                        <i class='bx bx-navigation'></i> Open Directions</a></p>
                            </div>
                        @endif
                        <div class="info-item full-width">
                            <label><i class='bx bx-map'></i> Address</label>
                            <p>{{ $business->address }}</p>
                        </div>
                        <div class="info-item full-width">
                            <label><i class='bx bx-message-square-detail'></i> Description</label>
                            <p>{{ $business->description }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Business Permit Card (ADD THIS NEW SECTION) -->
            @if ($business->business_permit)
                <div class="dashboard-card">
                    <div class="dashboard-card-header">
                        <h5 class="dashboard-card-title">
                            <i class='bx bx-file-blank'></i> Business Permit
                        </h5>
                        @if ($business->hasVerifiedPermit())
                            <span class="badge bg-success ms-auto">
                                <i class='bx bx-check-circle'></i> Verified
                            </span>
                        @else
                            <span class="badge bg-warning ms-auto">
                                <i class='bx bx-time'></i> Pending Verification
                            </span>
                        @endif
                    </div>
                    <div class="dashboard-card-body">
                        <div class="permit-display-card">
                            <div class="permit-display-header">
                                <div class="permit-display-icon">
                                    @if (Str::endsWith($business->business_permit, '.pdf'))
                                        <i class='bx bxs-file-pdf'></i>
                                    @else
                                        <i class='bx bxs-file-image'></i>
                                    @endif
                                </div>
                                <div class="permit-display-info">
                                    <h6 class="mb-1">Business Permit Document</h6>
                                    <p class="text-muted small mb-2">
                                        Submitted: {{ $business->created_at->format('M d, Y') }}
                                    </p>
                                    @if ($business->hasVerifiedPermit())
                                        <div class="alert alert-success alert-sm mb-0">
                                            <i class='bx bx-check-circle'></i>
                                            <strong>Verified on
                                                {{ $business->permit_verified_at->format('M d, Y') }}</strong>
                                            <br>
                                            <small>Your business permit has been verified by our admin team.</small>
                                        </div>
                                    @else
                                        <div class="alert alert-warning alert-sm mb-0">
                                            <i class='bx bx-time'></i>
                                            <strong>Pending Verification</strong>
                                            <br>
                                            <small>Your permit is currently being reviewed. You'll be notified once
                                                verified.</small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="permit-display-actions">
                                <a href="{{ asset('storage/' . $business->business_permit) }}" target="_blank"
                                    class="btn btn-primary">
                                    <i class='bx bx-show'></i> View Document
                                </a>
                                <a href="{{ asset('storage/' . $business->business_permit) }}" download
                                    class="btn btn-outline-secondary">
                                    <i class='bx bx-download'></i> Download
                                </a>
                            </div>
                        </div>

                        <!-- Permit Preview for Images -->
                        @if (!Str::endsWith($business->business_permit, '.pdf'))
                            <div class="permit-preview-thumbnail mt-3">
                                <img src="{{ asset('storage/' . $business->business_permit) }}" alt="Business Permit"
                                    class="img-fluid rounded"
                                    onclick="viewPermit('{{ asset('storage/' . $business->business_permit) }}')">
                                <p class="text-center text-muted small mt-2">Click to view full size</p>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <!-- No Permit Warning -->
                <div class="dashboard-card border-warning">
                    <div class="dashboard-card-header bg-warning-subtle">
                        <h5 class="dashboard-card-title text-warning">
                            <i class='bx bx-error'></i> Business Permit Missing
                        </h5>
                    </div>
                    <div class="dashboard-card-body">
                        <div class="alert alert-warning mb-0">
                            <i class='bx bx-info-circle'></i>
                            <strong>Action Required:</strong> You haven't uploaded your business permit yet.
                            Please upload a valid business permit to complete your profile verification.
                            <br><br>
                            <a href="{{ route('business-owner.profile.edit') }}" class="btn btn-warning btn-sm mt-2">
                                <i class='bx bx-upload'></i> Upload Permit Now
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Operating Hours -->
            @if ($business->operating_hours)
                <div class="dashboard-card">
                    <div class="dashboard-card-header">
                        <h5 class="dashboard-card-title">
                            <i class='bx bx-time'></i> Operating Hours
                        </h5>
                    </div>
                    <div class="dashboard-card-body">
                        <pre class="operating-hours-text">{{ $business->operating_hours }}</pre>
                    </div>
                </div>
            @endif

            <!-- Services -->
            @if (
                $business->services &&
                    (is_array($business->services) ? count($business->services) > 0 : !empty($business->services)))
                <div class="dashboard-card">
                    <div class="dashboard-card-header">
                        <h5 class="dashboard-card-title">
                            <i class='bx bx-list-ul'></i> Services Offered
                        </h5>
                    </div>
                    <div class="dashboard-card-body">
                        <div class="services-list">
                            @if (is_array($business->services))
                                @foreach ($business->services as $service)
                                    <div class="service-item">
                                        <i class='bx bx-check-circle'></i>
                                        <span>{{ $service }}</span>
                                    </div>
                                @endforeach
                            @else
                                <pre class="services-text">{{ $business->services }}</pre>
                            @endif
                        </div>
                    </div>
                </div>
            @endif


            <!-- Business Images -->
            @if ($business->images && count($business->images) > 0)
                <div class="dashboard-card">
                    <div class="dashboard-card-header">
                        <h5 class="dashboard-card-title">
                            <i class='bx bx-image'></i> Business Photos
                        </h5>
                    </div>
                    <div class="dashboard-card-body">
                        <div class="business-images-grid">
                            @foreach ($business->images as $image)
                                <div class="business-image-item">
                                    <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $business->name }}"
                                        onclick="viewImage('{{ asset('storage/' . $image->image_path) }}')">
                                    <div class="image-caption">{{ $image->caption ?? 'Photo ' . $loop->iteration }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Statistics Card -->
            <div class="dashboard-card">
                <div class="dashboard-card-header">
                    <h5 class="dashboard-card-title">
                        <i class='bx bx-bar-chart'></i> Statistics
                    </h5>
                </div>
                <div class="dashboard-card-body">
                    <div class="stat-item-compact">
                        <div class="stat-icon-compact stat-icon-primary">
                            <i class='bx bx-show'></i>
                        </div>
                        <div>
                            <p class="stat-label-compact">Total Views</p>
                            <h4 class="stat-value-compact">{{ number_format($business->views) }}</h4>
                        </div>
                    </div>
                    <div class="stat-item-compact">
                        <div class="stat-icon-compact stat-icon-warning">
                            <i class='bx bxs-star'></i>
                        </div>
                        <div>
                            <p class="stat-label-compact">Average Rating</p>
                            <h4 class="stat-value-compact">{{ number_format($business->getAverageRating(), 1) }}</h4>
                            <div class="rating-stars rating-stars-small">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class='bx {{ $i <= $business->getAverageRating() ? 'bxs-star' : 'bx-star' }}'></i>
                                @endfor
                            </div>
                        </div>
                    </div>
                    <div class="stat-item-compact">
                        <div class="stat-icon-compact stat-icon-success">
                            <i class='bx bx-message-square-detail'></i>
                        </div>
                        <div>
                            <p class="stat-label-compact">Total Reviews</p>
                            <h4 class="stat-value-compact">{{ $business->approvedReviews->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Reviews -->
            <div class="dashboard-card">
                <div class="dashboard-card-header">
                    <h5 class="dashboard-card-title">
                        <i class='bx bx-message-square-detail'></i> Recent Reviews
                    </h5>
                </div>
                <div class="dashboard-card-body p-0">
                    @forelse($business->approvedReviews->take(5) as $review)
                        <div class="review-item-compact">
                            <div class="review-user-compact">
                                <div class="review-avatar-compact">
                                    @if ($review->user->profile_picture)
                                        <img src="{{ asset('storage/' . $review->user->profile_picture) }}"
                                            alt="{{ $review->user->name }}">
                                    @else
                                        <i class='bx bx-user'></i>
                                    @endif
                                </div>
                                <div>
                                    <h6>{{ $review->user->name }}</h6>
                                    <div class="rating-stars rating-stars-small">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class='bx {{ $i <= $review->rating ? 'bxs-star' : 'bx-star' }}'></i>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                            @if ($review->comment)
                                <p class="review-comment-compact">{{ Str::limit($review->comment, 80) }}</p>
                            @endif
                            <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                        </div>
                    @empty
                        <div class="empty-state-compact">
                            <i class='bx bx-message-square-detail'></i>
                            <p>No reviews yet</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-0">
                    <img src="" alt="Business Photo" id="modalImage" class="img-fluid">
                </div>
            </div>
        </div>
    </div>

    <!-- Permit Modal -->
    <div class="modal fade" id="permitModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title">Business Permit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-0">
                    <img src="" alt="Business Permit" id="modalPermit" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Permit Display Card */
        .permit-display-card {
            background: linear-gradient(135deg, #fef3c7, #fef08a);
            border-radius: 16px;
            padding: 2rem;
            border: 2px solid #fbbf24;
        }

        .permit-display-header {
            display: flex;
            align-items: flex-start;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .permit-display-icon {
            width: 80px;
            height: 80px;
            border-radius: 16px;
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(251, 191, 36, 0.3);
        }

        .permit-display-info {
            flex: 1;
        }

        .permit-display-info h6 {
            font-size: 1.25rem;
            font-weight: 700;
            color: #78350f;
            margin-bottom: 0.5rem;
        }

        .alert-sm {
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
        }

        .alert-success.alert-sm {
            background: #d1fae5;
            border-color: #10b981;
            color: #065f46;
        }

        .alert-warning.alert-sm {
            background: #fef3c7;
            border-color: #fbbf24;
            color: #78350f;
        }

        .permit-display-actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .permit-preview-thumbnail {
            border: 2px solid #fbbf24;
            border-radius: 12px;
            padding: 1rem;
            background: white;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .permit-preview-thumbnail:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(251, 191, 36, 0.3);
        }

        .permit-preview-thumbnail img {
            max-height: 400px;
            object-fit: contain;
            margin: 0 auto;
            display: block;
        }

        /* Warning Card Styling */
        .dashboard-card.border-warning {
            border: 2px solid #fbbf24;
        }

        .bg-warning-subtle {
            background: linear-gradient(135deg, #fef3c7, #fef08a);
        }
    </style>
@endpush

@push('scripts')
    <script>
        function viewImage(src) {
            document.getElementById('modalImage').src = src;
            const modal = new bootstrap.Modal(document.getElementById('imageModal'));
            modal.show();
        }

        function viewPermit(src) {
            document.getElementById('modalPermit').src = src;
            const modal = new bootstrap.Modal(document.getElementById('permitModal'));
            modal.show();
        }
    </script>
@endpush
