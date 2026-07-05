@extends('layouts.app')

@section('title', $attraction->name . ' - Dasmariñas Tourism')

@section('content')
    <!-- Hero Image Section -->
    <section class="attraction-hero">
        @if ($attraction->images->count() > 0)
            <div id="attractionCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    @foreach ($attraction->images as $index => $image)
                        <button type="button" data-bs-target="#attractionCarousel" data-bs-slide-to="{{ $index }}"
                            class="{{ $index === 0 ? 'active' : '' }}"></button>
                    @endforeach
                </div>
                <div class="carousel-inner">
                    @foreach ($attraction->images as $index => $image)
                        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                            <img src="{{ asset('storage/' . $image->image_path) }}" class="d-block w-100"
                                alt="{{ $image->caption ?? $attraction->name }}">
                            <div class="carousel-overlay"></div>
                            @if ($image->caption)
                                <div class="carousel-caption-custom">
                                    <p>{{ $image->caption }}</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
                @if ($attraction->images->count() > 1)
                    <button class="carousel-control-prev" type="button" data-bs-target="#attractionCarousel"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#attractionCarousel"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                @endif
            </div>
        @else
            <div class="hero-placeholder">
                <i class='bx bx-image'></i>
            </div>
        @endif

        <!-- Breadcrumb Overlay -->
        <div class="breadcrumb-overlay">
            <div class="container">
                <a href="{{ route('attractions.index') }}" class="btn-back-hero">
                    <i class='bx bx-arrow-back'></i> Back to Attractions
                </a>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="section-padding">
        <div class="container">
            <div class="row">
                <!-- Main Content Column -->
                <div class="col-lg-8 mb-4">
                    <!-- Title & Badges -->
                    <div class="attraction-header mb-4">
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <span class="badge badge-category-large">{{ $attraction->category->name }}</span>
                            @if ($attraction->is_historical_site)
                                <span class="badge badge-historical-large">
                                    <i class='bx bx-landmark'></i> Historical Site
                                </span>
                            @endif
                            @if ($attraction->is_featured)
                                <span class="badge badge-featured-large">
                                    <i class='bx bx-star'></i> Featured
                                </span>
                            @endif
                        </div>

                        <h1 class="attraction-title">{{ $attraction->name }}</h1>

                        <div class="attraction-meta">
                            <div class="meta-item">
                                <i class='bx bx-map-pin'></i>
                                <span>{{ $attraction->address }}</span>
                            </div>
                            <div class="meta-item">
                                <div class="rating-display">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i
                                            class='bx {{ $i <= $attraction->getAverageRating() ? 'bxs-star' : 'bx-star' }}'></i>
                                    @endfor
                                    <span class="rating-text">
                                        {{ number_format($attraction->getAverageRating(), 1) }}
                                        ({{ $attraction->getTotalReviews() }} reviews)
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description Card -->
                    <div class="content-card mb-4">
                        <h3 class="content-card-title">
                            <i class='bx bx-info-circle'></i> About This Place
                        </h3>
                        <p class="content-card-text">{{ $attraction->description }}</p>
                    </div>

                    <!-- Facilities Card -->
                    @if ($attraction->facilities)
                        <div class="content-card mb-4">
                            <h3 class="content-card-title">
                                <i class='bx bx-buildings'></i> Facilities & Amenities
                            </h3>
                            <p class="content-card-text">{{ $attraction->facilities }}</p>
                        </div>
                    @endif

                    <!-- How to Get There Card -->
                    @if ($attraction->commute_guide)
                        <div class="content-card mb-4 commute-guide-card">
                            <h3 class="content-card-title">
                                <i class='bx bx-bus'></i> How to Get There
                            </h3>
                            <div class="commute-guide-body">
                                {!! nl2br(e($attraction->commute_guide)) !!}
                            </div>
                        </div>
                    @endif

                    <!-- Walking Tour Video Card (ADD THIS NEW SECTION) -->
                    @if ($attraction->youtube_video_url || $attraction->uploaded_video_path)
                        <div class="content-card mb-4">
                            <h3 class="content-card-title">
                                <i class='bx bx-video'></i> Virtual Walking Tour
                                @if ($attraction->youtube_video_url)
                                    <span class="video-source-badge youtube">
                                        <i class='bx bxl-youtube'></i> YouTube
                                    </span>
                                @else
                                    <span class="video-source-badge uploaded">
                                        <i class='bx bx-video'></i> Video
                                    </span>
                                @endif
                            </h3>

                            <div class="video-tour-container">
                                @if ($attraction->youtube_video_url)
                                    <!-- YouTube Video -->
                                    <div class="video-wrapper youtube-video">
                                        <iframe width="100%" height="450" src="{{ $attraction->getYoutubeEmbedUrl() }}"
                                            frameborder="0"
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                            allowfullscreen>
                                        </iframe>
                                    </div>
                                @elseif($attraction->uploaded_video_path)
                                    <!-- Uploaded Video -->
                                    <div class="video-wrapper uploaded-video">
                                        <video controls width="100%" height="450"
                                            poster="{{ $attraction->primaryImage ? asset('storage/' . $attraction->primaryImage->image_path) : '' }}">
                                            <source src="{{ asset('storage/' . $attraction->uploaded_video_path) }}"
                                                type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                    </div>
                                @endif

                                <div class="video-info-banner">
                                    <i class='bx bx-info-circle'></i>
                                    <span>Experience a virtual tour of {{ $attraction->name }} from the comfort of your
                                        home!</span>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Reviews Section -->
                    <div class="content-card">
                        <h3 class="content-card-title">
                            <i class='bx bx-message-square-detail'></i> Reviews & Ratings
                        </h3>

                        @auth
                            @if (auth()->user()->email_verified_at)
                                <!-- Review Form -->
                                <div class="review-form-wrapper">
                                    <h5 class="mb-3">Share Your Experience</h5>
                                    <form method="POST" action="{{ route('reviews.store') }}" class="review-form">
                                        @csrf
                                        <input type="hidden" name="reviewable_type" value="App\Models\Attraction">
                                        <input type="hidden" name="reviewable_id" value="{{ $attraction->id }}">
                                        <input type="hidden" name="rating" id="rating-input" value="5">

                                        <div class="mb-4">
                                            <label class="form-label fw-semibold">Your Rating</label>
                                            <div class="star-rating-input">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <i class='bx bxs-star' id="star-{{ $i }}"
                                                        onclick="setRating({{ $i }})"></i>
                                                @endfor
                                            </div>
                                        </div>

                                        <div class="mb-4">
                                            <label class="form-label fw-semibold">Your Review (optional)</label>
                                            <textarea name="comment" class="form-control" rows="4" placeholder="Share your thoughts about this place..."></textarea>
                                        </div>

                                        <button type="submit" class="btn btn-primary btn-lg">
                                            <i class='bx bx-send me-2'></i>Submit Review
                                        </button>
                                    </form>
                                </div>

                                <hr class="my-4">
                            @else
                                <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
                                    <div class="d-flex align-items-center">
                                        <i class='bx bx-envelope-open me-3' style="font-size: 1.5rem;"></i>
                                        <div>
                                            <strong>Email Verification Required</strong>
                                            <p class="mb-0 mt-1">Please verify your email address to leave reviews and access
                                                other features.</p>
                                        </div>
                                    </div>
                                    <a href="{{ route('auth.verify.show') }}" class="btn btn-sm btn-warning mt-3">
                                        <i class='bx bx-envelope-open me-1'></i>Verify Email Now
                                    </a>
                                </div>
                            @endif
                        @else
                            <div class="alert alert-info-custom mb-4">
                                <i class='bx bx-info-circle'></i>
                                <span>Please <a href="{{ route('login') }}" class="alert-link">login</a> to leave a
                                    review</span>
                            </div>
                        @endauth

                        <!-- Reviews List -->
                        <div class="reviews-list">
                            @forelse($attraction->approvedReviews as $review)
                                <div class="review-item">
                                    <div class="review-header">
                                        <div class="reviewer-info">
                                            <div class="reviewer-avatar">
                                                {{ substr($review->user->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <h6 class="reviewer-name">{{ $review->user->name }}</h6>
                                                <div class="review-rating">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <i
                                                            class='bx {{ $i <= $review->rating ? 'bxs-star' : 'bx-star' }}'></i>
                                                    @endfor
                                                </div>
                                            </div>
                                        </div>
                                        <small class="review-date">{{ $review->created_at->diffForHumans() }}</small>
                                    </div>
                                    @if ($review->comment)
                                        <p class="review-comment">{{ $review->comment }}</p>
                                    @endif
                                </div>
                            @empty
                                <div class="empty-reviews">
                                    <i class='bx bx-message-square-dots'></i>
                                    <p>No reviews yet. Be the first to share your experience!</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Sidebar Column -->
                <div class="col-lg-4">
                    <!-- Quick Info Card -->
                    <div class="sidebar-card mb-4">
                        <h5 class="sidebar-card-title">
                            <i class='bx bx-info-square'></i> Quick Information
                        </h5>

                        @if ($attraction->entrance_fee)
                            <div class="info-item">
                                <div class="info-icon">
                                    <i class='bx bx-money'></i>
                                </div>
                                <div class="info-content">
                                    <span class="info-label">Entrance Fee</span>
                                    <span class="info-value">₱{{ number_format($attraction->entrance_fee, 2) }}</span>
                                </div>
                            </div>
                        @endif

                        @if ($attraction->contact_number)
                            <div class="info-item">
                                <div class="info-icon">
                                    <i class='bx bx-phone'></i>
                                </div>
                                <div class="info-content">
                                    <span class="info-label">Contact</span>
                                    <a href="tel:{{ $attraction->contact_number }}" class="info-value">
                                        {{ $attraction->contact_number }}
                                    </a>
                                </div>
                            </div>
                        @endif

                        @if ($attraction->email)
                            <div class="info-item">
                                <div class="info-icon">
                                    <i class='bx bx-envelope'></i>
                                </div>
                                <div class="info-content">
                                    <span class="info-label">Email</span>
                                    <a href="mailto:{{ $attraction->email }}" class="info-value">
                                        {{ $attraction->email }}
                                    </a>
                                </div>
                            </div>
                        @endif

                        @if ($attraction->website)
                            <div class="info-item">
                                <div class="info-icon">
                                    <i class='bx bx-globe'></i>
                                </div>
                                <div class="info-content">
                                    <span class="info-label">Website</span>
                                    <a href="{{ $attraction->website }}" target="_blank" class="info-value">
                                        Visit Website <i class='bx bx-link-external'></i>
                                    </a>
                                </div>
                            </div>
                        @endif

                        @if ($attraction->google_maps_link)
                            <div class="info-item">
                                <div class="info-icon">
                                    <i class='bx bx-map'></i>
                                </div>
                                <div class="info-content">
                                    <span class="info-label">Get Directions</span>
                                    <a href="{{ $attraction->google_maps_link }}" target="_blank"
                                        class="info-value text-primary">
                                        <i class='bx bx-navigation'></i> Open in Google Maps
                                    </a>
                                </div>
                            </div>
                        @endif

                        <div class="info-item">
                            <div class="info-icon">
                                <i class='bx bx-show'></i>
                            </div>
                            <div class="info-content">
                                <span class="info-label">Total Views</span>
                                <span class="info-value">{{ number_format($attraction->views) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Operating Hours Card -->
                    @if ($attraction->schedules->count() > 0)
                        <div class="sidebar-card mb-4">
                            <h5 class="sidebar-card-title">
                                <i class='bx bx-time-five'></i> Operating Hours
                            </h5>
                            @foreach ($attraction->schedules as $schedule)
                                <div class="schedule-item">
                                    <span class="schedule-day">{{ ucfirst($schedule->day_of_week) }}</span>
                                    @if ($schedule->is_closed)
                                        <span class="schedule-time schedule-closed">Closed</span>
                                    @else
                                        <span class="schedule-time">
                                            {{ $schedule->opening_time->format('g:i A') }} -
                                            {{ $schedule->closing_time->format('g:i A') }}
                                        </span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Map Card -->
                    <div class="sidebar-card">
                        <h5 class="sidebar-card-title">
                            <i class='bx bx-map-alt'></i> Location
                        </h5>
                        <p class="text-muted small mb-3">View this attraction on the interactive map</p>
                        <a href="{{ route('map.index') }}" class="btn btn-primary w-100">
                            <i class='bx bx-map me-2'></i>View on Map
                        </a>
                    </div>
                </div>
            </div>

            <!-- Related Attractions -->
            @if ($relatedAttractions->count() > 0)
                <div class="related-section mt-5">
                    <h3 class="related-title">You Might Also Like</h3>
                    <div class="row g-4">
                        @foreach ($relatedAttractions as $related)
                            <div class="col-lg-3 col-md-6">
                                <div class="related-card">
                                    <div class="related-image">
                                        @if ($related->primaryImage)
                                            <img src="{{ asset('storage/' . $related->primaryImage->image_path) }}"
                                                alt="{{ $related->name }}">
                                        @else
                                            <div class="image-placeholder">
                                                <i class='bx bx-image'></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="related-content">
                                        <h6 class="related-name">{{ $related->name }}</h6>
                                        <p class="related-category">{{ $related->category->name }}</p>
                                    </div>
                                    <a href="{{ route('attractions.show', $related->slug) }}"
                                        class="card-link-overlay"></a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection

@push('styles')
    <style>
        /* Commute Guide Card */
        .commute-guide-card {
            border-left: 4px solid #f59e0b;
        }

        .commute-guide-card .content-card-title i {
            color: #f59e0b;
        }

        .commute-guide-body {
            color: #374151;
            line-height: 1.8;
            font-size: 0.975rem;
            white-space: pre-line;
        }

        /* Video Source Badge */
        .video-source-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.375rem 0.875rem;
            border-radius: 20px;
            font-size: 0.8125rem;
            font-weight: 600;
            margin-left: 0.75rem;
            vertical-align: middle;
        }

        .video-source-badge.youtube {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
        }

        .video-source-badge.uploaded {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
        }

        .video-source-badge i {
            font-size: 1rem;
        }

        /* Video Tour Container */
        .video-tour-container {
            margin-top: 1.5rem;
        }

        .video-wrapper {
            border-radius: 12px;
            overflow: hidden;
            background: #000;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
        }

        .video-wrapper iframe,
        .video-wrapper video {
            width: 100%;
            display: block;
            background: #000;
        }

        /* YouTube Video Responsive */
        .youtube-video {
            position: relative;
            padding-bottom: 56.25%;
            /* 16:9 aspect ratio */
            height: 0;
            overflow: hidden;
        }

        .youtube-video iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        /* Uploaded Video */
        .uploaded-video video {
            max-height: 500px;
            object-fit: contain;
        }

        /* Video Info Banner */
        .video-info-banner {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem 1.25rem;
            background: linear-gradient(135deg, #dbeafe, #bfdbfe);
            border: 2px solid #3b82f6;
            border-radius: 10px;
            margin-top: 1.5rem;
            color: #1e40af;
            font-weight: 500;
        }

        .video-info-banner i {
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .video-source-badge {
                display: flex;
                margin-left: 0;
                margin-top: 0.5rem;
                width: fit-content;
            }

            .video-wrapper iframe,
            .video-wrapper video {
                height: 250px !important;
            }

            .uploaded-video video {
                max-height: 300px;
            }
        }
    </style>
@endpush
