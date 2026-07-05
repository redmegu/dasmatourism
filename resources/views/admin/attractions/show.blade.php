@extends('layouts.admin')

@section('page-title', 'Attraction Details')

@section('content')
    <!-- Enhanced Page Header -->
    <div class="admin-detail-header mb-4">
        <div class="admin-detail-header-content">
            <div>
                <a href="{{ route('admin.attractions.index') }}" class="admin-breadcrumb-link">
                    <i class='bx bx-chevron-left'></i> Back to Attractions
                </a>
                <h2 class="admin-detail-title">{{ $attraction->name }}</h2>
                <div class="admin-detail-meta">
                    <span><i class='bx bx-calendar'></i> Created {{ $attraction->created_at->format('M d, Y') }}</span>
                    <span><i class='bx bx-user'></i> By {{ $attraction->creator->name }}</span>
                    <span><i class='bx bx-show'></i> {{ number_format($attraction->views) }} views</span>
                </div>
            </div>
            <div class="admin-detail-badges">
                <span class="admin-status-badge-large {{ $attraction->status }}">
                    @if ($attraction->status === 'approved')
                        <i class='bx bx-check-circle'></i>
                    @elseif($attraction->status === 'pending')
                        <i class='bx bx-time'></i>
                    @else
                        <i class='bx bx-x-circle'></i>
                    @endif
                    {{ ucfirst($attraction->status) }}
                </span>
                @if ($attraction->is_featured)
                    <span class="admin-badge-large featured">
                        <i class='bx bxs-star'></i> Featured
                    </span>
                @endif
                @if ($attraction->is_historical_site)
                    <span class="admin-badge-large historical">
                        <i class='bx bx-landmark'></i> Historical
                    </span>
                @endif
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Description -->
            <div class="admin-detail-card">
                <div class="admin-detail-card-header">
                    <div class="admin-detail-card-icon primary">
                        <i class='bx bx-message-square-detail'></i>
                    </div>
                    <h5>Description</h5>
                </div>
                <div class="admin-detail-card-body">
                    <p class="admin-description-text">{{ $attraction->description }}</p>
                </div>
            </div>

            <!-- Basic Information -->
            <div class="admin-detail-card">
                <div class="admin-detail-card-header">
                    <div class="admin-detail-card-icon success">
                        <i class='bx bx-info-circle'></i>
                    </div>
                    <h5>Basic Information</h5>
                </div>
                <div class="admin-detail-card-body">
                    <div class="admin-info-grid">
                        <div class="admin-info-item">
                            <div class="admin-info-icon primary">
                                <i class='bx bx-category'></i>
                            </div>
                            <div>
                                <label>Category</label>
                                <p>{{ $attraction->category->name }}</p>
                            </div>
                        </div>

                        <div class="admin-info-item">
                            <div class="admin-info-icon warning">
                                <i class='bx bx-money'></i>
                            </div>
                            <div>
                                <label>Entrance Fee</label>
                                <p>{{ $attraction->entrance_fee ? 'PHP ' . number_format($attraction->entrance_fee, 2) : 'Free' }}
                                </p>
                            </div>
                        </div>

                        <div class="admin-info-item">
                            <div class="admin-info-icon info">
                                <i class='bx bx-show'></i>
                            </div>
                            <div>
                                <label>Total Views</label>
                                <p>{{ number_format($attraction->views) }}</p>
                            </div>
                        </div>

                        <div class="admin-info-item">
                            <div class="admin-info-icon success">
                                <i class='bx bxs-star'></i>
                            </div>
                            <div>
                                <label>Average Rating</label>
                                <p>{{ number_format($attraction->getAverageRating(), 1) }} / 5.0</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Location -->
            <div class="admin-detail-card">
                <div class="admin-detail-card-header">
                    <div class="admin-detail-card-icon info">
                        <i class='bx bx-map'></i>
                    </div>
                    <h5>Location Information</h5>
                </div>
                <div class="admin-detail-card-body">
                    <div class="admin-location-display">
                        <div class="admin-location-icon">
                            <i class='bx bx-map-pin'></i>
                        </div>
                        <div class="admin-location-details">
                            <h6>{{ $attraction->address }}</h6>
                            <p>
                                <i class='bx bx-crosshair'></i>
                                Coordinates: {{ $attraction->latitude }}, {{ $attraction->longitude }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Details -->
            @if ($attraction->contact_number || $attraction->email || $attraction->website)
                <div class="admin-detail-card">
                    <div class="admin-detail-card-header">
                        <div class="admin-detail-card-icon warning">
                            <i class='bx bx-phone'></i>
                        </div>
                        <h5>Contact Information</h5>
                    </div>
                    <div class="admin-detail-card-body">
                        <div class="admin-contact-grid">
                            @if ($attraction->contact_number)
                                <div class="admin-contact-item">
                                    <i class='bx bx-phone'></i>
                                    <div>
                                        <label>Phone</label>
                                        <p>{{ $attraction->contact_number }}</p>
                                    </div>
                                </div>
                            @endif

                            @if ($attraction->email)
                                <div class="admin-contact-item">
                                    <i class='bx bx-envelope'></i>
                                    <div>
                                        <label>Email</label>
                                        <p><a href="mailto:{{ $attraction->email }}">{{ $attraction->email }}</a></p>
                                    </div>
                                </div>
                            @endif

                            @if ($attraction->website)
                                <div class="admin-contact-item">
                                    <i class='bx bx-globe'></i>
                                    <div>
                                        <label>Website</label>
                                        <p><a href="{{ $attraction->website }}" target="_blank">Visit Website <i
                                                    class='bx bx-link-external'></i></a></p>
                                    </div>
                                </div>
                            @endif

                            @if ($attraction->google_maps_link)
                                <div class="admin-contact-item">
                                    <i class='bx bx-map text-primary'></i>
                                    <div>
                                        <label>Google Maps</label>
                                        <p><a href="{{ $attraction->google_maps_link }}" target="_blank"
                                                class="text-primary">
                                                <i class='bx bx-navigation'></i> Open Directions</a></p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        @if ($attraction->facilities)
                            <div class="admin-facilities-section">
                                <label><i class='bx bx-building'></i> Available Facilities</label>
                                <p>{{ $attraction->facilities }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Images Gallery -->
            @if ($attraction->images->count() > 0)
                <div class="admin-detail-card">
                    <div class="admin-detail-card-header">
                        <div class="admin-detail-card-icon secondary">
                            <i class='bx bx-image'></i>
                        </div>
                        <h5>Photo Gallery</h5>
                        <span class="admin-count-badge">{{ $attraction->images->count() }}
                            {{ Str::plural('photo', $attraction->images->count()) }}</span>
                    </div>
                    <div class="admin-detail-card-body">
                        <div class="admin-gallery-grid">
                            @foreach ($attraction->images as $image)
                                <div class="admin-gallery-item-enhanced">
                                    <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $attraction->name }}">
                                    @if ($image->is_primary)
                                        <span class="admin-primary-badge">Primary</span>
                                    @endif
                                    <div class="admin-gallery-overlay">
                                        <button class="admin-gallery-view-btn"
                                            onclick="viewImage('{{ asset('storage/' . $image->image_path) }}')">
                                            <i class='bx bx-search-alt'></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Walking Tour Video (ADD THIS NEW CARD) -->
            @if ($attraction->youtube_video_url || $attraction->uploaded_video_path)
                <div class="admin-detail-card">
                    <div class="admin-detail-card-header">
                        <div class="admin-detail-card-icon" style="background: linear-gradient(135deg, #ef4444, #dc2626);">
                            <i class='bx bx-video'></i>
                        </div>
                        <h5>Walking Tour Video</h5>
                        @if ($attraction->youtube_video_url)
                            <span class="admin-video-type-badge youtube">
                                <i class='bx bxl-youtube'></i> YouTube
                            </span>
                        @else
                            <span class="admin-video-type-badge uploaded">
                                <i class='bx bx-video'></i> Uploaded
                            </span>
                        @endif
                    </div>
                    <div class="admin-detail-card-body">
                        @if ($attraction->youtube_video_url)
                            <!-- YouTube Video Display -->
                            <div class="admin-video-display youtube-video">
                                <div class="admin-video-container">
                                    <iframe width="100%" height="450" src="{{ $attraction->getYoutubeEmbedUrl() }}"
                                        frameborder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen>
                                    </iframe>
                                </div>
                                <div class="admin-video-info">
                                    <div class="admin-video-info-item">
                                        <i class='bx bxl-youtube'></i>
                                        <div>
                                            <label>Source</label>
                                            <p>YouTube</p>
                                        </div>
                                    </div>
                                    <div class="admin-video-info-item">
                                        <i class='bx bx-link'></i>
                                        <div>
                                            <label>URL</label>
                                            <p><a href="{{ $attraction->youtube_video_url }}"
                                                    target="_blank">{{ Str::limit($attraction->youtube_video_url, 50) }}</a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @elseif($attraction->uploaded_video_path)
                            <!-- Uploaded Video Display -->
                            <div class="admin-video-display uploaded-video">
                                <div class="admin-video-container">
                                    <video controls width="100%" height="450" controlsList="nodownload">
                                        <source src="{{ asset('storage/' . $attraction->uploaded_video_path) }}"
                                            type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                </div>
                                <div class="admin-video-info">
                                    <div class="admin-video-info-item">
                                        <i class='bx bx-video'></i>
                                        <div>
                                            <label>Source</label>
                                            <p>Uploaded Video</p>
                                        </div>
                                    </div>
                                    <div class="admin-video-info-item">
                                        <i class='bx bx-file'></i>
                                        <div>
                                            <label>File</label>
                                            <p>{{ basename($attraction->uploaded_video_path) }}</p>
                                        </div>
                                    </div>
                                    <div class="admin-video-info-item">
                                        <i class='bx bx-download'></i>
                                        <div>
                                            <label>Actions</label>
                                            <a href="{{ asset('storage/' . $attraction->uploaded_video_path) }}" download
                                                class="admin-video-download-btn">
                                                <i class='bx bx-download'></i> Download
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif


            <!-- Reviews -->
            @if ($attraction->reviews->count() > 0)
                <div class="admin-detail-card">
                    <div class="admin-detail-card-header">
                        <div class="admin-detail-card-icon primary">
                            <i class='bx bx-message-square-detail'></i>
                        </div>
                        <h5>Customer Reviews</h5>
                        <span class="admin-count-badge">{{ $attraction->reviews->count() }}
                            {{ Str::plural('review', $attraction->reviews->count()) }}</span>
                    </div>
                    <div class="admin-detail-card-body p-0">
                        <div class="admin-reviews-list">
                            @foreach ($attraction->reviews->take(5) as $review)
                                <div class="admin-review-item-enhanced">
                                    <div class="admin-review-avatar-large">
                                        @if ($review->user->profile && $review->user->profile->profile_picture)
                                            <img src="{{ asset('storage/' . $review->user->profile->profile_picture) }}"
                                                alt="{{ $review->user->name }}">
                                        @else
                                            <div class="admin-avatar-placeholder-large">
                                                {{ substr($review->user->name, 0, 1) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="admin-review-content-enhanced">
                                        <div class="admin-review-header-enhanced">
                                            <div>
                                                <h6>{{ $review->user->name }}</h6>
                                                <div class="admin-rating-stars-enhanced">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <i
                                                            class='bx {{ $i <= $review->rating ? 'bxs-star' : 'bx-star' }}'></i>
                                                    @endfor
                                                    <span>{{ $review->rating }}.0</span>
                                                </div>
                                            </div>
                                            <span
                                                class="admin-review-time">{{ $review->created_at->diffForHumans() }}</span>
                                        </div>
                                        @if ($review->comment)
                                            <p class="admin-review-comment">{{ $review->comment }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if ($attraction->reviews->count() > 5)
                            <div class="admin-reviews-footer">
                                <p>Showing 5 of {{ $attraction->reviews->count() }} reviews</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="admin-detail-card">
                <div class="admin-detail-card-header">
                    <div class="admin-detail-card-icon secondary">
                        <i class='bx bx-cog'></i>
                    </div>
                    <h5>Quick Actions</h5>
                </div>
                <div class="admin-detail-card-body">
                    <div class="admin-action-buttons-stacked">
                        <a href="{{ route('admin.attractions.edit', $attraction) }}"
                            class="admin-action-btn-large primary">
                            <i class='bx bx-edit'></i>
                            <span>Edit Attraction</span>
                        </a>

                        <a href="{{ route('attractions.show', $attraction->slug) }}"
                            class="admin-action-btn-large secondary" target="_blank">
                            <i class='bx bx-link-external'></i>
                            <span>View Public Page</span>
                        </a>

                        <button type="button" class="admin-action-btn-large danger"
                            onclick="confirmDelete('delete-form')">
                            <i class='bx bx-trash'></i>
                            <span>Delete Attraction</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="admin-detail-card">
                <div class="admin-detail-card-header">
                    <div class="admin-detail-card-icon primary">
                        <i class='bx bx-bar-chart'></i>
                    </div>
                    <h5>Statistics</h5>
                </div>
                <div class="admin-detail-card-body">
                    <div class="admin-stats-list">
                        <div class="admin-stat-item-enhanced">
                            <div class="admin-stat-icon-enhanced primary">
                                <i class='bx bx-show'></i>
                            </div>
                            <div class="admin-stat-content-enhanced">
                                <label>Total Views</label>
                                <h4>{{ number_format($attraction->views) }}</h4>
                            </div>
                        </div>

                        <div class="admin-stat-item-enhanced">
                            <div class="admin-stat-icon-enhanced warning">
                                <i class='bx bxs-star'></i>
                            </div>
                            <div class="admin-stat-content-enhanced">
                                <label>Average Rating</label>
                                <h4>{{ number_format($attraction->getAverageRating(), 1) }}<span>/5.0</span></h4>
                            </div>
                        </div>

                        <div class="admin-stat-item-enhanced">
                            <div class="admin-stat-icon-enhanced success">
                                <i class='bx bx-message-square-detail'></i>
                            </div>
                            <div class="admin-stat-content-enhanced">
                                <label>Total Reviews</label>
                                <h4>{{ $attraction->reviews->count() }}</h4>
                            </div>
                        </div>

                        <div class="admin-stat-item-enhanced">
                            <div class="admin-stat-icon-enhanced info">
                                <i class='bx bx-image'></i>
                            </div>
                            <div class="admin-stat-content-enhanced">
                                <label>Total Photos</label>
                                <h4>{{ $attraction->images->count() }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Operating Hours -->
            @if ($attraction->schedules->count() > 0)
                <div class="admin-detail-card">
                    <div class="admin-detail-card-header">
                        <div class="admin-detail-card-icon info">
                            <i class='bx bx-time'></i>
                        </div>
                        <h5>Operating Hours</h5>
                    </div>
                    <div class="admin-detail-card-body">
                        <div class="admin-schedule-list-enhanced">
                            @foreach ($attraction->schedules as $schedule)
                                <div class="admin-schedule-item-enhanced">
                                    <div class="admin-schedule-day">
                                        <i class='bx bx-calendar'></i>
                                        <strong>{{ ucfirst($schedule->day_of_week) }}</strong>
                                    </div>
                                    <div class="admin-schedule-time">
                                        @if ($schedule->is_closed)
                                            <span class="admin-closed-badge">Closed</span>
                                        @else
                                            <span class="admin-time-badge">
                                                {{ date('g:i A', strtotime($schedule->opening_time)) }} -
                                                {{ date('g:i A', strtotime($schedule->closing_time)) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="admin-image-modal" onclick="closeModal()">
        <span class="admin-modal-close">&times;</span>
        <img class="admin-modal-content" id="modalImage">
    </div>

    <!-- Delete Form (Hidden) -->
    <form id="delete-form" action="{{ route('admin.attractions.destroy', $attraction) }}" method="POST"
        style="display: none;">
        @csrf
        @method('DELETE')
    </form>
@endsection

@push('styles')
    <style>
        /* Detail Header */
        .admin-detail-header {
            background: linear-gradient(135deg, #ffffff, #f8fafc);
            border-radius: 16px;
            padding: 2rem;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .admin-detail-header-content {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 2rem;
        }

        .admin-detail-title {
            font-size: 2rem;
            font-weight: 800;
            color: #1e293b;
            margin: 0.5rem 0;
        }

        .admin-detail-meta {
            display: flex;
            gap: 1.5rem;
            flex-wrap: wrap;
            font-size: 0.875rem;
            color: #64748b;
        }

        .admin-detail-meta span {
            display: flex;
            align-items: center;
            gap: 0.375rem;
        }

        .admin-detail-meta i {
            font-size: 1rem;
        }

        .admin-detail-badges {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .admin-status-badge-large {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.25rem;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.9375rem;
        }

        .admin-status-badge-large.approved {
            background: #d1fae5;
            color: #059669;
        }

        .admin-status-badge-large.pending {
            background: #fef3c7;
            color: #d97706;
        }

        .admin-status-badge-large.rejected {
            background: #fee2e2;
            color: #dc2626;
        }

        .admin-badge-large {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.25rem;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.9375rem;
        }

        .admin-badge-large.featured {
            background: #dbeafe;
            color: #0284c7;
        }

        .admin-badge-large.historical {
            background: #fef3c7;
            color: #d97706;
        }

        /* Detail Cards */
        .admin-detail-card {
            background: white;
            border-radius: 16px;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #f1f5f9;
            overflow: hidden;
        }

        .admin-detail-card-header {
            padding: 1.5rem;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .admin-detail-card-icon {
            width: 44px;
            height: 44px;
            border-radius: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.375rem;
            color: white;
            flex-shrink: 0;
        }

        .admin-detail-card-icon.primary {
            background: linear-gradient(135deg, #1a7838, #27a345);
        }

        .admin-detail-card-icon.success {
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .admin-detail-card-icon.info {
            background: linear-gradient(135deg, #00A8E8, #0284c7);
        }

        .admin-detail-card-icon.warning {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }

        .admin-detail-card-icon.secondary {
            background: linear-gradient(135deg, #64748b, #475569);
        }

        .admin-detail-card-header h5 {
            flex: 1;
            font-size: 1.125rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }

        .admin-count-badge {
            padding: 0.5rem 1rem;
            background: #f1f5f9;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            color: #64748b;
        }

        .admin-detail-card-body {
            padding: 1.5rem;
        }

        /* Description */
        .admin-description-text {
            font-size: 1rem;
            line-height: 1.7;
            color: #475569;
            margin: 0;
        }

        /* Info Grid */
        .admin-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.25rem;
        }

        .admin-info-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            padding: 1.25rem;
            background: #f8fafc;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
        }

        .admin-info-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            flex-shrink: 0;
        }

        .admin-info-icon.primary {
            background: linear-gradient(135deg, #1a7838, #27a345);
        }

        .admin-info-icon.warning {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }

        .admin-info-icon.info {
            background: linear-gradient(135deg, #00A8E8, #0284c7);
        }

        .admin-info-icon.success {
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .admin-info-item label {
            display: block;
            font-size: 0.8125rem;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.375rem;
        }

        .admin-info-item p {
            font-size: 1rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }

        /* Location Display */
        .admin-location-display {
            display: flex;
            gap: 1.25rem;
            padding: 1.5rem;
            background: #f8fafc;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
        }

        .admin-location-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            background: linear-gradient(135deg, #1a7838, #27a345);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
            flex-shrink: 0;
        }

        .admin-location-details h6 {
            font-size: 1rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }

        .admin-location-details p {
            font-size: 0.875rem;
            color: #64748b;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.375rem;
        }

        /* Contact Grid */
        .admin-contact-grid {
            display: grid;
            gap: 1.25rem;
            margin-bottom: 1.5rem;
        }

        .admin-contact-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            padding: 1.25rem;
            background: #f8fafc;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
        }

        .admin-contact-item>i {
            font-size: 1.75rem;
            color: #1a7838;
            margin-top: 0.25rem;
        }

        .admin-contact-item label {
            display: block;
            font-size: 0.8125rem;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.375rem;
        }

        .admin-contact-item p {
            font-size: 0.9375rem;
            color: #1e293b;
            margin: 0;
        }

        .admin-contact-item a {
            color: #1a7838;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s ease;
        }

        .admin-contact-item a:hover {
            color: #27a345;
        }

        .admin-facilities-section {
            padding-top: 1.5rem;
            border-top: 1px solid #e2e8f0;
        }

        .admin-facilities-section label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9375rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.75rem;
        }

        .admin-facilities-section p {
            color: #64748b;
            line-height: 1.6;
            margin: 0;
        }

        /* Gallery Grid */
        .admin-gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
        }

        .admin-gallery-item-enhanced {
            position: relative;
            aspect-ratio: 1;
            border-radius: 12px;
            overflow: hidden;
            border: 2px solid #e2e8f0;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .admin-gallery-item-enhanced:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
        }

        .admin-gallery-item-enhanced img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .admin-gallery-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .admin-gallery-item-enhanced:hover .admin-gallery-overlay {
            opacity: 1;
        }

        .admin-gallery-view-btn {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: white;
            border: none;
            color: #1a7838;
            font-size: 1.5rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .admin-gallery-view-btn:hover {
            transform: scale(1.1);
            background: #1a7838;
            color: white;
        }

        /* Reviews */
        .admin-reviews-list {
            display: flex;
            flex-direction: column;
        }

        .admin-review-item-enhanced {
            display: flex;
            gap: 1.25rem;
            padding: 1.5rem;
            border-bottom: 1px solid #f1f5f9;
        }

        .admin-review-item-enhanced:last-child {
            border-bottom: none;
        }

        .admin-review-avatar-large {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            overflow: hidden;
            flex-shrink: 0;
        }

        .admin-review-avatar-large img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .admin-avatar-placeholder-large {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #1a7838, #27a345);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: 700;
        }

        .admin-review-content-enhanced {
            flex: 1;
        }

        .admin-review-header-enhanced {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 0.75rem;
        }

        .admin-review-header-enhanced h6 {
            font-size: 1rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0 0 0.375rem 0;
        }

        .admin-rating-stars-enhanced {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            color: #fbbf24;
        }

        .admin-rating-stars-enhanced span {
            font-size: 0.875rem;
            font-weight: 600;
            color: #64748b;
            margin-left: 0.375rem;
        }

        .admin-review-time {
            font-size: 0.8125rem;
            color: #94a3b8;
        }

        .admin-review-comment {
            color: #64748b;
            line-height: 1.6;
            margin: 0;
        }

        .admin-reviews-footer {
            padding: 1rem 1.5rem;
            text-align: center;
            border-top: 1px solid #f1f5f9;
            background: #f8fafc;
        }

        .admin-reviews-footer p {
            color: #64748b;
            font-size: 0.875rem;
            margin: 0;
        }

        /* Action Buttons */
        .admin-action-buttons-stacked {
            display: flex;
            flex-direction: column;
            gap: 0.875rem;
        }

        .admin-action-btn-large {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            padding: 1rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .admin-action-btn-large.primary {
            background: linear-gradient(135deg, #1a7838, #27a345);
            color: white;
            box-shadow: 0 4px 12px rgba(26, 120, 56, 0.25);
        }

        .admin-action-btn-large.primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(26, 120, 56, 0.35);
            color: white;
        }

        .admin-action-btn-large.secondary {
            background: #f1f5f9;
            color: #475569;
        }

        .admin-action-btn-large.secondary:hover {
            background: #e2e8f0;
            color: #475569;
        }

        .admin-action-btn-large.danger {
            background: #fee2e2;
            color: #dc2626;
        }

        .admin-action-btn-large.danger:hover {
            background: #dc2626;
            color: white;
        }

        .admin-action-btn-large i {
            font-size: 1.25rem;
        }

        /* Stats List */
        .admin-stats-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .admin-stat-item-enhanced {
            display: flex;
            align-items: center;
            gap: 1.25rem;
            padding: 1.25rem;
            background: #f8fafc;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
        }

        .admin-stat-icon-enhanced {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            flex-shrink: 0;
        }

        .admin-stat-icon-enhanced.primary {
            background: linear-gradient(135deg, #1a7838, #27a345);
        }

        .admin-stat-icon-enhanced.warning {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }

        .admin-stat-icon-enhanced.success {
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .admin-stat-icon-enhanced.info {
            background: linear-gradient(135deg, #00A8E8, #0284c7);
        }

        .admin-stat-content-enhanced label {
            display: block;
            font-size: 0.75rem;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.375rem;
        }

        .admin-stat-content-enhanced h4 {
            font-size: 1.75rem;
            font-weight: 800;
            color: #1e293b;
            margin: 0;
        }

        .admin-stat-content-enhanced h4 span {
            font-size: 1rem;
            color: #94a3b8;
        }

        /* Schedule List */
        .admin-schedule-list-enhanced {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .admin-schedule-item-enhanced {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            background: #f8fafc;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
        }

        .admin-schedule-day {
            display: flex;
            align-items: center;
            gap: 0.625rem;
        }

        .admin-schedule-day i {
            color: #1a7838;
            font-size: 1.125rem;
        }

        .admin-schedule-day strong {
            font-size: 0.9375rem;
            color: #1e293b;
        }

        .admin-closed-badge {
            padding: 0.375rem 0.875rem;
            background: #fee2e2;
            color: #dc2626;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 700;
        }

        .admin-time-badge {
            font-size: 0.875rem;
            font-weight: 600;
            color: #64748b;
        }

        /* Image Modal */
        .admin-image-modal {
            display: none;
            position: fixed;
            z-index: 9999;
            padding-top: 100px;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.9);
        }

        .admin-modal-content {
            margin: auto;
            display: block;
            max-width: 90%;
            max-height: 80%;
            border-radius: 12px;
        }

        .admin-modal-close {
            position: absolute;
            top: 40px;
            right: 40px;
            color: #f1f1f1;
            font-size: 50px;
            font-weight: bold;
            cursor: pointer;
        }

        .admin-modal-close:hover {
            color: #bbb;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .admin-detail-header-content {
                flex-direction: column;
            }

            .admin-detail-title {
                font-size: 1.5rem;
            }

            .admin-info-grid {
                grid-template-columns: 1fr;
            }

            .admin-gallery-grid {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            }
        }

        /* Video Type Badge */
        .admin-video-type-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 700;
        }

        .admin-video-type-badge.youtube {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
        }

        .admin-video-type-badge.uploaded {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
        }

        .admin-video-type-badge i {
            font-size: 1.125rem;
        }

        /* Video Display */
        .admin-video-display {
            border-radius: 12px;
            overflow: hidden;
            background: #000;
        }

        .admin-video-container {
            position: relative;
            width: 100%;
            background: #000;
        }

        .admin-video-container iframe,
        .admin-video-container video {
            width: 100%;
            display: block;
            background: #000;
        }

        .admin-video-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            padding: 1.5rem;
            background: #f8fafc;
            border-top: 2px solid #e2e8f0;
        }

        .admin-video-info-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            padding: 1rem;
            background: white;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
        }

        .admin-video-info-item>i {
            font-size: 1.75rem;
            color: #1a7838;
            margin-top: 0.25rem;
            flex-shrink: 0;
        }

        .admin-video-info-item label {
            display: block;
            font-size: 0.75rem;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.375rem;
        }

        .admin-video-info-item p {
            font-size: 0.9375rem;
            color: #1e293b;
            margin: 0;
            word-break: break-word;
        }

        .admin-video-info-item a {
            color: #1a7838;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s ease;
        }

        .admin-video-info-item a:hover {
            color: #27a345;
        }

        .admin-video-download-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: linear-gradient(135deg, #1a7838, #27a345);
            color: white;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .admin-video-download-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(26, 120, 56, 0.3);
            color: white;
        }

        .admin-video-download-btn i {
            font-size: 1rem;
        }

        /* YouTube Video Specific Styling */
        .youtube-video .admin-video-container {
            position: relative;
            padding-bottom: 56.25%;
            /* 16:9 aspect ratio */
            height: 0;
            overflow: hidden;
        }

        .youtube-video .admin-video-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        /* Uploaded Video Specific Styling */
        .uploaded-video .admin-video-container video {
            max-height: 600px;
            object-fit: contain;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .admin-video-info {
                grid-template-columns: 1fr;
            }

            .admin-video-container iframe,
            .admin-video-container video {
                height: 300px !important;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        function viewImage(src) {
            const modal = document.getElementById('imageModal');
            const modalImg = document.getElementById('modalImage');
            modal.style.display = 'block';
            modalImg.src = src;
        }

        function closeModal() {
            document.getElementById('imageModal').style.display = 'none';
        }
    </script>
@endpush
