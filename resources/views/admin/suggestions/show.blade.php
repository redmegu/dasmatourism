@extends('layouts.admin')

@section('page-title', 'Landmark Suggestion Details')

@section('content')
    <!-- Enhanced Page Header -->
    <div class="admin-page-header mb-4">
        <div class="admin-page-header-content">
            <div>
                <div class="admin-breadcrumb mb-2">
                    <a href="{{ route('admin.suggestions.index') }}" class="admin-breadcrumb-link">
                        <i class='bx bx-bulb'></i> Suggestions
                    </a>
                    <i class='bx bx-chevron-right'></i>
                    <span class="admin-breadcrumb-current">{{ $suggestion->name }}</span>
                </div>
                <h2 class="admin-page-title">{{ $suggestion->name }}</h2>
                <div class="admin-header-badges">
                    <span class="admin-badge {{ $suggestion->status }}">
                        <i
                            class='bx {{ $suggestion->status === 'approved' ? 'bx-check-circle' : ($suggestion->status === 'pending' ? 'bx-time' : 'bx-x-circle') }}'></i>
                        {{ ucfirst($suggestion->status) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Suggestion Details -->
            <div class="admin-detail-card mb-4">
                <div class="admin-detail-card-header">
                    <div class="admin-detail-card-icon primary">
                        <i class='bx bx-info-circle'></i>
                    </div>
                    <div>
                        <h5 class="admin-detail-card-title">Suggestion Information</h5>
                        <p class="admin-detail-card-subtitle">Details about the proposed landmark</p>
                    </div>
                </div>
                <div class="admin-detail-card-body">
                    <div class="admin-info-section mb-4">
                        <div class="admin-info-label">
                            <i class='bx bx-message-square-detail'></i>
                            <span>Description</span>
                        </div>
                        <div class="admin-info-description">{{ $suggestion->description }}</div>
                    </div>

                    <div class="admin-info-grid">
                        <div class="admin-info-item">
                            <div class="admin-info-label">
                                <i class='bx bx-category'></i>
                                <span>Category</span>
                            </div>
                            <div class="admin-info-value">{{ $suggestion->category }}</div>
                        </div>

                        <div class="admin-info-item">
                            <div class="admin-info-label">
                                <i class='bx bx-map-pin'></i>
                                <span>Location</span>
                            </div>
                            <div class="admin-info-value">{{ $suggestion->location }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submitted Images -->
            @if ($suggestion->images && count($suggestion->images) > 0)
                <div class="admin-detail-card mb-4">
                    <div class="admin-detail-card-header">
                        <div class="admin-detail-card-icon success">
                            <i class='bx bx-image'></i>
                        </div>
                        <div>
                            <h5 class="admin-detail-card-title">Submitted Images</h5>
                            <p class="admin-detail-card-subtitle">{{ count($suggestion->images) }} image(s) attached</p>
                        </div>
                    </div>
                    <div class="admin-detail-card-body">
                        <div class="admin-modern-gallery">
                            @foreach ($suggestion->images as $image)
                                <div class="admin-modern-gallery-item">
                                    <img src="{{ asset('storage/' . $image) }}" alt="{{ $suggestion->name }}"
                                        class="admin-modern-gallery-img" onclick="openImageModal(this.src)">
                                    <div class="admin-gallery-overlay">
                                        <i class='bx bx-zoom-in'></i>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Admin Response -->
            @if ($suggestion->status !== 'pending')
                <div class="admin-detail-card">
                    <div class="admin-detail-card-header">
                        <div class="admin-detail-card-icon info">
                            <i class='bx bx-message'></i>
                        </div>
                        <div>
                            <h5 class="admin-detail-card-title">Admin Response</h5>
                            <p class="admin-detail-card-subtitle">Official review feedback</p>
                        </div>
                    </div>
                    <div class="admin-detail-card-body">
                        @if ($suggestion->admin_notes)
                            <div class="admin-response-content">
                                <p>{{ $suggestion->admin_notes }}</p>
                            </div>
                        @else
                            <div class="admin-empty-response">
                                <i class='bx bx-message-square-x'></i>
                                <p>No response notes provided</p>
                            </div>
                        @endif

                        @if ($suggestion->reviewed_at)
                            <div class="admin-response-meta">
                                <i class='bx bx-time'></i>
                                <span>Reviewed {{ $suggestion->reviewed_at->diffForHumans() }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Submitter Info -->
            <div class="admin-submitter-card mb-4">
                <div class="admin-submitter-card-header">
                    <i class='bx bx-user'></i>
                    <span>Submitted By</span>
                </div>
                <div class="admin-submitter-card-body">
                    <div class="admin-submitter-profile">
                        <div class="admin-submitter-avatar">
                            {{ substr($suggestion->user->name, 0, 1) }}
                        </div>
                        <div class="admin-submitter-details">
                            <h6>{{ $suggestion->user->name }}</h6>
                            <p>{{ $suggestion->user->email }}</p>
                            <span class="admin-submitter-date">
                                <i class='bx bx-calendar'></i>
                                {{ $suggestion->created_at->format('M d, Y h:i A') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Review Actions -->
            @if ($suggestion->status === 'pending')
                <div class="admin-action-card mb-4">
                    <div class="admin-action-card-header">
                        <i class='bx bx-check-double'></i>
                        <span>Review Actions</span>
                    </div>
                    <div class="admin-action-card-body">
                        <button type="button" class="admin-action-btn approve" data-bs-toggle="modal"
                            data-bs-target="#approveModal">
                            <i class='bx bx-check-circle'></i>
                            <span>Approve Suggestion</span>
                        </button>

                        <button type="button" class="admin-action-btn reject" data-bs-toggle="modal"
                            data-bs-target="#rejectModal">
                            <i class='bx bx-x-circle'></i>
                            <span>Reject Suggestion</span>
                        </button>

                        <a href="{{ route('admin.suggestions.convert', $suggestion) }}" class="admin-action-btn convert">
                            <i class='bx bx-transfer'></i>
                            <span>Convert to Attraction</span>
                        </a>
                    </div>
                </div>
            @endif

            <!-- Convert to Attraction -->
            @if ($suggestion->status === 'approved')
                <div class="admin-convert-card">
                    <div class="admin-convert-card-header">
                        <i class='bx bx-transfer'></i>
                        <span>Convert to Attraction</span>
                    </div>
                    <div class="admin-convert-card-body">
                        <div class="admin-convert-info">
                            <i class='bx bx-info-circle'></i>
                            <p>This suggestion has been approved. Convert it to an official attraction.</p>
                        </div>
                        <a href="{{ route('admin.suggestions.convert', $suggestion) }}" class="admin-convert-btn">
                            <i class='bx bx-transfer'></i>
                            <span>Convert Now</span>
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Approve Modal -->
    <div class="modal fade" id="approveModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content admin-modern-modal">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class='bx bx-check-circle text-success'></i>
                        Approve Suggestion
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.suggestions.approve', $suggestion) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="admin-modal-form-group">
                            <label class="admin-modal-label">Response Notes (Optional)</label>
                            <textarea class="admin-modal-textarea" name="admin_notes" rows="4"
                                placeholder="Add any notes or feedback for the user..."></textarea>
                        </div>
                        <div class="admin-modal-alert success">
                            <i class='bx bx-info-circle'></i>
                            <span>The user will be notified that their suggestion has been approved.</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="admin-modal-btn secondary" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <button type="submit" class="admin-modal-btn success">
                            <i class='bx bx-check-circle'></i>
                            Approve Suggestion
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content admin-modern-modal">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class='bx bx-x-circle text-danger'></i>
                        Reject Suggestion
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.suggestions.reject', $suggestion) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="admin-modal-form-group">
                            <label class="admin-modal-label">
                                Reason for Rejection
                                <span class="text-danger">*</span>
                            </label>
                            <textarea class="admin-modal-textarea" name="admin_notes" rows="4"
                                placeholder="Explain why this suggestion is being rejected..." required></textarea>
                        </div>
                        <div class="admin-modal-alert warning">
                            <i class='bx bx-info-circle'></i>
                            <span>The user will be notified with your rejection reason.</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="admin-modal-btn secondary" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <button type="submit" class="admin-modal-btn danger">
                            <i class='bx bx-x-circle'></i>
                            Reject Suggestion
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content admin-image-modal">
                <div class="modal-body p-0">
                    <button type="button" class="admin-image-modal-close" data-bs-dismiss="modal">
                        <i class='bx bx-x'></i>
                    </button>
                    <img id="modalImage" src="" alt="Full size" class="admin-modal-image">
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

        .admin-header-badges {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            margin-top: 0.75rem;
        }

        .admin-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .admin-badge.approved {
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
            color: #059669;
        }

        .admin-badge.pending {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            color: #d97706;
        }

        .admin-badge.rejected {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            color: #dc2626;
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

        /* Info Section */
        .admin-info-section {
            border-bottom: 1px solid #f1f5f9;
            padding-bottom: 1.5rem;
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
            margin-bottom: 0.75rem;
        }

        .admin-info-label i {
            font-size: 1rem;
        }

        .admin-info-description {
            font-size: 0.9375rem;
            color: #1e293b;
            line-height: 1.6;
            white-space: pre-wrap;
        }

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

        .admin-info-value {
            font-size: 1rem;
            font-weight: 600;
            color: #1e293b;
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
            border-radius: 12px;
            overflow: hidden;
            background: #f1f5f9;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .admin-modern-gallery-item:hover {
            transform: scale(1.02);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
        }

        .admin-modern-gallery-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .admin-gallery-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
            font-size: 2rem;
            color: white;
        }

        .admin-modern-gallery-item:hover .admin-gallery-overlay {
            opacity: 1;
        }

        /* Response Content */
        .admin-response-content {
            padding: 1.25rem;
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border-radius: 10px;
            border-left: 4px solid #1a7838;
            margin-bottom: 1rem;
        }

        .admin-response-content p {
            font-size: 0.9375rem;
            color: #475569;
            line-height: 1.6;
            margin: 0;
            white-space: pre-wrap;
        }

        .admin-empty-response {
            text-align: center;
            padding: 3rem 2rem;
            color: #94a3b8;
        }

        .admin-empty-response i {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .admin-empty-response p {
            margin: 0;
        }

        .admin-response-meta {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.8125rem;
            color: #64748b;
        }

        /* Submitter Card */
        .admin-submitter-card {
            background: white;
            border-radius: 14px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #f1f5f9;
            overflow: hidden;
        }

        .admin-submitter-card-header {
            padding: 1.25rem 1.5rem;
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 700;
            color: #1e293b;
        }

        .admin-submitter-card-header i {
            font-size: 1.25rem;
            color: #1a7838;
        }

        .admin-submitter-card-body {
            padding: 1.5rem;
        }

        .admin-submitter-profile {
            display: flex;
            gap: 1rem;
        }

        .admin-submitter-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1a7838, #27a345);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: 700;
            flex-shrink: 0;
        }

        .admin-submitter-details {
            flex: 1;
        }

        .admin-submitter-details h6 {
            font-size: 1rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0 0 0.25rem 0;
        }

        .admin-submitter-details p {
            font-size: 0.8125rem;
            color: #64748b;
            margin: 0 0 0.5rem 0;
        }

        .admin-submitter-date {
            display: flex;
            align-items: center;
            gap: 0.375rem;
            font-size: 0.75rem;
            color: #94a3b8;
        }

        /* Action Cards */
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
            padding: 1rem;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .admin-action-btn {
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

        .admin-action-btn.approve {
            background: #d1fae5;
            color: #059669;
        }

        .admin-action-btn.approve:hover {
            background: #059669;
            color: white;
            transform: translateY(-2px);
        }

        .admin-action-btn.reject {
            background: #fee2e2;
            color: #dc2626;
        }

        .admin-action-btn.reject:hover {
            background: #dc2626;
            color: white;
            transform: translateY(-2px);
        }

        .admin-action-btn.convert {
            background: linear-gradient(135deg, #1a7838, #27a345);
            color: white;
            box-shadow: 0 4px 12px rgba(26, 120, 56, 0.25);
        }

        .admin-action-btn.convert:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(26, 120, 56, 0.35);
        }

        /* Convert Card */
        .admin-convert-card {
            background: white;
            border-radius: 14px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #f1f5f9;
            overflow: hidden;
        }

        .admin-convert-card-header {
            padding: 1.25rem 1.5rem;
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 700;
            color: #1e293b;
        }

        .admin-convert-card-header i {
            font-size: 1.25rem;
            color: #1a7838;
        }

        .admin-convert-card-body {
            padding: 1.5rem;
        }

        .admin-convert-info {
            display: flex;
            gap: 1rem;
            padding: 1rem;
            background: #eff6ff;
            border-radius: 10px;
            margin-bottom: 1.25rem;
        }

        .admin-convert-info i {
            font-size: 1.5rem;
            color: #0284c7;
            flex-shrink: 0;
        }

        .admin-convert-info p {
            font-size: 0.875rem;
            color: #475569;
            line-height: 1.6;
            margin: 0;
        }

        .admin-convert-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.875rem 1.25rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.9375rem;
            background: linear-gradient(135deg, #1a7838, #27a345);
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(26, 120, 56, 0.25);
        }

        .admin-convert-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(26, 120, 56, 0.35);
            color: white;
        }

        /* Modern Modals */
        .admin-modern-modal {
            border: none;
            border-radius: 16px;
            overflow: hidden;
        }

        .admin-modern-modal .modal-header {
            padding: 1.5rem;
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border-bottom: 1px solid #e2e8f0;
        }

        .admin-modern-modal .modal-title {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 700;
            font-size: 1.125rem;
        }

        .admin-modal-form-group {
            margin-bottom: 1.25rem;
        }

        .admin-modal-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 0.625rem;
        }

        .admin-modal-textarea {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 0.9375rem;
            transition: all 0.25s ease;
            resize: vertical;
        }

        .admin-modal-textarea:focus {
            outline: none;
            border-color: #1a7838;
            box-shadow: 0 0 0 4px rgba(26, 120, 56, 0.1);
        }

        .admin-modal-alert {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem;
            border-radius: 10px;
            font-size: 0.875rem;
        }

        .admin-modal-alert.success {
            background: #d1fae5;
            color: #059669;
        }

        .admin-modal-alert.warning {
            background: #fef3c7;
            color: #d97706;
        }

        .admin-modal-alert i {
            font-size: 1.25rem;
        }

        .admin-modal-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.9375rem;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .admin-modal-btn.secondary {
            background: #f1f5f9;
            color: #64748b;
        }

        .admin-modal-btn.secondary:hover {
            background: #e2e8f0;
        }

        .admin-modal-btn.success {
            background: #10b981;
            color: white;
        }

        .admin-modal-btn.success:hover {
            background: #059669;
            transform: translateY(-2px);
        }

        .admin-modal-btn.danger {
            background: #dc2626;
            color: white;
        }

        .admin-modal-btn.danger:hover {
            background: #b91c1c;
            transform: translateY(-2px);
        }

        /* Image Modal */
        .admin-image-modal {
            background: transparent;
            border: none;
        }

        .admin-image-modal .modal-body {
            position: relative;
        }

        .admin-image-modal-close {
            position: absolute;
            top: 1rem;
            right: 1rem;
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            cursor: pointer;
            z-index: 10;
            transition: all 0.3s ease;
        }

        .admin-image-modal-close:hover {
            background: rgba(0, 0, 0, 0.9);
            transform: rotate(90deg);
        }

        .admin-modal-image {
            width: 100%;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }

        /* Responsive */
        @media (max-width: 992px) {
            .admin-info-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .admin-page-header {
                padding: 1.5rem;
            }

            .admin-modern-gallery {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        function openImageModal(src) {
            document.getElementById('modalImage').src = src;
            new bootstrap.Modal(document.getElementById('imageModal')).show();
        }
    </script>
@endpush
