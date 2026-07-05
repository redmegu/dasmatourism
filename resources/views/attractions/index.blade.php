@extends('layouts.app')

@section('title', 'Attractions - Dasmariñas Tourism')

@section('content')
    <!-- Hero Section -->
    <section class="page-hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="page-hero-title">Discover Attractions</h1>
                    <p class="page-hero-subtitle">Explore the most amazing places and destinations in Dasmariñas City</p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    @auth
                        @if (auth()->user()->email_verified_at)
                            <a href="{{ route('user.suggestions.create') }}" class="btn btn-light btn-lg">
                                <i class='bx bx-plus-circle me-2'></i>Suggest a Place
                            </a>
                        @else
                            <a href="{{ route('auth.verify.show') }}" class="btn btn-light btn-lg"
                                title="Verify your email to suggest places">
                                <i class='bx bx-envelope-open me-2'></i>Verify to Suggest
                            </a>
                        @endif
                    @endauth
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
                                <i class='bx bx-filter-alt'></i> Filter Attractions
                            </h5>
                        </div>

                        <form method="GET" action="{{ route('attractions.index') }}">
                            <!-- Search -->
                            <div class="filter-group">
                                <label class="filter-label">Search</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class='bx bx-search'></i>
                                    </span>
                                    <input type="text" name="search" class="form-control"
                                        placeholder="Search attractions..." value="{{ request('search') }}">
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

                            <!-- Historical Sites -->
                            <div class="filter-group">
                                <div class="form-check custom-checkbox">
                                    <input class="form-check-input" type="checkbox" name="historical" value="1"
                                        id="historical" {{ request('historical') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="historical">
                                        <i class='bx bx-landmark'></i> Historical Sites Only
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
                                <a href="{{ route('attractions.index') }}" class="btn btn-outline-secondary w-100">
                                    <i class='bx bx-reset me-2'></i>Clear Filters
                                </a>
                            </div>
                        </form>

                        <!-- Popular Categories Quick Links -->
                        <div class="quick-categories mt-4">
                            <h6 class="filter-label">Popular Categories</h6>
                            <div class="d-flex flex-column gap-2">
                                @foreach ($categories->take(5) as $category)
                                    <a href="{{ route('attractions.index', ['category' => $category->id]) }}"
                                        class="category-quick-link">
                                        <i class='bx {{ $category->icon ?? 'bx-category' }}'></i>
                                        {{ $category->name }}
                                        <span
                                            class="badge bg-light text-dark ms-auto">{{ $category->attractions_count }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Attractions Grid -->
                <div class="col-lg-9">
                    <!-- Results Header -->
                    <div class="results-header mb-4">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <div>
                                <h5 class="mb-1">
                                    @if (request()->hasAny(['search', 'category', 'historical']))
                                        Search Results
                                    @else
                                        All Attractions
                                    @endif
                                </h5>
                                <p class="text-muted mb-0 small">
                                    Showing {{ $attractions->count() }} of {{ $attractions->total() }} attractions
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
                            @if (request()->hasAny(['search', 'category', 'historical', 'sort_by']))
                                <a href="{{ route('attractions.index') }}" class="btn btn-sm btn-outline-primary">
                                    <i class='bx bx-x me-1'></i>Clear All Filters
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Attractions Grid -->
                    <div class="row g-4">
                        @forelse($attractions as $index => $attraction)
                            <div class="col-md-6 col-xl-4">
                                <div class="attraction-card-public" data-url="{{ route('attractions.show', $attraction->slug) }}">
                                    <!-- Image -->
                                    <div class="attraction-card-image">
                                        @if ($attraction->primaryImage)
                                            <img src="{{ asset('storage/' . $attraction->primaryImage->image_path) }}"
                                                alt="{{ $attraction->name }}">
                                        @else
                                            <div class="image-placeholder">
                                                <i class='bx bx-image'></i>
                                            </div>
                                        @endif

                                        <!-- Badges Overlay -->
                                        <div class="attraction-badges-overlay">
                                            <span class="badge-category">{{ $attraction->category->name }}</span>
                                            @if ($attraction->is_historical_site)
                                                <span class="badge-historical">
                                                    <i class='bx bx-landmark'></i> Historical
                                                </span>
                                            @endif
                                            @if ($attraction->is_featured)
                                                <span class="badge-featured">
                                                    <i class='bx bx-star'></i> Featured
                                                </span>
                                            @endif
                                        </div>

                                        <!-- Bookmark Button -->
                                        @auth
                                            @if (auth()->user()->email_verified_at)
                                                <form method="POST"
                                                    action="{{ route('user.bookmarks.toggle', ['attraction', $attraction->id]) }}"
                                                    class="bookmark-form">
                                                    @csrf
                                                    <button type="submit"
                                                        class="btn-bookmark {{ $attraction->bookmarks->count() > 0 ? 'active' : '' }}"
                                                        title="{{ $attraction->bookmarks->count() > 0 ? 'Remove from bookmarks' : 'Add to bookmarks' }}">
                                                        <i
                                                            class='bx {{ $attraction->bookmarks->count() > 0 ? 'bxs-bookmark' : 'bx-bookmark' }}'></i>
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
                                    <div class="attraction-card-content">
                                        <h5 class="attraction-card-title">{{ $attraction->name }}</h5>

                                        <p class="attraction-card-location">
                                            <i class='bx bx-map-pin'></i>
                                            {{ Str::limit($attraction->address, 50) }}
                                        </p>

                                        <p class="attraction-card-description">
                                            {{ Str::limit($attraction->description, 90) }}
                                        </p>

                                        <!-- Rating & Action -->
                                        <div class="attraction-card-footer">
                                            <div class="attraction-rating">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <i
                                                        class='bx {{ $i <= $attraction->getAverageRating() ? 'bxs-star' : 'bx-star' }}'></i>
                                                @endfor
                                                <span class="rating-count">({{ $attraction->getTotalReviews() }})</span>
                                            </div>
                                            <a href="{{ route('attractions.show', $attraction->slug) }}"
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
                                    <i class='bx bx-search-alt'></i>
                                    <h4>No Attractions Found</h4>
                                    <p>We couldn't find any attractions matching your criteria.</p>
                                    @if (request()->hasAny(['search', 'category', 'historical']))
                                        <a href="{{ route('attractions.index') }}" class="btn btn-primary mt-3">
                                            <i class='bx bx-reset me-2'></i>Clear All Filters
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    @if ($attractions->hasPages())
                        <div class="mt-5">
                            {{ $attractions->links('vendor.pagination.user') }}
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
        .attraction-card-public[data-url] {
            cursor: pointer;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.querySelectorAll('.attraction-card-public[data-url]').forEach(function (card) {
            card.addEventListener('click', function (e) {
                if (e.target.closest('.btn-bookmark, .bookmark-form, a')) return;
                window.location.href = card.dataset.url;
            });
        });
    </script>
@endpush
