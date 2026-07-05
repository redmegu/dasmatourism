@extends('layouts.app')

@section('title', $business->name . ' - Business Directory')

@section('content')
    <!-- Hero Image Section -->
    <section class="attraction-hero">
        @if ($business->images->count() > 0)
            <div id="businessCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    @foreach ($business->images as $index => $image)
                        <button type="button" data-bs-target="#businessCarousel" data-bs-slide-to="{{ $index }}"
                            class="{{ $index === 0 ? 'active' : '' }}"></button>
                    @endforeach
                </div>
                <div class="carousel-inner">
                    @foreach ($business->images as $index => $image)
                        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                            <img src="{{ asset('storage/' . $image->image_path) }}" class="d-block w-100"
                                alt="{{ $image->caption ?? $business->name }}">
                            <div class="carousel-overlay"></div>
                            @if ($image->caption)
                                <div class="carousel-caption-custom">
                                    <p>{{ $image->caption }}</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
                @if ($business->images->count() > 1)
                    <button class="carousel-control-prev" type="button" data-bs-target="#businessCarousel"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#businessCarousel"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                @endif
            </div>
        @else
            <div class="hero-placeholder">
                <i class='bx bx-store'></i>
            </div>
        @endif

        <!-- Breadcrumb Overlay -->
        <div class="breadcrumb-overlay">
            <div class="container">
                <a href="{{ route('businesses.index') }}" class="btn-back-hero">
                    <i class='bx bx-arrow-back'></i> Back to Directory
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
                            <span class="badge badge-category-large">{{ $business->category->name }}</span>
                            @if ($business->is_verified)
                                <span class="badge-verified-large">
                                    <i class='bx bx-check-circle'></i> Verified Business
                                </span>
                            @endif
                        </div>

                        <h1 class="attraction-title">{{ $business->name }}</h1>

                        <div class="attraction-meta">
                            <div class="meta-item">
                                <i class='bx bx-map-pin'></i>
                                <span>{{ $business->address }}</span>
                            </div>
                            <div class="meta-item">
                                <div class="rating-display">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i
                                            class='bx {{ $i <= $business->getAverageRating() ? 'bxs-star' : 'bx-star' }}'></i>
                                    @endfor
                                    <span class="rating-text">
                                        {{ number_format($business->getAverageRating(), 1) }}
                                        ({{ $business->getTotalReviews() }} reviews)
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description Card -->
                    <div class="content-card mb-4">
                        <h3 class="content-card-title">
                            <i class='bx bx-info-circle'></i> About This Business
                        </h3>
                        <p class="content-card-text">{{ $business->description }}</p>
                    </div>

                    <!-- Services Card -->
                    @if ($business->services && count($business->services) > 0)
                        <div class="content-card mb-4">
                            <h3 class="content-card-title">
                                <i class='bx bx-list-check'></i> Services Offered
                            </h3>
                            <div class="services-grid">
                                @foreach ($business->services as $service)
                                    <div class="service-item">
                                        <i class='bx bx-check-circle'></i>
                                        <span>{{ $service }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Operating Hours Card -->
                    @if ($business->operating_hours)
                        <div class="content-card mb-4">
                            <h3 class="content-card-title">
                                <i class='bx bx-time-five'></i> Operating Hours
                            </h3>
                            <p class="content-card-text">{{ $business->operating_hours }}</p>
                        </div>
                    @endif

                    <!-- Active Promotions -->
                    @if ($activePromotions->count() > 0)
                        <div class="content-card mb-4">
                            <h3 class="content-card-title">
                                <i class='bx bx-purchase-tag'></i> Current Promotions
                            </h3>

                            <div class="row g-3">
                                @foreach ($activePromotions as $promotion)
                                    <div class="col-md-6">
                                        <div class="promotion-item"
                                            style="background: linear-gradient(135deg, #667eea22 0%, #764ba222 100%); border-radius: 12px; padding: 1.25rem; border: 2px solid #667eea44;">
                                            @if ($promotion->image)
                                                <div class="promotion-image mb-3">
                                                    <img src="{{ asset('storage/' . $promotion->image) }}"
                                                        alt="{{ $promotion->title }}"
                                                        style="width: 100%; height: 180px; object-fit: cover; border-radius: 8px;">
                                                </div>
                                            @endif

                                            <div class="promotion-details">
                                                <h5 style="color: #667eea; font-weight: 600; margin-bottom: 0.5rem;">
                                                    {{ $promotion->title }}
                                                </h5>
                                                <p style="color: #64748b; margin-bottom: 0.75rem; line-height: 1.6;">
                                                    {{ $promotion->description }}
                                                </p>

                                                <div class="d-flex align-items-center gap-3 flex-wrap">
                                                    <span class="badge"
                                                        style="background: #667eea; color: white; padding: 0.5rem 0.75rem; border-radius: 6px;">
                                                        <i class='bx bx-time-five me-1'></i>
                                                        Valid until {{ $promotion->end_date->format('M d, Y') }}
                                                    </span>

                                                    @if ($promotion->discount_percentage)
                                                        <span class="badge"
                                                            style="background: #10b981; color: white; padding: 0.5rem 0.75rem; border-radius: 6px;">
                                                            <i class='bx bx-purchase-tag me-1'></i>
                                                            {{ $promotion->discount_percentage }}% OFF
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
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
                                        <input type="hidden" name="reviewable_type" value="App\Models\Business">
                                        <input type="hidden" name="reviewable_id" value="{{ $business->id }}">
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
                                            <textarea name="comment" class="form-control" rows="4"
                                                placeholder="Share your thoughts about this business..."></textarea>
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
                            @forelse($business->approvedReviews as $review)
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
                    <!-- Contact Information Card -->
                    <div class="sidebar-card mb-4">
                        <h5 class="sidebar-card-title">
                            <i class='bx bx-phone-call'></i> Contact Information
                        </h5>

                        <div class="info-item">
                            <div class="info-icon">
                                <i class='bx bx-phone'></i>
                            </div>
                            <div class="info-content">
                                <span class="info-label">Phone</span>
                                <a href="tel:{{ $business->contact_number }}" class="info-value">
                                    {{ $business->contact_number }}
                                </a>
                            </div>
                        </div>

                        @if ($business->email)
                            <div class="info-item">
                                <div class="info-icon">
                                    <i class='bx bx-envelope'></i>
                                </div>
                                <div class="info-content">
                                    <span class="info-label">Email</span>
                                    <a href="mailto:{{ $business->email }}" class="info-value">
                                        {{ $business->email }}
                                    </a>
                                </div>
                            </div>
                        @endif

                        @if ($business->website)
                            <div class="info-item">
                                <div class="info-icon">
                                    <i class='bx bx-globe'></i>
                                </div>
                                <div class="info-content">
                                    <span class="info-label">Website</span>
                                    <a href="{{ $business->website }}" target="_blank" class="info-value">
                                        Visit Website <i class='bx bx-link-external'></i>
                                    </a>
                                </div>
                            </div>
                        @endif

                        @if ($business->google_maps_link)
                            <div class="info-item">
                                <div class="info-icon">
                                    <i class='bx bx-map'></i>
                                </div>
                                <div class="info-content">
                                    <span class="info-label">Get Directions</span>
                                    <a href="{{ $business->google_maps_link }}" target="_blank"
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
                                <span class="info-value">{{ number_format($business->views) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Business Owner Card -->
                    <div class="sidebar-card mb-4">
                        <h5 class="sidebar-card-title">
                            <i class='bx bx-user-circle'></i> Business Owner
                        </h5>
                        <div class="owner-info">
                            <div class="owner-avatar">
                                {{ substr($business->owner->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="owner-name">{{ $business->owner->name }}</p>
                                <small class="text-muted">Verified Owner</small>
                            </div>
                        </div>
                    </div>


                </div>
            </div>

            <!-- Related Businesses -->
            @if ($relatedBusinesses->count() > 0)
                <div class="related-section mt-5">
                    <h3 class="related-title">Similar Businesses</h3>
                    <div class="row g-4">
                        @foreach ($relatedBusinesses as $related)
                            <div class="col-lg-3 col-md-6">
                                <div class="related-card">
                                    <div class="related-image">
                                        @if ($related->primaryImage)
                                            <img src="{{ asset('storage/' . $related->primaryImage->image_path) }}"
                                                alt="{{ $related->name }}">
                                        @else
                                            <div class="image-placeholder">
                                                <i class='bx bx-store'></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="related-content">
                                        <h6 class="related-name">{{ $related->name }}</h6>
                                        <p class="related-category">{{ $related->category->name }}</p>
                                    </div>
                                    <a href="{{ route('businesses.show', $related->slug) }}"
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
