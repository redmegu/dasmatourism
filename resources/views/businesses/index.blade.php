@extends('layouts.app')

@section('title', 'Business Directory - Dasmariñas Tourism')

@section('content')
    <!-- Hero Section -->
    <section class="page-hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12 text-center">
                    <h1 class="page-hero-title">Business Directory</h1>
                    <p class="page-hero-subtitle">Discover local businesses, restaurants, shops, and services in Dasmariñas
                        City</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="section-padding">
        <div class="container">
            <div class="row">
                <!-- Filters Sidebar -->
                <div class="col-lg-3 mb-4">
                    <div class="filter-sidebar">
                        <div class="filter-header">
                            <h5 class="filter-title">
                                <i class='bx bx-filter-alt'></i> Filter Businesses
                            </h5>
                        </div>

                        <form method="GET" action="{{ route('businesses.index') }}">
                            <!-- Search -->
                            <div class="filter-group">
                                <label class="filter-label">Search</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class='bx bx-search'></i>
                                    </span>
                                    <input type="text" name="search" class="form-control"
                                        placeholder="Search businesses..." value="{{ request('search') }}">
                                </div>
                            </div>

                            <!-- Category -->
                            <div class="filter-group">
                                <label class="filter-label">Category</label>
                                <select name="category" class="form-select">
                                    <option value="">All Categories</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ request('category') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Verified Only -->
                            <div class="filter-group">
                                <div class="form-check custom-checkbox">
                                    <input class="form-check-input" type="checkbox" name="verified" value="1"
                                        id="verified" {{ request('verified') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="verified">
                                        <i class='bx bx-check-shield'></i> Verified Businesses Only
                                    </label>
                                </div>
                            </div>

                            <!-- Sort By -->
                            <div class="filter-group">
                                <label class="filter-label">Sort By</label>
                                <select name="sort_by" class="form-select">
                                    <option value="rating_score" {{ request('sort_by', 'rating_count') === 'rating_score' ? 'selected' : '' }}>
                                        ⭐ Highest Rating
                                    </option>
                                    <option value="rating_count" {{ request('sort_by', 'rating_count') === 'rating_count' ? 'selected' : '' }}>
                                        💬 Most Reviewed
                                    </option>
                                    <option value="name_asc" {{ request('sort_by') === 'name_asc' ? 'selected' : '' }}>
                                        🔤 Name (A → Z)
                                    </option>
                                    <option value="name_desc" {{ request('sort_by') === 'name_desc' ? 'selected' : '' }}>
                                        🔤 Name (Z → A)
                                    </option>
                                </select>
                            </div>

                            <!-- Action Buttons -->
                            <div class="filter-actions">
                                <button type="submit" class="btn btn-primary w-100 mb-2">
                                    <i class='bx bx-filter-alt me-2'></i>Apply Filters
                                </button>
                                <a href="{{ route('businesses.index') }}" class="btn btn-outline-secondary w-100">
                                    <i class='bx bx-reset me-2'></i>Clear Filters
                                </a>
                            </div>
                        </form>

                        <!-- Popular Categories Quick Links -->
                        <div class="quick-categories mt-4">
                            <h6 class="filter-label">Popular Categories</h6>
                            <div class="d-flex flex-column gap-2">
                                @foreach ($categories->take(5) as $category)
                                    <a href="{{ route('businesses.index', ['category' => $category->id]) }}"
                                        class="category-quick-link">
                                        <i class='bx {{ $category->icon ?? 'bx-category' }}'></i>
                                        {{ $category->name }}
                                        <span
                                            class="badge bg-light text-dark ms-auto">{{ $category->businesses_count }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Business Grid -->
                <div class="col-lg-9">
                    <!-- Results Header -->
                    <div class="results-header mb-4">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <div>
                                <h5 class="mb-1">
                                    @if (request()->hasAny(['search', 'category', 'verified']))
                                        Search Results
                                    @else
                                        All Businesses
                                    @endif
                                </h5>
                                <p class="text-muted mb-0 small">
                                    Showing {{ $businesses->count() }} of {{ $businesses->total() }} businesses
                                    &mdash;
                                    @php
                                        $sortLabels = [
                                            'rating_score' => 'Sorted by Highest Rating',
                                            'rating_count' => 'Sorted by Most Reviewed',
                                            'name_asc'     => 'Sorted A → Z',
                                            'name_desc'    => 'Sorted Z → A',
                                        ];
                                        echo $sortLabels[request('sort_by', 'rating_count')] ?? 'Sorted by Highest Rating';
                                    @endphp
                                </p>
                            </div>
                            @if (request()->hasAny(['search', 'category', 'verified', 'sort_by']))
                                <a href="{{ route('businesses.index') }}" class="btn btn-sm btn-outline-primary">
                                    <i class='bx bx-x me-1'></i>Clear All Filters
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Business Grid -->
                    <div class="row g-4">
                        @forelse($businesses as $business)
                            <div class="col-md-6 col-xl-4">
                                <div class="business-card-public" data-url="{{ route('businesses.show', $business->slug) }}">
                                    <!-- Image -->
                                    <div class="business-card-image">
                                        @if ($business->logo)
                                            <img src="{{ asset('storage/' . $business->logo) }}"
                                                alt="{{ $business->name }} Logo" class="business-card-logo">
                                        @elseif ($business->primaryImage)
                                            <img src="{{ asset('storage/' . $business->primaryImage->image_path) }}"
                                                alt="{{ $business->name }}" class="business-card-logo">
                                        @else
                                            <div class="image-placeholder">
                                                <i class='bx bx-store'></i>
                                            </div>
                                        @endif

                                        <!-- Badges Overlay -->
                                        <div class="business-badges-overlay">
                                            <span class="badge-category">{{ $business->category->name }}</span>
                                            @if ($business->is_verified)
                                                <span class="badge-verified">
                                                    <i class='bx bx-check-circle'></i> Verified
                                                </span>
                                            @endif
                                        </div>

                                        <!-- Bookmark Button -->
                                        @auth
                                            @if (auth()->user()->email_verified_at)
                                                <form method="POST"
                                                    action="{{ route('user.bookmarks.toggle', ['business', $business->id]) }}"
                                                    class="bookmark-form">
                                                    @csrf
                                                    <button type="submit"
                                                        class="btn-bookmark {{ $business->bookmarks->count() > 0 ? 'active' : '' }}"
                                                        title="{{ $business->bookmarks->count() > 0 ? 'Remove from bookmarks' : 'Add to bookmarks' }}">
                                                        <i
                                                            class='bx {{ $business->bookmarks->count() > 0 ? 'bxs-bookmark' : 'bx-bookmark' }}'></i>
                                                    </button>
                                                </form>
                                            @else
                                                <a href="{{ route('auth.verify.show') }}" class="btn-bookmark"
                                                    title="Verify email to bookmark">
                                                    <i class='bx bx-bookmark'></i>
                                                </a>
                                            @endif
                                        @else
                                            <a href="{{ route('login') }}" class="btn-bookmark" title="Login to bookmark">
                                                <i class='bx bx-bookmark'></i>
                                            </a>
                                        @endauth
                                    </div>

                                    <!-- Content -->
                                    <div class="business-card-content">
                                        <h5 class="business-card-title">{{ $business->name }}</h5>

                                        <p class="business-card-location">
                                            <i class='bx bx-map-pin'></i>
                                            {{ Str::limit($business->address, 50) }}
                                        </p>

                                        <p class="business-card-description">
                                            {{ Str::limit($business->description, 90) }}
                                        </p>

                                        <!-- Contact Info -->
                                        @if ($business->contact_number)
                                            <p class="business-contact">
                                                <i class='bx bx-phone'></i>
                                                {{ $business->contact_number }}
                                            </p>
                                        @endif

                                        <!-- Rating & Action -->
                                        <div class="business-card-footer">
                                            <div class="business-rating">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <i
                                                        class='bx {{ $i <= $business->getAverageRating() ? 'bxs-star' : 'bx-star' }}'></i>
                                                @endfor
                                                <span class="rating-count">({{ $business->getTotalReviews() }})</span>
                                            </div>
                                            <a href="{{ route('businesses.show', $business->slug) }}"
                                                class="btn-view-details">
                                                View Details <i class='bx bx-right-arrow-alt'></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        @empty
                            <div class="col-12">
                                <div class="empty-state-public">
                                    <i class='bx bx-store'></i>
                                    <h4>No Businesses Found</h4>
                                    <p>We couldn't find any businesses matching your criteria.</p>
                                    @if (request()->hasAny(['search', 'category', 'verified']))
                                        <a href="{{ route('businesses.index') }}" class="btn btn-primary mt-3">
                                            <i class='bx bx-reset me-2'></i>Clear All Filters
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    @if ($businesses->hasPages())
                        <div class="mt-5">
                            {{ $businesses->links('vendor.pagination.user') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
    <style>
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

        .user-pagination-info p {
            margin: 0;
        }

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

        .user-page-item {
            display: inline-flex;
        }

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

        .user-page-link i {
            font-size: 1.125rem;
        }
    </style>
@endpush

@push('styles')
    <style>
        .business-card-public[data-url] {
            cursor: pointer;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.querySelectorAll('.business-card-public[data-url]').forEach(function (card) {
            card.addEventListener('click', function (e) {
                if (e.target.closest('.btn-bookmark, .bookmark-form, a')) return;
                window.location.href = card.dataset.url;
            });
        });
    </script>
@endpush
