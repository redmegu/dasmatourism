@extends('layouts.admin')

@section('page-title', 'Manage Promotions')

@section('content')
    <!-- Enhanced Page Header -->
    <div class="admin-page-header mb-4">
        <div class="admin-page-header-content">
            <div>
                <h2 class="admin-page-title">Promotions & Offers</h2>
                <p class="admin-page-subtitle">Manage promotional campaigns and special offers</p>
            </div>
            <div class="admin-header-actions">
                <a href="{{ route('admin.promotions.create') }}" class="admin-header-action-btn primary">
                    <i class='bx bx-plus'></i>
                    <span>Create Promotion</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Enhanced Filters -->
    <div class="admin-filter-card mb-4">
        <form method="GET" action="{{ route('admin.promotions.index') }}">
            <div class="admin-filter-grid">
                <div class="admin-filter-item">
                    <label class="admin-filter-label">
                        <i class='bx bx-category'></i> Type
                    </label>
                    <select name="type" class="admin-filter-select">
                        <option value="">All Types</option>
                        <option value="attraction" {{ request('type') === 'attraction' ? 'selected' : '' }}>Attractions
                        </option>
                        <option value="business" {{ request('type') === 'business' ? 'selected' : '' }}>Businesses</option>
                    </select>
                </div>

                <div class="admin-filter-item">
                    <label class="admin-filter-label">
                        <i class='bx bx-check-shield'></i> Status
                    </label>
                    <select name="status" class="admin-filter-select">
                        <option value="">All Statuses</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div class="admin-filter-actions">
                    <button type="submit" class="admin-filter-btn primary">
                        <i class='bx bx-search'></i>
                        <span>Apply Filters</span>
                    </button>
                    <a href="{{ route('admin.promotions.index') }}" class="admin-filter-btn secondary">
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
                    <i class='bx bxs-megaphone'></i> <!-- Changed icon -->
                </div>
                <div class="admin-stat-card-content-modern">
                    <p>Total Promotions</p>
                    <h3>{{ $promotions->total() }}</h3>
                </div>
                <div class="admin-stat-card-decoration"></div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="admin-stat-card-modern success">
                <div class="admin-stat-card-icon-modern">
                    <i class='bx bxs-check-circle'></i> <!-- Changed to solid -->
                </div>
                <div class="admin-stat-card-content-modern">
                    <p>Active Now</p>
                    <h3>{{ \App\Models\Promotion::active()->count() }}</h3>
                </div>
                <div class="admin-stat-card-decoration"></div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="admin-stat-card-modern warning">
                <div class="admin-stat-card-icon-modern">
                    <i class='bx bxs-time-five'></i> <!-- Changed to solid -->
                </div>
                <div class="admin-stat-card-content-modern">
                    <p>Ending Soon</p>
                    <h3>{{ \App\Models\Promotion::where('end_date', '<=', now()->addDays(7))->where('end_date', '>=', now())->count() }}
                    </h3>
                </div>
                <div class="admin-stat-card-decoration"></div>
            </div>
        </div>
    </div>


    <!-- Promotions Grid -->
    <div class="row g-4">
        @forelse($promotions as $promotion)
            <div class="col-md-6 col-lg-4">
                <div class="admin-promotion-card-modern">
                    <!-- Image Section -->
                    @if ($promotion->image)
                        <div class="admin-promotion-image-wrapper">
                            <img src="{{ asset('storage/' . $promotion->image) }}" alt="{{ $promotion->title }}"
                                class="admin-promotion-image">
                            <div class="admin-promotion-overlay">
                                <div class="admin-promotion-badge-group">
                                    <span class="admin-promo-badge {{ $promotion->isActive() ? 'active' : 'inactive' }}">
                                        <i class='bx {{ $promotion->isActive() ? 'bx-check-circle' : 'bx-x-circle' }}'></i>
                                        {{ $promotion->isActive() ? 'Active' : 'Inactive' }}
                                    </span>
                                    <span class="admin-promo-badge type">
                                        <i
                                            class='bx {{ class_basename($promotion->promotable_type) === 'Attraction' ? 'bx-map-pin' : 'bx-store' }}'></i>
                                        {{ class_basename($promotion->promotable_type) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="admin-promotion-image-wrapper no-image">
                            <div class="admin-promotion-placeholder">
                                <i class='bx bx-megaphone'></i>
                            </div>
                            <div class="admin-promotion-overlay">
                                <div class="admin-promotion-badge-group">
                                    <span class="admin-promo-badge {{ $promotion->isActive() ? 'active' : 'inactive' }}">
                                        <i class='bx {{ $promotion->isActive() ? 'bx-check-circle' : 'bx-x-circle' }}'></i>
                                        {{ $promotion->isActive() ? 'Active' : 'Inactive' }}
                                    </span>
                                    <span class="admin-promo-badge type">
                                        <i
                                            class='bx {{ class_basename($promotion->promotable_type) === 'Attraction' ? 'bx-map-pin' : 'bx-store' }}'></i>
                                        {{ class_basename($promotion->promotable_type) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Card Body -->
                    <div class="admin-promotion-card-body">
                        <h6 class="admin-promotion-card-title">{{ $promotion->title }}</h6>
                        <p class="admin-promotion-card-desc">{{ Str::limit($promotion->description, 80) }}</p>

                        <!-- Info Section -->
                        <div class="admin-promotion-info-section">
                            <div class="admin-promotion-info-item">
                                <i class='bx bx-calendar'></i>
                                <span>{{ $promotion->start_date->format('M d') }} -
                                    {{ $promotion->end_date->format('M d, Y') }}</span>
                            </div>

                            <div class="admin-promotion-info-item">
                                <i
                                    class='bx {{ class_basename($promotion->promotable_type) === 'Attraction' ? 'bx-map-pin' : 'bx-store' }}'></i>
                                <span>{{ Str::limit($promotion->promotable->name ?? 'N/A', 30) }}</span>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="admin-promotion-card-actions">
                            <a href="{{ route('admin.promotions.show', $promotion) }}" class="admin-promo-action-btn view"
                                title="View Details">
                                <i class='bx bx-show'></i>
                            </a>
                            <a href="{{ route('admin.promotions.edit', $promotion) }}" class="admin-promo-action-btn edit"
                                title="Edit">
                                <i class='bx bx-edit'></i>
                            </a>
                            <button type="button" class="admin-promo-action-btn delete"
                                onclick="deletePromotion({{ $promotion->id }})" title="Delete">
                                <i class='bx bx-trash'></i>
                            </button>
                            <form id="delete-form-{{ $promotion->id }}"
                                action="{{ route('admin.promotions.destroy', $promotion) }}" method="POST"
                                class="d-none">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="admin-empty-state">
                    <div class="admin-empty-icon">
                        <i class='bx bx-megaphone'></i>
                    </div>
                    <h5>No Promotions Found</h5>
                    <p>Start promoting your attractions and businesses</p>
                    <a href="{{ route('admin.promotions.create') }}" class="admin-empty-action-btn">
                        <i class='bx bx-plus'></i>
                        Create First Promotion
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if ($promotions->hasPages())
        <div class="admin-pagination-wrapper mt-4">
            {{ $promotions->links('vendor.pagination.admin') }}
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
            flex-wrap: wrap;
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

        .admin-header-actions {
            display: flex;
            gap: 0.75rem;
        }

        .admin-header-action-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.875rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.9375rem;
            text-decoration: none;
            transition: all 0.3s ease;
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
            grid-template-columns: 1fr 1fr auto;
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

        /* Modern Stat Cards - FIXED */
        .admin-stat-card-modern {
            position: relative;
            padding: 1.75rem;
            border-radius: 16px;
            overflow: visible;
            /* Changed from hidden */
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

        .admin-stat-card-modern.success {
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
            border-color: rgba(16, 185, 129, 0.2);
        }

        .admin-stat-card-modern.warning {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            border-color: rgba(245, 158, 11, 0.2);
        }

        .admin-stat-card-modern:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
        }

        .admin-stat-card-icon-modern {
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
            /* Ensure icon is on top */
        }

        .admin-stat-card-modern.primary .admin-stat-card-icon-modern {
            background: linear-gradient(135deg, #1a7838, #27a345);
        }

        .admin-stat-card-modern.success .admin-stat-card-icon-modern {
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .admin-stat-card-modern.warning .admin-stat-card-icon-modern {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }

        .admin-stat-card-content-modern {
            flex: 1;
            z-index: 2;
            /* Ensure content is on top */
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
            /* Ensure decoration is behind */
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

        /* Modern Promotion Cards */
        .admin-promotion-card-modern {
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

        .admin-promotion-card-modern:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.12);
        }

        .admin-promotion-image-wrapper {
            position: relative;
            width: 100%;
            height: 220px;
            overflow: hidden;
        }

        .admin-promotion-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .admin-promotion-card-modern:hover .admin-promotion-image {
            transform: scale(1.05);
        }

        .admin-promotion-image-wrapper.no-image {
            background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
        }

        .admin-promotion-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 4rem;
            color: #cbd5e1;
        }

        .admin-promotion-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            padding: 1.25rem;
        }

        .admin-promotion-badge-group {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .admin-promo-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .admin-promo-badge.active {
            background: rgba(16, 185, 129, 0.9);
            color: white;
        }

        .admin-promo-badge.inactive {
            background: rgba(100, 116, 139, 0.9);
            color: white;
        }

        .admin-promo-badge.type {
            background: rgba(0, 168, 232, 0.9);
            color: white;
        }

        .admin-promotion-card-body {
            padding: 1.5rem;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .admin-promotion-card-title {
            font-size: 1.125rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0 0 0.75rem 0;
            line-height: 1.4;
        }

        .admin-promotion-card-desc {
            font-size: 0.875rem;
            color: #64748b;
            line-height: 1.6;
            margin: 0 0 1.25rem 0;
            flex: 1;
        }

        .admin-promotion-info-section {
            padding: 1rem 0;
            border-top: 1px solid #f1f5f9;
            border-bottom: 1px solid #f1f5f9;
            margin-bottom: 1.25rem;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .admin-promotion-info-item {
            display: flex;
            align-items: center;
            gap: 0.625rem;
            font-size: 0.875rem;
            color: #475569;
        }

        .admin-promotion-info-item i {
            font-size: 1.125rem;
            color: #1a7838;
            flex-shrink: 0;
        }

        .admin-promotion-card-actions {
            display: flex;
            gap: 0.5rem;
        }

        .admin-promo-action-btn {
            flex: 1;
            height: 40px;
            border-radius: 8px;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.125rem;
            cursor: pointer;
            transition: all 0.25s ease;
            text-decoration: none;
        }

        .admin-promo-action-btn.view {
            background: #dbeafe;
            color: #0284c7;
        }

        .admin-promo-action-btn.view:hover {
            background: #0284c7;
            color: white;
            transform: translateY(-2px);
        }

        .admin-promo-action-btn.edit {
            background: #f1f5f9;
            color: #64748b;
        }

        .admin-promo-action-btn.edit:hover {
            background: #64748b;
            color: white;
            transform: translateY(-2px);
        }

        .admin-promo-action-btn.delete {
            background: #fee2e2;
            color: #dc2626;
        }

        .admin-promo-action-btn.delete:hover {
            background: #dc2626;
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
            margin-bottom: 2rem;
        }

        .admin-empty-action-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.875rem 1.75rem;
            background: linear-gradient(135deg, #1a7838, #27a345);
            color: white;
            border-radius: 10px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(26, 120, 56, 0.25);
        }

        .admin-empty-action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(26, 120, 56, 0.35);
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
            .admin-page-header-content {
                flex-direction: column;
                align-items: stretch;
            }

            .admin-header-action-btn {
                width: 100%;
                justify-content: center;
            }

            .admin-promotion-image-wrapper {
                height: 200px;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        function deletePromotion(id) {
            if (confirm('Are you sure you want to delete this promotion?\n\nThis action cannot be undone.')) {
                document.getElementById('delete-form-' + id).submit();
            }
        }
    </script>
@endpush
