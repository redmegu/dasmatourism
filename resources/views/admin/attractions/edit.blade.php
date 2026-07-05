@extends('layouts.admin')

@section('page-title', 'Edit Attraction')

@section('content')
    <!-- Enhanced Page Header -->
    <div class="admin-form-header mb-4">
        <div class="admin-form-header-content">
            <div>
                <a href="{{ route('admin.attractions.show', $attraction) }}" class="admin-breadcrumb-link">
                    <i class='bx bx-chevron-left'></i> Back to Details
                </a>
                <h2 class="admin-form-title">Edit Attraction</h2>
                <p class="admin-form-subtitle">Update attraction information and settings</p>
            </div>
            <div class="admin-form-progress">
                <div class="admin-progress-circle">
                    <i class='bx bx-edit'></i>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.attractions.update', $attraction) }}" method="POST" enctype="multipart/form-data"
        id="attractionForm">
        @csrf
        @method('PUT')

        <div class="row g-4">
            <!-- Main Form Content -->
            <div class="col-lg-8">
                <!-- Basic Information -->
                <div class="admin-form-card">
                    <div class="admin-form-card-header">
                        <div class="admin-form-card-icon primary">
                            <i class='bx bx-info-circle'></i>
                        </div>
                        <div>
                            <h5 class="admin-form-card-title">Basic Information</h5>
                            <p class="admin-form-card-subtitle">Essential details about the attraction</p>
                        </div>
                    </div>
                    <div class="admin-form-card-body">
                        <div class="admin-form-group">
                            <label for="name" class="admin-form-label">
                                <i class='bx bx-rename'></i> Attraction Name
                                <span class="admin-required">*</span>
                            </label>
                            <input type="text" class="admin-form-input @error('name') is-invalid @enderror"
                                id="name" name="name" value="{{ old('name', $attraction->name) }}" required
                                placeholder="Enter attraction name">
                            @error('name')
                                <div class="admin-form-error">
                                    <i class='bx bx-error-circle'></i> {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="admin-form-group">
                            <label for="category_id" class="admin-form-label">
                                <i class='bx bx-category'></i> Category
                                <span class="admin-required">*</span>
                            </label>
                            <select class="admin-form-select @error('category_id') is-invalid @enderror" id="category_id"
                                name="category_id" required>
                                <option value="">Select Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id', $attraction->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="admin-form-error">
                                    <i class='bx bx-error-circle'></i> {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="admin-form-group">
                            <label for="description" class="admin-form-label">
                                <i class='bx bx-detail'></i> Description
                                <span class="admin-required">*</span>
                            </label>
                            <textarea class="admin-form-textarea @error('description') is-invalid @enderror" id="description" name="description"
                                rows="5" required placeholder="Describe the attraction...">{{ old('description', $attraction->description) }}</textarea>
                            <div class="admin-form-help">
                                <i class='bx bx-info-circle'></i> Provide a detailed description to help visitors
                            </div>
                            @error('description')
                                <div class="admin-form-error">
                                    <i class='bx bx-error-circle'></i> {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Location Information -->
                <div class="admin-form-card">
                    <div class="admin-form-card-header">
                        <div class="admin-form-card-icon success">
                            <i class='bx bx-map'></i>
                        </div>
                        <div>
                            <h5 class="admin-form-card-title">Location Information</h5>
                            <p class="admin-form-card-subtitle">Click on the map to set the exact location in Dasmariñas</p>
                        </div>
                    </div>
                    <div class="admin-form-card-body">
                        <div class="admin-form-group">
                            <label for="address" class="admin-form-label">
                                <i class='bx bx-map-pin'></i> Address
                                <span class="admin-required">*</span>
                            </label>
                            <textarea class="admin-form-textarea @error('address') is-invalid @enderror" id="address" name="address"
                                rows="2" required placeholder="Enter complete address">{{ old('address', $attraction->address) }}</textarea>
                            @error('address')
                                <div class="admin-form-error">
                                    <i class='bx bx-error-circle'></i> {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Static Map with Click Handler -->
                        <div class="admin-form-group">
                            <label class="admin-form-label">
                                <i class='bx bx-map-alt'></i> Select Location on Map <span class="admin-required">*</span>
                            </label>
                            <!-- Zoom Controls (Outside Container) -->
                            <div class="map-zoom-controls-external">
                                <button type="button" class="map-zoom-btn" id="zoom-in" title="Zoom In">
                                    <i class='bx bx-plus'></i>
                                </button>
                                <button type="button" class="map-zoom-btn" id="zoom-out" title="Zoom Out">
                                    <i class='bx bx-minus'></i>
                                </button>
                                <button type="button" class="map-zoom-btn" id="zoom-reset" title="Reset Zoom">
                                    <i class='bx bx-refresh'></i>
                                </button>
                                <div class="zoom-level-indicator" id="zoom-level">100%</div>
                            </div>
                            <div id="static-map-viewport">
                                <div id="static-map-container">
                                    <img id="dasma-map" src="{{ asset('assets/dasma_map.png') }}" alt="Dasmariñas Map"
                                        draggable="false">
                                    <div id="map-marker">
                                        <i class='bx bxs-map'></i>
                                    </div>
                                </div>
                            </div>
                            <div class="admin-form-help">
                                <i class='bx bx-info-circle'></i> Click to place marker. Drag to pan. Use zoom controls or
                                Ctrl+Scroll to zoom.
                            </div>
                        </div>

                        <div class="admin-form-row">
                            <div class="admin-form-col">
                                <label for="latitude" class="admin-form-label">
                                    <i class='bx bx-crosshair'></i> X Coordinate <span class="admin-required">*</span>
                                </label>
                                <input type="number" step="any"
                                    class="admin-form-input @error('latitude') is-invalid @enderror" id="latitude"
                                    name="latitude" value="{{ old('latitude', $attraction->latitude) }}" required
                                    placeholder="Click map to set X" readonly>
                                @error('latitude')
                                    <div class="admin-form-error">
                                        <i class='bx bx-error-circle'></i> {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="admin-form-col">
                                <label for="longitude" class="admin-form-label">
                                    <i class='bx bx-crosshair'></i> Y Coordinate <span class="admin-required">*</span>
                                </label>
                                <input type="number" step="any"
                                    class="admin-form-input @error('longitude') is-invalid @enderror" id="longitude"
                                    name="longitude" value="{{ old('longitude', $attraction->longitude) }}" required
                                    placeholder="Click map to set Y" readonly>
                                @error('longitude')
                                    <div class="admin-form-error">
                                        <i class='bx bx-error-circle'></i> {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Contact & Details -->
                <div class="admin-form-card">
                    <div class="admin-form-card-header">
                        <div class="admin-form-card-icon info">
                            <i class='bx bx-phone'></i>
                        </div>
                        <div>
                            <h5 class="admin-form-card-title">Contact & Details</h5>
                            <p class="admin-form-card-subtitle">Optional contact and additional information</p>
                        </div>
                    </div>
                    <div class="admin-form-card-body">
                        <div class="admin-form-row">
                            <div class="admin-form-col">
                                <label for="contact_number" class="admin-form-label">
                                    <i class='bx bx-phone'></i> Contact Number
                                </label>
                                <input type="text"
                                    class="admin-form-input @error('contact_number') is-invalid @enderror"
                                    id="contact_number" name="contact_number"
                                    value="{{ old('contact_number', $attraction->contact_number) }}"
                                    placeholder="e.g., (046) 123-4567">
                                @error('contact_number')
                                    <div class="admin-form-error">
                                        <i class='bx bx-error-circle'></i> {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="admin-form-col">
                                <label for="email" class="admin-form-label">
                                    <i class='bx bx-envelope'></i> Email
                                </label>
                                <input type="email" class="admin-form-input @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email', $attraction->email) }}"
                                    placeholder="email@example.com">
                                @error('email')
                                    <div class="admin-form-error">
                                        <i class='bx bx-error-circle'></i> {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="admin-form-row">
                            <div class="admin-form-col">
                                <label for="website" class="admin-form-label">
                                    <i class='bx bx-globe'></i> Website
                                </label>
                                <input type="url" class="admin-form-input @error('website') is-invalid @enderror"
                                    id="website" name="website" value="{{ old('website', $attraction->website) }}"
                                    placeholder="https://example.com">
                                @error('website')
                                    <div class="admin-form-error">
                                        <i class='bx bx-error-circle'></i> {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="admin-form-col">
                                <label for="google_maps_link" class="admin-form-label">
                                    <i class='bx bx-map'></i> Google Maps Link <span
                                        class="badge bg-secondary">Optional</span>
                                </label>
                                <input type="url"
                                    class="admin-form-input @error('google_maps_link') is-invalid @enderror"
                                    id="google_maps_link" name="google_maps_link"
                                    value="{{ old('google_maps_link', $attraction->google_maps_link) }}"
                                    placeholder="https://maps.app.goo.gl/...">
                                <div class="admin-form-help">
                                    <i class='bx bx-info-circle'></i> Paste Google Maps share link for route assistance
                                </div>
                                @error('google_maps_link')
                                    <div class="admin-form-error">
                                        <i class='bx bx-error-circle'></i> {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="admin-form-row">
                            <div class="admin-form-col">
                                <label for="entrance_fee" class="admin-form-label">
                                    <i class='bx bx-money'></i> Entrance Fee (PHP)
                                </label>
                                <input type="number" step="0.01"
                                    class="admin-form-input @error('entrance_fee') is-invalid @enderror"
                                    id="entrance_fee" name="entrance_fee"
                                    value="{{ old('entrance_fee', $attraction->entrance_fee ?? 0) }}" placeholder="0.00">
                                @error('entrance_fee')
                                    <div class="admin-form-error">
                                        <i class='bx bx-error-circle'></i> {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="admin-form-group">
                            <label for="facilities" class="admin-form-label">
                                <i class='bx bx-building'></i> Facilities
                            </label>
                            <textarea class="admin-form-textarea @error('facilities') is-invalid @enderror" id="facilities" name="facilities"
                                rows="3" placeholder="Describe available facilities...">{{ old('facilities', $attraction->facilities) }}</textarea>
                            <div class="admin-form-help">
                                <i class='bx bx-info-circle'></i> List amenities like parking, restrooms, Wi-Fi, etc.
                            </div>
                            @error('facilities')
                                <div class="admin-form-error">
                                    <i class='bx bx-error-circle'></i> {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="admin-form-group">
                            <label for="commute_guide" class="admin-form-label">
                                <i class='bx bx-bus'></i> How to Get There
                            </label>
                            <textarea class="admin-form-textarea @error('commute_guide') is-invalid @enderror" id="commute_guide" name="commute_guide"
                                rows="5" placeholder="Describe commute options, jeepney routes, landmarks, etc...">{{ old('commute_guide', $attraction->commute_guide) }}</textarea>
                            <div class="admin-form-help">
                                <i class='bx bx-info-circle'></i> Provide step-by-step commute directions — jeepney routes, tricycle stops, landmarks, walking directions, etc.
                            </div>
                            @error('commute_guide')
                                <div class="admin-form-error">
                                    <i class='bx bx-error-circle'></i> {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Walking Tour Videos (ADD THIS NEW CARD) -->
                <div class="admin-form-card">
                    <div class="admin-form-card-header">
                        <div class="admin-form-card-icon" style="background: linear-gradient(135deg, #ef4444, #dc2626);">
                            <i class='bx bx-video'></i>
                        </div>
                        <div>
                            <h5 class="admin-form-card-title">Walking Tour Videos</h5>
                            <p class="admin-form-card-subtitle">Add or update video tour</p>
                        </div>
                        @if ($attraction->youtube_video_url || $attraction->uploaded_video_path)
                            <span class="admin-count-badge">1</span>
                        @endif
                    </div>
                    <div class="admin-form-card-body">
                        <!-- Current Video Display -->
                        @if ($attraction->youtube_video_url || $attraction->uploaded_video_path)
                            <div class="current-video-section">
                                <h6 class="current-video-title">
                                    <i class='bx bx-video'></i> Current Video
                                </h6>

                                @if ($attraction->youtube_video_url)
                                    <!-- YouTube Video Display -->
                                    <div class="current-youtube-display">
                                        <div class="youtube-display-header">
                                            <i class='bx bxl-youtube'></i>
                                            <span>YouTube Video</span>
                                            <button type="button" class="btn-remove-video"
                                                onclick="removeCurrentVideo('youtube')">
                                                <i class='bx bx-trash'></i> Remove
                                            </button>
                                        </div>
                                        <div class="youtube-display-container">
                                            <iframe width="100%" height="315"
                                                src="{{ $attraction->getYoutubeEmbedUrl() }}" frameborder="0"
                                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                                allowfullscreen>
                                            </iframe>
                                        </div>
                                        <div class="video-info-display">
                                            <small class="text-muted">{{ $attraction->youtube_video_url }}</small>
                                        </div>
                                    </div>
                                @elseif($attraction->uploaded_video_path)
                                    <!-- Uploaded Video Display -->
                                    <div class="current-uploaded-display">
                                        <div class="uploaded-display-header">
                                            <i class='bx bx-video'></i>
                                            <span>Uploaded Video</span>
                                            <button type="button" class="btn-remove-video"
                                                onclick="removeCurrentVideo('uploaded')">
                                                <i class='bx bx-trash'></i> Remove
                                            </button>
                                        </div>
                                        <div class="uploaded-display-container">
                                            <video controls width="100%" height="auto">
                                                <source src="{{ asset('storage/' . $attraction->uploaded_video_path) }}"
                                                    type="video/mp4">
                                                Your browser does not support the video tag.
                                            </video>
                                        </div>
                                    </div>
                                @endif

                                <div class="alert alert-info mt-3 mb-0">
                                    <i class='bx bx-info-circle'></i>
                                    <strong>Note:</strong> To replace the current video, upload a new one below. The
                                    existing video will be replaced.
                                </div>
                            </div>

                            <div class="video-divider my-4">
                                <span>UPDATE VIDEO</span>
                            </div>
                        @endif

                        <!-- Upload New Video -->
                        <div class="row g-4">
                            <!-- YouTube Video URL -->
                            <div class="col-md-12">
                                <label for="youtube_video_url" class="admin-form-label">
                                    <i class='bx bxl-youtube'></i> YouTube Video URL
                                </label>
                                <input type="url"
                                    class="admin-form-input @error('youtube_video_url') is-invalid @enderror"
                                    id="youtube_video_url" name="youtube_video_url"
                                    value="{{ old('youtube_video_url', $attraction->youtube_video_url) }}"
                                    placeholder="https://www.youtube.com/watch?v=..."
                                    onchange="previewYoutubeVideo(this.value)">
                                <div class="admin-form-help">
                                    <i class='bx bx-info-circle'></i> Paste a YouTube video URL for virtual tour
                                </div>
                                @error('youtube_video_url')
                                    <div class="admin-form-error">
                                        <i class='bx bx-error-circle'></i> {{ $message }}
                                    </div>
                                @enderror

                                <!-- YouTube Preview -->
                                <div id="youtube-preview" class="youtube-video-preview mt-3" style="display: none;">
                                    <div class="youtube-preview-header">
                                        <i class='bx bxl-youtube'></i>
                                        <span>YouTube Video Preview</span>
                                        <button type="button" class="btn-close-preview" onclick="clearYoutubePreview()">
                                            <i class='bx bx-x'></i>
                                        </button>
                                    </div>
                                    <div class="youtube-preview-container">
                                        <iframe id="youtube-iframe" width="100%" height="315" frameborder="0"
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                            allowfullscreen>
                                        </iframe>
                                    </div>
                                </div>
                            </div>

                            <!-- OR Divider -->
                            <div class="col-md-12">
                                <div class="video-divider">
                                    <span>OR</span>
                                </div>
                            </div>

                            <!-- Upload Video File -->
                            <div class="col-md-12">
                                <label for="uploaded_video" class="admin-form-label">
                                    <i class='bx bx-upload'></i> Upload Video File
                                </label>
                                <div class="video-upload-wrapper">
                                    <div class="video-upload-area" id="videoUploadArea">
                                        <div class="video-upload-icon">
                                            <i class='bx bx-video-plus'></i>
                                        </div>
                                        <div class="video-upload-text">
                                            <p class="mb-1"><strong>Click to upload video</strong></p>
                                            <p class="text-muted small mb-0">MP4, MOV, AVI, WMV (Max 100MB)</p>
                                        </div>
                                    </div>
                                    <input type="file"
                                        class="admin-form-input d-none @error('uploaded_video') is-invalid @enderror"
                                        id="uploaded_video" name="uploaded_video"
                                        accept="video/mp4,video/quicktime,video/x-msvideo,video/x-ms-wmv"
                                        onchange="previewUploadedVideo(event)">
                                    <label for="uploaded_video" class="video-upload-btn">
                                        <i class='bx bx-upload'></i> Choose Video File
                                    </label>
                                </div>
                                <div class="admin-form-help">
                                    <i class='bx bx-info-circle'></i> Upload your own walking tour video (recommended:
                                    1080p)
                                </div>
                                @error('uploaded_video')
                                    <div class="admin-form-error">
                                        <i class='bx bx-error-circle'></i> {{ $message }}
                                    </div>
                                @enderror

                                <!-- Video Preview -->
                                <div id="video-preview" class="uploaded-video-preview mt-3" style="display: none;">
                                    <div class="video-preview-header">
                                        <i class='bx bx-video'></i>
                                        <span id="video-filename">Video Preview</span>
                                        <button type="button" class="btn-close-preview" onclick="clearVideoPreview()">
                                            <i class='bx bx-x'></i>
                                        </button>
                                    </div>
                                    <div class="video-preview-container">
                                        <video id="video-player" controls width="100%" height="auto">
                                            <source id="video-source" src="" type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                    </div>
                                    <div class="video-info mt-2">
                                        <small class="text-muted" id="video-size"></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Current Images -->
                @if ($attraction->images->count() > 0)
                    <div class="admin-form-card">
                        <div class="admin-form-card-header">
                            <div class="admin-form-card-icon warning">
                                <i class='bx bx-image'></i>
                            </div>
                            <div>
                                <h5 class="admin-form-card-title">Current Images</h5>
                                <p class="admin-form-card-subtitle">Manage existing attraction photos</p>
                            </div>
                            <span class="admin-count-badge">{{ $attraction->images->count() }}</span>
                        </div>
                        <div class="admin-form-card-body">
                            <div class="admin-image-preview-grid">
                                @foreach ($attraction->images as $image)
                                    <div class="admin-gallery-item-editable" id="image-{{ $image->id }}">
                                        <img src="{{ asset('storage/' . $image->image_path) }}"
                                            alt="{{ $attraction->name }}">

                                        <!-- Primary badge (shows which image is currently primary) -->
                                        @if ($image->is_primary)
                                            <span class="admin-primary-badge">PRIMARY</span>
                                        @endif

                                        <!-- Styled Primary radio button (always show toggle for each image) -->
                                        <div style="position:absolute;bottom:0.75rem;right:0.75rem;z-index:2;">
                                            <label class="image-radio">
                                                <input type="radio" name="primary_image_id"
                                                    value="{{ $image->id }}"
                                                    {{ $image->is_primary ? 'checked' : '' }}>
                                                <span></span>
                                            </label>
                                            <span class="image-radio-label">Primary</span>
                                        </div>

                                        <!-- Delete image button -->
                                        <button type="button" class="admin-delete-image-btn"
                                            onclick="deleteImage({{ $image->id }})">
                                            <i class='bx bx-trash'></i>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Upload New Images -->
                <div class="admin-form-card">
                    <div class="admin-form-card-header">
                        <div class="admin-form-card-icon success">
                            <i class='bx bx-image-add'></i>
                        </div>
                        <div>
                            <h5 class="admin-form-card-title">Add New Images</h5>
                            <p class="admin-form-card-subtitle">Upload additional photos</p>
                        </div>
                    </div>
                    <div class="admin-form-card-body">
                        <div class="admin-upload-area">
                            <input type="file" class="admin-upload-input" id="images" name="images[]" multiple
                                accept="image/*">
                            <label for="images" class="admin-upload-label">
                                <div class="admin-upload-icon">
                                    <i class='bx bx-cloud-upload'></i>
                                </div>
                                <div class="admin-upload-text">
                                    <span class="admin-upload-title">Click to upload images</span>
                                    <span class="admin-upload-subtitle">or drag and drop</span>
                                </div>
                                <div class="admin-upload-info">
                                    PNG, JPG up to 10MB
                                </div>
                            </label>
                        </div>
                        <div class="admin-form-help">
                            <i class='bx bx-info-circle'></i> You can upload multiple images at once
                        </div>
                        @error('images.*')
                            <div class="admin-form-error">
                                <i class='bx bx-error-circle'></i> {{ $message }}
                            </div>
                        @enderror

                        <div id="image-preview" class="admin-image-preview-grid" style="margin-top: 1.5rem;"></div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Status Card -->
                <div class="admin-form-card">
                    <div class="admin-form-card-header">
                        <div class="admin-form-card-icon secondary">
                            <i class='bx bx-check-shield'></i>
                        </div>
                        <div>
                            <h5 class="admin-form-card-title">Status</h5>
                            <p class="admin-form-card-subtitle">Approval status</p>
                        </div>
                    </div>
                    <div class="admin-form-card-body">
                        <div class="admin-form-group">
                            <label for="status" class="admin-form-label">
                                <i class='bx bx-check-circle'></i> Approval Status
                            </label>
                            <select class="admin-form-select @error('status') is-invalid @enderror" id="status"
                                name="status" required>
                                <option value="pending"
                                    {{ old('status', $attraction->status) == 'pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="approved"
                                    {{ old('status', $attraction->status) == 'approved' ? 'selected' : '' }}>Approved
                                </option>
                                <option value="rejected"
                                    {{ old('status', $attraction->status) == 'rejected' ? 'selected' : '' }}>Rejected
                                </option>
                            </select>
                            @error('status')
                                <div class="admin-form-error">
                                    <i class='bx bx-error-circle'></i> {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Settings Card -->
                <div class="admin-form-card">
                    <div class="admin-form-card-header">
                        <div class="admin-form-card-icon info">
                            <i class='bx bx-cog'></i>
                        </div>
                        <div>
                            <h5 class="admin-form-card-title">Settings</h5>
                            <p class="admin-form-card-subtitle">Attraction preferences</p>
                        </div>
                    </div>
                    <div class="admin-form-card-body">
                        <div class="admin-toggle-group">
                            <div class="admin-toggle-item">
                                <div class="admin-toggle-info">
                                    <i class='bx bx-star'></i>
                                    <div>
                                        <strong>Featured Attraction</strong>
                                        <p>Highlight on homepage</p>
                                    </div>
                                </div>
                                <label class="admin-toggle-switch">
                                    <input type="checkbox" id="is_featured" name="is_featured" value="1"
                                        {{ old('is_featured', $attraction->is_featured) ? 'checked' : '' }}>
                                    <span class="admin-toggle-slider"></span>
                                </label>
                            </div>

                            <div class="admin-toggle-item">
                                <div class="admin-toggle-info">
                                    <i class='bx bx-landmark'></i>
                                    <div>
                                        <strong>Historical Site</strong>
                                        <p>Mark as heritage site</p>
                                    </div>
                                </div>
                                <label class="admin-toggle-switch">
                                    <input type="checkbox" id="is_historical_site" name="is_historical_site"
                                        value="1"
                                        {{ old('is_historical_site', $attraction->is_historical_site) ? 'checked' : '' }}>
                                    <span class="admin-toggle-slider"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="admin-form-actions">
                    <button type="submit" class="admin-form-btn primary">
                        <i class='bx bx-save'></i>
                        <span>Update Attraction</span>
                    </button>
                    <a href="{{ route('admin.attractions.show', $attraction) }}" class="admin-form-btn secondary">
                        <i class='bx bx-x'></i>
                        <span>Cancel</span>
                    </a>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('styles')
    <style>
        /* Use all styles from create form */
        /* Form Header */
        .admin-form-header {
            background: linear-gradient(135deg, #ffffff, #f8fafc);
            border-radius: 16px;
            padding: 2rem;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .admin-form-header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 2rem;
        }

        .admin-breadcrumb-link {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            color: #64748b;
            text-decoration: none;
            font-size: 0.9375rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
            transition: color 0.2s ease;
        }

        .admin-breadcrumb-link:hover {
            color: #1a7838;
        }

        .admin-form-title {
            font-size: 1.75rem;
            font-weight: 800;
            color: #1e293b;
            margin-bottom: 0.375rem;
        }

        .admin-form-subtitle {
            color: #64748b;
            margin: 0;
            font-size: 0.9375rem;
        }

        .admin-progress-circle {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1a7838, #27a345);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
            box-shadow: 0 4px 12px rgba(26, 120, 56, 0.25);
        }

        /* Form Cards */
        .admin-form-card {
            background: white;
            border-radius: 16px;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #f1f5f9;
            overflow: hidden;
        }

        .admin-form-card-header {
            padding: 1.75rem;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            gap: 1.25rem;
        }

        .admin-form-card-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            flex-shrink: 0;
        }

        .admin-form-card-icon.primary {
            background: linear-gradient(135deg, #1a7838, #27a345);
        }

        .admin-form-card-icon.success {
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .admin-form-card-icon.info {
            background: linear-gradient(135deg, #00A8E8, #0284c7);
        }

        .admin-form-card-icon.warning {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }

        .admin-form-card-icon.secondary {
            background: linear-gradient(135deg, #64748b, #475569);
        }

        .admin-form-card-title {
            font-size: 1.125rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0 0 0.25rem 0;
        }

        .admin-form-card-subtitle {
            font-size: 0.875rem;
            color: #64748b;
            margin: 0;
        }

        .admin-count-badge {
            padding: 0.5rem 1rem;
            background: #f1f5f9;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            color: #64748b;
            margin-left: auto;
        }

        .admin-form-card-body {
            padding: 1.75rem;
        }

        /* Form Elements */
        .admin-form-group {
            margin-bottom: 1.5rem;
        }

        .admin-form-row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.25rem;
            margin-bottom: 1.5rem;
        }

        .admin-form-col {
            display: flex;
            flex-direction: column;
        }

        .admin-form-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9375rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 0.625rem;
        }

        .admin-form-label i {
            font-size: 1.125rem;
            color: #64748b;
        }

        .admin-required {
            color: #ef4444;
            font-weight: 700;
        }

        .admin-form-input,
        .admin-form-select,
        .admin-form-textarea {
            width: 100%;
            padding: 0.875rem 1.125rem;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 0.9375rem;
            transition: all 0.25s ease;
            background: white;
            color: #1e293b;
        }

        .admin-form-input:focus,
        .admin-form-select:focus,
        .admin-form-textarea:focus {
            outline: none;
            border-color: #1a7838;
            box-shadow: 0 0 0 4px rgba(26, 120, 56, 0.1);
        }

        .admin-form-textarea {
            resize: vertical;
            min-height: 100px;
        }

        .admin-form-help {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.8125rem;
            color: #94a3b8;
            margin-top: 0.5rem;
        }

        .admin-form-error {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 0.5rem;
            font-weight: 600;
        }

        /* Upload Area */
        .admin-upload-area {
            position: relative;
        }

        .admin-upload-input {
            position: absolute;
            width: 1px;
            height: 1px;
            opacity: 0;
            pointer-events: none;
        }

        .admin-upload-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3rem 2rem;
            border: 2px dashed #cbd5e1;
            border-radius: 12px;
            background: #f8fafc;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .admin-upload-label:hover {
            border-color: #1a7838;
            background: #f0fdf4;
        }

        .admin-upload-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #e0f2e9, #d1fae5);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: #1a7838;
            margin-bottom: 1rem;
        }

        .admin-upload-text {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.25rem;
            margin-bottom: 0.75rem;
        }

        .admin-upload-title {
            font-weight: 700;
            color: #1e293b;
            font-size: 1rem;
        }

        .admin-upload-subtitle {
            color: #64748b;
            font-size: 0.875rem;
        }

        .admin-upload-info {
            font-size: 0.8125rem;
            color: #94a3b8;
        }

        /* Image Preview Grid */
        .admin-image-preview-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 1rem;
        }

        .admin-gallery-item-editable {
            position: relative;
            aspect-ratio: 1;
            border-radius: 12px;
            overflow: hidden;
            border: 2px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .admin-gallery-item-editable:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            z-index: 1;
        }

        .admin-gallery-item-editable img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .admin-primary-badge {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            padding: 0.375rem 0.875rem;
            background: linear-gradient(135deg, #1a7838, #27a345);
            color: white;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .admin-delete-image-btn {
            position: absolute;
            top: 0.5rem;
            left: 0.5rem;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(239, 68, 68, 0.95);
            color: white;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            opacity: 0;
            transition: all 0.3s ease;
            font-size: 1.125rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .admin-gallery-item-editable:hover .admin-delete-image-btn {
            opacity: 1;
        }

        .admin-delete-image-btn:hover {
            background: #dc2626;
            transform: scale(1.1);
        }

        /* Toggle Group */
        .admin-toggle-group {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        .admin-toggle-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.25rem;
            background: #f8fafc;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
        }

        .admin-toggle-info {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            flex: 1;
        }

        .admin-toggle-info>i {
            font-size: 1.75rem;
            color: #1a7838;
            margin-top: 0.25rem;
        }

        .admin-toggle-info strong {
            display: block;
            font-size: 0.9375rem;
            color: #1e293b;
            margin-bottom: 0.25rem;
        }

        .admin-toggle-info p {
            font-size: 0.8125rem;
            color: #64748b;
            margin: 0;
        }

        .admin-toggle-switch {
            position: relative;
            display: inline-block;
            width: 52px;
            height: 28px;
            flex-shrink: 0;
        }

        .admin-toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .admin-toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #cbd5e1;
            border-radius: 28px;
            transition: 0.3s;
        }

        .admin-toggle-slider:before {
            position: absolute;
            content: "";
            height: 22px;
            width: 22px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            border-radius: 50%;
            transition: 0.3s;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .admin-toggle-switch input:checked+.admin-toggle-slider {
            background: linear-gradient(135deg, #1a7838, #27a345);
        }

        .admin-toggle-switch input:checked+.admin-toggle-slider:before {
            transform: translateX(24px);
        }

        /* Action Buttons */
        .admin-form-actions {
            display: flex;
            flex-direction: column;
            gap: 0.875rem;
            margin-top: 1.5rem;
        }

        .admin-form-btn {
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

        .admin-form-btn.primary {
            background: linear-gradient(135deg, #1a7838, #27a345);
            color: white;
            box-shadow: 0 4px 12px rgba(26, 120, 56, 0.25);
        }

        .admin-form-btn.primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(26, 120, 56, 0.35);
        }

        .admin-form-btn.secondary {
            background: #f1f5f9;
            color: #475569;
        }

        .admin-form-btn.secondary:hover {
            background: #e2e8f0;
        }

        .admin-form-btn i {
            font-size: 1.25rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .admin-form-header-content {
                flex-direction: column;
            }

            .admin-progress-circle {
                width: 60px;
                height: 60px;
                font-size: 1.75rem;
            }

            .admin-form-row {
                grid-template-columns: 1fr;
            }
        }

        .image-radio {
            display: inline-block;
            cursor: pointer;
            margin-top: 0.5rem;
            border: 2px solid #a3e635;
            background: #f1f5f9;
            border-radius: 50%;
            padding: 0.2em;
            transition: border-color .15s;
            vertical-align: middle;
        }

        .image-radio input[type="radio"] {
            display: none;
        }

        .image-radio span {
            display: block;
            width: 20px;
            height: 20px;
            background: #fff;
            border-radius: 50%;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
            position: relative;
        }

        .image-radio input[type="radio"]:checked+span {
            background: #22c55e;
            box-shadow: 0 0 0 4px #dcfce7;
        }

        .image-radio-label {
            font-size: 0.85em;
            margin-left: 0.4em;
            vertical-align: middle;
            color: #15803d;
            font-weight: 600;
        }

        /* Video Section Styles */
        .current-video-section {
            margin-bottom: 2rem;
        }

        .current-video-title {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 1rem;
        }

        .current-youtube-display,
        .current-uploaded-display {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
            background: white;
        }

        .youtube-display-header,
        .uploaded-display-header {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 600;
        }

        .uploaded-display-header {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
        }

        .youtube-display-header i,
        .uploaded-display-header i {
            font-size: 1.5rem;
        }

        .btn-remove-video {
            margin-left: auto;
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.2s;
        }

        .btn-remove-video:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .youtube-display-container,
        .uploaded-display-container {
            position: relative;
            padding: 1rem;
            background: #000;
        }

        .youtube-display-container {
            padding-bottom: 56.25%;
            height: 0;
            overflow: hidden;
        }

        .youtube-display-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .uploaded-display-container video {
            width: 100%;
            max-height: 400px;
            object-fit: contain;
        }

        .video-info-display {
            padding: 0.75rem 1.5rem;
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
        }

        .video-divider {
            text-align: center;
            position: relative;
            margin: 1.5rem 0;
        }

        .video-divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, #e2e8f0, transparent);
            z-index: 0;
        }

        .video-divider span {
            background: white;
            padding: 0.5rem 1.5rem;
            position: relative;
            z-index: 1;
            color: #64748b;
            font-weight: 600;
            font-size: 0.875rem;
            border: 2px solid #e2e8f0;
            border-radius: 20px;
        }

        .video-upload-wrapper {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .video-upload-area {
            border: 2px dashed #cbd5e1;
            border-radius: 12px;
            padding: 2.5rem;
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            display: flex;
            align-items: center;
            gap: 1.5rem;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .video-upload-area:hover {
            border-color: #3b82f6;
            background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        }

        .video-upload-icon {
            width: 80px;
            height: 80px;
            border-radius: 16px;
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .video-upload-text {
            flex: 1;
        }

        .video-upload-btn {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 12px 28px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
            display: inline-flex;
            gap: 0.75rem;
            align-items: center;
            font-size: 1rem;
            transition: all 0.3s;
            align-self: flex-start;
        }

        .video-upload-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(59, 130, 246, 0.4);
        }

        .youtube-video-preview,
        .uploaded-video-preview {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
            background: white;
        }

        .youtube-preview-header,
        .video-preview-header {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 600;
        }

        .video-preview-header {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
        }

        .youtube-preview-header i,
        .video-preview-header i {
            font-size: 1.5rem;
        }

        .youtube-preview-container {
            position: relative;
            padding-bottom: 56.25%;
            height: 0;
            overflow: hidden;
        }

        .youtube-preview-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .video-preview-container {
            padding: 1rem;
            background: #000;
        }

        .video-preview-container video {
            border-radius: 8px;
            max-height: 400px;
            object-fit: contain;
        }

        .btn-close-preview {
            margin-left: auto;
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-close-preview:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: scale(1.1);
        }

        .video-info {
            padding: 0.75rem 1.5rem;
            background: #f8fafc;
        }

        /* Static Map Styles */
        #static-map-viewport {
            position: relative;
            width: 100%;
            height: 600px;
            border-radius: 12px;
            overflow: auto;
            border: 3px solid #e2e8f0;
            background: #f8fafc;
            cursor: grab;
            user-select: none;
        }

        #static-map-viewport:active {
            cursor: grabbing;
        }

        #static-map-viewport:hover {
            border-color: #1a7838;
        }

        #static-map-container {
            position: relative;
            display: inline-block;
            transform-origin: top left;
            transition: transform 0.3s ease;
            min-width: 100%;
            min-height: 100%;
        }

        #dasma-map {
            display: block;
            width: 100%;
            height: auto;
            user-select: none;
            -webkit-user-drag: none;
            pointer-events: auto;
            cursor: crosshair;
        }

        #map-marker {
            position: absolute;
            width: 30px;
            height: 30px;
            transform: translate(-50%, -100%);
            display: none;
            z-index: 1000;
            pointer-events: none;
            transition: none;
            will-change: transform, left, top;
        }

        #map-marker i {
            font-size: 30px;
            color: #FF0000;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
            display: block;
        }

        #map-marker.animate {
            animation: bounce 0.5s ease;
        }

        @keyframes bounce {

            0%,
            100% {
                transform: translate(-50%, -100%) scale(1);
            }

            50% {
                transform: translate(-50%, -110%) scale(1.2);
            }
        }

        /* Map Zoom Controls - External */
        .map-zoom-controls-external {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1rem;
            align-items: center;
        }

        .map-zoom-btn {
            width: 44px;
            height: 44px;
            border: none;
            border-radius: 10px;
            background: linear-gradient(135deg, #1a7838, #27a345);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(26, 120, 56, 0.3);
            transition: all 0.3s ease;
        }

        .map-zoom-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(26, 120, 56, 0.4);
        }

        .map-zoom-btn:active {
            transform: translateY(0);
        }

        .zoom-level-indicator {
            background: white;
            color: #1a7838;
            padding: 0.625rem 1rem;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.9375rem;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border: 2px solid #1a7838;
            min-width: 70px;
            margin-left: 0.5rem;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Image preview handling for new uploads
            const imageInput = document.getElementById('images');
            const preview = document.getElementById('image-preview');

            imageInput.addEventListener('change', function(e) {
                preview.innerHTML = '';

                if (e.target.files && e.target.files.length > 0) {
                    Array.from(e.target.files).forEach((file) => {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const div = document.createElement('div');
                            div.className = 'admin-gallery-item-editable';
                            div.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
                            preview.appendChild(div);
                        };
                        reader.readAsDataURL(file);
                    });
                }
            });

            // Static Map with Zoom and Pan
            const viewport = document.getElementById('static-map-viewport');
            const mapContainer = document.getElementById('static-map-container');
            const mapImage = document.getElementById('dasma-map');
            const marker = document.getElementById('map-marker');
            const latitudeInput = document.getElementById('latitude');
            const longitudeInput = document.getElementById('longitude');

            // Zoom functionality
            let currentZoom = 1;
            const minZoom = 1;
            const maxZoom = 4;
            const zoomStep = 0.25;

            const zoomInBtn = document.getElementById('zoom-in');
            const zoomOutBtn = document.getElementById('zoom-out');
            const zoomResetBtn = document.getElementById('zoom-reset');
            const zoomLevelIndicator = document.getElementById('zoom-level');

            // Store marker position in percentage (0-1000 range)
            let markerCoordX = {{ old('latitude', $attraction->latitude) }};
            let markerCoordY = {{ old('longitude', $attraction->longitude) }};
            let hasMarker = (markerCoordX > 0 || markerCoordY > 0);

            // Pan functionality
            let isPanning = false;
            let startPanX = 0;
            let startPanY = 0;
            let hasMoved = false;

            function placeMarker(coordX, coordY) {
                markerCoordX = coordX;
                markerCoordY = coordY;
                hasMarker = true;

                // Update inputs
                latitudeInput.value = coordX;
                longitudeInput.value = coordY;

                updateMarkerPosition();
                marker.style.display = 'block';

                // Animation
                marker.classList.remove('animate');
                setTimeout(() => marker.classList.add('animate'), 10);

                // Visual feedback
                viewport.style.borderColor = '#1a7838';
                setTimeout(() => viewport.style.borderColor = '#e2e8f0', 500);

                console.log(`Marker placed at coordinates: X=${coordX}, Y=${coordY}`);
            }

            function updateMarkerPosition() {
                if (!hasMarker) return;

                // Get image base dimensions
                const baseWidth = mapImage.offsetWidth;
                const baseHeight = mapImage.offsetHeight;

                // Calculate pixel position on base image
                const baseX = (markerCoordX / 1000) * baseWidth;
                const baseY = (markerCoordY / 1000) * baseHeight;

                // Apply zoom and position marker
                marker.style.left = baseX + 'px';
                marker.style.top = baseY + 'px';
                marker.style.transform = `translate(-50%, -100%) scale(${1 / currentZoom})`;
            }

            function updateZoom(newZoom) {
                currentZoom = Math.max(minZoom, Math.min(maxZoom, newZoom));
                zoomLevelIndicator.textContent = `${Math.round(currentZoom * 100)}%`;
                mapContainer.style.transform = `scale(${currentZoom})`;
                updateMarkerPosition();
            }

            // Zoom controls
            zoomInBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                updateZoom(currentZoom + zoomStep);
            });

            zoomOutBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                updateZoom(currentZoom - zoomStep);
            });

            zoomResetBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                updateZoom(1);
                viewport.scrollLeft = 0;
                viewport.scrollTop = 0;
            });

            // Mouse wheel zoom
            viewport.addEventListener('wheel', function(e) {
                if (e.ctrlKey) {
                    e.preventDefault();
                    updateZoom(currentZoom + (e.deltaY > 0 ? -zoomStep : zoomStep));
                }
            }, {
                passive: false
            });

            // Pan on viewport with right-click or left-click on background
            viewport.addEventListener('mousedown', function(e) {
                // Right-click (button 2) always pans
                if (e.button === 2) {
                    e.preventDefault();
                    isPanning = true;
                    hasMoved = false;
                    startPanX = e.clientX + viewport.scrollLeft;
                    startPanY = e.clientY + viewport.scrollTop;
                    viewport.style.cursor = 'grabbing';
                    return;
                }

                // Left-click on viewport background (not map image) also pans
                if (e.button === 0 && e.target !== mapImage) {
                    isPanning = true;
                    hasMoved = false;
                    startPanX = e.clientX + viewport.scrollLeft;
                    startPanY = e.clientY + viewport.scrollTop;
                    viewport.style.cursor = 'grabbing';
                }
            });

            document.addEventListener('mousemove', function(e) {
                if (isPanning) {
                    hasMoved = true;
                    viewport.scrollLeft = startPanX - e.clientX;
                    viewport.scrollTop = startPanY - e.clientY;
                }
            });

            document.addEventListener('mouseup', function() {
                if (isPanning) {
                    isPanning = false;
                    viewport.style.cursor = 'grab';
                    mapImage.style.cursor = 'crosshair';
                }
            });

            // Disable context menu on right-click for panning
            viewport.addEventListener('contextmenu', function(e) {
                e.preventDefault();
            });

            // Click on map to place marker or pan with right-click
            mapImage.addEventListener('mousedown', function(e) {
                // Right-click or middle mouse button for panning
                if (e.button === 2 || e.button === 1) {
                    e.preventDefault();
                    isPanning = true;
                    hasMoved = false;
                    startPanX = e.clientX + viewport.scrollLeft;
                    startPanY = e.clientY + viewport.scrollTop;
                    mapImage.style.cursor = 'grabbing';
                } else {
                    hasMoved = false;
                }
            });

            mapImage.addEventListener('mouseup', function(e) {
                if (hasMoved) return; // Don't place marker if dragging

                e.stopPropagation();

                // Get base image dimensions
                const baseWidth = mapImage.offsetWidth;
                const baseHeight = mapImage.offsetHeight;

                // Get click position relative to the image element (not zoomed position)
                const rect = mapImage.getBoundingClientRect();
                const clickX = e.clientX - rect.left;
                const clickY = e.clientY - rect.top;

                // Account for zoom: divide by zoom to get base position
                const baseX = clickX / currentZoom;
                const baseY = clickY / currentZoom;

                // Validate click is within bounds
                if (baseX >= 0 && baseX <= baseWidth && baseY >= 0 && baseY <= baseHeight) {
                    // Convert to 0-1000 coordinate system
                    const coordX = parseFloat(((baseX / baseWidth) * 1000).toFixed(2));
                    const coordY = parseFloat(((baseY / baseHeight) * 1000).toFixed(2));

                    placeMarker(coordX, coordY);

                    console.log(
                        `Zoom: ${currentZoom}x | Screen click: (${clickX.toFixed(0)}, ${clickY.toFixed(0)}) | Base: (${baseX.toFixed(0)}, ${baseY.toFixed(0)})`
                    );
                }
            });

            // Initialize marker positioning
            marker.style.position = 'absolute';
            marker.style.zIndex = '1000';

            // Show existing marker if coordinates exist
            if (hasMarker) {
                updateMarkerPosition();
                marker.style.display = 'block';
            }

            console.log('Map initialized. Current coordinates: X=' + markerCoordX + ', Y=' + markerCoordY);
        });

        // Delete image function
        function deleteImage(imageId) {
            if (!confirm('Are you sure you want to delete this image? This action cannot be undone.')) {
                return;
            }

            fetch(`/admin/attractions/images/${imageId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const imageElement = document.getElementById(`image-${imageId}`);
                        imageElement.style.opacity = '0';
                        imageElement.style.transform = 'scale(0.8)';
                        setTimeout(() => {
                            imageElement.remove();
                        }, 300);

                        // Show success message
                        const successMsg = document.createElement('div');
                        successMsg.className = 'alert alert-success';
                        successMsg.style.cssText =
                            'position: fixed; top: 20px; right: 20px; z-index: 9999; animation: slideIn 0.3s ease;';
                        successMsg.innerHTML = '<i class="bx bx-check-circle"></i> Image deleted successfully';
                        document.body.appendChild(successMsg);
                        setTimeout(() => successMsg.remove(), 3000);
                    } else {
                        alert(data.message || 'Failed to delete image');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the image');
                });
        }

        // YouTube Video Preview
        function previewYoutubeVideo(url) {
            const preview = document.getElementById('youtube-preview');
            const iframe = document.getElementById('youtube-iframe');

            if (!url) {
                preview.style.display = 'none';
                return;
            }

            let videoId = null;
            const regex =
                /(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/;
            const match = url.match(regex);

            if (match && match[1]) {
                videoId = match[1];
                iframe.src = `https://www.youtube.com/embed/${videoId}`;
                preview.style.display = 'block';
                clearVideoPreview();
            } else {
                alert('Please enter a valid YouTube URL');
                document.getElementById('youtube_video_url').value = '';
            }
        }

        function clearYoutubePreview() {
            document.getElementById('youtube-preview').style.display = 'none';
            document.getElementById('youtube-iframe').src = '';
        }

        // Uploaded Video Preview
        function previewUploadedVideo(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('video-preview');
            const player = document.getElementById('video-player');
            const source = document.getElementById('video-source');
            const uploadArea = document.getElementById('videoUploadArea');
            const filename = document.getElementById('video-filename');
            const sizeInfo = document.getElementById('video-size');

            if (file) {
                if (file.size > 100 * 1024 * 1024) {
                    alert('Video file size must be less than 100MB');
                    event.target.value = '';
                    return;
                }

                const allowedTypes = ['video/mp4', 'video/quicktime', 'video/x-msvideo', 'video/x-ms-wmv'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Only MP4, MOV, AVI, and WMV video files are allowed');
                    event.target.value = '';
                    return;
                }

                const videoURL = URL.createObjectURL(file);
                source.src = videoURL;
                player.load();

                uploadArea.classList.add('has-video');
                filename.textContent = file.name;
                sizeInfo.textContent = `File size: ${(file.size / (1024 * 1024)).toFixed(2)} MB`;
                preview.style.display = 'block';

                clearYoutubePreview();
            }
        }

        function clearVideoPreview() {
            const preview = document.getElementById('video-preview');
            const player = document.getElementById('video-player');
            const source = document.getElementById('video-source');
            const input = document.getElementById('uploaded_video');
            const uploadArea = document.getElementById('videoUploadArea');

            if (preview) preview.style.display = 'none';
            if (source) source.src = '';
            if (player) player.load();
            if (input) input.value = '';
            if (uploadArea) uploadArea.classList.remove('has-video');
        }

        function removeCurrentVideo(type) {
            if (!confirm(
                    'Are you sure you want to remove the current video? This will be permanently deleted when you save.')) {
                return;
            }

            if (type === 'youtube') {
                document.getElementById('youtube_video_url').value = '';
            } else if (type === 'uploaded') {
                // Add hidden input to signal deletion
                const deleteInput = document.createElement('input');
                deleteInput.type = 'hidden';
                deleteInput.name = 'delete_uploaded_video';
                deleteInput.value = '1';
                document.getElementById('attractionForm').appendChild(deleteInput);
            }

            alert('Video will be removed when you save the form.');
        }
    </script>
@endpush
