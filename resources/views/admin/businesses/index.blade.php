@extends('layouts.admin')

@section('page-title', 'Manage Businesses')

@section('content')
    <!-- Enhanced Page Header -->
    <div class="admin-page-header mb-4">
        <div class="admin-page-header-content">
            <div>
                <h2 class="admin-page-title">Business Directory</h2>
                <p class="admin-page-subtitle">Manage and moderate business listings</p>
            </div>
            <div class="admin-header-stats">
                <div class="admin-mini-stat">
                    <i class='bx bx-store'></i>
                    <span>{{ $businesses->total() }} Businesses</span>
                </div>
                <a href="{{ route('admin.businesses.create') }}" class="admin-add-btn">
                    <i class='bx bx-plus'></i> Add New Business
                </a>
            </div>
        </div>
    </div>

    <!-- Enhanced Filters -->
    <div class="admin-filter-card mb-4">
        <form method="GET" action="{{ route('admin.businesses.index') }}">
            <div class="admin-filter-grid">
                <div class="admin-filter-item">
                    <label class="admin-filter-label">
                        <i class='bx bx-search'></i> Search
                    </label>
                    <input type="text" name="search" class="admin-filter-input" placeholder="Search businesses..."
                        value="{{ request('search') }}">
                </div>

                <div class="admin-filter-item">
                    <label class="admin-filter-label">
                        <i class='bx bx-check-shield'></i> Status
                    </label>
                    <select name="status" class="admin-filter-select">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>

                <div class="admin-filter-item">
                    <label class="admin-filter-label">
                        <i class='bx bx-category'></i> Category
                    </label>
                    <select name="category" class="admin-filter-select">
                        <option value="">All Categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="admin-filter-actions">
                    <button type="submit" class="admin-filter-btn primary">
                        <i class='bx bx-search'></i>
                        <span>Apply Filters</span>
                    </button>
                    <a href="{{ route('admin.businesses.index') }}" class="admin-filter-btn secondary">
                        <i class='bx bx-reset'></i>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Enhanced Stats -->
    <div class="admin-stats-grid mb-4">
        <div class="admin-stat-card-modern primary">
            <div class="admin-stat-card-icon-modern">
                <i class='bx bx-store'></i>
            </div>
            <div class="admin-stat-card-content-modern">
                <p>Total Businesses</p>
                <h3>{{ $businesses->total() }}</h3>
            </div>
            <div class="admin-stat-card-decoration"></div>
        </div>

        <div class="admin-stat-card-modern success">
            <div class="admin-stat-card-icon-modern">
                <i class='bx bx-check-circle'></i>
            </div>
            <div class="admin-stat-card-content-modern">
                <p>Approved</p>
                <h3>{{ \App\Models\Business::where('status', 'approved')->count() }}</h3>
            </div>
            <div class="admin-stat-card-decoration"></div>
        </div>

        <div class="admin-stat-card-modern warning">
            <div class="admin-stat-card-icon-modern">
                <i class='bx bx-time'></i>
            </div>
            <div class="admin-stat-card-content-modern">
                <p>Pending Review</p>
                <h3>{{ \App\Models\Business::where('status', 'pending')->count() }}</h3>
            </div>
            <div class="admin-stat-card-decoration"></div>
        </div>

        <div class="admin-stat-card-modern info">
            <div class="admin-stat-card-icon-modern">
                <i class='bx bxs-badge-check'></i>
            </div>
            <div class="admin-stat-card-content-modern">
                <p>Verified</p>
                <h3>{{ \App\Models\Business::where('is_verified', true)->count() }}</h3>
            </div>
            <div class="admin-stat-card-decoration"></div>
        </div>
    </div>

    <!-- Businesses Table -->
    <div class="admin-table-card">
        <div class="admin-table-card-header">
            <div>
                <h5 class="admin-table-card-title">All Businesses</h5>
                <p class="admin-table-card-subtitle">View and manage business listings</p>
            </div>
        </div>

        <div class="admin-table-wrapper">
            <table class="admin-table-enhanced">
                <thead>
                    <tr>
                        <th style="min-width: 250px;">Business</th>
                        <th style="min-width: 180px;">Owner</th>
                        <th style="min-width: 120px;">Category</th>
                        <th style="min-width: 100px;">Status</th>
                        <th style="min-width: 80px; text-align: center;">Verified</th>
                        <th style="min-width: 120px;">Rating/Views</th>
                        <th style="min-width: 100px;">Created</th>
                        <th style="min-width: 100px; text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($businesses as $business)
                        <tr class="admin-table-row">
                            <td>
                                <div class="admin-business-cell">
                                    @if ($business->logo)
                                        <div class="admin-business-logo">
                                            <img src="{{ asset('storage/' . $business->logo) }}"
                                                alt="{{ $business->name }}">
                                        </div>
                                    @else
                                        <div class="admin-business-logo-empty">
                                            <i class='bx bx-store'></i>
                                        </div>
                                    @endif
                                    <div class="admin-business-info">
                                        <h6 class="admin-business-name">{{ $business->name }}</h6>
                                        <p class="admin-business-address">
                                            <i class='bx bx-map-pin'></i> {{ Str::limit($business->address, 30) }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="admin-owner-cell">
                                    <strong>{{ $business->owner->name }}</strong>
                                    <p>{{ Str::limit($business->owner->email, 20) }}</p>
                                </div>
                            </td>
                            <td>
                                <div class="admin-category-badge">
                                    <i class='bx bx-category'></i>
                                    <span>{{ $business->category->name }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="admin-status-badge-pill {{ $business->status }}">
                                    @if ($business->status === 'approved')
                                        <i class='bx bx-check-circle'></i>
                                    @elseif ($business->status === 'pending')
                                        <i class='bx bx-time'></i>
                                    @else
                                        <i class='bx bx-x-circle'></i>
                                    @endif
                                    <span>{{ ucfirst($business->status) }}</span>
                                </div>
                            </td>
                            <td style="text-align: center;">
                                @if ($business->is_verified)
                                    <div class="admin-verified-icon">
                                        <i class='bx bxs-badge-check'></i>
                                    </div>
                                @else
                                    <div class="admin-unverified-icon">
                                        <i class='bx bx-badge'></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="admin-metrics-combined">
                                    <div class="admin-rating-badge">
                                        <i class='bx bxs-star'></i>
                                        <span>{{ number_format($business->getAverageRating(), 1) }}</span>
                                    </div>
                                    <div class="admin-metric-badge-small">
                                        <i class='bx bx-show'></i>
                                        <span>{{ number_format($business->views) }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="admin-date-cell">
                                    <span>{{ $business->created_at->format('M d, Y') }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="admin-action-buttons">
                                    <a href="{{ route('admin.businesses.show', $business) }}"
                                        class="admin-action-btn view" title="View Details">
                                        <i class='bx bx-show'></i>
                                    </a>
                                    <button type="button" class="admin-action-btn delete"
                                        onclick="confirmDelete({{ $business->id }})" title="Delete">
                                        <i class='bx bx-trash'></i>
                                    </button>
                                </div>

                                <form id="delete-form-{{ $business->id }}"
                                    action="{{ route('admin.businesses.destroy', $business) }}" method="POST"
                                    class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="admin-empty-state-table">
                                    <div class="admin-empty-icon">
                                        <i class='bx bx-store'></i>
                                    </div>
                                    <h6>No Businesses Found</h6>
                                    <p>No business listings match your current filters</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($businesses->hasPages())
            <div class="admin-table-pagination">
                {{ $businesses->links('vendor.pagination.admin') }}
            </div>
        @endif
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
            min-width: 0;
            overflow: hidden;
        }

        .admin-page-header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1.25rem;
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

        .admin-header-stats {
            display: flex;
            gap: 1rem;
        }

        .admin-mini-stat {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.875rem 1.5rem;
            background: linear-gradient(135deg, #1a7838, #27a345);
            color: white;
            border-radius: 12px;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(26, 120, 56, 0.2);
        }


        .admin-add-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            background: white;
            color: #1a7838;
            border: 2px solid #1a7838;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.9375rem;
            text-decoration: none;
            transition: all 0.25s ease;
            box-shadow: 0 2px 8px rgba(26,120,56,0.1);
        }

        .admin-add-btn:hover {
            background: #1a7838;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(26,120,56,0.3);
        }

        .admin-add-btn i {
            font-size: 1.25rem;
        }

        .admin-mini-stat i {
            font-size: 1.5rem;
        }

        /* Filter Card */
        .admin-filter-card {
            background: white;
            border-radius: 14px;
            padding: 1.75rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #f1f5f9;
            min-width: 0;
            overflow: hidden;
        }

        .admin-filter-grid {
            display: grid;
            grid-template-columns: minmax(0, 2fr) minmax(0, 1fr) minmax(0, 1fr) auto;
            gap: 1.25rem;
            align-items: end;
            width: 100%;
            min-width: 0;
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

        .admin-filter-label i {
            font-size: 1rem;
            color: #64748b;
        }

        .admin-filter-input,
        .admin-filter-select {
            padding: 0.75rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 0.9375rem;
            transition: all 0.25s ease;
        }

        .admin-filter-input:focus,
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

        .admin-filter-btn i {
            font-size: 1.125rem;
        }

        /* Stats Grid */
        .admin-stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.25rem;
            width: 100%;
        }

        /* Modern Stats Cards */
        .admin-stat-card-modern {
            position: relative;
            padding: 1.75rem;
            border-radius: 16px;
            overflow: hidden;
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

        .admin-stat-card-modern.info {
            background: linear-gradient(135deg, #dbeafe, #bfdbfe);
            border-color: rgba(0, 168, 232, 0.2);
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

        .admin-stat-card-modern.info .admin-stat-card-icon-modern {
            background: linear-gradient(135deg, #00A8E8, #0284c7);
        }

        .admin-stat-card-content-modern {
            flex: 1;
            z-index: 1;
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

        /* Table Card */
        .admin-table-card {
            background: white;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #f1f5f9;
        }

        .admin-table-card-header {
            padding: 1.75rem;
            border-bottom: 1px solid #f1f5f9;
        }

        .admin-table-card-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0 0 0.25rem 0;
        }

        .admin-table-card-subtitle {
            font-size: 0.875rem;
            color: #64748b;
            margin: 0;
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

        /* Enhanced Table - CRITICAL SCROLLING FIX */
        .admin-table-wrapper {
            overflow-x: auto;
            overflow-y: visible;
            -webkit-overflow-scrolling: touch;
            max-width: 100%;
            width: 100%;
        }

        .admin-table-card {
            min-width: 0;
            overflow: hidden;
        }

        .admin-table-enhanced {
            width: 100%;
            min-width: 950px;
            /* Ensures horizontal scroll when needed */
            border-collapse: separate;
            border-spacing: 0;
        }

        .admin-table-enhanced thead tr {
            background: #f8fafc;
        }

        .admin-table-enhanced th {
            padding: 1.125rem 1.25rem;
            text-align: left;
            font-size: 0.8125rem;
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 2px solid #e2e8f0;
            white-space: nowrap;
        }

        .admin-table-row {
            transition: all 0.2s ease;
        }

        .admin-table-row:hover {
            background: #f8fafc;
        }

        .admin-table-row td {
            padding: 1.25rem;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        /* Business Cell */
        .admin-business-cell {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .admin-business-logo,
        .admin-business-logo-empty {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            flex-shrink: 0;
        }

        .admin-business-logo {
            overflow: hidden;
            border: 2px solid #e2e8f0;
        }

        .admin-business-logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .admin-business-logo-empty {
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: #94a3b8;
        }

        .admin-business-info h6 {
            font-size: 0.875rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0 0 0.25rem 0;
        }

        .admin-business-address {
            font-size: 0.75rem;
            color: #64748b;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.375rem;
        }

        /* Owner Cell */
        .admin-owner-cell strong {
            display: block;
            font-size: 0.875rem;
            color: #1e293b;
            margin-bottom: 0.25rem;
        }

        .admin-owner-cell p {
            font-size: 0.75rem;
            color: #64748b;
            margin: 0;
        }

        /* Category Badge */
        .admin-category-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.375rem 0.75rem;
            background: #f1f5f9;
            border-radius: 6px;
            font-size: 0.8125rem;
            font-weight: 600;
            color: #475569;
            white-space: nowrap;
        }

        .admin-category-badge i {
            font-size: 0.875rem;
        }

        /* Status Badge Pill */
        .admin-status-badge-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.375rem 0.75rem;
            border-radius: 6px;
            font-size: 0.8125rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .admin-status-badge-pill.approved {
            background: #d1fae5;
            color: #059669;
        }

        .admin-status-badge-pill.pending {
            background: #fef3c7;
            color: #d97706;
        }

        .admin-status-badge-pill.rejected {
            background: #fee2e2;
            color: #dc2626;
        }

        .admin-status-badge-pill i {
            font-size: 1rem;
        }

        /* Verified Icons */
        .admin-verified-icon,
        .admin-unverified-icon {
            display: inline-flex;
            justify-content: center;
            font-size: 1.5rem;
        }

        .admin-verified-icon {
            color: #10b981;
        }

        .admin-unverified-icon {
            color: #cbd5e1;
        }

        /* Combined Metrics */
        .admin-metrics-combined {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .admin-metric-badge-small {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.375rem 0.625rem;
            background: #f1f5f9;
            border-radius: 6px;
            font-size: 0.8125rem;
            font-weight: 600;
            color: #475569;
            white-space: nowrap;
        }

        .admin-metric-badge-small i {
            font-size: 1rem;
            color: #64748b;
        }

        /* Rating Badge */
        .admin-rating-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.375rem 0.625rem;
            background: #fef3c7;
            border-radius: 6px;
            font-size: 0.8125rem;
            font-weight: 700;
            color: #d97706;
            white-space: nowrap;
        }

        .admin-rating-badge i {
            font-size: 1rem;
        }

        /* Date Cell */
        .admin-date-cell {
            font-size: 0.8125rem;
            color: #64748b;
            font-weight: 600;
            white-space: nowrap;
        }

        /* Action Buttons */
        .admin-action-buttons {
            display: flex;
            justify-content: flex-end;
            gap: 0.5rem;
        }

        .admin-action-btn {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.125rem;
            cursor: pointer;
            transition: all 0.25s ease;
            text-decoration: none;
            flex-shrink: 0;
        }

        .admin-action-btn.view {
            background: #dbeafe;
            color: #0284c7;
        }

        .admin-action-btn.view:hover {
            background: #0284c7;
            color: white;
            transform: translateY(-2px);
        }

        .admin-action-btn.delete {
            background: #fee2e2;
            color: #dc2626;
        }

        .admin-action-btn.delete:hover {
            background: #dc2626;
            color: white;
            transform: translateY(-2px);
        }

        /* Empty State */
        .admin-empty-state-table {
            text-align: center;
            padding: 4rem 2rem;
        }

        .admin-empty-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 1.5rem;
            border-radius: 50%;
            background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            color: #94a3b8;
        }

        .admin-empty-state-table h6 {
            font-size: 1.125rem;
            font-weight: 700;
            color: #475569;
            margin-bottom: 0.5rem;
        }

        .admin-empty-state-table p {
            color: #94a3b8;
        }

        /* Pagination */
        .admin-table-pagination {
            padding: 1.5rem;
            border-top: 1px solid #f1f5f9;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .admin-stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .admin-page-header-content {
                flex-direction: column;
                align-items: stretch;
            }

            .admin-header-stats {
                flex-wrap: wrap;
            }

            .admin-stats-grid {
                grid-template-columns: 1fr;
            }

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
    </style>
@endpush


@push('scripts')
    <script>
        function confirmDelete(id) {
            if (confirm(
                    'Are you sure you want to delete this business? This action cannot be undone and will remove all associated data.'
                )) {
                document.getElementById('delete-form-' + id).submit();
            }
        }
    </script>
@endpush
