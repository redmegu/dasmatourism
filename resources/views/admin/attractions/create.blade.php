@extends('layouts.admin')

@section('page-title', 'Create New Attraction')

@section('content')
    <!-- Enhanced Page Header -->
    <div class="admin-form-header mb-4">
        <div class="admin-form-header-content">
            <div>
                <a href="{{ route('admin.attractions.index') }}" class="admin-breadcrumb-link">
                    <i class='bx bx-chevron-left'></i> Back to Attractions
                </a>
                <h2 class="admin-form-title">Create New Attraction</h2>
                <p class="admin-form-subtitle">Add a new tourist destination to the system</p>
            </div>
            <div class="admin-form-progress">
                <div class="admin-progress-circle">
                    <i class='bx bx-map-pin'></i>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.attractions.store') }}" method="POST" enctype="multipart/form-data" id="attractionForm">
        @csrf

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
                                id="name" name="name" value="{{ old('name') }}" required
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
                                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                                rows="5" required placeholder="Describe the attraction...">{{ old('description') }}</textarea>
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
                                <i class='bx bx-map-pin'></i> Address <span class="admin-required">*</span>
                            </label>
                            <textarea class="admin-form-textarea @error('address') is-invalid @enderror" id="address" name="address"
                                rows="2" required placeholder="Enter complete address">{{ old('address') }}</textarea>
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

                            <!-- Zoom Controls (External) -->
                            <div class="map-zoom-controls-external">
                                <button type="button" id="zoom-in" class="map-zoom-btn" title="Zoom In">
                                    <i class='bx bx-plus'></i>
                                </button>
                                <button type="button" id="zoom-out" class="map-zoom-btn" title="Zoom Out">
                                    <i class='bx bx-minus'></i>
                                </button>
                                <button type="button" id="zoom-reset" class="map-zoom-btn" title="Reset Zoom">
                                    <i class='bx bx-reset'></i>
                                </button>
                                <div class="zoom-level-indicator">
                                    <span id="zoom-level">100%</span>
                                </div>
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
                                <i class='bx bx-info-circle'></i> Click anywhere on the map to place a marker. Use zoom
                                controls to adjust view and drag to pan.
                            </div>
                        </div>

                        <div class="admin-form-row">
                            <div class="admin-form-col">
                                <label for="latitude" class="admin-form-label">
                                    <i class='bx bx-crosshair'></i> X Coordinate <span class="admin-required">*</span>
                                </label>
                                <input type="number" step="any"
                                    class="admin-form-input @error('latitude') is-invalid @enderror" id="latitude"
                                    name="latitude" value="{{ old('latitude', 0) }}" required
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
                                    name="longitude" value="{{ old('longitude', 0) }}" required
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
                                    id="contact_number" name="contact_number" value="{{ old('contact_number') }}"
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
                                    id="email" name="email" value="{{ old('email') }}"
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
                                    id="website" name="website" value="{{ old('website') }}"
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
                                    id="google_maps_link" name="google_maps_link" value="{{ old('google_maps_link') }}"
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
                                    id="entrance_fee" name="entrance_fee" value="{{ old('entrance_fee', 0) }}"
                                    placeholder="0.00">
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
                                rows="3" placeholder="Describe available facilities...">{{ old('facilities') }}</textarea>
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
                                rows="5" placeholder="Describe commute options, jeepney routes, landmarks, etc...">{{ old('commute_guide') }}</textarea>
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
                <div class="admin-form-card mb-4">
                    <div class="admin-form-card-header">
                        <div class="admin-form-card-icon" style="background: linear-gradient(135deg, #ef4444, #dc2626);">
                            <i class='bx bx-video'></i>
                        </div>
                        <div>
                            <h5 class="admin-form-card-title">Walking Tour Videos</h5>

                            <p class="text-muted mb-0">Add YouTube video or upload your own video tour</p>
                        </div>
                        <span class="badge bg-secondary ms-auto">Optional</span>
                    </div>
                    <div class="admin-form-card-body">
                        <div class="row g-4">
                            <!-- YouTube Video URL -->
                            <div class="col-md-12">
                                <label for="youtube_video_url" class="form-label">
                                    <i class='bx bxl-youtube'></i> YouTube Video URL
                                </label>
                                <input type="url"
                                    class="form-control @error('youtube_video_url') is-invalid @enderror"
                                    id="youtube_video_url" name="youtube_video_url"
                                    value="{{ old('youtube_video_url') }}"
                                    placeholder="https://www.youtube.com/watch?v=..."
                                    onchange="previewYoutubeVideo(this.value)">
                                <small class="text-muted">
                                    Paste a YouTube video URL for virtual tour or walking guide
                                </small>
                                @error('youtube_video_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
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
                                <label for="uploaded_video" class="form-label">
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
                                        class="form-control d-none @error('uploaded_video') is-invalid @enderror"
                                        id="uploaded_video" name="uploaded_video"
                                        accept="video/mp4,video/quicktime,video/x-msvideo,video/x-ms-wmv"
                                        onchange="previewUploadedVideo(event)">
                                    <label for="uploaded_video" class="video-upload-btn">
                                        <i class='bx bx-upload'></i> Choose Video File
                                    </label>
                                </div>
                                <small class="text-muted d-block mt-2">
                                    <i class='bx bx-info-circle'></i>
                                    Upload your own recorded walking tour video (recommended: 1080p, landscape orientation)
                                </small>
                                @error('uploaded_video')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
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

                            <!-- Info Alert -->
                            <div class="col-md-12">
                                <div class="alert alert-info mb-0">
                                    <i class='bx bx-info-circle me-2'></i>
                                    <strong>Tip:</strong> Choose YouTube for faster loading or upload your own video for
                                    offline availability.
                                    You can only use one option at a time.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Images -->
                <div class="admin-form-card">
                    <div class="admin-form-card-header">
                        <div class="admin-form-card-icon warning">
                            <i class='bx bx-image'></i>
                        </div>
                        <div>
                            <h5 class="admin-form-card-title">Images</h5>
                            <p class="admin-form-card-subtitle">Upload photos of the attraction</p>
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
                            <i class='bx bx-info-circle'></i> First image will be set as primary. You can upload multiple
                            images at once.
                        </div>
                        @error('images.*')
                            <div class="admin-form-error">
                                <i class='bx bx-error-circle'></i> {{ $message }}
                            </div>
                        @enderror

                        <div id="image-preview" class="admin-image-preview-grid"></div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Settings Card -->
                <div class="admin-form-card">

                    <div class="admin-form-card-header">
                        <div class="admin-form-card-icon secondary">
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
                                        {{ old('is_featured') ? 'checked' : '' }}>
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
                                        value="1" {{ old('is_historical_site') ? 'checked' : '' }}>
                                    <span class="admin-toggle-slider"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="admin-form-actions">
                    <button type="submit" class="admin-form-btn primary">
                        <i class='bx bx-check-circle'></i>
                        <span>Create Attraction</span>
                    </button>
                    <a href="{{ route('admin.attractions.index') }}" class="admin-form-btn secondary">
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
            margin-top: 1.5rem;
        }

        .admin-gallery-item {
            position: relative;
            aspect-ratio: 1;
            border-radius: 12px;
            overflow: hidden;
            border: 2px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .admin-gallery-item:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            z-index: 1;
        }

        .admin-gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .admin-primary-badge {
            position: absolute;
            top: 0.5rem;
            left: 0.5rem;
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

        /* Video Upload Section Styles */
        .video-divider {
            text-align: center;
            position: relative;
            margin: 1rem 0;
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

        /* Video Upload Area */
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

        .video-upload-area.has-video {
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

        /* YouTube Video Preview */
        .youtube-video-preview {
            border: 2px solid #ef4444;
            border-radius: 12px;
            overflow: hidden;
            background: white;
        }

        .youtube-preview-header {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 600;
        }

        .youtube-preview-header i {
            font-size: 1.5rem;
        }

        .youtube-preview-container {
            position: relative;
            padding-bottom: 56.25%;
            /* 16:9 aspect ratio */
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

        /* Uploaded Video Preview */
        .uploaded-video-preview {
            border: 2px solid #3b82f6;
            border-radius: 12px;
            overflow: hidden;
            background: white;
        }

        .video-preview-header {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 600;
        }

        .video-preview-header i {
            font-size: 1.5rem;
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
            line-height: 0;
        }

        #map-marker i {
            font-size: 30px;
            color: #FF0000;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
            display: block;
            line-height: 1;
        }

        #map-marker.visible {
            display: flex !important;
            align-items: center;
            justify-content: center;
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
            let markerCoordX = 0;
            let markerCoordY = 0;
            let hasMarker = false;

            // Pan functionality
            let isPanning = false;
            let startPanX = 0;
            let startPanY = 0;
            let hasMoved = false;

            // Wait for image to load
            if (mapImage.complete) {
                console.log('Map image already loaded:', mapImage.naturalWidth, 'x', mapImage.naturalHeight);
            } else {
                mapImage.addEventListener('load', function() {
                    console.log('Map image loaded:', mapImage.naturalWidth, 'x', mapImage.naturalHeight);
                });
            }

            function placeMarker(coordX, coordY) {
                console.log(`placeMarker called with: X=${coordX}, Y=${coordY}`);

                markerCoordX = coordX;
                markerCoordY = coordY;
                hasMarker = true;

                // Update inputs
                latitudeInput.value = coordX;
                longitudeInput.value = coordY;

                // Update marker position
                updateMarkerPosition();

                // Visual feedback
                marker.classList.add('animate');
                setTimeout(() => marker.classList.remove('animate'), 500);

                console.log(`Marker should now be visible at coords: X=${coordX}, Y=${coordY}`);
            }

            function updateMarkerPosition() {
                if (!hasMarker) return;

                // Wait for image to load if not loaded yet
                if (!mapImage.complete || mapImage.naturalWidth === 0) {
                    mapImage.onload = updateMarkerPosition;
                    return;
                }

                // Use DISPLAYED dimensions, not natural dimensions
                const rect = mapImage.getBoundingClientRect();
                const displayedWidth = mapImage.offsetWidth;
                const displayedHeight = mapImage.offsetHeight;

                // Calculate pixel position from coordinates (0-1000 range) using displayed size
                const x = (markerCoordX / 1000) * displayedWidth;
                const y = (markerCoordY / 1000) * displayedHeight;

                // Show marker
                marker.style.display = 'flex';
                marker.classList.add('visible');
                marker.style.left = `${x}px`;
                marker.style.top = `${y}px`;

                console.log(
                    `Marker positioned at: left=${x}px, top=${y}px (displayed: ${displayedWidth}x${displayedHeight}, coords: ${markerCoordX}, ${markerCoordY})`
                );
            }

            function updateZoom(newZoom) {
                currentZoom = Math.max(minZoom, Math.min(maxZoom, newZoom));
                mapContainer.style.transform = `scale(${currentZoom})`;
                zoomLevelIndicator.textContent = `${Math.round(currentZoom * 100)}%`;
            }

            // Zoom controls
            zoomInBtn.addEventListener('click', () => {
                updateZoom(currentZoom + zoomStep);
            });

            zoomOutBtn.addEventListener('click', () => {
                updateZoom(currentZoom - zoomStep);
            });

            zoomResetBtn.addEventListener('click', () => {
                updateZoom(1);
                viewport.scrollLeft = 0;
                viewport.scrollTop = 0;
            });

            // Mouse wheel zoom
            viewport.addEventListener('wheel', (e) => {
                e.preventDefault();
                const delta = e.deltaY > 0 ? -zoomStep : zoomStep;
                updateZoom(currentZoom + delta);
            }, {
                passive: false
            });

            // Pan with mouse drag (right-click or left-click drag)
            viewport.addEventListener('mousedown', (e) => {
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

            viewport.addEventListener('mousemove', (e) => {
                if (!isPanning) return;
                hasMoved = true;
                viewport.scrollLeft = startPanX - e.clientX;
                viewport.scrollTop = startPanY - e.clientY;
            });

            viewport.addEventListener('mouseup', () => {
                isPanning = false;
                viewport.style.cursor = 'grab';
            });

            viewport.addEventListener('mouseleave', () => {
                isPanning = false;
                viewport.style.cursor = 'grab';
            });

            // Disable context menu on right-click for panning
            viewport.addEventListener('contextmenu', (e) => {
                e.preventDefault();
            });

            // Pan on map image with right-click or middle mouse button
            mapImage.addEventListener('mousedown', (e) => {
                // Right-click or middle mouse button for panning
                if (e.button === 2 || e.button === 1) {
                    e.preventDefault();
                    isPanning = true;
                    hasMoved = false;
                    startPanX = e.clientX + viewport.scrollLeft;
                    startPanY = e.clientY + viewport.scrollTop;
                    mapImage.style.cursor = 'grabbing';
                }
            });

            mapImage.addEventListener('mouseup', () => {
                if (isPanning) {
                    isPanning = false;
                    mapImage.style.cursor = 'crosshair';
                }
            });

            // Handle map click for marker placement
            mapImage.addEventListener('click', function(e) {
                if (hasMoved) {
                    hasMoved = false;
                    return;
                }

                // Ensure image is loaded
                if (!mapImage.complete || mapImage.naturalWidth === 0) {
                    console.error('Map image not loaded yet');
                    return;
                }

                // Get click position relative to the viewport
                const rect = mapImage.getBoundingClientRect();
                const clickX = e.clientX - rect.left;
                const clickY = e.clientY - rect.top;

                // Get the base (unscaled) dimensions of the image
                const baseWidth = mapImage.offsetWidth;
                const baseHeight = mapImage.offsetHeight;

                // Account for zoom: divide by currentZoom to get position on unscaled image
                const x = clickX / currentZoom;
                const y = clickY / currentZoom;

                // Normalize coordinates to 0-1000 range based on base size
                const normalizedX = (x / baseWidth) * 1000;
                const normalizedY = (y / baseHeight) * 1000;

                // Round to 2 decimal places
                const coordX = parseFloat(normalizedX.toFixed(2));
                const coordY = parseFloat(normalizedY.toFixed(2));

                console.log(
                    `Click: screen(${clickX}, ${clickY}) -> unscaled(${x}, ${y}) -> size(${baseWidth}x${baseHeight}) -> zoom(${currentZoom}) -> coords(${coordX}, ${coordY})`
                );

                placeMarker(coordX, coordY);
            });

            // Image preview handling
            const imageInput = document.getElementById('images');
            const preview = document.getElementById('image-preview');

            if (imageInput) {
                imageInput.addEventListener('change', function(e) {
                    preview.innerHTML = '';

                    if (e.target.files && e.target.files.length > 0) {
                        Array.from(e.target.files).forEach((file, index) => {
                            const reader = new FileReader();

                            reader.onload = function(e) {
                                const div = document.createElement('div');
                                div.className = 'admin-gallery-item';
                                div.innerHTML = `
                                <img src="${e.target.result}" alt="Preview ${index + 1}">
                                ${index === 0 ? '<span class="admin-primary-badge">Primary</span>' : ''}
                            `;
                                preview.appendChild(div);
                            };

                            reader.readAsDataURL(file);
                        });
                    }
                });
            }

            // YouTube Video Preview
            window.previewYoutubeVideo = function(url) {
                const preview = document.getElementById('youtube-preview');
                const iframe = document.getElementById('youtube-iframe');

                if (!url) {
                    preview.style.display = 'none';
                    return;
                }

                let videoId = null;
                const regex =
                    /(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/;
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
            };

            window.clearYoutubePreview = function() {
                document.getElementById('youtube-preview').style.display = 'none';
                document.getElementById('youtube_video_url').value = '';
                document.getElementById('youtube-iframe').src = '';
            };

            // Uploaded Video Preview
            window.previewUploadedVideo = function(event) {
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
            };

            window.clearVideoPreview = function() {
                const preview = document.getElementById('video-preview');
                const player = document.getElementById('video-player');
                const source = document.getElementById('video-source');
                const input = document.getElementById('uploaded_video');
                const uploadArea = document.getElementById('videoUploadArea');

                preview.style.display = 'none';
                source.src = '';
                player.load();
                input.value = '';
                uploadArea.classList.remove('has-video');
            };
        });
    </script>
@endpush
