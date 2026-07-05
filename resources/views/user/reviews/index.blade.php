@extends('layouts.app')

@section('title', 'My Reviews - Dasmariñas Tourism')

@section('content')
    <section class="profile-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <!-- Header -->
                    <div class="profile-header-card">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <div>
                                <h2 class="profile-header-title">
                                    <i class='bx bx-message-square-detail'></i> My Reviews
                                </h2>
                                <p class="profile-header-subtitle">Reviews you've written</p>
                            </div>
                            <div class="d-flex gap-2 align-items-center flex-wrap">
                                <div class="view-toggle-buttons">
                                    <button type="button" class="view-toggle-btn active" data-view="card"
                                        onclick="switchView('card')">
                                        <i class='bx bx-grid-alt'></i>
                                        <span>Cards</span>
                                    </button>
                                    <button type="button" class="view-toggle-btn" data-view="list"
                                        onclick="switchView('list')">
                                        <i class='bx bx-list-ul'></i>
                                        <span>List</span>
                                    </button>
                                </div>
                                <a href="{{ route('user.profile.show') }}" class="btn btn-outline-primary">
                                    <i class='bx bx-arrow-back me-2'></i>Back to Profile
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Success/Error Messages -->
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class='bx bx-check-circle me-2'></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class='bx bx-error-circle me-2'></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Filters & Search -->
                    <div class="review-filters-card mb-4">
                        <form method="GET" action="{{ route('user.reviews.index') }}" id="filterForm">
                            <div class="filters-grid">
                                <div class="filter-item">
                                    <label class="filter-label">
                                        <i class='bx bx-search-alt'></i> Search
                                    </label>
                                    <input type="text" name="search" class="filter-input"
                                        placeholder="Search by place name or comment..." value="{{ request('search') }}">
                                </div>

                                <div class="filter-item">
                                    <label class="filter-label">
                                        <i class='bx bx-filter'></i> Status
                                    </label>
                                    <select name="status" class="filter-select" onchange="this.form.submit()">
                                        <option value="">All Statuses</option>
                                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>
                                            Pending</option>
                                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>
                                            Approved</option>
                                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>
                                            Rejected</option>
                                    </select>
                                </div>

                                <div class="filter-item">
                                    <label class="filter-label">
                                        <i class='bx bx-category'></i> Type
                                    </label>
                                    <select name="type" class="filter-select" onchange="this.form.submit()">
                                        <option value="">All Types</option>
                                        <option value="attraction" {{ request('type') === 'attraction' ? 'selected' : '' }}>
                                            Attractions</option>
                                        <option value="business" {{ request('type') === 'business' ? 'selected' : '' }}>
                                            Businesses</option>
                                    </select>
                                </div>

                                <div class="filter-actions">
                                    <button type="submit" class="filter-btn primary">
                                        <i class='bx bx-search'></i>
                                        <span>Search</span>
                                    </button>
                                    <a href="{{ route('user.reviews.index') }}" class="filter-btn secondary">
                                        <i class='bx bx-reset'></i>
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>

                    @if ($reviews->count() > 0)
                        <!-- Stats -->
                        <div class="review-stats mb-4">
                            <div class="stat-badge">
                                <i class='bx bx-message-square-detail'></i>
                                <span>{{ $reviews->total() }} Total Reviews</span>
                            </div>
                            <div class="stat-badge">
                                <i class='bx bxs-star'></i>
                                <span>{{ number_format($reviews->avg('rating'), 1) }} Average Rating</span>
                            </div>
                        </div>

                        <!-- Reviews List -->
                        <div class="reviews-list" id="reviewsCard">
                            @foreach ($reviews as $review)
                                <div class="review-item-card">
                                    <!-- Review Header -->
                                    <div class="review-item-header">
                                        <div class="review-item-info">
                                            <div
                                                class="review-type-badge {{ $review->reviewable_type === 'App\Models\Attraction' ? 'badge-attraction' : 'badge-business' }}">
                                                <i
                                                    class='bx {{ $review->reviewable_type === 'App\Models\Attraction' ? 'bx-map-pin' : 'bx-store-alt' }}'></i>
                                                {{ $review->reviewable_type === 'App\Models\Attraction' ? 'Attraction' : 'Business' }}
                                            </div>
                                            <h5 class="review-item-title">{{ $review->reviewable->name }}</h5>
                                        </div>
                                        <div class="review-rating-large">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i class='bx {{ $i <= $review->rating ? 'bxs-star' : 'bx-star' }}'></i>
                                            @endfor
                                            <span class="rating-number">{{ $review->rating }}.0</span>
                                        </div>
                                    </div>

                                    <!-- Review Body -->
                                    <div class="review-item-body">
                                        @if ($review->comment)
                                            <p class="review-comment">{{ $review->comment }}</p>
                                        @else
                                            <p class="text-muted fst-italic">No written review</p>
                                        @endif
                                    </div>

                                    <!-- Review Footer -->
                                    <div class="review-item-footer">
                                        <div class="review-meta">
                                            <i class='bx bx-time'></i>
                                            <span>{{ $review->created_at->diffForHumans() }}</span>
                                            @if ($review->status === 'pending')
                                                <span class="badge bg-warning text-dark ms-2">
                                                    <i class='bx bx-time-five'></i> Pending Approval
                                                </span>
                                            @endif
                                        </div>
                                        <div class="d-flex gap-2 flex-wrap">
                                            <a href="{{ $review->reviewable_type === 'App\Models\Attraction' ? route('attractions.show', $review->reviewable->slug) : route('businesses.show', $review->reviewable->slug) }}"
                                                class="btn btn-outline-primary btn-sm">
                                                <i class='bx bx-link-external me-1'></i>View Place
                                            </a>
                                            <button type="button" class="btn btn-outline-secondary btn-sm"
                                                onclick="openEditModal({{ $review->id }})">
                                                <i class='bx bx-edit me-1'></i>Edit
                                            </button>
                                            <form action="{{ route('reviews.destroy', $review->id) }}" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Are you sure you want to delete this review?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                                    <i class='bx bx-trash'></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- List/Table View -->
                        <div class="reviews-table-wrapper" id="reviewsList" style="display: none;">
                            <div class="table-responsive">
                                <table class="reviews-table">
                                    <thead>
                                        <tr>
                                            <th>Place</th>
                                            <th>Type</th>
                                            <th>Rating</th>
                                            <th>Comment</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($reviews as $review)
                                            <tr>
                                                <td>
                                                    <div class="table-place-name">
                                                        {{ $review->reviewable->name }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <span
                                                        class="table-type-badge {{ $review->reviewable_type === 'App\Models\Attraction' ? 'attraction' : 'business' }}">
                                                        <i
                                                            class='bx {{ $review->reviewable_type === 'App\Models\Attraction' ? 'bx-map-pin' : 'bx-store-alt' }}'></i>
                                                        {{ $review->reviewable_type === 'App\Models\Attraction' ? 'Attraction' : 'Business' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="table-rating">
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            <i
                                                                class='bx {{ $i <= $review->rating ? 'bxs-star' : 'bx-star' }}'></i>
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
                                                        <a href="{{ $review->reviewable_type === 'App\Models\Attraction' ? route('attractions.show', $review->reviewable->slug) : route('businesses.show', $review->reviewable->slug) }}"
                                                            class="table-action-btn view" title="View Place">
                                                            <i class='bx bx-link-external'></i>
                                                        </a>
                                                        <button type="button" class="table-action-btn edit"
                                                            onclick="openEditModal({{ $review->id }})" title="Edit">
                                                            <i class='bx bx-edit'></i>
                                                        </button>
                                                        <form action="{{ route('reviews.destroy', $review->id) }}"
                                                            method="POST" class="d-inline"
                                                            onsubmit="return confirm('Are you sure you want to delete this review?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="table-action-btn delete"
                                                                title="Delete">
                                                                <i class='bx bx-trash'></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-4">
                            {{ $reviews->links('vendor.pagination.user') }}
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="review-empty-state">
                            <i class='bx bx-message-square-detail'></i>
                            <h4>No Reviews Yet</h4>
                            <p>{{ request('search') || request('status') || request('type') ? 'No reviews match your filters. Try adjusting your search.' : 'Share your experiences by leaving reviews!' }}
                            </p>
                            <div class="empty-state-actions">
                                <a href="{{ route('attractions.index') }}" class="btn btn-primary">
                                    <i class='bx bx-map-pin me-2'></i>Explore Attractions
                                </a>
                                <a href="{{ route('businesses.index') }}" class="btn btn-outline-primary">
                                    <i class='bx bx-store-alt me-2'></i>Browse Businesses
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Edit Review Modals -->
    @foreach ($reviews as $review)
        <div class="modal" id="editReviewModal{{ $review->id }}" tabindex="-1" style="display: none;">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class='bx bx-edit'></i> Edit Review
                        </h5>
                        <button type="button" class="btn-close" onclick="closeEditModal({{ $review->id }})"></button>
                    </div>
                    <form action="{{ route('reviews.update', $review->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Place</label>
                                <p class="mb-0">{{ $review->reviewable->name }}</p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Rating <span class="text-danger">*</span>
                                </label>
                                <div class="star-rating-input">
                                    @for ($i = 5; $i >= 1; $i--)
                                        <input type="radio" id="star{{ $i }}_{{ $review->id }}"
                                            name="rating" value="{{ $i }}"
                                            {{ $review->rating == $i ? 'checked' : '' }} required>
                                        <label for="star{{ $i }}_{{ $review->id }}">
                                            <i class='bx bxs-star'></i>
                                        </label>
                                    @endfor
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="comment{{ $review->id }}" class="form-label fw-semibold">
                                    Comment (Optional)
                                </label>
                                <textarea class="form-control" id="comment{{ $review->id }}" name="comment" rows="4" maxlength="1000"
                                    placeholder="Share your experience...">{{ $review->comment }}</textarea>
                                <small class="text-muted">Maximum 1000 characters</small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary"
                                onclick="closeEditModal({{ $review->id }})">
                                Cancel
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class='bx bx-save me-2'></i>Update Review
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endsection

@push('scripts')
    <script>
        function switchView(view) {
            const cardView = document.getElementById('reviewsCard');
            const listView = document.getElementById('reviewsList');
            const buttons = document.querySelectorAll('.view-toggle-btn');

            // Toggle visibility
            if (view === 'list') {
                cardView.style.display = 'none';
                listView.style.display = 'block';
            } else {
                cardView.style.display = 'flex';
                listView.style.display = 'none';
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
            localStorage.setItem('userReviewsViewPreference', view);
        }

        // Load saved view preference on page load
        document.addEventListener('DOMContentLoaded', function() {
            const savedView = localStorage.getItem('userReviewsViewPreference') || 'card';
            switchView(savedView);
        });

        function openEditModal(reviewId) {
            const modal = document.getElementById('editReviewModal' + reviewId);
            modal.style.display = 'block';
            modal.classList.add('show');
            document.body.classList.add('modal-open');

            // Create backdrop
            const backdrop = document.createElement('div');
            backdrop.className = 'modal-backdrop fade show';
            backdrop.id = 'modalBackdrop' + reviewId;
            backdrop.onclick = function() {
                closeEditModal(reviewId);
            };
            document.body.appendChild(backdrop);
        }

        function closeEditModal(reviewId) {
            const modal = document.getElementById('editReviewModal' + reviewId);
            const backdrop = document.getElementById('modalBackdrop' + reviewId);

            modal.style.display = 'none';
            modal.classList.remove('show');
            document.body.classList.remove('modal-open');

            if (backdrop) {
                backdrop.remove();
            }
        }
    </script>
@endpush

@push('styles')
    <style>
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
            padding: 0.625rem 1rem;
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
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
        }

        /* Filters Card */
        .review-filters-card {
            background: white;
            border-radius: 14px;
            padding: 1.75rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #f1f5f9;
        }

        .filters-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr auto;
            gap: 1rem;
            align-items: end;
        }

        .filter-item {
            display: flex;
            flex-direction: column;
        }

        .filter-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 0.625rem;
        }

        .filter-input,
        .filter-select {
            padding: 0.75rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 0.9375rem;
            transition: all 0.25s ease;
            width: 100%;
        }

        .filter-input:focus,
        .filter-select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }

        .filter-actions {
            display: flex;
            gap: 0.5rem;
        }

        .filter-btn {
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

        .filter-btn.primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.25);
        }

        .filter-btn.primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.35);
        }

        .filter-btn.secondary {
            background: #f1f5f9;
            color: #64748b;
            padding: 0.75rem 1rem;
        }

        .filter-btn.secondary:hover {
            background: #e2e8f0;
        }

        /* Table View Styles */
        .reviews-table-wrapper {
            background: white;
            border-radius: 14px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #f1f5f9;
            overflow: hidden;
        }

        .reviews-table {
            width: 100%;
            border-collapse: collapse;
        }

        .reviews-table thead {
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border-bottom: 2px solid #e2e8f0;
        }

        .reviews-table th {
            padding: 1rem;
            text-align: left;
            font-size: 0.8125rem;
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            white-space: nowrap;
        }

        .reviews-table tbody tr {
            border-bottom: 1px solid #f1f5f9;
            transition: all 0.2s ease;
        }

        .reviews-table tbody tr:hover {
            background: #f8fafc;
        }

        .reviews-table td {
            padding: 1rem;
            font-size: 0.875rem;
            color: #475569;
            vertical-align: middle;
        }

        .table-place-name {
            font-weight: 600;
            color: #1e293b;
        }

        .table-type-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.375rem 0.75rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            white-space: nowrap;
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
            max-width: 250px;
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

        .table-action-btn.view {
            background: #dbeafe;
            color: #1e40af;
        }

        .table-action-btn.view:hover {
            background: #1e40af;
            color: white;
            transform: scale(1.1);
        }

        .table-action-btn.edit {
            background: #e0e7ff;
            color: #4f46e5;
        }

        .table-action-btn.edit:hover {
            background: #4f46e5;
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

        /* Responsive */
        @media (max-width: 992px) {
            .filters-grid {
                grid-template-columns: 1fr;
            }

            .filter-actions {
                width: 100%;
            }

            .filter-btn {
                flex: 1;
            }

            .reviews-table-wrapper {
                overflow-x: auto;
            }

            .reviews-table {
                min-width: 800px;
            }
        }

        /* User Pagination Styles */
        .user-pagination-nav {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
            padding: 0;
        }

        @media (min-width: 640px) {
            .user-pagination-nav {
                flex-direction: row;
                justify-content: space-between;
            }
        }

        .user-pagination-info {
            color: #64748b;
            font-size: 0.875rem;
        }

        .user-pagination-info p { margin: 0; }

        .user-pagination-info .font-semibold {
            font-weight: 600;
            color: #1e293b;
        }

        .user-pagination {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            list-style: none;
            padding: 0;
            margin: 0;
            flex-wrap: wrap;
            justify-content: center;
        }

        .user-page-item { display: inline-flex; }

        .user-page-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
            padding: 0 0.75rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: #475569;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .user-page-link:hover {
            background: #f1f5f9;
            border-color: #cbd5e1;
            color: #1e293b;
        }

        .user-page-item.active .user-page-link {
            background: linear-gradient(135deg, #1a7838, #27a345);
            border-color: #1a7838;
            color: white;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(26, 120, 56, 0.25);
        }

        .user-page-item.disabled .user-page-link {
            color: #cbd5e1;
            background: #f8fafc;
            border-color: #e2e8f0;
            cursor: not-allowed;
            pointer-events: none;
        }

        .user-page-link i { font-size: 1.125rem; }
    </style>
@endpush
