@extends('layouts.app')

@section('title', 'Story Mode - Dasmariñas Tourism')

@section('content')
    <!-- Hero Section -->
    <section class="story-hero">
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8">
                    <div class="story-hero-icon">
                        <i class='bx bx-book-reader'></i>
                    </div>
                    <h1 class="story-hero-title">Tourism Story Mode</h1>
                    <p class="story-hero-subtitle">
                        Experience a virtual tour of Dasmariñas City through an interactive story
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="section-padding">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    @if ($firstChapter)
                        <!-- Main Story Card -->
                        <div class="story-main-card">
                            <div class="story-main-header">
                                <i class='bx bx-map-alt'></i>
                                <h3>Interactive Virtual Tour</h3>
                            </div>

                            <p class="story-intro-text">
                                Embark on a guided journey through Dasmariñas City's most iconic locations.
                                Make choices that shape your experience and discover the rich history and
                                culture of our city.
                            </p>

                            <!-- Features Grid -->
                            <div class="row g-4 mb-5">
                                <div class="col-md-4">
                                    <div class="story-feature-card">
                                        <div class="feature-icon feature-primary">
                                            <i class='bx bx-map-pin'></i>
                                        </div>
                                        <h5 class="feature-title">Multiple Locations</h5>
                                        <p class="feature-text">Visit various attractions across the city</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="story-feature-card">
                                        <div class="feature-icon feature-success">
                                            <i class='bx bx-git-branch'></i>
                                        </div>
                                        <h5 class="feature-title">Your Choices Matter</h5>
                                        <p class="feature-text">Shape your own unique journey</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="story-feature-card">
                                        <div class="feature-icon feature-warning">
                                            <i class='bx bx-book-open'></i>
                                        </div>
                                        <h5 class="feature-title">Learn & Explore</h5>
                                        <p class="feature-text">Discover the city's rich heritage</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Area -->
                            <div class="story-action-area">
                                @auth
                                    @if (auth()->user()->email_verified_at)
                                        @if ($userProgress && !$userProgress->is_completed)
                                            <div class="alert-story-progress">
                                                <i class='bx bx-info-circle'></i>
                                                <span>You have an ongoing journey. Would you like to continue where you left
                                                    off?</span>
                                            </div>

                                            <div class="story-buttons-group">
                                                <a href="{{ route('story-mode.chapter', $userProgress->current_chapter_id) }}"
                                                    class="btn btn-primary btn-lg story-btn-main">
                                                    <i class='bx bx-play-circle me-2'></i>Continue Journey
                                                </a>
                                                <a href="{{ route('story-mode.start') }}"
                                                    class="btn btn-outline-secondary btn-lg story-btn-alt">
                                                    <i class='bx bx-refresh me-2'></i>Start Over
                                                </a>
                                            </div>
                                        @else
                                            <a href="{{ route('story-mode.start') }}"
                                                class="btn btn-primary btn-lg story-btn-single">
                                                <i class='bx bx-play-circle me-2'></i>Begin Your Journey
                                            </a>
                                        @endif
                                    @else
                                        <div class="alert-story-auth">
                                            <i class='bx bx-envelope-open'></i>
                                            <span>Please <a href="{{ route('auth.verify.show') }}" class="alert-link">verify
                                                    your email</a> to start your story mode journey and track your
                                                progress.</span>
                                        </div>

                                        <div class="story-buttons-group">
                                            <a href="{{ route('auth.verify.show') }}"
                                                class="btn btn-primary btn-lg story-btn-main">
                                                <i class='bx bx-envelope-open me-2'></i>Verify Email
                                            </a>
                                        </div>
                                    @endif
                                @else
                                    <div class="alert-story-auth">
                                        <i class='bx bx-lock-alt'></i>
                                        <span>Please <a href="{{ route('login') }}" class="alert-link">login</a> to start your
                                            story mode journey and track your progress.</span>
                                    </div>

                                    <div class="story-buttons-group">
                                        <a href="{{ route('login') }}" class="btn btn-primary btn-lg story-btn-main">
                                            <i class='bx bx-log-in me-2'></i>Login
                                        </a>
                                        <a href="{{ route('register') }}" class="btn btn-outline-primary btn-lg story-btn-alt">
                                            <i class='bx bx-user-plus me-2'></i>Register
                                        </a>
                                    </div>
                                @endauth
                            </div>
                        </div>

                        <!-- How It Works Section -->
                        <div class="story-info-card mt-4">
                            <h5 class="story-info-title">
                                <i class='bx bx-help-circle'></i> How It Works
                            </h5>
                            <div class="story-steps">
                                <div class="story-step">
                                    <div class="step-number">1</div>
                                    <div class="step-content">
                                        <h6>Begin Your Journey</h6>
                                        <p>Click the button above to start your adventure</p>
                                    </div>
                                </div>
                                <div class="story-step">
                                    <div class="step-number">2</div>
                                    <div class="step-content">
                                        <h6>Read & Explore</h6>
                                        <p>Immerse yourself in stories about each location</p>
                                    </div>
                                </div>
                                <div class="story-step">
                                    <div class="step-number">3</div>
                                    <div class="step-content">
                                        <h6>Make Decisions</h6>
                                        <p>Choose your path through different attractions</p>
                                    </div>
                                </div>
                                <div class="story-step">
                                    <div class="step-number">4</div>
                                    <div class="step-content">
                                        <h6>Learn History</h6>
                                        <p>Discover fascinating facts and cultural insights</p>
                                    </div>
                                </div>
                                <div class="story-step">
                                    <div class="step-number">5</div>
                                    <div class="step-content">
                                        <h6>Complete Your Tour</h6>
                                        <p>Finish at your own pace and revisit anytime</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="story-empty-state">
                            <i class='bx bx-book-reader'></i>
                            <h5>Story Mode Not Available</h5>
                            <p>The story mode is currently being set up. Please check back later!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
