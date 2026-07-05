@extends('layouts.app')

@section('title', $chapter->title . ' - Story Mode')

@section('content')
    <!-- Story Hero -->
    <section class="story-chapter-hero">
        @if ($chapter->background_image)
            <div class="chapter-hero-image"
                style="background-image: url('{{ asset('storage/' . $chapter->background_image) }}');">
                <div class="chapter-hero-overlay"></div>
            </div>
        @else
            <div class="chapter-hero-gradient"></div>
        @endif

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="chapter-hero-content">
                        <div class="chapter-badge">
                            <span class="chapter-number">{{ $chapter->chapter_number }}</span>
                            <span class="chapter-label">Chapter</span>
                        </div>
                        <h1 class="chapter-hero-title">{{ $chapter->title }}</h1>
                        @if ($chapter->attraction)
                            <p class="chapter-hero-location">
                                <i class='bx bx-map-pin'></i> {{ $chapter->attraction->name }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Story Content -->
    <section class="story-chapter-content">
        <div class="story-content-wrapper">
            <!-- Character Models (Left Side) -->
            @if ($chapter->character_models && count($chapter->character_models) > 0)
                <div class="character-models-container character-models-left">
                    @foreach ($chapter->character_models as $index => $characterModel)
                        @if ($index % 2 == 0)
                            <div class="character-model-sprite" data-aos="fade-right" data-aos-delay="{{ $index * 150 }}">
                                <img src="{{ asset('storage/' . $characterModel) }}" alt="Character {{ $index + 1 }}"
                                    loading="lazy">
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif

            <!-- Main Content Center -->
            <div class="story-center-content">
                <!-- Visual Novel Images Section (MOVED TO TOP) -->
                @if ($chapter->visual_images && count($chapter->visual_images) > 0)
                    <div class="visual-novel-section">
                        <div class="visual-images-horizontal">
                            @foreach ($chapter->visual_images as $index => $visualImage)
                                <div class="visual-image-card-horizontal" data-aos="fade-up"
                                    data-aos-delay="{{ $index * 100 }}">
                                    <div class="visual-image-wrapper-horizontal">
                                        <img src="{{ asset('storage/' . $visualImage) }}" alt="Scene {{ $index + 1 }}"
                                            loading="lazy">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Main Story Card -->
                <div class="story-card">
                    <div class="story-card-content">
                        <p class="story-text">{{ $chapter->content }}</p>
                    </div>
                </div>

                <!-- Walking Tour Video Section (ADD THIS NEW SECTION) -->
                @if ($chapter->attraction && ($chapter->attraction->youtube_video_url || $chapter->attraction->uploaded_video_path))
                    <div class="story-video-card">
                        <div class="story-video-header">
                            <div class="video-header-icon">
                                <i class='bx bx-video'></i>
                            </div>
                            <div class="video-header-content">
                                <h5 class="video-header-title">Virtual Walking Tour</h5>
                                <p class="video-header-subtitle">Experience
                                    {{ $chapter->attraction->name }} virtually
                                </p>
                            </div>
                            @if ($chapter->attraction->youtube_video_url)
                                <span class="video-type-badge youtube">
                                    <i class='bx bxl-youtube'></i> YouTube
                                </span>
                            @else
                                <span class="video-type-badge uploaded">
                                    <i class='bx bx-video'></i> Video
                                </span>
                            @endif
                        </div>

                        <div class="story-video-container">
                            @if ($chapter->attraction->youtube_video_url)
                                <!-- YouTube Video -->
                                <div class="story-video-wrapper youtube-video">
                                    <iframe width="100%" height="500"
                                        src="{{ $chapter->attraction->getYoutubeEmbedUrl() }}" frameborder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                        allowfullscreen>
                                    </iframe>
                                </div>
                            @elseif($chapter->attraction->uploaded_video_path)
                                <!-- Uploaded Video -->
                                <div class="story-video-wrapper uploaded-video">
                                    <video controls width="100%" height="500"
                                        poster="{{ $chapter->attraction->primaryImage ? asset('storage/' . $chapter->attraction->primaryImage->image_path) : '' }}">
                                        <source src="{{ asset('storage/' . $chapter->attraction->uploaded_video_path) }}"
                                            type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                </div>
                            @endif

                            <div class="story-video-info">
                                <i class='bx bx-info-circle'></i>
                                <span>Take a virtual tour and explore this location from your screen!</span>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Choices Section -->
                @if ($chapter->choices->count() > 0)
                    <div class="choices-section">
                        <h5 class="choices-title">
                            <i class='bx bx-git-branch'></i> What would you like to do?
                        </h5>

                        <div class="choices-grid">
                            @foreach ($chapter->choices as $index => $choice)
                                <form method="POST" action="{{ route('story-mode.choice') }}">
                                    @csrf
                                    <input type="hidden" name="choice_id" value="{{ $choice->id }}">

                                    <button type="submit" class="choice-button">
                                        <div class="choice-card {{ 'choice-card-' . (($index % 4) + 1) }}">
                                            <div class="choice-icon">
                                                <i class='bx bx-right-arrow-circle'></i>
                                            </div>
                                            <div class="choice-content">
                                                <p class="choice-text">{{ $choice->choice_text }}</p>
                                                @if ($choice->next_chapter_id)
                                                    <span class="choice-badge choice-continue">
                                                        <i class='bx bx-play-circle'></i> Continue
                                                    </span>
                                                @else
                                                    <span class="choice-badge choice-complete">
                                                        <i class='bx bx-check-circle'></i> Complete Tour
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </button>
                                </form>
                            @endforeach
                        </div>
                    </div>
                @else
                    <!-- Journey Complete -->
                    <div class="journey-complete">
                        <div class="complete-icon">
                            <i class='bx bx-trophy'></i>
                        </div>
                        <h4 class="complete-title">Journey Complete!</h4>
                        <p class="complete-text">You've completed this chapter of your tour.</p>
                        <a href="{{ route('story-mode.index') }}" class="btn btn-primary btn-lg">
                            <i class='bx bx-home me-2'></i>Return to Story Mode
                        </a>
                    </div>
                @endif

                <!-- Progress & Actions Bar -->
                <div class="story-footer-bar">
                    <div class="progress-info">
                        @if ($progress)
                            <div class="progress-stats">
                                <div class="stat-item">
                                    <i class='bx bx-book-open'></i>
                                    <span>Chapter {{ $chapter->chapter_number }}</span>
                                </div>
                                <div class="stat-item">
                                    <i class='bx bx-map'></i>
                                    <span>{{ count($progress->visited_chapters ?? []) }} Visited</span>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="story-actions">
                        <a href="{{ route('story-mode.index') }}" class="btn btn-outline-secondary">
                            <i class='bx bx-exit me-2'></i>Exit Story
                        </a>
                    </div>
                </div>

                <!-- Attraction Info Card -->
                @if ($chapter->attraction)
                    <div class="attraction-info-card">
                        <div class="attraction-info-header">
                            <i class='bx bx-info-circle'></i>
                            <h6>About This Location</h6>
                        </div>
                        <div class="attraction-info-body">
                            <h5 class="attraction-name">{{ $chapter->attraction->name }}</h5>
                            <p class="attraction-description">
                                {{ Str::limit($chapter->attraction->description, 180) }}
                            </p>
                            <a href="{{ route('attractions.show', $chapter->attraction->slug) }}" class="btn btn-primary"
                                target="_blank">
                                <i class='bx bx-link-external me-2'></i>View Full Details
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Character Models (Right Side) -->
            @if ($chapter->character_models && count($chapter->character_models) > 0)
                <div class="character-models-container character-models-right">
                    @foreach ($chapter->character_models as $index => $characterModel)
                        @if ($index % 2 != 0)
                            <div class="character-model-sprite" data-aos="fade-left"
                                data-aos-delay="{{ $index * 150 }}">
                                <img src="{{ asset('storage/' . $characterModel) }}" alt="Character {{ $index + 1 }}"
                                    loading="lazy">
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endsection

@push('styles')
    <style>
        /* Story Chapter Content Section */
        .story-chapter-content {
            background: linear-gradient(180deg, #f8fafc 0%, #ffffff 100%);
            padding: 3rem 0;
            min-height: calc(100vh - 400px);
        }

        /* Story Content Wrapper with Character Models */
        .story-content-wrapper {
            position: relative;
            display: grid;
            grid-template-columns: 450px 1fr 450px;
            gap: 3rem;
            max-width: 2100px;
            margin: 0 auto;
            padding: 0 1rem;
            align-items: start;
        }

        /* Character Models Containers */
        .character-models-container {
            position: sticky;
            top: 100px;
            display: flex;
            flex-direction: column;
            gap: 2rem;
            align-self: start;
            padding: 1rem 0;
        }

        .character-models-left {
            justify-content: flex-start;
            align-items: flex-end;
        }

        .character-models-right {
            justify-content: flex-start;
            align-items: flex-start;
        }

        /* Character Model Sprite - BIGGER THAN VISUAL IMAGES */
        .character-model-sprite {
            width: 100%;
            max-width: 450px;
            max-height: 1000px;
            filter: drop-shadow(0 12px 35px rgba(0, 0, 0, 0.25));
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.05), rgba(255, 255, 255, 0.02));
            border-radius: 16px;
            padding: 1.25rem;
        }

        .character-model-sprite:hover {
            transform: scale(1.03);
            filter: drop-shadow(0 18px 50px rgba(0, 0, 0, 0.3));
        }

        .character-model-sprite img {
            width: 100%;
            height: auto;
            max-height: 950px;
            object-fit: contain;
            display: block;
        }

        /* Center Content */
        .story-center-content {
            grid-column: 2;
            width: 100%;
            max-width: 900px;
            margin: 0 auto;
            position: relative;
            z-index: 10;
        }

        /* Story Card */
        .story-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
            margin-bottom: 2.5rem;
            overflow: hidden;
            border: 2px solid #e5e7eb;
        }

        .story-card-content {
            padding: 2.5rem;
        }

        .story-text {
            font-size: 1.125rem;
            line-height: 1.8;
            color: #374151;
            margin: 0;
            white-space: pre-wrap;
        }

        /* Visual Novel Section - Horizontal Layout */
        .visual-novel-section {
            background: linear-gradient(135deg, #166534, #15803d);
            border-radius: 16px;
            padding: 2rem 0;
            margin-bottom: 2.5rem;
            box-shadow: 0 8px 24px rgba(22, 101, 52, 0.2);
            overflow: hidden;
        }

        /* Horizontal Images Container */
        .visual-images-horizontal {
            display: flex;
            justify-content: center;
            align-items: flex-end;
            gap: 1.5rem;
            flex-wrap: wrap;
            padding: 0 1rem;
        }

        .visual-image-card-horizontal {
            position: relative;
            border-radius: 0;
            overflow: visible;
            background: transparent;
            box-shadow: none;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .visual-image-card-horizontal:hover {
            transform: translateY(-12px);
        }

        .visual-image-wrapper-horizontal {
            position: relative;
            width: 220px;
            height: 320px;
            overflow: hidden;
            border-radius: 12px;
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.3);
            border: 3px solid rgba(255, 255, 255, 0.2);
        }

        .visual-image-wrapper-horizontal img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .visual-image-card-horizontal:hover .visual-image-wrapper-horizontal img {
            transform: scale(1.05);
        }

        /* Story Video Card Styles (ADD THESE NEW STYLES) */
        .story-video-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
            margin-bottom: 2.5rem;
            overflow: hidden;
            border: 2px solid #f1f5f9;
            transition: all 0.3s ease;
        }

        .story-video-card:hover {
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.12);
            transform: translateY(-4px);
        }

        .story-video-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1.5rem 2rem;
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border-bottom: 2px solid #e2e8f0;
        }

        .video-header-icon {
            width: 56px;
            height: 56px;
            border-radius: 12px;
            background: linear-gradient(135deg, #1a7838, #27a345);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(26, 120, 56, 0.3);
        }

        .video-header-content {
            flex: 1;
        }

        .video-header-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0 0 0.25rem 0;
        }

        .video-header-subtitle {
            font-size: 0.9375rem;
            color: #64748b;
            margin: 0;
        }

        .video-type-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 700;
            flex-shrink: 0;
        }

        .video-type-badge.youtube {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
        }

        .video-type-badge.uploaded {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
        }

        .video-type-badge i {
            font-size: 1.125rem;
        }

        .story-video-container {
            padding: 2rem;
        }

        .story-video-wrapper {
            border-radius: 12px;
            overflow: hidden;
            background: #000;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
        }

        .story-video-wrapper iframe,
        .story-video-wrapper video {
            width: 100%;
            display: block;
            background: #000;
        }

        /* YouTube Video Responsive */
        .youtube-video {
            position: relative;
            padding-bottom: 56.25%;
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

        .story-video-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1.25rem 1.5rem;
            background: linear-gradient(135deg, #dbeafe, #bfdbfe);
            border: 2px solid #3b82f6;
            border-radius: 10px;
            margin-top: 1.5rem;
            color: #1e40af;
            font-weight: 500;
        }

        .story-video-info i {
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        /* Responsive Design */
        @media (max-width: 1900px) {
            .story-content-wrapper {
                grid-template-columns: 400px 1fr 400px;
                gap: 2.5rem;
            }

            .character-model-sprite {
                max-width: 380px;
                max-height: 900px;
            }

            .character-model-sprite img {
                max-height: 850px;
            }
        }

        @media (max-width: 1700px) {
            .story-content-wrapper {
                grid-template-columns: 350px 1fr 350px;
                gap: 2rem;
            }

            .character-model-sprite {
                max-width: 330px;
                max-height: 800px;
            }

            .character-model-sprite img {
                max-height: 750px;
            }
        }

        @media (max-width: 1500px) {
            .story-content-wrapper {
                grid-template-columns: 300px 1fr 300px;
                gap: 1.75rem;
            }

            .character-model-sprite {
                max-width: 280px;
                max-height: 580px;
                padding: 1rem;
            }

            .character-model-sprite img {
                max-height: 530px;
            }
        }

        @media (max-width: 1300px) {
            .story-content-wrapper {
                grid-template-columns: 260px 1fr 260px;
                gap: 1.5rem;
            }

            .character-model-sprite {
                max-width: 240px;
                max-height: 520px;
                padding: 0.75rem;
            }

            .character-model-sprite img {
                max-height: 470px;
            }
        }

        @media (max-width: 1200px) {
            .story-content-wrapper {
                grid-template-columns: 1fr;
                padding: 2rem 1rem;
            }

            .character-models-container {
                display: none;
            }

            .story-center-content {
                grid-column: 1;
            }
        }

        @media (max-width: 768px) {
            .story-chapter-content {
                padding: 2rem 0;
            }

            .story-content-wrapper {
                padding: 0 0.75rem;
            }

            .visual-novel-section {
                padding: 1.5rem 0;
                margin-bottom: 2rem;
            }

            .visual-images-horizontal {
                gap: 1rem;
                justify-content: center;
            }

            .visual-image-wrapper-horizontal {
                width: 160px;
                height: 240px;
            }

            .story-video-header {
                flex-wrap: wrap;
                padding: 1.25rem 1.5rem;
            }

            .video-type-badge {
                width: 100%;
                justify-content: center;
                margin-top: 0.5rem;
            }

            .story-video-container {
                padding: 1.5rem;
            }

            .story-video-wrapper iframe,
            .story-video-wrapper video {
                height: 250px !important;
            }

            .uploaded-video video {
                max-height: 300px;
            }
        }

        @media (max-width: 576px) {
            .visual-images-horizontal {
                flex-wrap: nowrap;
                overflow-x: auto;
                justify-content: flex-start;
                padding: 0 1rem;
                scroll-snap-type: x mandatory;
                -webkit-overflow-scrolling: touch;
            }

            .visual-image-card-horizontal {
                flex: 0 0 auto;
                scroll-snap-align: center;
            }

            .visual-image-wrapper-horizontal {
                width: 140px;
                height: 200px;
            }

            .video-header-icon {
                width: 48px;
                height: 48px;
                font-size: 1.5rem;
            }

            .video-header-title {
                font-size: 1.125rem;
            }
        }
    </style>
@endpush
