@extends('layouts.app')

@section('title', 'Home - Dasmariñas Tourism Information System')

@section('content')

<script>
        // Define the function globally before any links can call it
        window.showPolicyModal = function(type) {
            const titleElement = document.getElementById('policyModalTitle');
            const bodyContainer = document.getElementById('policyModalBody');
            
            // Re-query the modal instance inside the function to avoid null errors on repeated open/close
            const modalElement = document.getElementById('policyModal');
            let policyModalInstance = null;
            if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                 // Use getInstance or create new one if needed, but for simplicity, recreate instance if null
                 policyModalInstance = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);
            }
            
            let contentSourceElement;
            let titleText;

            // 1. Identify which content source element and title to use
            if (type === 'privacy') {
                contentSourceElement = document.getElementById('privacyContent');
                titleText = 'Privacy Policy';
            } else if (type === 'terms') {
                contentSourceElement = document.getElementById('termsContent');
                titleText = 'Terms of Use';
            } else if (type === 'faq') {
                contentSourceElement = document.getElementById('faqContent');
                titleText = 'FAQ & Support';
            } else {
                return;
            }
            
            // 2. Set the Title and Inject the content 
            if (contentSourceElement && bodyContainer) {
                titleElement.textContent = titleText;
                // Copy the inner HTML (the fully rendered policy content)
                bodyContainer.innerHTML = contentSourceElement.innerHTML; 
            } else {
                // Fallback for missing elements
                titleElement.textContent = 'Error Loading Content';
                bodyContainer.innerHTML = '<div class="alert alert-danger">Policy content failed to load (Source element missing).</div>';
            }

            // 3. Open the modal 
            if (policyModalInstance) {
                policyModalInstance.show();
            } else {
                 console.error("Bootstrap Modal Instance not initialized.");
            }
        }
    </script>

    <section class="hero-section hero-carousel-section">

        {{-- Carousel Background --}}
        @if ($heroSlides->count() > 0)
            <div class="hero-carousel-bg" id="heroCarousel">
                @foreach ($heroSlides as $index => $slide)
                    <div class="hero-carousel-slide {{ $index === 0 ? 'active' : '' }}"
                         style="background-image: url('{{ asset('storage/' . $slide->image_path) }}');"
                         data-index="{{ $index }}">
                    </div>
                @endforeach

                {{-- Carousel Controls --}}
                @if ($heroSlides->count() > 1)
                    <button class="hero-carousel-btn hero-carousel-prev" id="heroPrev" aria-label="Previous slide">
                        <i class='bx bx-chevron-left'></i>
                    </button>
                    <button class="hero-carousel-btn hero-carousel-next" id="heroNext" aria-label="Next slide">
                        <i class='bx bx-chevron-right'></i>
                    </button>
                    <div class="hero-carousel-dots" id="heroDots">
                        @foreach ($heroSlides as $index => $slide)
                            <button class="hero-dot {{ $index === 0 ? 'active' : '' }}" data-index="{{ $index }}" aria-label="Go to slide {{ $index + 1 }}"></button>
                        @endforeach
                    </div>
                @endif
            </div>
        @else
            {{-- Fallback gradient if no slides are set --}}
            <div class="hero-carousel-bg hero-gradient-fallback"></div>
        @endif

        {{-- Overlay --}}
        <div class="hero-overlay"></div>

        {{-- Content --}}
        <div class="container position-relative" style="z-index: 2;">
            <div class="row align-items-center g-5">
                <div class="col-lg-7 text-white">
                    <div class="hero-content">
                        <span class="hero-badge mb-3">Welcome to Dasmariñas</span>
                        <h1 class="hero-title mb-4">
                            Discover the Beauty of <span class="text-highlight">Dasmariñas City</span>
                        </h1>
                        <p class="hero-subtitle mb-4">
                            Explore historical sites, tourist destinations, and local businesses through our interactive
                            digital platform. Your journey starts here!
                        </p>
                        <div class="hero-actions d-flex gap-3 flex-wrap">
                            <a href="{{ route('attractions.index') }}" class="btn btn-light btn-lg shadow-lg">
                                <i class='bx bx-map-pin me-2'></i>Explore Attractions
                            </a>
                            <a href="{{ route('map.index') }}" class="btn btn-secondary btn-lg shadow-lg">
                                <i class='bx bx-map me-2'></i>View Map
                            </a>
                        </div>
                        <div class="hero-stats mt-5 d-none d-lg-block">
                            <div class="row g-4">
                                <div class="col-4">
                                    <h3 class="mb-0 fw-bold">{{ number_format($categories->sum('attractions_count')) }}</h3>
                                    <small class="text-white-50">Attractions</small>
                                </div>
                                <div class="col-4">
                                    <h3 class="mb-0 fw-bold">{{ number_format($businessesCount) }}</h3>
                                    <small class="text-white-50">Businesses</small>
                                </div>
                                <div class="col-4">
                                    <h3 class="mb-0 fw-bold">1</h3>
                                    <small class="text-white-50">Story Tour</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 text-center d-none d-lg-flex align-items-center justify-content-center">
                    <div class="hero-image-wrapper">
                        <div class="hero-circle-bg"></div>
                        <img src="{{ asset('assets/dasma-logo.png') }}" alt="Dasmariñas City Logo"
                            class="hero-logo-overlay img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="stats-section py-5">
        <div class="container">
            <div class="row g-4">
                
                
                {{-- 2. Total Attractions --}}
                <div class="col-md-6 col-sm-6">
                    <a href="{{ route('attractions.index') }}" class="stats-card-link-wrapper">
                        <div class="stats-card-modern">
                            <div class="stats-icon bg-success-light">
                                <i class='bx bx-building'></i>
                            </div>
                            <h3 class="stats-number">{{ $categories->sum('attractions_count') }}</h3>
                            <p class="stats-label">Total Attractions</p>
                        </div>
                    </a>
                </div>
                
                {{-- 3. Local Businesses (Dynamic Count and Clickable) --}}
                <div class="col-md-6 col-sm-6">
                    <a href="{{ route('attractions.index') }}" class="stats-card-link-wrapper">
                        <div class="stats-card-modern">
                            <div class="stats-icon bg-warning-light">
                                <i class='bx bx-store'></i>
                            </div>
                            {{-- ⬅️ UPDATED LOGIC: Using available business count variables --}}
                            <h3 class="stats-number">
                                @if (isset($businessesCount))
                                    {{ number_format($businessesCount) }}
                                @elseif (isset($businesses))
                                    {{ number_format($businesses->count()) }}
                                @else
                                    100+
                                @endif
                            </h3>
                            <p class="stats-label">Local Businesses</p>
                        </div>
                    </a>
                </div>
                
               
                
            </div>
        </div>
    </section>

    @if ($announcements->count())
        <section class="section-padding">
            <div class="container">
                <div class="section-header text-center mb-5">
                    <span class="section-badge">Latest Updates</span>
                    <h2 class="section-title">Announcements</h2>
                    <p class="section-subtitle">Stay updated with the latest news from Dasmariñas City</p>
                </div>

                <div class="row g-4">
                    @foreach ($announcements as $announcement)
                        <div class="col-md-4">
                            <div class="announcement-card h-100" style="cursor: pointer; transition: transform 0.3s;"
                                onclick="openAnnouncementModal({{ $announcement->id }})"
                                onmouseover="this.style.transform='translateY(-8px)'"
                                onmouseout="this.style.transform='translateY(0)'">
                                @if ($announcement->image)
                                    <div class="announcement-image">
                                        <img src="{{ asset('storage/' . $announcement->image) }}"
                                            alt="{{ $announcement->title }}" class="img-fluid rounded"
                                            style="width: 100%; height: 200px; object-fit: cover;">
                                    </div>
                                @endif
                                <div class="announcement-content p-4">
                                    <h5 class="announcement-title mb-3">{{ $announcement->title }}</h5>
                                    <p class="announcement-text">{{ Str::limit($announcement->content, 100) }}</p>
                                    <div class="announcement-date text-muted mt-3">
                                        <i class='bx bx-calendar'></i>
                                        {{ $announcement->published_at->format('F d, Y') }}
                                    </div>
                                    <div class="text-primary mt-3" style="font-weight: 500;">
                                        Read More <i class='bx bx-right-arrow-alt'></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <div class="modal fade" id="announcementModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="border: none; border-radius: 16px; overflow: hidden;">
                <div class="modal-header"
                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; padding: 1.5rem 2rem;">
                    <h5 class="modal-title text-white fw-bold" id="modalTitle"></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body" style="padding: 0;">
                    <div id="modalImage"
                        style="width: 100%; height: 350px; background-size: cover; background-position: center; display: none;">
                    </div>
                    <div style="padding: 2rem;">
                        <div class="d-flex align-items-center mb-3" style="color: #64748b; font-size: 0.875rem;">
                            <i class='bx bx-calendar me-2'></i>
                            <span id="modalDate"></span>
                        </div>
                        <div id="modalContent" style="color: #475569; line-height: 1.8; font-size: 1rem;"></div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 2px solid #f1f5f9; padding: 1.25rem 2rem;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        style="padding: 0.625rem 1.5rem; border-radius: 8px;">
                        <i class='bx bx-x me-1'></i>Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <section class="section-padding bg-light-subtle">
        <div class="container">
            <div class="section-header text-center mb-5">
                <span class="section-badge">Top Picks</span>
                <h2 class="section-title">Featured Attractions</h2>
                <p class="section-subtitle">Discover the most popular destinations in Dasmariñas City</p>
            </div>

            @if ($featuredAttractions->count() > 0)
                <div class="row g-4 mb-4">
                    @foreach ($featuredAttractions as $attraction)
                        <div class="col-lg-4 col-md-6">
                            <div class="attraction-card">
                                <div class="attraction-image">
                                    @if ($attraction->primaryImage)
                                        <img src="{{ asset('storage/' . $attraction->primaryImage->image_path) }}"
                                            alt="{{ $attraction->name }}">
                                    @else
                                        <div class="image-placeholder">
                                            <i class='bx bx-image'></i>
                                        </div>
                                    @endif
                                    <div class="attraction-badges">
                                        <span class="badge-category">{{ $attraction->category->name }}</span>
                                        @if ($attraction->is_historical_site)
                                            <span class="badge-historical">Historical</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="attraction-content">
                                    <h6 class="attraction-title">{{ $attraction->name }}</h6>
                                    <p class="attraction-description">{{ Str::limit($attraction->description, 100) }}</p>
                                    <div class="attraction-footer">
                                        <div class="attraction-rating">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i
                                                    class='bx {{ $i <= $attraction->getAverageRating() ? 'bxs-star' : 'bx-star' }}'></i>
                                            @endfor
                                            <span class="rating-count">({{ $attraction->getTotalReviews() }})</span>
                                        </div>
                                        <a href="{{ route('attractions.show', $attraction->slug) }}"
                                            class="btn-link-arrow">
                                            View Details <i class='bx bx-right-arrow-alt'></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="text-center">
                    <a href="{{ route('attractions.index') }}" class="btn btn-primary btn-lg">
                        View All Attractions <i class='bx bx-right-arrow-alt ms-2'></i>
                    </a>
                </div>
            @else
                <div class="empty-state">
                    <i class='bx bx-map-pin'></i>
                    <p>No featured attractions available yet.</p>
                </div>
            @endif
        </div>
    </section>

    @if ($historicalSites->count() > 0)
        <section class="section-padding">
            <div class="container">
                <div class="section-header text-center mb-5">
                    <span class="section-badge">Heritage</span>
                    <h2 class="section-title">Historical Sites</h2>
                    <p class="section-subtitle">Explore the rich historical heritage of Dasmariñas City</p>
                </div>

                <div class="row g-4">
                    @foreach ($historicalSites as $site)
                        <div class="col-lg-3 col-md-6">
                            <div class="heritage-card">
                                <div class="heritage-image">
                                    @if ($site->primaryImage)
                                        <img src="{{ asset('storage/' . $site->primaryImage->image_path) }}"
                                            alt="{{ $site->name }}">
                                    @else
                                        <div class="image-placeholder" style="height: 220px;">
                                            <i class='bx bxs-landmark'></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="heritage-content">
                                    <span class="heritage-badge">Historical</span>
                                    <h6 class="heritage-title">{{ $site->name }}</h6>
                                </div>
                                <a href="{{ route('attractions.show', $site->slug) }}" class="card-link-overlay"></a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif


    @if ($hotelListings->count() > 0)
    <section class="section-padding">
        <div class="container">
            <div class="section-header text-center mb-5">
                <span class="section-badge">Stay</span>
                <h2 class="section-title">Hotels &amp; Accommodations</h2>
                <p class="section-subtitle">Find the perfect place to stay in Dasmariñas City</p>
            </div>

            <div class="row g-4 mb-4">
                @foreach ($hotelListings as $hotel)
                    <div class="col-lg-4 col-md-6">
                        <div class="hotel-card" data-url="{{ route('attractions.show', $hotel->slug) }}">
                            <div class="hotel-image">
                                @if ($hotel->primaryImage)
                                    <img src="{{ asset('storage/' . $hotel->primaryImage->image_path) }}"
                                        alt="{{ $hotel->name }}">
                                @else
                                    <div class="image-placeholder">
                                        <i class='bx bx-buildings'></i>
                                    </div>
                                @endif
                                @if ($hotel->is_featured)
                                    <span class="hotel-verified-badge">
                                        <i class='bx bx-star'></i> Featured
                                    </span>
                                @endif
                            </div>
                            <div class="hotel-content">
                                <h6 class="hotel-title">{{ $hotel->name }}</h6>
                                @if ($hotel->address)
                                    <p class="hotel-location">
                                        <i class='bx bx-map-pin'></i>
                                        {{ Str::limit($hotel->address, 55) }}
                                    </p>
                                @endif
                                <div class="hotel-footer">
                                    <div class="hotel-rating">
                                        @php $avg = round($hotel->approved_reviews_avg_rating ?? 0); @endphp
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class='bx {{ $i <= $avg ? "bxs-star" : "bx-star" }}'></i>
                                        @endfor
                                        <span class="rating-count">({{ $hotel->approved_reviews_count }})</span>
                                    </div>
                                    <a href="{{ route('attractions.show', $hotel->slug) }}" class="btn-link-arrow">
                                        View Details <i class='bx bx-right-arrow-alt'></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center">
                @if ($hotelsCategory)
                    <a href="{{ route('attractions.index', ['category' => $hotelsCategory->id]) }}"
                        class="btn btn-primary btn-lg">
                        View All Hotels &amp; Accommodations <i class='bx bx-right-arrow-alt ms-2'></i>
                    </a>
                @else
                    <a href="{{ route('attractions.index') }}" class="btn btn-primary btn-lg">
                        View All Attractions <i class='bx bx-right-arrow-alt ms-2'></i>
                    </a>
                @endif
            </div>
        </div>
    </section>
    @endif

    <section class="section-padding bg-light-subtle">
        <div class="container">
            <div class="section-header text-center mb-5">
                <span class="section-badge">Browse</span>
                <h2 class="section-title">Explore by Category</h2>
                <p class="section-subtitle">Find attractions based on your interests</p>
            </div>

            <div class="row g-4">
                @foreach ($categories as $category)
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <a href="{{ route('attractions.index', ['category' => $category->id]) }}"
                            class="category-card-link">
                            <div class="category-card-modern">
                                <div class="category-icon-wrapper">
                                    <i class='bx {{ $category->icon ?? 'bx-category' }}'></i>
                                </div>
                                <h5 class="category-name">{{ $category->name }}</h5>
                                <p class="category-count">{{ $category->attractions_count }} attractions</p>
                                <div class="category-arrow">
                                    <i class='bx bx-right-arrow-alt'></i>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    @if ($activePromotions->count() > 0)
        <section class="section-padding">
            <div class="container">
                <div class="section-header text-center mb-5">
                    <span class="section-badge">Special Offers</span>
                    <h2 class="section-title">Current Promotions</h2>
                    <p class="section-subtitle">Don't miss out on these exciting offers!</p>
                </div>

                <div class="row g-4">
                    @foreach ($activePromotions as $promotion)
                        <div class="col-lg-4 col-md-6">
                            <a href="@if ($promotion->promotable_type === 'App\Models\Business') {{ route('businesses.show', $promotion->promotable->slug) }}@else{{ route('attractions.show', $promotion->promotable->slug) }} @endif"
                                class="text-decoration-none">
                                <div class="promotion-card" style="cursor: pointer; transition: transform 0.3s;"
                                    onmouseover="this.style.transform='translateY(-5px)'"
                                    onmouseout="this.style.transform='translateY(0)'">
                                    @if ($promotion->image)
                                        <div class="promotion-image">
                                            <img src="{{ asset('storage/' . $promotion->image) }}"
                                                alt="{{ $promotion->title }}">
                                            <span class="promotion-badge">Active</span>
                                        </div>
                                    @endif
                                    <div class="promotion-content">
                                        <h5 class="promotion-title">{{ $promotion->title }}</h5>
                                        <p class="promotion-description">{{ Str::limit($promotion->description, 100) }}
                                        </p>

                                        @if ($promotion->promotable)
                                            <div class="promotion-business mb-2">
                                                <i class='bx bx-store-alt me-1'></i>
                                                <small class="text-muted">{{ $promotion->promotable->name }}</small>
                                            </div>
                                        @endif

                                        <div class="promotion-footer">
                                            <i class='bx bx-time-five'></i>
                                            <small>Valid until {{ $promotion->end_date->format('M d, Y') }}</small>
                                        </div>

                                        <div class="text-primary mt-2" style="font-weight: 500; font-size: 0.875rem;">
                                            View Details <i class='bx bx-right-arrow-alt'></i>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif


    <section class="cta-section">
        <div class="cta-background"></div>
        <div class="container position-relative" style="z-index: 2;">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center text-white">
                    <h2 class="cta-title mb-4">Ready to Explore Dasmariñas?</h2>
                    <p class="cta-subtitle mb-5">Start your virtual tour of Dasmariñas City today and discover its hidden
                        gems!</p>
                    <div class="cta-buttons d-flex justify-content-center gap-3 flex-wrap">
                        <a href="{{ route('story-mode.index') }}" class="btn btn-light btn-lg px-5">
                            <i class='bx bx-book-reader me-2'></i>Start Story Tour
                        </a>
                        @guest
                            <a href="{{ route('register') }}" class="btn btn-secondary btn-lg px-5">
                                <i class='bx bx-user-plus me-2'></i>Create Account
                            </a>
                        @endguest
                        <a href="#" onclick="showPolicyModal('faq')" data-bs-toggle="modal" data-bs-target="#policyModal" class="btn btn-light btn-lg px-5">
                            <i class='bx bx-store-alt me-2'></i>Frequently Asked Questions (FAQ)
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
    <style>
        /* ── Hero Carousel ─────────────────────────────────── */
        .hero-carousel-section {
            position: relative;
            overflow: hidden;
        }

        .hero-carousel-bg {
            position: absolute;
            inset: 0;
            z-index: 0;
        }

        .hero-gradient-fallback {
            background: linear-gradient(135deg, var(--dasma-green) 0%, var(--dasma-green-dark) 50%, var(--dasma-blue) 100%);
        }

        .hero-carousel-slide {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center;
            opacity: 0;
            transition: opacity 1s ease-in-out;
        }

        .hero-carousel-slide.active {
            opacity: 1;
        }

        .hero-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(
                135deg,
                rgba(10, 60, 25, 0.72) 0%,
                rgba(10, 40, 20, 0.55) 50%,
                rgba(0, 50, 80, 0.60) 100%
            );
            z-index: 1;
        }

        /* Carousel nav buttons */
        .hero-carousel-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 3;
            background: rgba(255,255,255,0.18);
            border: 2px solid rgba(255,255,255,0.5);
            color: #fff;
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            cursor: pointer;
            transition: background 0.2s, border-color 0.2s;
            backdrop-filter: blur(4px);
        }

        .hero-carousel-btn:hover {
            background: rgba(255,255,255,0.35);
            border-color: #fff;
        }

        .hero-carousel-prev { left: 1.25rem; }
        .hero-carousel-next { right: 1.25rem; }

        /* Dots */
        .hero-carousel-dots {
            position: absolute;
            bottom: 1.5rem;
            left: 50%;
            transform: translateX(-50%);
            z-index: 3;
            display: flex;
            gap: 0.5rem;
        }

        .hero-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            border: 2px solid rgba(255,255,255,0.7);
            background: transparent;
            cursor: pointer;
            transition: background 0.2s, border-color 0.2s;
            padding: 0;
        }

        .hero-dot.active {
            background: #fff;
            border-color: #fff;
        }

        /* Logo overlay — restored float animation */
        .hero-logo-overlay {
            max-width: 380px;
            filter: drop-shadow(0 20px 40px rgba(0, 0, 0, 0.45));
            animation: float 6s ease-in-out infinite;
        }

        /* ── Clickable Cards ─────────────────────────────── */
        .stats-card-link-wrapper {
            display: block;
            text-decoration: none;
            color: inherit;
        }

        .stats-card-link-wrapper:hover .stats-card-modern {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }

        .stats-card-modern {
            transition: all 0.3s ease;
        }
        
        .announcement-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .announcement-image img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .announcement-title {
            font-weight: 600;
            color: #1e293b;
        }

        .announcement-text {
            color: #64748b;
            line-height: 1.6;
        }

        .announcement-date {
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* ── Hotels & Accommodations Cards ──────────────────── */
        .hotel-card {
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 2px 16px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .hotel-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.14);
        }

        .hotel-image {
            position: relative;
            height: 210px;
            overflow: hidden;
            background: #f1f5f9;
        }

        .hotel-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .hotel-card:hover .hotel-image img {
            transform: scale(1.05);
        }

        .hotel-image .image-placeholder {
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: #94a3b8;
        }

        .hotel-verified-badge {
            position: absolute;
            top: 0.75rem;
            left: 0.75rem;
            background: rgba(16, 185, 129, 0.92);
            color: #fff;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.25rem 0.6rem;
            border-radius: 20px;
            display: flex;
            align-items: center;
            gap: 0.25rem;
            backdrop-filter: blur(4px);
        }

        .hotel-content {
            padding: 1.25rem;
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
        }

        .hotel-title {
            font-weight: 700;
            color: #1e293b;
            margin: 0;
            font-size: 1rem;
            line-height: 1.4;
        }

        .hotel-location {
            color: #64748b;
            font-size: 0.825rem;
            margin: 0;
            display: flex;
            align-items: flex-start;
            gap: 0.3rem;
        }

        .hotel-location i {
            color: var(--dasma-green, #1a7838);
            font-size: 1rem;
            flex-shrink: 0;
            margin-top: 1px;
        }

        .hotel-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: auto;
            padding-top: 0.75rem;
            border-top: 1px solid #f1f5f9;
        }

        .hotel-rating {
            display: flex;
            align-items: center;
            gap: 0.15rem;
        }

        .hotel-rating i {
            color: #f59e0b;
            font-size: 0.9rem;
        }

        .hotel-rating .rating-count {
            color: #94a3b8;
            font-size: 0.8rem;
            margin-left: 0.2rem;
        }

    </style>
@endpush

@push('scripts')
    <script>
    (function () {
        const slides = document.querySelectorAll('.hero-carousel-slide');
        const dots   = document.querySelectorAll('.hero-dot');
        if (!slides.length) return;

        let current = 0;
        let timer;
        const INTERVAL = 5000;

        function goTo(n) {
            slides[current].classList.remove('active');
            if (dots[current]) dots[current].classList.remove('active');
            current = (n + slides.length) % slides.length;
            slides[current].classList.add('active');
            if (dots[current]) dots[current].classList.add('active');
        }

        function next() { goTo(current + 1); }
        function prev() { goTo(current - 1); }

        function startAuto() { timer = setInterval(next, INTERVAL); }
        function stopAuto()  { clearInterval(timer); }

        document.getElementById('heroNext')?.addEventListener('click', () => { stopAuto(); next(); startAuto(); });
        document.getElementById('heroPrev')?.addEventListener('click', () => { stopAuto(); prev(); startAuto(); });

        dots.forEach((dot, i) => {
            dot.addEventListener('click', () => { stopAuto(); goTo(i); startAuto(); });
        });

        startAuto();
    })();
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Store announcement data
            window.announcements = @json($announcements);
            window.currentModal = null; // Store modal instance globally
        });

        function openAnnouncementModal(id) {
            if (!window.announcements) {
                console.error('Announcements data not loaded');
                return;
            }

            const announcement = window.announcements.find(a => a.id === id);
            if (!announcement) {
                console.error('Announcement not found:', id);
                return;
            }

            // Set modal content
            document.getElementById('modalTitle').textContent = announcement.title;
            document.getElementById('modalContent').textContent = announcement.content;
            document.getElementById('modalDate').textContent = new Date(announcement.published_at).toLocaleDateString(
                'en-US', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });

            // Set image if exists
            const modalImage = document.getElementById('modalImage');
            if (announcement.image) {
                modalImage.style.backgroundImage = `url(/storage/${announcement.image})`;
                modalImage.style.display = 'block';
            } else {
                modalImage.style.display = 'none';
            }

            // Show modal
            const modalElement = document.getElementById('announcementModal');

            // Try Bootstrap 5 first
            if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                // Store modal instance globally so Bootstrap can handle closing
                window.currentModal = new bootstrap.Modal(modalElement);
                window.currentModal.show();
            }
            // Try jQuery/Bootstrap 4 fallback
            else if (typeof $ !== 'undefined' && $.fn.modal) {
                $(modalElement).modal('show');
            }
            // Manual fallback
            else {
                modalElement.classList.add('show');
                modalElement.style.display = 'block';
                modalElement.setAttribute('aria-modal', 'true');
                modalElement.setAttribute('role', 'dialog');
                document.body.classList.add('modal-open');

                // Create backdrop
                const backdrop = document.createElement('div');
                backdrop.className = 'modal-backdrop fade show';
                backdrop.id = 'customBackdrop';
                document.body.appendChild(backdrop);

                // Function to close modal
                const closeModal = function() {
                    modalElement.classList.remove('show');
                    modalElement.style.display = 'none';
                    document.body.classList.remove('modal-open');
                    document.getElementById('customBackdrop')?.remove();
                };

                // Close handlers for BOTH buttons
                modalElement.querySelectorAll('.btn-close, [data-bs-dismiss="modal"]').forEach(function(btn) {
                    btn.addEventListener('click', closeModal);
                });

                // Close on backdrop click
                backdrop.addEventListener('click', closeModal);
            }
        }
    </script>
    

    <script>
        document.querySelectorAll('.hotel-card[data-url]').forEach(function (card) {
            card.addEventListener('click', function (e) {
                if (e.target.closest('a, button, form')) return;
                window.location.href = card.dataset.url;
            });
        });
    </script>
@endpush
