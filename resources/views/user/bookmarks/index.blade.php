@extends('layouts.app')

@section('title', 'My Bookmarks - Dasmariñas Tourism')

@section('content')
    <section class="profile-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <!-- Header -->
                    <div class="profile-header-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h2 class="profile-header-title">
                                    <i class='bx bx-bookmark'></i> My Bookmarks
                                </h2>
                                <p class="profile-header-subtitle">Places and businesses you've saved</p>
                            </div>
                            <a href="{{ route('user.profile.show') }}" class="btn btn-outline-primary">
                                <i class='bx bx-arrow-back me-2'></i>Back to Profile
                            </a>
                        </div>
                    </div>

                    <!-- Success Message -->
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class='bx bx-check-circle me-2'></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if ($bookmarks->count() > 0)
                        <!-- Stats -->
                        <div class="bookmark-stats mb-4">
                            <div class="stat-badge">
                                <i class='bx bx-bookmark'></i>
                                <span>{{ $bookmarks->total() }} Total Bookmarks</span>
                            </div>
                        </div>

                        <!-- Bookmarks Grid -->
                        <div class="row g-4">
                            @foreach ($bookmarks as $bookmark)
                                <div class="col-md-6">
                                    <div class="bookmark-card">
                                        <!-- Type Badge -->
                                        <div
                                            class="bookmark-type-badge {{ $bookmark->bookmarkable_type === 'App\Models\Attraction' ? 'badge-attraction' : 'badge-business' }}">
                                            <i
                                                class='bx {{ $bookmark->bookmarkable_type === 'App\Models\Attraction' ? 'bx-map-pin' : 'bx-store-alt' }}'></i>
                                            {{ $bookmark->bookmarkable_type === 'App\Models\Attraction' ? 'Attraction' : 'Business' }}
                                        </div>

                                        <!-- Card Body -->
                                        <div class="bookmark-card-body">
                                            <h5 class="bookmark-title">{{ $bookmark->bookmarkable->name }}</h5>

                                            @if ($bookmark->bookmarkable->description)
                                                <p class="bookmark-description">
                                                    {{ Str::limit($bookmark->bookmarkable->description, 100) }}
                                                </p>
                                            @endif

                                            <!-- Meta Info -->
                                            <div class="bookmark-meta">
                                                <small class="text-muted">
                                                    <i class='bx bx-time'></i> Saved
                                                    {{ $bookmark->created_at->diffForHumans() }}
                                                </small>
                                            </div>

                                            <!-- Actions -->
                                            <div class="bookmark-actions">
                                                <a href="{{ $bookmark->bookmarkable_type === 'App\Models\Attraction' ? route('attractions.show', $bookmark->bookmarkable->slug) : route('businesses.show', $bookmark->bookmarkable->slug) }}"
                                                    class="btn btn-primary btn-sm">
                                                    <i class='bx bx-show me-1'></i>View Details
                                                </a>
                                                <form method="POST"
                                                    action="{{ route('user.bookmarks.toggle', [strtolower(class_basename($bookmark->bookmarkable_type)), $bookmark->bookmarkable->id]) }}"
                                                    onsubmit="return confirm('Remove this bookmark?');" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                                        <i class='bx bx-trash'></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-4">
                            {{ $bookmarks->links('vendor.pagination.user') }}
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="bookmark-empty-state">
                            <i class='bx bx-bookmark'></i>
                            <h4>No Bookmarks Yet</h4>
                            <p>Start exploring and save your favorite places!</p>
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
