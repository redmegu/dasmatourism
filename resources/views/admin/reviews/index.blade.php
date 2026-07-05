@extends('layouts.admin')

@section('page-title', 'Manage Reviews')

@section('content')
    <!-- Enhanced Page Header -->
    <div class="admin-page-header mb-4">
        <div class="admin-page-header-content">
            <div>
                <h2 class="admin-page-title">Manage Reviews</h2>
                <p class="admin-page-subtitle">Review and moderate user feedback</p>
            </div>
            <div class="view-toggle-buttons">
                <button type="button" class="view-toggle-btn active" data-view="list" onclick="switchView('list')">
                    <i class='bx bx-list-ul'></i>
                    <span>List</span>
                </button>
                <button type="button" class="view-toggle-btn" data-view="grid" onclick="switchView('grid')">
                    <i class='bx bx-grid-alt'></i>
                    <span>Grid</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Enhanced Filters -->
    <div class="admin-filter-card mb-4">
        <form method="GET" action="{{ route('admin.reviews.index') }}" id="filterForm">
            <div class="admin-filter-grid-enhanced">
                <div class="admin-filter-item">
                    <label class="admin-filter-label">
                        <i class='bx bx-search-alt'></i> Search
                    </label>
                    <input type="text" name="search" class="admin-filter-input"
                        placeholder="Search by user, place name, or comment..." value="{{ request('search') }}">
                </div>

                <div class="admin-filter-item">
                    <label class="admin-filter-label">
                        <i class='bx bx-filter'></i> Status
                    </label>
                    <select name="status" class="admin-filter-select" onchange="this.form.submit()">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending Only
                        </option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved Only
                        </option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected Only
                        </option>
                    </select>
                </div>

                <div class="admin-filter-item">
                    <label class="admin-filter-label">
                        <i class='bx bx-category'></i> Type
                    </label>
                    <select name="type" class="admin-filter-select" onchange="this.form.submit()">
                        <option value="">All Types</option>
                        <option value="attraction" {{ request('type') === 'attraction' ? 'selected' : '' }}>Attractions
                        </option>
                        <option value="business" {{ request('type') === 'business' ? 'selected' : '' }}>Businesses</option>
                    </select>
                </div>

                <div class="admin-filter-actions">
                    <button type="submit" class="admin-filter-btn primary">
                        <i class='bx bx-search'></i>
                        <span>Search</span>
                    </button>
                    <a href="{{ route('admin.reviews.index') }}" class="admin-filter-btn secondary">
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
                    <i class='bx bxs-message-square-detail'></i>
                </div>
                <div class="admin-stat-card-content-modern">
                    <p>Total Reviews</p>
                    <h3>{{ $reviews->total() }}</h3>
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
                    <p>Pending</p>
                    <h3>{{ \App\Models\Review::where('status', 'pending')->count() }}</h3>
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
                    <h3>{{ \App\Models\Review::where('status', 'approved')->count() }}</h3>
                </div>
                <div class="admin-stat-card-decoration"></div>
            </div>
        </div>
    </div>
    
   <div class="d-flex justify-content-between align-items-center mb-3">
    {{-- Left: Filters --}}
    <form method="GET" class="d-flex gap-2">
        {{-- filters here --}}
    </form>

    {{-- Right: Bulk Actions --}}
    <div class="d-flex gap-2">
        <form id="bulkActionForm" method="POST">
    @csrf

    <div class="d-flex justify-content-end gap-2 mb-3">
        <button type="button" class="btn btn-success"
            onclick="submitBulk('approve')"
            id="bulkApproveBtn" disabled>
            <i class="bx bx-check-double"></i> Approve Selected
        </button>

        <button type="button" class="btn btn-outline-danger"
            onclick="submitBulk('reject')"
            id="bulkRejectBtn" disabled>
            <i class="bx bx-x-circle"></i> Reject Selected
        </button>
    </div>
</form>

    </div>
</div>




    <!-- Reviews List/Table -->
    <div class="admin-reviews-wrapper">
        <!-- Card View -->
        <div class="admin-reviews-container" id="reviewsContainer">
            @forelse($reviews as $review)
                <div class="admin-review-card-modern">
                    <!-- Review Header -->
                    <div class="admin-review-card-header">
                        <div class="admin-review-user-info">
                            <div class="admin-review-avatar-wrapper">
                                @if ($review->user->profile && $review->user->profile->profile_picture)
                                    <img src="{{ asset('storage/' . $review->user->profile->profile_picture) }}"
                                        alt="{{ $review->user->name }}" class="admin-review-avatar-img">
                                @else
                                    <div class="admin-review-avatar-placeholder">
                                        {{ substr($review->user->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            <div class="admin-review-user-details">
                                <h6 class="admin-review-user-name">{{ $review->user->name }}</h6>
                                <p class="admin-review-user-email">{{ $review->user->email }}</p>
                            </div>
                        </div>
                        <div class="admin-review-meta-info">
                            <span class="admin-review-status-badge {{ $review->status }}">
                                <i
                                    class='bx {{ $review->status === 'approved' ? 'bx-check-circle' : ($review->status === 'pending' ? 'bx-time' : 'bx-x-circle') }}'></i>
                                {{ ucfirst($review->status) }}
                            </span>
                            <span class="admin-review-time">{{ $review->created_at->diffForHumans() }}</span>
                        </div>
                    </div>

                    <!-- Review Content -->
                    <div class="admin-review-card-body">
                        <!-- Target Info -->
                        <div class="admin-review-target-info">
                            <div class="admin-review-target-icon">
                                <i
                                    class='bx {{ class_basename($review->reviewable_type) === 'Attraction' ? 'bx-map-pin' : 'bx-store' }}'></i>
                            </div>
                            <div class="admin-review-target-details">
                                <span class="admin-review-target-label">Reviewed</span>
                                <a href="#" class="admin-review-target-name">
                                    {{ $review->reviewable->name ?? 'N/A' }}
                                </a>
                                <span
                                    class="admin-review-target-type">{{ class_basename($review->reviewable_type) }}</span>
                            </div>
                        </div>

                        <!-- Rating -->
                        <div class="admin-review-rating">
                            <div class="admin-review-stars">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class='bx {{ $i <= $review->rating ? 'bxs-star' : 'bx-star' }}'></i>
                                @endfor
                            </div>
                            <span class="admin-review-rating-text">{{ $review->rating }}/5</span>
                        </div>

                        <!-- Comment -->
                        @if ($review->comment)
                            <div class="admin-review-comment">
                                <p>{{ Str::limit($review->comment, 150) }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- Review Actions -->
                    <div class="admin-review-card-footer">
                        @if ($review->status === 'pending')
                            <form action="{{ route('admin.reviews.approve', $review) }}" method="POST"
                                class="d-inline">
                                @csrf
                                <button type="submit" class="admin-review-action-btn approve">
                                    <i class='bx bx-check'></i>
                                    <span>Approve</span>
                                </button>
                            </form>
                            <form action="{{ route('admin.reviews.reject', $review) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="admin-review-action-btn reject">
                                    <i class='bx bx-x'></i>
                                    <span>Reject</span>
                                </button>
                            </form>
                        @endif

                        <button type="button" class="admin-review-action-btn delete"
                            onclick="confirmDelete({{ $review->id }})">
                            <i class='bx bx-trash'></i>
                            <span>Delete</span>
                        </button>

                        <form id="delete-form-{{ $review->id }}"
                            action="{{ route('admin.reviews.destroy', $review) }}" method="POST" class="d-none">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </div>
            @empty
                <div class="admin-empty-state">
                    <div class="admin-empty-icon">
                        <i class='bx bx-message-square-detail'></i>
                    </div>
                    <h5>No Reviews Found</h5>
                    <p>{{ request('search') || request('status') || request('type') ? 'No reviews match your filters. Try adjusting your search.' : 'Reviews will appear here when users submit feedback' }}
                    </p>
                </div>
            @endforelse
        </div>

        <!-- Table View -->
        <div class="admin-reviews-table-wrapper" id="reviewsTable" style="display: none;">
            <div class="table-responsive">
                <table class="admin-reviews-table">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" id="selectAll">
                            </th>

                            <th class="sortable" onclick="sortTable('user')">
                                User <i class='bx bx-sort'></i>
                            </th>
                            <th class="sortable" onclick="sortTable('place')">
                                Place <i class='bx bx-sort'></i>
                            </th>
                            <th>Type</th>
                            <th class="sortable" onclick="sortTable('rating')">
                                Rating <i class='bx bx-sort'></i>
                            </th>
                            <th>Comment</th>
                            <th class="sortable" onclick="sortTable('status')">
                                Status <i class='bx bx-sort'></i>
                            </th>
                            <th class="sortable" onclick="sortTable('date')">
                                Date <i class='bx bx-sort'></i>
                            </th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reviews as $review)
                            <tr data-status="{{ $review->status }}" data-rating="{{ $review->rating }}"
                                data-user="{{ $review->user->name }}"
                                data-place="{{ $review->reviewable->name ?? 'N/A' }}"
                                data-date="{{ $review->created_at->timestamp }}">
                                <td>
                                    <input type="checkbox"
                                        class="review-checkbox"
                                        name="ids[]"
                                        value="{{ $review->id }}">
                                </td>

                                <td>
                                    <div class="table-user-cell">
                                        @if ($review->user->profile && $review->user->profile->profile_picture)
                                            <img src="{{ asset('storage/' . $review->user->profile->profile_picture) }}"
                                                alt="{{ $review->user->name }}" class="table-avatar">
                                        @else
                                            <div class="table-avatar-placeholder">
                                                {{ substr($review->user->name, 0, 1) }}
                                            </div>
                                        @endif
                                        <div>
                                            <div class="table-user-name">{{ $review->user->name }}</div>
                                            <div class="table-user-email">{{ $review->user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="table-place-cell">
                                        <strong>{{ $review->reviewable->name ?? 'N/A' }}</strong>
                                    </div>
                                </td>
                                <td>
                                    <span
                                        class="table-type-badge {{ class_basename($review->reviewable_type) === 'Attraction' ? 'attraction' : 'business' }}">
                                        <i
                                            class='bx {{ class_basename($review->reviewable_type) === 'Attraction' ? 'bx-map-pin' : 'bx-store' }}'></i>
                                        {{ class_basename($review->reviewable_type) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="table-rating">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class='bx {{ $i <= $review->rating ? 'bxs-star' : 'bx-star' }}'></i>
                                        @endfor
                                        <span>{{ $review->rating }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="table-comment">
                                        {{ $review->comment ? Str::limit($review->comment, 60) : '-' }}
                                    </div>
                                </td>
                                <td>
                                    <span class="table-status-badge {{ $review->status }}">
                                        <i
                                            class='bx {{ $review->status === 'approved' ? 'bx-check-circle' : ($review->status === 'pending' ? 'bx-time' : 'bx-x-circle') }}'></i>
                                        {{ ucfirst($review->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="table-date">
                                        <div>{{ $review->created_at->format('M d, Y') }}</div>
                                        <small>{{ $review->created_at->format('h:i A') }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="table-actions">
                                        @if ($review->status === 'pending')
                                            <form action="{{ route('admin.reviews.approve', $review) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                <button type="submit" class="table-action-btn approve" title="Approve">
                                                    <i class='bx bx-check'></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.reviews.reject', $review) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                <button type="submit" class="table-action-btn reject" title="Reject">
                                                    <i class='bx bx-x'></i>
                                                </button>
                                            </form>
                                        @endif
                                        <button type="button" class="table-action-btn delete"
                                            onclick="confirmDelete({{ $review->id }})" title="Delete">
                                            <i class='bx bx-trash'></i>
                                        </button>
                                        <form id="delete-form-{{ $review->id }}"
                                            action="{{ route('admin.reviews.destroy', $review) }}" method="POST"
                                            class="d-none">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="admin-empty-state" style="padding: 3rem 1rem;">
                                        <div class="admin-empty-icon" style="margin: 0 auto 1.5rem;">
                                            <i class='bx bx-message-square-detail'></i>
                                        </div>
                                        <h5>No Reviews Found</h5>
                                        <p>{{ request('search') || request('status') || request('type') ? 'No reviews match your filters. Try adjusting your search.' : 'Reviews will appear here when users submit feedback' }}
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if ($reviews->hasPages())
        <div class="admin-pagination-wrapper mt-4">
            {{ $reviews->links('vendor.pagination.admin') }}
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

        /* View Toggle Buttons */
        .view-toggle-buttons {
            display: flex;
            gap: 0.5rem;
            background: white;
            padding: 0.375rem;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .view-toggle-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.625rem 1.25rem;
            border: none;
            border-radius: 8px;
            background: transparent;
            color: #64748b;
            font-weight: 600;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .view-toggle-btn i {
            font-size: 1.125rem;
        }

        .view-toggle-btn:hover {
            background: #f1f5f9;
            color: #1e293b;
        }

        .view-toggle-btn.active {
            background: linear-gradient(135deg, #1a7838, #27a345);
            color: white;
            box-shadow: 0 2px 8px rgba(26, 120, 56, 0.3);
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

        .admin-filter-grid-enhanced {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr auto;
            gap: 1rem;
            align-items: end;
        }

        .admin-filter-input {
            padding: 0.75rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 0.9375rem;
            transition: all 0.25s ease;
            width: 100%;
        }

        .admin-filter-input:focus {
            outline: none;
            border-color: #1a7838;
            box-shadow: 0 0 0 4px rgba(26, 120, 56, 0.1);
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

        /* Reviews Container */
        .admin-reviews-wrapper {
            position: relative;
        }

        .admin-reviews-container {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        /* Grid View */
        .admin-reviews-container.grid-view {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
            gap: 1.25rem;
        }

        .admin-reviews-container.grid-view .admin-review-card-modern {
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .admin-reviews-container.grid-view .admin-review-card-body {
            flex: 1;
        }

        .admin-reviews-container.grid-view .admin-review-card-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .admin-reviews-container.grid-view .admin-review-meta-info {
            width: 100%;
            justify-content: space-between;
        }

        .admin-reviews-container.grid-view .admin-review-card-footer {
            flex-direction: column;
        }

        .admin-reviews-container.grid-view .admin-review-action-btn {
            width: 100%;
            justify-content: center;
        }

        /* Table View Styles */
        .admin-reviews-table-wrapper {
            background: white;
            border-radius: 14px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #f1f5f9;
            overflow: hidden;
        }

        .admin-reviews-table {
            width: 100%;
            border-collapse: collapse;
        }

        .admin-reviews-table thead {
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border-bottom: 2px solid #e2e8f0;
        }

        .admin-reviews-table th {
            padding: 1rem;
            text-align: left;
            font-size: 0.8125rem;
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            white-space: nowrap;
        }

        .admin-reviews-table th.sortable {
            cursor: pointer;
            user-select: none;
            transition: all 0.2s ease;
        }

        .admin-reviews-table th.sortable:hover {
            background: rgba(26, 120, 56, 0.05);
            color: #1a7838;
        }

        .admin-reviews-table th.sortable i {
            margin-left: 0.25rem;
            font-size: 1rem;
            opacity: 0.5;
        }

        .admin-reviews-table tbody tr {
            border-bottom: 1px solid #f1f5f9;
            transition: all 0.2s ease;
        }

        .admin-reviews-table tbody tr:hover {
            background: #f8fafc;
        }

        .admin-reviews-table td {
            padding: 1rem;
            font-size: 0.875rem;
            color: #475569;
            vertical-align: middle;
        }

        .table-user-cell {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .table-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            flex-shrink: 0;
        }

        .table-avatar-placeholder {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1a7838, #27a345);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            font-weight: 700;
            border: 2px solid white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            flex-shrink: 0;
        }

        .table-user-name {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.125rem;
        }

        .table-user-email {
            font-size: 0.75rem;
            color: #94a3b8;
        }

        .table-place-cell strong {
            color: #1e293b;
            font-weight: 600;
        }

        .table-type-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.375rem 0.75rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .table-type-badge.attraction {
            background: #dbeafe;
            color: #1e40af;
        }

        .table-type-badge.business {
            background: #fce7f3;
            color: #9f1239;
        }

        .table-rating {
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .table-rating i {
            font-size: 1rem;
        }

        .table-rating .bxs-star {
            color: #fbbf24;
        }

        .table-rating .bx-star {
            color: #e2e8f0;
        }

        .table-rating span {
            margin-left: 0.25rem;
            font-weight: 600;
            color: #1e293b;
        }

        .table-comment {
            max-width: 200px;
            color: #64748b;
            line-height: 1.4;
        }

        .table-status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.375rem 0.75rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            white-space: nowrap;
        }

        .table-status-badge.approved {
            background: #d1fae5;
            color: #059669;
        }

        .table-status-badge.pending {
            background: #fef3c7;
            color: #d97706;
        }

        .table-status-badge.rejected {
            background: #fee2e2;
            color: #dc2626;
        }

        .table-date {
            white-space: nowrap;
        }

        .table-date div {
            font-weight: 600;
            color: #1e293b;
        }

        .table-date small {
            font-size: 0.75rem;
            color: #94a3b8;
        }

        .table-actions {
            display: flex;
            gap: 0.375rem;
            justify-content: center;
        }

        .table-action-btn {
            width: 32px;
            height: 32px;
            border: none;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 1.125rem;
        }

        .table-action-btn.approve {
            background: #d1fae5;
            color: #059669;
        }

        .table-action-btn.approve:hover {
            background: #059669;
            color: white;
            transform: scale(1.1);
        }

        .table-action-btn.reject {
            background: #fef3c7;
            color: #d97706;
        }

        .table-action-btn.reject:hover {
            background: #d97706;
            color: white;
            transform: scale(1.1);
        }

        .table-action-btn.delete {
            background: #fee2e2;
            color: #dc2626;
        }

        .table-action-btn.delete:hover {
            background: #dc2626;
            color: white;
            transform: scale(1.1);
        }

        /* Modern Review Cards */
        .admin-review-card-modern {
            background: white;
            border-radius: 14px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #f1f5f9;
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .admin-review-card-modern:hover {
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        /* Review Header */
        .admin-review-card-header {
            padding: 1.5rem;
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .admin-review-user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .admin-review-avatar-wrapper {
            flex-shrink: 0;
        }

        .admin-review-avatar-img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .admin-review-avatar-placeholder {
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
            border: 3px solid white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .admin-review-user-details {
            flex: 1;
        }

        .admin-review-user-name {
            font-size: 1rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0 0 0.25rem 0;
        }

        .admin-review-user-email {
            font-size: 0.8125rem;
            color: #64748b;
            margin: 0;
        }

        .admin-review-meta-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .admin-review-status-badge {
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

        .admin-review-status-badge.approved {
            background: #d1fae5;
            color: #059669;
        }

        .admin-review-status-badge.pending {
            background: #fef3c7;
            color: #d97706;
        }

        .admin-review-status-badge.rejected {
            background: #fee2e2;
            color: #dc2626;
        }

        .admin-review-time {
            font-size: 0.8125rem;
            color: #94a3b8;
        }

        /* Review Body */
        .admin-review-card-body {
            padding: 1.5rem;
        }

        .admin-review-target-info {
            display: flex;
            gap: 1rem;
            padding: 1rem;
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border-radius: 10px;
            margin-bottom: 1.25rem;
        }

        .admin-review-target-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background: linear-gradient(135deg, #1a7838, #27a345);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        .admin-review-target-details {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .admin-review-target-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .admin-review-target-name {
            font-size: 1rem;
            font-weight: 700;
            color: #1a7838;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .admin-review-target-name:hover {
            color: #27a345;
        }

        .admin-review-target-type {
            font-size: 0.8125rem;
            color: #94a3b8;
        }

        .admin-review-rating {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.25rem;
        }

        .admin-review-stars {
            display: flex;
            gap: 0.25rem;
            font-size: 1.5rem;
        }

        .admin-review-stars .bxs-star {
            color: #fbbf24;
        }

        .admin-review-stars .bx-star {
            color: #e2e8f0;
        }

        .admin-review-rating-text {
            font-size: 1rem;
            font-weight: 700;
            color: #1e293b;
        }

        .admin-review-comment {
            padding: 1.25rem;
            background: #f8fafc;
            border-radius: 10px;
            border-left: 4px solid #1a7838;
        }

        .admin-review-comment p {
            font-size: 0.9375rem;
            color: #475569;
            line-height: 1.6;
            margin: 0;
        }

        /* Review Footer */
        .admin-review-card-footer {
            padding: 1.25rem 1.5rem;
            background: #fafbfc;
            border-top: 1px solid #e2e8f0;
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .admin-review-action-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.625rem 1.25rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.875rem;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .admin-review-action-btn.approve {
            background: #d1fae5;
            color: #059669;
        }

        .admin-review-action-btn.approve:hover {
            background: #059669;
            color: white;
            transform: translateY(-2px);
        }

        .admin-review-action-btn.reject {
            background: #fef3c7;
            color: #d97706;
        }

        .admin-review-action-btn.reject:hover {
            background: #d97706;
            color: white;
            transform: translateY(-2px);
        }

        .admin-review-action-btn.delete {
            background: #fee2e2;
            color: #dc2626;
        }

        .admin-review-action-btn.delete:hover {
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

            .admin-filter-grid-enhanced {
                grid-template-columns: 1fr;
            }

            .admin-filter-actions {
                justify-content: stretch;
                width: 100%;
            }

            .admin-filter-btn {
                flex: 1;
            }

            .admin-reviews-container.grid-view {
                grid-template-columns: 1fr;
            }

            .view-toggle-buttons {
                width: 100%;
                justify-content: center;
            }

            .admin-page-header-content {
                flex-direction: column;
                align-items: stretch;
            }

            .admin-reviews-table-wrapper {
                overflow-x: auto;
            }

            .admin-reviews-table {
                min-width: 900px;
            }
        }

        @media (max-width: 768px) {
            .admin-review-card-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .admin-review-meta-info {
                width: 100%;
            }

            .admin-review-card-footer {
                flex-direction: column;
            }

            .admin-review-action-btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        function confirmDelete(id) {
            if (confirm('Are you sure you want to delete this review?\n\nThis action cannot be undone.')) {
                document.getElementById('delete-form-' + id).submit();
            }
        }

        function switchView(view) {
            const cardContainer = document.getElementById('reviewsContainer');
            const tableContainer = document.getElementById('reviewsTable');
            const buttons = document.querySelectorAll('.view-toggle-btn');

            // Toggle visibility
            if (view === 'list') {
                cardContainer.style.display = 'none';
                tableContainer.style.display = 'block';
            } else {
                cardContainer.style.display = 'flex';
                tableContainer.style.display = 'none';

                if (view === 'grid') {
                    cardContainer.classList.add('grid-view');
                } else {
                    cardContainer.classList.remove('grid-view');
                }
            }

            // Update button states
            buttons.forEach(btn => {
                if (btn.dataset.view === view) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });

            // Save preference to localStorage
            localStorage.setItem('reviewsViewPreference', view);
        }

        // Table sorting functionality
        let sortDirection = {};

        function sortTable(column) {
            const table = document.querySelector('.admin-reviews-table tbody');
            const rows = Array.from(table.querySelectorAll('tr')).filter(row => !row.querySelector('.admin-empty-state'));

            // Toggle sort direction
            sortDirection[column] = sortDirection[column] === 'asc' ? 'desc' : 'asc';

            rows.sort((a, b) => {
                let aVal, bVal;

                switch (column) {
                    case 'user':
                        aVal = a.dataset.user.toLowerCase();
                        bVal = b.dataset.user.toLowerCase();
                        break;
                    case 'place':
                        aVal = a.dataset.place.toLowerCase();
                        bVal = b.dataset.place.toLowerCase();
                        break;
                    case 'rating':
                        aVal = parseInt(a.dataset.rating);
                        bVal = parseInt(b.dataset.rating);
                        break;
                    case 'status':
                        aVal = a.dataset.status;
                        bVal = b.dataset.status;
                        break;
                    case 'date':
                        aVal = parseInt(a.dataset.date);
                        bVal = parseInt(b.dataset.date);
                        break;
                }

                if (sortDirection[column] === 'asc') {
                    return aVal > bVal ? 1 : -1;
                } else {
                    return aVal < bVal ? 1 : -1;
                }
            });

            // Re-append sorted rows
            rows.forEach(row => table.appendChild(row));
        }

        // Load saved view preference on page load
        document.addEventListener('DOMContentLoaded', function() {
            const savedView = localStorage.getItem('reviewsViewPreference') || 'list';
            switchView(savedView);
        });
       
    const checkboxes = () => document.querySelectorAll('.review-checkbox');
    const bulkApproveBtn = document.getElementById('bulkApproveBtn');
    const bulkRejectBtn = document.getElementById('bulkRejectBtn');
    const selectAll = document.getElementById('selectAll');

    function updateBulkButtons() {
        const anyChecked = [...checkboxes()].some(cb => cb.checked);
        bulkApproveBtn.disabled = !anyChecked;
        bulkRejectBtn.disabled = !anyChecked;
    }

    document.addEventListener('change', function (e) {
        if (e.target.classList.contains('review-checkbox')) {
            updateBulkButtons();
        }

        if (e.target === selectAll) {
            checkboxes().forEach(cb => cb.checked = selectAll.checked);
            updateBulkButtons();
        }
    });

    function submitBulk(action) {
        const form = document.getElementById('bulkActionForm');
        const url = action === 'approve'
            ? "{{ route('admin.reviews.bulk-approve') }}"
            : "{{ route('admin.reviews.bulk-reject') }}";

        if (!confirm(`Are you sure you want to ${action} selected reviews?`)) {
            return;
        }

        form.action = url;

        checkboxes().forEach(cb => {
            if (cb.checked) {
                form.appendChild(cb.cloneNode(true));
            }
        });

        form.submit();
    }

    </script>
@endpush
