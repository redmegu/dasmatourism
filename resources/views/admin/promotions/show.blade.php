@extends('layouts.admin')

@section('page-title', 'Promotion Details')

@section('content')
    <!-- Enhanced Page Header -->
    <div class="admin-page-header mb-4">
        <div class="admin-page-header-content">
            <div>
                <div class="admin-breadcrumb mb-2">
                    <a href="{{ route('admin.promotions.index') }}" class="admin-breadcrumb-link">
                        <i class='bx bx-bullhorn'></i> Promotions
                    </a>
                    <i class='bx bx-chevron-right'></i>
                    <span class="admin-breadcrumb-current">{{ $promotion->title }}</span>
                </div>
                <h2 class="admin-page-title">{{ $promotion->title }}</h2>
                <div class="admin-header-badges">
                    <span class="admin-badge {{ $promotion->isActive() ? 'active' : 'inactive' }}">
                        <i class='bx {{ $promotion->isActive() ? 'bx-check-circle' : 'bx-x-circle' }}'></i>
                        {{ $promotion->isActive() ? 'Active' : 'Inactive' }}
                    </span>
                    <span class="admin-badge type">
                        <i
                            class='bx {{ class_basename($promotion->promotable_type) === 'Attraction' ? 'bx-map-pin' : 'bx-store' }}'></i>
                        {{ class_basename($promotion->promotable_type) }}
                    </span>
                </div>
            </div>
            <div class="admin-header-actions">
                <a href="{{ route('admin.promotions.edit', $promotion) }}" class="admin-header-action-btn primary">
                    <i class='bx bx-edit'></i>
                    <span>Edit</span>
                </a>
                <button type="button" class="admin-header-action-btn danger" onclick="confirmDelete()">
                    <i class='bx bx-trash'></i>
                    <span>Delete</span>
                </button>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Promotion Image -->
            @if ($promotion->image)
                <div class="admin-promo-image-card mb-4">
                    <img src="{{ asset('storage/' . $promotion->image) }}" alt="{{ $promotion->title }}"
                        class="admin-promo-image">
                </div>
            @endif

            <!-- Promotion Details -->
            <div class="admin-detail-card mb-4">
                <div class="admin-detail-card-header">
                    <div class="admin-detail-card-icon primary">
                        <i class='bx bx-info-circle'></i>
                    </div>
                    <div>
                        <h5 class="admin-detail-card-title">Promotion Information</h5>
                        <p class="admin-detail-card-subtitle">Campaign details and timeline</p>
                    </div>
                </div>
                <div class="admin-detail-card-body">
                    <div class="admin-info-section mb-4">
                        <div class="admin-info-label">
                            <i class='bx bx-message-square-detail'></i>
                            <span>Description</span>
                        </div>
                        <div class="admin-info-description">{{ $promotion->description }}</div>
                    </div>

                    <div class="admin-info-grid">
                        <div class="admin-info-item">
                            <div class="admin-info-label">
                                <i class='bx bx-calendar-event'></i>
                                <span>Start Date</span>
                            </div>
                            <div class="admin-info-value">{{ $promotion->start_date->format('F d, Y') }}</div>
                        </div>

                        <div class="admin-info-item">
                            <div class="admin-info-label">
                                <i class='bx bx-calendar-x'></i>
                                <span>End Date</span>
                            </div>
                            <div class="admin-info-value">{{ $promotion->end_date->format('F d, Y') }}</div>
                        </div>

                        <div class="admin-info-item">
                            <div class="admin-info-label">
                                <i class='bx bx-time'></i>
                                <span>Duration</span>
                            </div>
                            <div class="admin-info-value">{{ $promotion->start_date->diffInDays($promotion->end_date) }}
                                days</div>
                        </div>

                        <div class="admin-info-item">
                            <div class="admin-info-label">
                                <i class='bx bx-show'></i>
                                <span>Total Views</span>
                            </div>
                            <div class="admin-info-value">{{ number_format($promotion->views) }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Target Details -->
            <div class="admin-detail-card">
                <div class="admin-detail-card-header">
                    <div class="admin-detail-card-icon success">
                        <i class='bx bx-target-lock'></i>
                    </div>
                    <div>
                        <h5 class="admin-detail-card-title">Promotion Target</h5>
                        <p class="admin-detail-card-subtitle">What this promotion is promoting</p>
                    </div>
                </div>
                <div class="admin-detail-card-body">
                    @if ($promotion->promotable)
                        <div class="admin-target-card">
                            <div class="admin-target-icon-wrapper">
                                <div class="admin-target-icon">
                                    <i
                                        class='bx {{ class_basename($promotion->promotable_type) === 'Attraction' ? 'bx-map-pin' : 'bx-store' }}'></i>
                                </div>
                            </div>
                            <div class="admin-target-content">
                                <span class="admin-target-badge">{{ class_basename($promotion->promotable_type) }}</span>
                                <h5 class="admin-target-name">{{ $promotion->promotable->name }}</h5>
                                @if ($promotion->promotable->description)
                                    <p class="admin-target-description">
                                        {{ Str::limit($promotion->promotable->description, 150) }}
                                    </p>
                                @endif
                                <a href="{{ class_basename($promotion->promotable_type) === 'Attraction' ? route('attractions.show', $promotion->promotable->slug) : route('businesses.show', $promotion->promotable->slug) }}"
                                    class="admin-target-link" target="_blank">
                                    <i class='bx bx-link-external'></i>
                                    View on Website
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="admin-empty-target">
                            <i class='bx bx-info-circle'></i>
                            <p>Target not found</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Status Card -->
            <div class="admin-status-info-card mb-4">
                <div class="admin-status-info-header">
                    <i class='bx bx-info-circle'></i>
                    <span>Campaign Status</span>
                </div>
                <div class="admin-status-info-body">
                    @if ($promotion->isActive())
                        <div class="admin-status-alert active">
                            <div class="admin-status-icon">
                                <i class='bx bxs-check-circle'></i>
                            </div>
                            <div class="admin-status-content">
                                <strong>Active Campaign</strong>
                                <span>This promotion is currently running</span>
                            </div>
                        </div>
                    @elseif($promotion->isExpired())
                        <div class="admin-status-alert expired">
                            <div class="admin-status-icon">
                                <i class='bx bxs-x-circle'></i>
                            </div>
                            <div class="admin-status-content">
                                <strong>Expired</strong>
                                <span>This promotion has ended</span>
                            </div>
                        </div>
                    @elseif($promotion->isUpcoming())
                        <div class="admin-status-alert upcoming">
                            <div class="admin-status-icon">
                                <i class='bx bxs-time'></i>
                            </div>
                            <div class="admin-status-content">
                                <strong>Upcoming</strong>
                                <span>Starts {{ $promotion->start_date->format('M d, Y') }}</span>
                            </div>
                        </div>
                    @else
                        <div class="admin-status-alert inactive">
                            <div class="admin-status-icon">
                                <i class='bx bxs-pause-circle'></i>
                            </div>
                            <div class="admin-status-content">
                                <strong>Inactive</strong>
                                <span>This promotion is disabled</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Creator Info -->
            @if ($promotion->creator)
                <div class="admin-creator-card mb-4">
                    <div class="admin-creator-card-header">
                        <i class='bx bx-user'></i>
                        <span>Created By</span>
                    </div>
                    <div class="admin-creator-card-body">
                        <div class="admin-creator-info">
                            <div class="admin-creator-avatar">
                                {{ substr($promotion->creator->name, 0, 1) }}
                            </div>
                            <div class="admin-creator-details">
                                <strong>{{ $promotion->creator->name }}</strong>
                                <span>{{ $promotion->creator->email }}</span>
                                <small>{{ $promotion->created_at->format('M d, Y h:i A') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Quick Actions -->
            <div class="admin-quick-actions-card">
                <div class="admin-quick-actions-header">
                    <i class='bx bx-bolt'></i>
                    <span>Quick Actions</span>
                </div>
                <div class="admin-quick-actions-body">
                    <a href="{{ route('admin.promotions.edit', $promotion) }}" class="admin-quick-action-item">
                        <i class='bx bx-edit'></i>
                        <span>Edit Promotion</span>
                        <i class='bx bx-chevron-right'></i>
                    </a>

                    <button type="button" class="admin-quick-action-item danger" onclick="confirmDelete()">
                        <i class='bx bx-trash'></i>
                        <span>Delete Promotion</span>
                        <i class='bx bx-chevron-right'></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Form -->
    <form id="delete-form" action="{{ route('admin.promotions.destroy', $promotion) }}" method="POST" class="d-none">
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

        .admin-badge.active {
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
            color: #059669;
        }

        .admin-badge.inactive {
            background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
            color: #64748b;
        }

        .admin-badge.type {
            background: linear-gradient(135deg, #dbeafe, #bfdbfe);
            color: #0284c7;
        }

        .admin-header-actions {
            display: flex;
            gap: 0.75rem;
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
        }

        .admin-header-action-btn.primary {
            background: linear-gradient(135deg, #1a7838, #27a345);
            color: white;
            box-shadow: 0 4px 12px rgba(26, 120, 56, 0.25);
        }

        .admin-header-action-btn.primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(26, 120, 56, 0.35);
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

        /* Promo Image Card */
        .admin-promo-image-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #f1f5f9;
        }

        .admin-promo-image {
            width: 100%;
            height: auto;
            display: block;
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

        /* Target Card */
        .admin-target-card {
            display: flex;
            gap: 1.5rem;
            padding: 1.5rem;
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border-radius: 12px;
            border: 1px solid #e2e8f0;
        }

        .admin-target-icon-wrapper {
            flex-shrink: 0;
        }

        .admin-target-icon {
            width: 70px;
            height: 70px;
            border-radius: 14px;
            background: linear-gradient(135deg, #1a7838, #27a345);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
            box-shadow: 0 4px 12px rgba(26, 120, 56, 0.25);
        }

        .admin-target-content {
            flex: 1;
        }

        .admin-target-badge {
            display: inline-block;
            padding: 0.375rem 0.875rem;
            background: linear-gradient(135deg, #00A8E8, #0284c7);
            color: white;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.75rem;
        }

        .admin-target-name {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0.5rem 0;
        }

        .admin-target-description {
            font-size: 0.875rem;
            color: #64748b;
            line-height: 1.6;
            margin: 0.75rem 0;
        }

        .admin-target-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.625rem 1.25rem;
            background: white;
            color: #1a7838;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.875rem;
            text-decoration: none;
            transition: all 0.3s ease;
            border: 2px solid #e2e8f0;
        }

        .admin-target-link:hover {
            background: #1a7838;
            color: white;
            border-color: #1a7838;
            transform: translateY(-2px);
        }

        .admin-empty-target {
            text-align: center;
            padding: 3rem 2rem;
            color: #94a3b8;
        }

        .admin-empty-target i {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        /* Status Info Card */
        .admin-status-info-card {
            background: white;
            border-radius: 14px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #f1f5f9;
            overflow: hidden;
        }

        .admin-status-info-header {
            padding: 1.25rem 1.5rem;
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 700;
            color: #1e293b;
        }

        .admin-status-info-header i {
            font-size: 1.25rem;
            color: #1a7838;
        }

        .admin-status-info-body {
            padding: 1.5rem;
        }

        .admin-status-alert {
            display: flex;
            gap: 1rem;
            padding: 1.25rem;
            border-radius: 12px;
            border: 2px solid;
        }

        .admin-status-alert.active {
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
            border-color: #059669;
        }

        .admin-status-alert.expired {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            border-color: #dc2626;
        }

        .admin-status-alert.upcoming {
            background: linear-gradient(135deg, #dbeafe, #bfdbfe);
            border-color: #0284c7;
        }

        .admin-status-alert.inactive {
            background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
            border-color: #94a3b8;
        }

        .admin-status-icon {
            font-size: 2rem;
            flex-shrink: 0;
        }

        .admin-status-alert.active .admin-status-icon {
            color: #059669;
        }

        .admin-status-alert.expired .admin-status-icon {
            color: #dc2626;
        }

        .admin-status-alert.upcoming .admin-status-icon {
            color: #0284c7;
        }

        .admin-status-alert.inactive .admin-status-icon {
            color: #64748b;
        }

        .admin-status-content {
            flex: 1;
        }

        .admin-status-content strong {
            display: block;
            font-size: 1rem;
            color: #1e293b;
            margin-bottom: 0.25rem;
        }

        .admin-status-content span {
            display: block;
            font-size: 0.875rem;
            color: #475569;
        }

        /* Creator Card */
        .admin-creator-card {
            background: white;
            border-radius: 14px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #f1f5f9;
            overflow: hidden;
        }

        .admin-creator-card-header {
            padding: 1.25rem 1.5rem;
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 700;
            color: #1e293b;
        }

        .admin-creator-card-header i {
            font-size: 1.25rem;
            color: #1a7838;
        }

        .admin-creator-card-body {
            padding: 1.5rem;
        }

        .admin-creator-info {
            display: flex;
            gap: 1rem;
        }

        .admin-creator-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1a7838, #27a345);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            font-weight: 700;
            flex-shrink: 0;
        }

        .admin-creator-details {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .admin-creator-details strong {
            font-size: 0.9375rem;
            color: #1e293b;
        }

        .admin-creator-details span {
            font-size: 0.8125rem;
            color: #64748b;
        }

        .admin-creator-details small {
            font-size: 0.75rem;
            color: #94a3b8;
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

            .admin-target-card {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        function confirmDelete() {
            if (confirm('Are you sure you want to delete this promotion?\n\nThis action cannot be undone.')) {
                document.getElementById('delete-form').submit();
            }
        }
    </script>
@endpush
