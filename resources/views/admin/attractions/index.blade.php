@extends('layouts.admin')

@section('page-title', 'Manage Attractions')

@section('content')
    <!-- Enhanced Header -->
    <div class="admin-page-header mb-4">
        <div class="admin-page-header-content">
            <div>
                <h2 class="admin-page-title">Attractions Management</h2>
                <p class="admin-page-subtitle">Manage tourist destinations and landmarks across Dasmariñas</p>
            </div>
            <a href="{{ route('admin.attractions.create') }}" class="admin-btn-primary-enhanced">
                <i class='bx bx-plus-circle'></i>
                <span>Add New Attraction</span>
            </a>
        </div>
    </div>

    <!-- Enhanced Stats Grid -->
    <div class="admin-stats-grid mb-4">
        <div class="admin-stat-card-mini primary">
            <div class="admin-stat-card-mini-icon">
                <i class='bx bx-map-pin'></i>
            </div>
            <div class="admin-stat-card-mini-content">
                <h4>{{ $attractions->total() }}</h4>
                <p>Total Attractions</p>
            </div>
            <div class="admin-stat-card-mini-decoration"></div>
        </div>

        <div class="admin-stat-card-mini success">
            <div class="admin-stat-card-mini-icon">
                <i class='bx bx-check-circle'></i>
            </div>
            <div class="admin-stat-card-mini-content">
                <h4>{{ $attractions->where('status', 'approved')->count() }}</h4>
                <p>Approved</p>
            </div>
            <div class="admin-stat-card-mini-decoration"></div>
        </div>

        <div class="admin-stat-card-mini warning">
            <div class="admin-stat-card-mini-icon">
                <i class='bx bx-time'></i>
            </div>
            <div class="admin-stat-card-mini-content">
                <h4>{{ $attractions->where('status', 'pending')->count() }}</h4>
                <p>Pending Review</p>
            </div>
            <div class="admin-stat-card-mini-decoration"></div>
        </div>

        <div class="admin-stat-card-mini info">
            <div class="admin-stat-card-mini-icon">
                <i class='bx bx-star'></i>
            </div>
            <div class="admin-stat-card-mini-content">
                <h4>{{ $attractions->where('is_featured', true)->count() }}</h4>
                <p>Featured</p>
            </div>
            <div class="admin-stat-card-mini-decoration"></div>
        </div>
    </div>

    <!-- Enhanced Filter Card -->
    <div class="admin-filter-card mb-4">
        <div class="admin-filter-header">
            <i class='bx bx-filter-alt'></i>
            <h6>Filter Attractions</h6>
        </div>
        <form method="GET" action="{{ route('admin.attractions.index') }}">
            <div class="admin-filter-grid">
                <div class="admin-filter-item">
                    <label class="admin-filter-label">
                        <i class='bx bx-search'></i> Search
                    </label>
                    <input type="text" name="search" class="admin-filter-input" placeholder="Search attractions..."
                        value="{{ request('search') }}">
                </div>

                <div class="admin-filter-item">
                    <label class="admin-filter-label">
                        <i class='bx bx-check-shield'></i> Status
                    </label>
                    <select name="status" class="admin-filter-select">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
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
                        <i class='bx bx-filter-alt'></i> Apply
                    </button>
                    <a href="{{ route('admin.attractions.index') }}" class="admin-filter-btn secondary">
                        <i class='bx bx-reset'></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Enhanced Table Card -->
    <div class="admin-table-card">
        <div class="admin-table-card-header">
            <div>
                <h5 class="admin-table-card-title">All Attractions</h5>
                <p class="admin-table-card-subtitle">{{ $attractions->total() }} attractions found</p>
            </div>
            @if (request()->hasAny(['search', 'status', 'category']))
                <div class="admin-active-filters">
                    <span class="admin-active-filters-label">Active Filters:</span>
                    @if (request('search'))
                        <span class="admin-filter-tag">
                            Search: "{{ request('search') }}"
                            <a
                                href="{{ route('admin.attractions.index', array_merge(request()->except('search'))) }}">×</a>
                        </span>
                    @endif
                    @if (request('status'))
                        <span class="admin-filter-tag">
                            Status: {{ ucfirst(request('status')) }}
                            <a href="{{ route('admin.attractions.index', request()->except('status')) }}">×</a>
                        </span>
                    @endif
                    @if (request('category'))
                        <span class="admin-filter-tag">
                            Category: {{ $categories->find(request('category'))->name ?? '' }}
                            <a href="{{ route('admin.attractions.index', request()->except('category')) }}">×</a>
                        </span>
                    @endif>
                </div>
            @endif
        </div>

        <div class="admin-table-wrapper">
            <table class="admin-table-enhanced">
                <thead>
                    <tr>
                        <th style="width: 80px;">Image</th>
                        <th>Attraction Details</th>
                        <th style="width: 140px;">Category</th>
                        <th style="width: 120px;">Status</th>
                        <th style="width: 100px;" class="text-center">Stats</th>
                        <th style="width: 120px;">Date</th>
                        <th style="width: 150px;" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attractions as $attraction)
                        <tr class="admin-table-row">
                            <td>
                                <div class="admin-thumbnail-wrapper">
                                    @if ($attraction->primaryImage)
                                        <img src="{{ asset('storage/' . $attraction->primaryImage->image_path) }}"
                                            alt="{{ $attraction->name }}" class="admin-thumbnail">
                                    @else
                                        <div class="admin-thumbnail-placeholder">
                                            <i class='bx bx-image'></i>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="admin-attraction-details">
                                    <h6 class="admin-attraction-name">{{ $attraction->name }}</h6>
                                    <p class="admin-attraction-address">
                                        <i class='bx bx-map-pin'></i>
                                        {{ Str::limit($attraction->address, 45) }}
                                    </p>
                                    <div class="admin-attraction-badges">
                                        @if ($attraction->is_historical_site)
                                            <span class="admin-badge-tiny historical">
                                                <i class='bx bx-landmark'></i> Historical
                                            </span>
                                        @endif
                                        @if ($attraction->is_featured)
                                            <span class="admin-badge-tiny featured">
                                                <i class='bx bx-star'></i> Featured
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="admin-category-badge">
                                    {{ $attraction->category->name }}
                                </span>
                            </td>
                            <td>
                                <div class="admin-status-badge-enhanced {{ $attraction->status }}">
                                    @if ($attraction->status === 'approved')
                                        <i class='bx bx-check-circle'></i>
                                    @elseif($attraction->status === 'pending')
                                        <i class='bx bx-time'></i>
                                    @else
                                        <i class='bx bx-x-circle'></i>
                                    @endif
                                    <span>{{ ucfirst($attraction->status) }}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="admin-stats-column">
                                    <div class="admin-stat-item-tiny">
                                        <i class='bx bx-show'></i>
                                        <span>{{ number_format($attraction->views) }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="admin-date-column">
                                    <span
                                        class="admin-date-primary">{{ $attraction->created_at->format('M d, Y') }}</span>
                                    <span
                                        class="admin-date-secondary">{{ $attraction->created_at->diffForHumans() }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="admin-action-buttons">
                                    <a href="{{ route('admin.attractions.show', $attraction) }}"
                                        class="admin-action-btn view" title="View Details">
                                        <i class='bx bx-show'></i>
                                    </a>
                                    <a href="{{ route('admin.attractions.edit', $attraction) }}"
                                        class="admin-action-btn edit" title="Edit">
                                        <i class='bx bx-edit'></i>
                                    </a>
                                    <button type="button" class="admin-action-btn delete" title="Delete"
                                        onclick="confirmDelete('delete-form-{{ $attraction->id }}')">
                                        <i class='bx bx-trash'></i>
                                    </button>
                                </div>

                                <form id="delete-form-{{ $attraction->id }}"
                                    action="{{ route('admin.attractions.destroy', $attraction) }}" method="POST"
                                    class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="admin-empty-state-table">
                                    <div class="admin-empty-icon">
                                        <i class='bx bx-map-pin'></i>
                                    </div>
                                    <h6>No Attractions Found</h6>
                                    <p>{{ request()->hasAny(['search', 'status', 'category']) ? 'Try adjusting your filters' : 'Get started by adding your first attraction' }}
                                    </p>
                                    @if (request()->hasAny(['search', 'status', 'category']))
                                        <a href="{{ route('admin.attractions.index') }}" class="admin-empty-btn">
                                            <i class='bx bx-reset'></i> Clear All Filters
                                        </a>
                                    @else
                                        <a href="{{ route('admin.attractions.create') }}" class="admin-empty-btn">
                                            <i class='bx bx-plus-circle'></i> Add New Attraction
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($attractions->hasPages())
            <div class="admin-table-pagination">
                {{ $attractions->links('vendor.pagination.admin') }}
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

        .admin-btn-primary-enhanced {
            display: inline-flex;
            align-items: center;
            gap: 0.625rem;
            padding: 0.875rem 1.75rem;
            background: linear-gradient(135deg, #1a7838, #27a345);
            color: white;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9375rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(26, 120, 56, 0.2);
            white-space: nowrap;
        }

        .admin-btn-primary-enhanced:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(26, 120, 56, 0.3);
            color: white;
        }

        .admin-btn-primary-enhanced i {
            font-size: 1.25rem;
        }

        /* Mini Stats Cards */
        .admin-stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.25rem;
        }

        .admin-stat-card-mini {
            background: white;
            border-radius: 14px;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1.25rem;
            position: relative;
            overflow: hidden;
            border: 1px solid #f1f5f9;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .admin-stat-card-mini:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }

        .admin-stat-card-mini-icon {
            width: 56px;
            height: 56px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            color: white;
            flex-shrink: 0;
            z-index: 2;
        }

        .admin-stat-card-mini.primary .admin-stat-card-mini-icon {
            background: linear-gradient(135deg, #1a7838, #27a345);
        }

        .admin-stat-card-mini.success .admin-stat-card-mini-icon {
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .admin-stat-card-mini.warning .admin-stat-card-mini-icon {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }

        .admin-stat-card-mini.info .admin-stat-card-mini-icon {
            background: linear-gradient(135deg, #00A8E8, #0284c7);
        }

        .admin-stat-card-mini-content h4 {
            font-size: 2rem;
            font-weight: 800;
            color: #1e293b;
            margin: 0 0 0.25rem 0;
            line-height: 1;
        }

        .admin-stat-card-mini-content p {
            font-size: 0.875rem;
            color: #64748b;
            margin: 0;
            font-weight: 600;
        }

        .admin-stat-card-mini-decoration {
            position: absolute;
            right: -20px;
            top: -20px;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(26, 120, 56, 0.08), transparent);
        }

        /* Filter Card */
        .admin-filter-card {
            background: white;
            border-radius: 14px;
            padding: 1.75rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #f1f5f9;
        }

        .admin-filter-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f1f5f9;
        }

        .admin-filter-header i {
            font-size: 1.5rem;
            color: #1a7838;
        }

        .admin-filter-header h6 {
            font-size: 1rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }

        .admin-filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.25rem;
            align-items: end;
        }

        .admin-filter-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 0.5rem;
        }

        .admin-filter-label i {
            font-size: 1rem;
            color: #64748b;
        }

        .admin-filter-input,
        .admin-filter-select {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            font-size: 0.9375rem;
            transition: all 0.25s ease;
            background: white;
        }

        .admin-filter-input:focus,
        .admin-filter-select:focus {
            outline: none;
            border-color: #1a7838;
            box-shadow: 0 0 0 3px rgba(26, 120, 56, 0.1);
        }

        .admin-filter-actions {
            display: flex;
            gap: 0.75rem;
        }

        .admin-filter-btn {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem 1.25rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.9375rem;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.25s ease;
        }

        .admin-filter-btn.primary {
            background: linear-gradient(135deg, #1a7838, #27a345);
            color: white;
        }

        .admin-filter-btn.primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(26, 120, 56, 0.3);
        }

        .admin-filter-btn.secondary {
            background: #f1f5f9;
            color: #64748b;
        }

        .admin-filter-btn.secondary:hover {
            background: #e2e8f0;
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
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
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

        .admin-active-filters {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .admin-active-filters-label {
            font-size: 0.875rem;
            font-weight: 600;
            color: #64748b;
        }

        .admin-filter-tag {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 0.875rem;
            background: #f1f5f9;
            border-radius: 8px;
            font-size: 0.8125rem;
            font-weight: 600;
            color: #475569;
        }

        .admin-filter-tag a {
            color: #64748b;
            text-decoration: none;
            font-size: 1.125rem;
            line-height: 1;
            margin-left: 0.25rem;
            transition: color 0.2s ease;
        }

        .admin-filter-tag a:hover {
            color: #dc3545;
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

        /* Enhanced Table */
        .admin-table-wrapper {
            overflow-x: auto;
        }

        .admin-table-enhanced {
            width: 100%;
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

        /* Thumbnail */
        .admin-thumbnail-wrapper {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            overflow: hidden;
        }

        .admin-thumbnail {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .admin-thumbnail-placeholder {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            color: #94a3b8;
        }

        /* Attraction Details */
        .admin-attraction-details {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .admin-attraction-name {
            font-size: 1rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }

        .admin-attraction-address {
            font-size: 0.875rem;
            color: #64748b;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.375rem;
        }

        .admin-attraction-address i {
            color: #94a3b8;
        }

        .admin-attraction-badges {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .admin-badge-tiny {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.25rem 0.625rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        .admin-badge-tiny.historical {
            background: #fef3c7;
            color: #d97706;
        }

        .admin-badge-tiny.featured {
            background: #dbeafe;
            color: #0284c7;
        }

        .admin-badge-tiny i {
            font-size: 0.875rem;
        }

        /* Category Badge */
        .admin-category-badge {
            display: inline-block;
            padding: 0.5rem 1rem;
            background: #f1f5f9;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            color: #475569;
        }

        /* Status Badge Enhanced */
        .admin-status-badge-enhanced {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 700;
            text-transform: capitalize;
        }

        .admin-status-badge-enhanced.approved {
            background: #d1fae5;
            color: #059669;
        }

        .admin-status-badge-enhanced.pending {
            background: #fef3c7;
            color: #d97706;
        }

        .admin-status-badge-enhanced.rejected {
            background: #fee2e2;
            color: #dc2626;
        }

        .admin-status-badge-enhanced i {
            font-size: 1.125rem;
        }

        /* Stats Column */
        .admin-stats-column {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .admin-stat-item-tiny {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.375rem;
            padding: 0.5rem 0.875rem;
            background: #f8fafc;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            color: #475569;
        }

        .admin-stat-item-tiny i {
            color: #64748b;
        }

        /* Date Column */
        .admin-date-column {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .admin-date-primary {
            font-size: 0.9375rem;
            font-weight: 600;
            color: #1e293b;
        }

        .admin-date-secondary {
            font-size: 0.75rem;
            color: #94a3b8;
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

        .admin-action-btn.edit {
            background: #fef3c7;
            color: #d97706;
        }

        .admin-action-btn.edit:hover {
            background: #d97706;
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
            margin-bottom: 1.5rem;
        }

        .admin-empty-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.875rem 1.75rem;
            background: linear-gradient(135deg, #1a7838, #27a345);
            color: white;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .admin-empty-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(26, 120, 56, 0.3);
            color: white;
        }

        /* Pagination */
        .admin-table-pagination {
            padding: 1.5rem;
            border-top: 1px solid #f1f5f9;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .admin-page-header-content {
                flex-direction: column;
                align-items: stretch;
            }

            .admin-btn-primary-enhanced {
                justify-content: center;
                width: 100%;
            }

            .admin-stats-grid {
                grid-template-columns: 1fr;
            }

            .admin-filter-grid {
                grid-template-columns: 1fr;
            }

            .admin-table-card-header {
                flex-direction: column;
                align-items: stretch;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        function confirmDelete(formId) {
            if (confirm('Are you sure you want to delete this attraction? This action cannot be undone.')) {
                document.getElementById(formId).submit();
            }
        }
    </script>
@endpush
