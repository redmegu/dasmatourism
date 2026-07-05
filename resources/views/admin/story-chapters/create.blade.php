@extends('layouts.admin')

@section('page-title', 'Create Story Chapter')

@section('content')
    <!-- Enhanced Page Header -->
    <div class="admin-form-header mb-4">
        <div class="admin-form-header-content">
            <div>
                <a href="{{ route('admin.story-chapters.index') }}" class="admin-breadcrumb-link">
                    <i class='bx bx-chevron-left'></i> Back to Chapters
                </a>
                <h2 class="admin-form-title">Create Story Chapter</h2>
                <p class="admin-form-subtitle">Write a new chapter for your interactive story</p>
            </div>
            <div class="admin-form-progress">
                <div class="admin-progress-circle">
                    <i class='bx bx-book-add'></i>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.story-chapters.store') }}" method="POST" enctype="multipart/form-data" id="chapterForm">
        @csrf

        <div class="row g-4">
            <!-- Main Form Content -->
            <div class="col-lg-8">
                <!-- Chapter Information -->
                <div class="admin-form-card">
                    <div class="admin-form-card-header">
                        <div class="admin-form-card-icon primary">
                            <i class='bx bx-book-open'></i>
                        </div>
                        <div>
                            <h5 class="admin-form-card-title">Chapter Information</h5>
                            <p class="admin-form-card-subtitle">Basic details about this chapter</p>
                        </div>
                    </div>
                    <div class="admin-form-card-body">
                        <div class="admin-form-row">
                            <div class="admin-form-col" style="flex: 0 0 150px;">
                                <label for="chapter_number" class="admin-form-label">
                                    <i class='bx bx-hash'></i> Chapter #
                                    <span class="admin-required">*</span>
                                </label>
                                <input type="number" class="admin-form-input @error('chapter_number') is-invalid @enderror"
                                    id="chapter_number" name="chapter_number" value="{{ old('chapter_number') }}" required
                                    min="1" placeholder="1">
                                @error('chapter_number')
                                    <div class="admin-form-error">
                                        <i class='bx bx-error-circle'></i> {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="admin-form-col" style="flex: 1;">
                                <label for="title" class="admin-form-label">
                                    <i class='bx bx-text'></i> Chapter Title
                                    <span class="admin-required">*</span>
                                </label>
                                <input type="text" class="admin-form-input @error('title') is-invalid @enderror"
                                    id="title" name="title" value="{{ old('title') }}" required
                                    placeholder="Enter chapter title">
                                @error('title')
                                    <div class="admin-form-error">
                                        <i class='bx bx-error-circle'></i> {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="admin-form-group">
                            <label for="content" class="admin-form-label">
                                <i class='bx bx-message-square-detail'></i> Chapter Content
                                <span class="admin-required">*</span>
                            </label>
                            <textarea class="admin-form-textarea-large @error('content') is-invalid @enderror" id="content" name="content"
                                rows="12" required placeholder="Write your story content here...">{{ old('content') }}</textarea>
                            <div class="admin-form-help">
                                <i class='bx bx-info-circle'></i> Write an engaging narrative for this chapter
                            </div>
                            @error('content')
                                <div class="admin-form-error">
                                    <i class='bx bx-error-circle'></i> {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="admin-form-group">
                            <label for="attraction_id" class="admin-form-label">
                                <i class='bx bx-map-pin'></i> Related Attraction
                            </label>
                            <select class="admin-form-select @error('attraction_id') is-invalid @enderror"
                                id="attraction_id" name="attraction_id">
                                <option value="">No attraction selected</option>
                                @foreach ($attractions as $attraction)
                                    <option value="{{ $attraction->id }}"
                                        {{ old('attraction_id') == $attraction->id ? 'selected' : '' }}>
                                        {{ $attraction->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="admin-form-help">
                                <i class='bx bx-info-circle'></i> Link this chapter to a specific tourist attraction
                            </div>
                            @error('attraction_id')
                                <div class="admin-form-error">
                                    <i class='bx bx-error-circle'></i> {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Background Image -->
                <div class="admin-form-card">
                    <div class="admin-form-card-header">
                        <div class="admin-form-card-icon warning">
                            <i class='bx bx-image'></i>
                        </div>
                        <div>
                            <h5 class="admin-form-card-title">Background Image</h5>
                            <p class="admin-form-card-subtitle">Optional background for this chapter</p>
                        </div>
                    </div>
                    <div class="admin-form-card-body">
                        <div class="admin-upload-area">
                            <input type="file" class="admin-upload-input" id="background_image" name="background_image"
                                accept="image/*">
                            <label for="background_image" class="admin-upload-label">
                                <div class="admin-upload-icon">
                                    <i class='bx bx-cloud-upload'></i>
                                </div>
                                <div class="admin-upload-text">
                                    <span class="admin-upload-title">Click to upload background image</span>
                                    <span class="admin-upload-subtitle">or drag and drop</span>
                                </div>
                                <div class="admin-upload-info">
                                    PNG, JPG up to 10MB
                                </div>
                            </label>
                        </div>
                        <div class="admin-form-help">
                            <i class='bx bx-info-circle'></i> This image will be displayed as the chapter background
                        </div>
                        @error('background_image')
                            <div class="admin-form-error">
                                <i class='bx bx-error-circle'></i> {{ $message }}
                            </div>
                        @enderror

                        <div id="image-preview" class="admin-image-preview-single"></div>
                    </div>
                </div>

                <!-- Visual Novel Images -->
                <div class="admin-form-card">
                    <div class="admin-form-card-header">
                        <div class="admin-form-card-icon info">
                            <i class='bx bx-user'></i>
                        </div>
                        <div>
                            <h5 class="admin-form-card-title">Visual Novel Images</h5>
                            <p class="admin-form-card-subtitle">Character poses and scene illustrations</p>
                        </div>
                    </div>
                    <div class="admin-form-card-body">
                        <div class="admin-upload-area">
                            <input type="file" class="admin-upload-input" id="visual_images" name="visual_images[]"
                                multiple accept="image/*">
                            <label for="visual_images" class="admin-upload-label">
                                <div class="admin-upload-icon">
                                    <i class='bx bx-image-add'></i>
                                </div>
                                <div class="admin-upload-text">
                                    <span class="admin-upload-title">Click to upload visual images</span>
                                    <span class="admin-upload-subtitle">Select multiple character/scene images</span>
                                </div>
                                <div class="admin-upload-info">
                                    PNG, JPG up to 10MB each
                                </div>
                            </label>
                        </div>
                        <div class="admin-form-help">
                            <i class='bx bx-info-circle'></i> Upload character poses or scene illustrations for visual
                            novel
                            style. Click X to remove images.
                        </div>
                        @error('visual_images.*')
                            <div class="admin-form-error">
                                <i class='bx bx-error-circle'></i> {{ $message }}
                            </div>
                        @enderror

                        <div id="visual-images-preview" class="admin-visual-images-grid"></div>
                    </div>
                </div>

                <!-- Character Models -->
                <div class="admin-form-card">
                    <div class="admin-form-card-header">
                        <div class="admin-form-card-icon secondary">
                            <i class='bx bx-body'></i>
                        </div>
                        <div>
                            <h5 class="admin-form-card-title">Character Models</h5>
                            <p class="admin-form-card-subtitle">Full-body character sprites</p>
                        </div>
                    </div>
                    <div class="admin-form-card-body">
                        <div class="admin-upload-area">
                            <input type="file" class="admin-upload-input" id="character_models"
                                name="character_models[]" multiple accept="image/*">
                            <label for="character_models" class="admin-upload-label">
                                <div class="admin-upload-icon">
                                    <i class='bx bx-user-plus'></i>
                                </div>
                                <div class="admin-upload-text">
                                    <span class="admin-upload-title">Click to upload character models</span>
                                    <span class="admin-upload-subtitle">Select multiple full-body character sprites</span>
                                </div>
                                <div class="admin-upload-info">
                                    PNG, JPG up to 10MB each (PNG with transparent background recommended)
                                </div>
                            </label>
                        </div>
                        <div class="admin-form-help">
                            <i class='bx bx-info-circle'></i> Upload full-body character sprites that will appear outside
                            the main story frame (like visual novel characters). PNG with transparent background works best.
                        </div>
                        @error('character_models.*')
                            <div class="admin-form-error">
                                <i class='bx bx-error-circle'></i> {{ $message }}
                            </div>
                        @enderror

                        <div id="character-models-preview" class="admin-visual-images-grid"></div>
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
                            <p class="admin-form-card-subtitle">Chapter preferences</p>
                        </div>
                    </div>
                    <div class="admin-form-card-body">
                        <div class="admin-toggle-item">
                            <div class="admin-toggle-info">
                                <i class='bx bx-check-circle'></i>
                                <div>
                                    <strong>Active Chapter</strong>
                                    <p>Make chapter available</p>
                                </div>
                            </div>
                            <label class="admin-toggle-switch">
                                <input type="checkbox" id="is_active" name="is_active" value="1"
                                    {{ old('is_active', true) ? 'checked' : '' }}>
                                <span class="admin-toggle-slider"></span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Tips Card -->
                <div class="admin-form-card">
                    <div class="admin-form-card-header">
                        <div class="admin-form-card-icon info">
                            <i class='bx bx-bulb'></i>
                        </div>
                        <div>
                            <h5 class="admin-form-card-title">Story Mode Tips</h5>
                            <p class="admin-form-card-subtitle">Best practices</p>
                        </div>
                    </div>
                    <div class="admin-form-card-body">
                        <div class="admin-tips-list">
                            <div class="admin-tip-item">
                                <i class='bx bx-check-circle'></i>
                                <span>Number chapters sequentially</span>
                            </div>
                            <div class="admin-tip-item">
                                <i class='bx bx-check-circle'></i>
                                <span>Write engaging narratives</span>
                            </div>
                            <div class="admin-tip-item">
                                <i class='bx bx-check-circle'></i>
                                <span>Link to attractions for context</span>
                            </div>
                            <div class="admin-tip-item">
                                <i class='bx bx-check-circle'></i>
                                <span>Add choices after creating</span>
                            </div>
                            <div class="admin-tip-item">
                                <i class='bx bx-check-circle'></i>
                                <span>Use vivid descriptions</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="admin-form-actions">
                    <button type="submit" class="admin-form-btn primary">
                        <i class='bx bx-save'></i>
                        <span>Create Chapter</span>
                    </button>
                    <a href="{{ route('admin.story-chapters.index') }}" class="admin-form-btn secondary">
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
        /* Reuse form styles from previous pages */
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

        .admin-form-card-icon.warning {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }

        .admin-form-card-icon.secondary {
            background: linear-gradient(135deg, #64748b, #475569);
        }

        .admin-form-card-icon.info {
            background: linear-gradient(135deg, #00A8E8, #0284c7);
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
        .admin-form-textarea,
        .admin-form-textarea-large {
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
        .admin-form-textarea:focus,
        .admin-form-textarea-large:focus {
            outline: none;
            border-color: #1a7838;
            box-shadow: 0 0 0 4px rgba(26, 120, 56, 0.1);
        }

        .admin-form-textarea-large {
            resize: vertical;
            min-height: 250px;
            line-height: 1.6;
            font-family: inherit;
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

        /* Image Preview */
        .admin-image-preview-single {
            margin-top: 1.5rem;
        }

        .admin-image-preview-single img {
            max-width: 100%;
            height: auto;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border: 2px solid #e2e8f0;
        }

        /* Toggle */
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

        /* Tips List */
        .admin-tips-list {
            display: flex;
            flex-direction: column;
            gap: 0.875rem;
        }

        .admin-tip-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.875rem;
            background: #f8fafc;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            font-size: 0.875rem;
            color: #475569;
            font-weight: 600;
        }

        .admin-tip-item i {
            font-size: 1.25rem;
            color: #10b981;
            flex-shrink: 0;
        }

        /* Action Buttons */
        .admin-form-actions {
            display: flex;
            flex-direction: column;
            gap: 0.875rem;
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

        /* Visual Images Grid */
        .admin-visual-images-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .visual-image-item {
            position: relative;
            border-radius: 12px;
            overflow: hidden;
            border: 2px solid #1a7838;
            box-shadow: 0 4px 12px rgba(26, 120, 56, 0.2);
            transition: all 0.3s ease;
            background: white;
        }

        .visual-image-item:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(26, 120, 56, 0.3);
        }

        .visual-image-item img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            display: block;
        }

        .visual-image-label {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(26, 120, 56, 0.95), transparent);
            color: white;
            padding: 0.75rem;
            font-size: 0.8125rem;
            font-weight: 700;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .visual-image-remove-btn {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: rgba(239, 68, 68, 0.95);
            border: 2px solid white;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 1.25rem;
            transition: all 0.3s ease;
            z-index: 10;
        }

        .visual-image-remove-btn:hover {
            background: #dc2626;
            transform: scale(1.1);
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

            .admin-visual-images-grid {
                grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Background image preview
            const imageInput = document.getElementById('background_image');
            const preview = document.getElementById('image-preview');

            imageInput.addEventListener('change', function(e) {
                preview.innerHTML = '';

                if (e.target.files && e.target.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.alt = 'Preview';
                        preview.appendChild(img);
                    };
                    reader.readAsDataURL(e.target.files[0]);
                }
            });

            // Visual images preview with remove functionality
            const visualImagesInput = document.getElementById('visual_images');
            const visualPreview = document.getElementById('visual-images-preview');
            let selectedFiles = [];

            visualImagesInput.addEventListener('change', function(e) {
                const newFiles = Array.from(e.target.files);
                selectedFiles = [...selectedFiles, ...newFiles];
                renderVisualImages();
            });

            // Character models preview with remove functionality
            const characterModelsInput = document.getElementById('character_models');
            const characterPreview = document.getElementById('character-models-preview');
            let selectedCharacterFiles = [];

            characterModelsInput.addEventListener('change', function(e) {
                const newFiles = Array.from(e.target.files);
                selectedCharacterFiles = [...selectedCharacterFiles, ...newFiles];
                renderCharacterModels();
            });

            function renderVisualImages() {
                visualPreview.innerHTML = '';

                selectedFiles.forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'visual-image-item';
                        div.dataset.index = index;
                        div.innerHTML = `
                            <img src="${e.target.result}" alt="Visual ${index + 1}">
                            <button type="button" class="visual-image-remove-btn" onclick="removeVisualImage(${index})">
                                <i class='bx bx-x'></i>
                            </button>
                            <div class="visual-image-label">
                                <i class='bx bx-image-add'></i> Image ${index + 1}
                            </div>
                        `;
                        visualPreview.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                });

                // Update the file input with remaining files
                updateFileInput();
            }

            function renderCharacterModels() {
                characterPreview.innerHTML = '';

                selectedCharacterFiles.forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'visual-image-item';
                        div.dataset.index = index;
                        div.innerHTML = `
                            <img src="${e.target.result}" alt="Character ${index + 1}">
                            <button type="button" class="visual-image-remove-btn" onclick="removeCharacterModel(${index})">
                                <i class='bx bx-x'></i>
                            </button>
                            <div class="visual-image-label">
                                <i class='bx bx-body'></i> Character ${index + 1}
                            </div>
                        `;
                        characterPreview.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                });

                // Update the file input with remaining files
                updateCharacterFileInput();
            }

            function updateFileInput() {
                const dataTransfer = new DataTransfer();
                selectedFiles.forEach(file => dataTransfer.items.add(file));
                visualImagesInput.files = dataTransfer.files;
            }

            function updateCharacterFileInput() {
                const dataTransfer = new DataTransfer();
                selectedCharacterFiles.forEach(file => dataTransfer.items.add(file));
                characterModelsInput.files = dataTransfer.files;
            }

            // Global function to remove image
            window.removeVisualImage = function(index) {
                selectedFiles.splice(index, 1);
                renderVisualImages();
            };

            // Global function to remove character model
            window.removeCharacterModel = function(index) {
                selectedCharacterFiles.splice(index, 1);
                renderCharacterModels();
            };
        });
    </script>
@endpush
