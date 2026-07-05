@extends('layouts.admin')

@section('page-title', 'Landmark Suggestions')

@section('content')
    <!-- Enhanced Page Header -->
    <div class="admin-page-header mb-4">
        <div class="admin-page-header-content">
            <div>
                <h2 class="admin-page-title">Landmark Suggestions</h2>
                <p class="admin-page-subtitle">Review and approve community-submitted landmarks</p>
            </div>
        </div>
    </div>

    <!-- Enhanced Filters -->
    <div class="admin-filter-card mb-4">
        <form method="GET" action="{{ route('admin.suggestions.index') }}">
            <div class="admin-filter-grid">
                <div class="admin-filter-item">
                    <label class="admin-filter-label">
                        <i class='bx bx-filter'></i> Status
                    </label>
                    <select name="status" class="admin-filter-select">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>

                <div class="admin-filter-actions">
                    <button type="submit" class="admin-filter-btn primary">
                        <i class='bx bx-search'></i>
                        <span>Apply Filters</span>
                    </button>
                    <a href="{{ route('admin.suggestions.index') }}" class="admin-filter-btn secondary">
                        <i class='bx bx-reset'></i>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Enhanced Stats -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="admin-stat-card-modern primary">
                <div class="admin-stat-card-icon-modern">
                    <i class='bx bxs-bulb'></i>
                </div>
                <div class="admin-stat-card-content-modern">
                    <p>Total Suggestions</p>
                    <h3>{{ $suggestions->total() }}</h3>
                </div>
                <div class="admin-stat-card-decoration"></div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="admin-stat-card-modern warning">
                <div class="admin-stat-card-icon-modern">
                    <i class='bx bxs-time'></i>
                </div>
                <div class="admin-stat-card-content-modern">
                    <p>Pending Review</p>
                    <h3>{{ \App\Models\LandmarkSuggestion::where('status', 'pending')->count() }}</h3>
                </div>
                <div class="admin-stat-card-decoration"></div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="admin-stat-card-modern success">
                <div class="admin-stat-card-icon-modern">
                    <i class='bx bxs-check-circle'></i>
                </div>
                <div class="admin-stat-card-content-modern">
                    <p>Approved</p>
                    <h3>{{ \App\Models\LandmarkSuggestion::where('status', 'approved')->count() }}</h3>
                </div>
                <div class="admin-stat-card-decoration"></div>
            </div>
        </div>
    </div>

    <!-- Suggestions Grid -->
    <div class="row g-4">
        @forelse($suggestions as $suggestion)
            <div class="col-md-6 col-lg-4">
                <div class="admin-suggestion-card-modern">
                    <!-- Image Section -->
                    @if ($suggestion->image_path)
                        <div class="admin-suggestion-image-wrapper">
                            <img src="{{ asset('storage/' . $suggestion->image_path) }}" alt="{{ $suggestion->name }}"
                                class="admin-suggestion-image">
                            <div class="admin-suggestion-overlay">
                                <span class="admin-suggestion-status-badge {{ $suggestion->status }}">
                                    <i
                                        class='bx {{ $suggestion->status === 'approved' ? 'bx-check-circle' : ($suggestion->status === 'pending' ? 'bx-time' : 'bx-x-circle') }}'></i>
                                    {{ ucfirst($suggestion->status) }}
                                </span>
                            </div>
                        </div>
                    @else
                        <div class="admin-suggestion-no-image">
                            <div class="admin-suggestion-no-image-icon">
                                <i class='bx bx-image-add'></i>
                            </div>
                            <div class="admin-suggestion-overlay">
                                <span class="admin-suggestion-status-badge {{ $suggestion->status }}">
                                    <i
                                        class='bx {{ $suggestion->status === 'approved' ? 'bx-check-circle' : ($suggestion->status === 'pending' ? 'bx-time' : 'bx-x-circle') }}'></i>
                                    {{ ucfirst($suggestion->status) }}
                                </span>
                            </div>
                        </div>
                    @endif

                    <!-- Card Body -->
                    <div class="admin-suggestion-card-body">
                        <h6 class="admin-suggestion-name">{{ $suggestion->name }}</h6>

                        <div class="admin-suggestion-location">
                            <i class='bx bx-map-pin'></i>
                            <span>{{ Str::limit($suggestion->location, 40) }}</span>
                        </div>

                        @if ($suggestion->description)
                            <p class="admin-suggestion-description">
                                {{ Str::limit($suggestion->description, 80) }}
                            </p>
                        @endif

                        <!-- Meta Info -->
                        <div class="admin-suggestion-meta">
                            <div class="admin-suggestion-user">
                                <div class="admin-suggestion-user-avatar">
                                    {{ substr($suggestion->user->name, 0, 1) }}
                                </div>
                                <div class="admin-suggestion-user-info">
                                    <span class="admin-suggestion-user-name">{{ $suggestion->user->name }}</span>
                                    <span
                                        class="admin-suggestion-time">{{ $suggestion->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="admin-suggestion-actions">
                            <a href="{{ route('admin.suggestions.show', $suggestion) }}"
                                class="admin-suggestion-action-btn view">
                                <i class='bx bx-show'></i>
                                <span>View Details</span>
                            </a>

                            @if ($suggestion->status === 'approved')
                                <a href="{{ route('admin.suggestions.convert', $suggestion) }}"
                                    class="admin-suggestion-action-btn convert">
                                    <i class='bx bx-right-arrow-circle'></i>
                                    <span>Convert</span>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="admin-empty-state">
                    <div class="admin-empty-icon">
                        <i class='bx bx-bulb'></i>
                    </div>
                    <h5>No Suggestions Found</h5>
                    <p>Landmark suggestions will appear here when users submit them</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if ($suggestions->hasPages())
        <div class="admin-pagination-wrapper mt-4">
            {{ $suggestions->links('vendor.pagination.admin') }}
        </div>
    @endif
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
            align-items: center;
            gap: 2rem;
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

        /* Filter Card */
        .admin-filter-card {
            background: white;
            border-radius: 14px;
            padding: 1.75rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #f1f5f9;
        }

        .admin-filter-grid {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 1.25rem;
            align-items: end;
        }

        .admin-filter-item {
            display: flex;
            flex-direction: column;
        }

        .admin-filter-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 0.625rem;
        }

        .admin-filter-select {
            padding: 0.75rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 0.9375rem;
            transition: all 0.25s ease;
        }

        .admin-filter-select:focus {
            outline: none;
            border-color: #1a7838;
            box-shadow: 0 0 0 4px rgba(26, 120, 56, 0.1);
        }

        .admin-filter-actions {
            display: flex;
            gap: 0.5rem;
        }

        .admin-filter-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
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

        .admin-filter-btn.primary {
            background: linear-gradient(135deg, #1a7838, #27a345);
            color: white;
            box-shadow: 0 4px 12px rgba(26, 120, 56, 0.25);
        }

        .admin-filter-btn.primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(26, 120, 56, 0.35);
        }

        .admin-filter-btn.secondary {
            background: #f1f5f9;
            color: #64748b;
            padding: 0.75rem 1rem;
        }

        .admin-filter-btn.secondary:hover {
            background: #e2e8f0;
        }

        /* Modern Stat Cards */
        .admin-stat-card-modern {
            position: relative;
            padding: 1.75rem;
            border-radius: 16px;
            overflow: visible;
            display: flex;
            align-items: center;
            gap: 1.5rem;
            transition: all 0.3s ease;
            border: 1px solid;
        }

        .admin-stat-card-modern.primary {
            background: linear-gradient(135deg, #e0f2e9, #d1fae5);
            border-color: rgba(26, 120, 56, 0.2);
        }

        .admin-stat-card-modern.warning {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            border-color: rgba(245, 158, 11, 0.2);
        }

        .admin-stat-card-modern.success {
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
            border-color: rgba(16, 185, 129, 0.2);
        }

        .admin-stat-card-modern:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
        }

        .admin-stat-card-icon-modern {
            min-width: 70px;
            width: 70px;
            height: 70px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            position: relative;
            z-index: 2;
        }

        .admin-stat-card-modern.primary .admin-stat-card-icon-modern {
            background: linear-gradient(135deg, #1a7838, #27a345);
        }

        .admin-stat-card-modern.warning .admin-stat-card-icon-modern {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }

        .admin-stat-card-modern.success .admin-stat-card-icon-modern {
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .admin-stat-card-content-modern {
            flex: 1;
            z-index: 2;
            position: relative;
        }

        .admin-stat-card-content-modern p {
            font-size: 0.875rem;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin: 0 0 0.5rem 0;
        }

        .admin-stat-card-content-modern h3 {
            font-size: 2.25rem;
            font-weight: 800;
            color: #1e293b;
            margin: 0;
        }

        .admin-stat-card-decoration {
            position: absolute;
            right: -20px;
            bottom: -20px;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            z-index: 0;
        }

        /* Pagination Styles */
        .pagination {
            margin: 0;
            gap: 0.25rem;
            flex-wrap: wrap;
        }

        .pagination .page-link {
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
            border-radius: 6px;
            border: 1px solid #e0e0e0;
            color: #667eea;
            transition: all 0.2s;
            min-width: 40px;
            text-align: center;
        }

        .pagination .page-link:hover {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }

        .pagination .page-item.active .page-link {
            background: #667eea;
            border-color: #667eea;
            color: white;
        }

        .pagination .page-item.disabled .page-link {
            color: #cbd5e0;
            background: #f8f9fa;
        }

        .pagination svg {
            width: 14px !important;
            height: 14px !important;
            vertical-align: middle;
        }

        .pagination .page-link svg,
        .pagination .page-item svg {
            width: 14px !important;
            height: 14px !important;
            max-width: 14px !important;
            max-height: 14px !important;
        }

        /* Modern Suggestion Cards */
        .admin-suggestion-card-modern {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #f1f5f9;
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .admin-suggestion-card-modern:hover {
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
            transform: translateY(-4px);
        }

        .admin-suggestion-image-wrapper {
            position: relative;
            width: 100%;
            height: 200px;
            overflow: hidden;
            background: #f1f5f9;
        }

        .admin-suggestion-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .admin-suggestion-card-modern:hover .admin-suggestion-image {
            transform: scale(1.05);
        }

        .admin-suggestion-no-image {
            position: relative;
            width: 100%;
            height: 200px;
            background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .admin-suggestion-no-image-icon {
            font-size: 4rem;
            color: #cbd5e1;
        }

        .admin-suggestion-overlay {
            position: absolute;
            top: 1rem;
            left: 1rem;
            right: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .admin-suggestion-status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .admin-suggestion-status-badge.approved {
            background: #22c55e;
            color: white;
        }

        .admin-suggestion-status-badge.pending {
            background: #f59e0b;
            color: white;
        }

        .admin-suggestion-status-badge.rejected {
            background: #dc2626;
            color: white;
        }

        .admin-suggestion-card-body {
            padding: 1.5rem;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .admin-suggestion-name {
            font-size: 1.125rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0 0 0.75rem 0;
            line-height: 1.4;
        }

        .admin-suggestion-location {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            color: #64748b;
            margin-bottom: 1rem;
        }

        .admin-suggestion-location i {
            font-size: 1.125rem;
            color: #1a7838;
        }

        .admin-suggestion-description {
            font-size: 0.875rem;
            color: #475569;
            line-height: 1.6;
            margin: 0 0 1rem 0;
        }

        .admin-suggestion-meta {
            padding: 1rem 0;
            border-top: 1px solid #f1f5f9;
            margin-top: auto;
            margin-bottom: 1rem;
        }

        .admin-suggestion-user {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .admin-suggestion-user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1a7838, #27a345);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.875rem;
            flex-shrink: 0;
        }

        .admin-suggestion-user-info {
            display: flex;
            flex-direction: column;
            gap: 0.125rem;
        }

        .admin-suggestion-user-name {
            font-size: 0.875rem;
            font-weight: 600;
            color: #1e293b;
        }

        .admin-suggestion-time {
            font-size: 0.75rem;
            color: #94a3b8;
        }

        .admin-suggestion-actions {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .admin-suggestion-action-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.875rem;
            text-decoration: none;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
        }

        .admin-suggestion-action-btn.view {
            background: linear-gradient(135deg, #1a7838, #27a345);
            color: white;
            box-shadow: 0 2px 8px rgba(26, 120, 56, 0.25);
        }

        .admin-suggestion-action-btn.view:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(26, 120, 56, 0.35);
        }

        .admin-suggestion-action-btn.convert {
            background: #d1fae5;
            color: #059669;
        }

        .admin-suggestion-action-btn.convert:hover {
            background: #059669;
            color: white;
            transform: translateY(-2px);
        }

        /* Empty State */
        .admin-empty-state {
            text-align: center;
            padding: 5rem 2rem;
            background: white;
            border-radius: 16px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #f1f5f9;
        }

        .admin-empty-icon {
            width: 100px;
            height: 100px;
            margin: 0 auto 2rem;
            border-radius: 50%;
            background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: #94a3b8;
        }

        .admin-empty-state h5 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.75rem;
        }

        .admin-empty-state p {
            font-size: 1rem;
            color: #64748b;
            margin: 0;
        }

        /* Pagination */
        .admin-pagination-wrapper {
            display: flex;
            justify-content: center;
            padding: 1rem;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .admin-filter-grid {
                grid-template-columns: 1fr;
            }

            .admin-filter-actions {
                justify-content: stretch;
            }

            .admin-filter-btn.primary {
                flex: 1;
            }
        }

        @media (max-width: 768px) {
            .admin-page-header {
                padding: 1.5rem;
            }

            .admin-suggestion-image-wrapper,
            .admin-suggestion-no-image {
                height: 180px;
            }
        }
    </style>
@endpush
