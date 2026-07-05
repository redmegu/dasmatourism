@extends('layouts.admin')

@section('page-title', 'Convert Suggestion to Attraction')

@section('content')
    <!-- Enhanced Page Header -->
    <div class="admin-page-header mb-4">
        <div class="admin-page-header-content">
            <div>
                <div class="admin-breadcrumb mb-2">
                    <a href="{{ route('admin.suggestions.index') }}" class="admin-breadcrumb-link">
                        <i class='bx bx-bulb'></i> Suggestions
                    </a>
                    <i class='bx bx-chevron-right'></i>
                    <a href="{{ route('admin.suggestions.show', $suggestion) }}" class="admin-breadcrumb-link">
                        {{ $suggestion->name }}
                    </a>
                    <i class='bx bx-chevron-right'></i>
                    <span class="admin-breadcrumb-current">Convert</span>
                </div>
                <h2 class="admin-page-title">Convert to Attraction</h2>
                <p class="admin-page-subtitle">Review and finalize the attraction details</p>
            </div>
        </div>
    </div>

    <!-- Info Alert -->
    <div class="admin-info-alert mb-4">
        <div class="admin-info-alert-icon">
            <i class='bx bx-info-circle'></i>
        </div>
        <div class="admin-info-alert-content">
            <strong>Converting Suggestion:</strong> {{ $suggestion->name }}
            <p>Review and modify the information below before creating the official attraction.</p>
        </div>
    </div>

    <form action="{{ route('admin.attractions.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="from_suggestion" value="{{ $suggestion->id }}">

        <div class="row g-4">
            <!-- Main Form -->
            <div class="col-lg-8">
                <!-- Basic Information -->
                <div class="admin-form-section-card mb-4">
                    <div class="admin-form-section-header">
                        <div class="admin-form-section-icon primary">
                            <i class='bx bx-info-circle'></i>
                        </div>
                        <div>
                            <h5 class="admin-form-section-title">Basic Information</h5>
                            <p class="admin-form-section-subtitle">Essential details about the attraction</p>
                        </div>
                    </div>
                    <div class="admin-form-section-body">
                        <div class="admin-form-group">
                            <label class="admin-form-label">
                                <i class='bx bx-text'></i>
                                Attraction Name
                                <span class="admin-required">*</span>
                            </label>
                            <input type="text" class="admin-form-input @error('name') is-invalid @enderror"
                                name="name" value="{{ old('name', $suggestion->name) }}" required>
                            @error('name')
                                <div class="admin-form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="admin-form-group">
                            <label class="admin-form-label">
                                <i class='bx bx-message-square-detail'></i>
                                Description
                                <span class="admin-required">*</span>
                            </label>
                            <textarea class="admin-form-textarea @error('description') is-invalid @enderror" name="description" rows="6"
                                required>{{ old('description', $suggestion->description) }}</textarea>
                            @error('description')
                                <div class="admin-form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="admin-form-group">
                            <label class="admin-form-label">
                                <i class='bx bx-category'></i>
                                Category
                                <span class="admin-required">*</span>
                            </label>
                            <select class="admin-form-select @error('category_id') is-invalid @enderror" name="category_id"
                                required>
                                <option value="">Select Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id', $suggestion->category) == $category->name ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="admin-form-hint">
                                <i class='bx bx-info-circle'></i>
                                Suggested: <strong>{{ $suggestion->category }}</strong>
                            </small>
                            @error('category_id')
                                <div class="admin-form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Location Information -->
                <div class="admin-form-section-card mb-4">
                    <div class="admin-form-section-header">
                        <div class="admin-form-section-icon success">
                            <i class='bx bx-map-pin'></i>
                        </div>
                        <div>
                            <h5 class="admin-form-section-title">Location Details</h5>
                            <p class="admin-form-section-subtitle">Address and coordinates</p>
                        </div>
                    </div>
                    <div class="admin-form-section-body">
                        <div class="admin-form-group">
                            <label class="admin-form-label">
                                <i class='bx bx-map'></i>
                                Address
                                <span class="admin-required">*</span>
                            </label>
                            <input type="text" class="admin-form-input @error('address') is-invalid @enderror"
                                name="address" value="{{ old('address', $suggestion->location) }}" required>
                            @error('address')
                                <div class="admin-form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="admin-form-group">
                                    <label class="admin-form-label">
                                        <i class='bx bx-target-lock'></i>
                                        Latitude
                                    </label>
                                    <input type="text" class="admin-form-input @error('latitude') is-invalid @enderror"
                                        name="latitude" value="{{ old('latitude') }}" placeholder="14.2938">
                                    @error('latitude')
                                        <div class="admin-form-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="admin-form-group">
                                    <label class="admin-form-label">
                                        <i class='bx bx-target-lock'></i>
                                        Longitude
                                    </label>
                                    <input type="text" class="admin-form-input @error('longitude') is-invalid @enderror"
                                        name="longitude" value="{{ old('longitude') }}" placeholder="120.9367">
                                    @error('longitude')
                                        <div class="admin-form-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="admin-form-section-card mb-4">
                    <div class="admin-form-section-header">
                        <div class="admin-form-section-icon info">
                            <i class='bx bx-detail'></i>
                        </div>
                        <div>
                            <h5 class="admin-form-section-title">Additional Details</h5>
                            <p class="admin-form-section-subtitle">Operating hours, fees, and contact info</p>
                        </div>
                    </div>
                    <div class="admin-form-section-body">
                        <div class="admin-form-group">
                            <label class="admin-form-label">
                                <i class='bx bx-money'></i>
                                Entrance Fee
                            </label>
                            <input type="text" class="admin-form-input @error('entrance_fee') is-invalid @enderror"
                                name="entrance_fee" value="{{ old('entrance_fee') }}"
                                placeholder="Free / ₱50 / ₱100-₱200">
                            @error('entrance_fee')
                                <div class="admin-form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="admin-form-group">
                            <label class="admin-form-label">
                                <i class='bx bx-time'></i>
                                Operating Hours
                            </label>
                            <textarea class="admin-form-textarea @error('operating_hours') is-invalid @enderror" name="operating_hours"
                                rows="3">{{ old('operating_hours') }}</textarea>
                            <small class="admin-form-hint">e.g., Mon-Fri: 8AM-5PM, Weekends: 9AM-6PM</small>
                            @error('operating_hours')
                                <div class="admin-form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="admin-form-group">
                            <label class="admin-form-label">
                                <i class='bx bx-phone'></i>
                                Contact Number
                            </label>
                            <input type="text" class="admin-form-input @error('contact_number') is-invalid @enderror"
                                name="contact_number" value="{{ old('contact_number') }}">
                            @error('contact_number')
                                <div class="admin-form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Suggested Images -->
                @if ($suggestion->images && count($suggestion->images) > 0)
                    <div class="admin-form-section-card mb-4">
                        <div class="admin-form-section-header">
                            <div class="admin-form-section-icon warning">
                                <i class='bx bx-image'></i>
                            </div>
                            <div>
                                <h5 class="admin-form-section-title">Suggested Images</h5>
                                <p class="admin-form-section-subtitle">{{ count($suggestion->images) }} image(s) from
                                    submission</p>
                            </div>
                        </div>
                        <div class="admin-form-section-body">
                            <div class="admin-suggested-images-notice">
                                <i class='bx bx-info-circle'></i>
                                <p>These images were submitted with the suggestion. You can upload additional images below.
                                </p>
                            </div>
                            <div class="admin-convert-gallery">
                                @foreach ($suggestion->images as $image)
                                    <div class="admin-convert-gallery-item">
                                        <img src="{{ asset('storage/' . $image) }}" alt="{{ $suggestion->name }}">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Upload New Images -->
                <div class="admin-form-section-card">
                    <div class="admin-form-section-header">
                        <div class="admin-form-section-icon success">
                            <i class='bx bx-image-add'></i>
                        </div>
                        <div>
                            <h5 class="admin-form-section-title">Upload Additional Images</h5>
                            <p class="admin-form-section-subtitle">Add more photos to the attraction</p>
                        </div>
                    </div>
                    <div class="admin-form-section-body">
                        <div class="admin-form-group">
                            <label class="admin-form-label">
                                <i class='bx bx-cloud-upload'></i>
                                Upload Images
                            </label>
                            <div class="admin-file-upload-wrapper">
                                <input type="file" class="admin-file-input @error('images.*') is-invalid @enderror"
                                    id="images" name="images[]" accept="image/*" multiple>
                                <label for="images" class="admin-file-upload-label">
                                    <div class="admin-file-upload-icon">
                                        <i class='bx bx-cloud-upload'></i>
                                    </div>
                                    <div class="admin-file-upload-text">
                                        <strong>Click to upload</strong> or drag and drop
                                    </div>
                                    <div class="admin-file-upload-hint">
                                        You can select multiple images
                                    </div>
                                </label>
                            </div>
                            @error('images.*')
                                <div class="admin-form-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div id="image-preview" class="admin-image-preview-container"></div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Original Suggestion Info -->
                <div class="admin-suggestion-info-card mb-4">
                    <div class="admin-suggestion-info-header">
                        <i class='bx bx-bulb'></i>
                        <span>Original Suggestion</span>
                    </div>
                    <div class="admin-suggestion-info-body">
                        <div class="admin-suggestion-info-item">
                            <div class="admin-suggestion-info-label">Submitted By</div>
                            <div class="admin-suggestion-info-value">{{ $suggestion->user->name }}</div>
                        </div>
                        <div class="admin-suggestion-info-item">
                            <div class="admin-suggestion-info-label">Date</div>
                            <div class="admin-suggestion-info-value">{{ $suggestion->created_at->format('M d, Y') }}</div>
                        </div>
                        <div class="admin-suggestion-info-item">
                            <div class="admin-suggestion-info-label">Status</div>
                            <div class="admin-suggestion-info-value">
                                <span class="admin-suggestion-status {{ $suggestion->status }}">
                                    {{ ucfirst($suggestion->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Settings -->
                <div class="admin-settings-card mb-4">
                    <div class="admin-settings-card-header">
                        <i class='bx bx-cog'></i>
                        <span>Attraction Settings</span>
                    </div>
                    <div class="admin-settings-card-body">
                        <div class="admin-toggle-setting">
                            <div class="admin-toggle-info">
                                <strong>Featured Attraction</strong>
                                <span>Show in featured section</span>
                            </div>
                            <label class="admin-toggle-switch">
                                <input type="checkbox" name="is_featured" value="1">
                                <span class="admin-toggle-slider"></span>
                            </label>
                        </div>

                        <div class="admin-toggle-setting">
                            <div class="admin-toggle-info">
                                <strong>Active</strong>
                                <span>Visible to visitors</span>
                            </div>
                            <label class="admin-toggle-switch">
                                <input type="checkbox" name="is_active" value="1" checked>
                                <span class="admin-toggle-slider"></span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="admin-action-final-card">
                    <div class="admin-action-final-body">
                        <button type="submit" class="admin-submit-btn primary">
                            <i class='bx bx-check-circle'></i>
                            <span>Create Attraction</span>
                        </button>
                        <a href="{{ route('admin.suggestions.show', $suggestion) }}" class="admin-submit-btn secondary">
                            <i class='bx bx-x'></i>
                            <span>Cancel</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('styles')
    <style>
        /* Page Header */
        .admin-page-header {
            background: linear-gradient(135deg, #ffffff, #f8fafc);
            border-radius: 16px;
            padding: 2rem;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .admin-page-header-content {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 2rem;
        }

        .admin-breadcrumb {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            color: #64748b;
            margin-bottom: 0.5rem;
        }

        .admin-breadcrumb-link {
            display: flex;
            align-items: center;
            gap: 0.375rem;
            color: #1a7838;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .admin-breadcrumb-link:hover {
            color: #27a345;
        }

        .admin-breadcrumb-current {
            color: #94a3b8;
        }

        .admin-page-title {
            font-size: 1.75rem;
            font-weight: 800;
            color: #1e293b;
            margin-bottom: 0.375rem;
        }

        .admin-page-subtitle {
            color: #64748b;
            margin: 0;
            font-size: 0.9375rem;
        }

        /* Info Alert */
        .admin-info-alert {
            display: flex;
            gap: 1rem;
            padding: 1.5rem;
            background: linear-gradient(135deg, #dbeafe, #bfdbfe);
            border-radius: 14px;
            border-left: 4px solid #0284c7;
        }

        .admin-info-alert-icon {
            font-size: 2rem;
            color: #0284c7;
            flex-shrink: 0;
        }

        .admin-info-alert-content strong {
            display: block;
            font-size: 1rem;
            color: #1e293b;
            margin-bottom: 0.375rem;
        }

        .admin-info-alert-content p {
            font-size: 0.875rem;
            color: #475569;
            margin: 0;
        }

        /* Form Section Cards */
        .admin-form-section-card {
            background: white;
            border-radius: 14px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #f1f5f9;
            overflow: hidden;
        }

        .admin-form-section-header {
            padding: 1.5rem;
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .admin-form-section-icon {
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

        .admin-form-section-icon.primary {
            background: linear-gradient(135deg, #1a7838, #27a345);
        }

        .admin-form-section-icon.success {
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .admin-form-section-icon.info {
            background: linear-gradient(135deg, #00A8E8, #0284c7);
        }

        .admin-form-section-icon.warning {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }

        .admin-form-section-title {
            font-size: 1.125rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }

        .admin-form-section-subtitle {
            font-size: 0.8125rem;
            color: #64748b;
            margin: 0.25rem 0 0 0;
        }

        .admin-form-section-body {
            padding: 1.5rem;
        }

        /* Form Elements */
        .admin-form-group {
            margin-bottom: 1.25rem;
        }

        .admin-form-group:last-child {
            margin-bottom: 0;
        }

        .admin-form-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 0.625rem;
        }

        .admin-form-label i {
            font-size: 1rem;
            color: #64748b;
        }

        .admin-required {
            color: #dc2626;
            font-weight: 700;
        }

        .admin-form-input,
        .admin-form-select,
        .admin-form-textarea {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 0.9375rem;
            transition: all 0.25s ease;
            background: white;
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
            line-height: 1.6;
        }

        .admin-form-error {
            margin-top: 0.5rem;
            font-size: 0.8125rem;
            color: #dc2626;
            display: flex;
            align-items: center;
            gap: 0.375rem;
        }

        .admin-form-hint {
            display: block;
            margin-top: 0.5rem;
            font-size: 0.8125rem;
            color: #64748b;
        }

        /* File Upload */
        .admin-file-upload-wrapper {
            position: relative;
        }

        .admin-file-input {
            position: absolute;
            width: 0.1px;
            height: 0.1px;
            opacity: 0;
            overflow: hidden;
            z-index: -1;
        }

        .admin-file-upload-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2.5rem 2rem;
            border: 2px dashed #cbd5e1;
            border-radius: 12px;
            background: #f8fafc;
            cursor: pointer;
            transition: all 0.3s ease;
            margin: 0;
        }

        .admin-file-upload-label:hover {
            border-color: #1a7838;
            background: #f0fdf4;
        }

        .admin-file-upload-icon {
            font-size: 3rem;
            color: #64748b;
            margin-bottom: 1rem;
        }

        .admin-file-upload-text {
            font-size: 0.9375rem;
            color: #475569;
            margin-bottom: 0.5rem;
        }

        .admin-file-upload-text strong {
            color: #1a7838;
        }

        .admin-file-upload-hint {
            font-size: 0.8125rem;
            color: #94a3b8;
        }

        .admin-image-preview-container {
            margin-top: 1.25rem;
        }

        /* Suggested Images Notice */
        .admin-suggested-images-notice {
            display: flex;
            gap: 0.75rem;
            padding: 1rem;
            background: #fef3c7;
            border-radius: 10px;
            margin-bottom: 1.25rem;
        }

        .admin-suggested-images-notice i {
            font-size: 1.25rem;
            color: #d97706;
            flex-shrink: 0;
        }

        .admin-suggested-images-notice p {
            font-size: 0.875rem;
            color: #78350f;
            margin: 0;
        }

        /* Convert Gallery */
        .admin-convert-gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 1rem;
        }

        .admin-convert-gallery-item {
            position: relative;
            aspect-ratio: 1;
            border-radius: 12px;
            overflow: hidden;
            background: #f1f5f9;
            border: 2px solid #e2e8f0;
        }

        .admin-convert-gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Suggestion Info Card */
        .admin-suggestion-info-card {
            background: white;
            border-radius: 14px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #f1f5f9;
            overflow: hidden;
        }

        .admin-suggestion-info-header {
            padding: 1.25rem 1.5rem;
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 700;
            color: #1e293b;
        }

        .admin-suggestion-info-header i {
            font-size: 1.25rem;
            color: #1a7838;
        }

        .admin-suggestion-info-body {
            padding: 1rem;
        }

        .admin-suggestion-info-item {
            padding: 1rem;
            border-bottom: 1px solid #f1f5f9;
        }

        .admin-suggestion-info-item:last-child {
            border-bottom: none;
        }

        .admin-suggestion-info-label {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            margin-bottom: 0.5rem;
        }

        .admin-suggestion-info-value {
            font-size: 0.9375rem;
            font-weight: 600;
            color: #1e293b;
        }

        .admin-suggestion-status {
            display: inline-flex;
            align-items: center;
            padding: 0.375rem 0.875rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        .admin-suggestion-status.approved {
            background: #d1fae5;
            color: #059669;
        }

        .admin-suggestion-status.pending {
            background: #fef3c7;
            color: #d97706;
        }

        /* Settings Card */
        .admin-settings-card {
            background: white;
            border-radius: 14px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #f1f5f9;
            overflow: hidden;
        }

        .admin-settings-card-header {
            padding: 1.25rem 1.5rem;
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 700;
            color: #1e293b;
        }

        .admin-settings-card-header i {
            font-size: 1.25rem;
            color: #1a7838;
        }

        .admin-settings-card-body {
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        /* Toggle Setting */
        .admin-toggle-setting {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }

        .admin-toggle-info {
            flex: 1;
        }

        .admin-toggle-info strong {
            display: block;
            font-size: 0.9375rem;
            color: #1e293b;
            margin-bottom: 0.25rem;
        }

        .admin-toggle-info span {
            display: block;
            font-size: 0.8125rem;
            color: #64748b;
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
            transition: 0.3s;
            border-radius: 28px;
        }

        .admin-toggle-slider:before {
            position: absolute;
            content: "";
            height: 20px;
            width: 20px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: 0.3s;
            border-radius: 50%;
        }

        input:checked+.admin-toggle-slider {
            background: linear-gradient(135deg, #1a7838, #27a345);
        }

        input:checked+.admin-toggle-slider:before {
            transform: translateX(24px);
        }

        /* Action Final Card */
        .admin-action-final-card {
            background: white;
            border-radius: 14px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #f1f5f9;
        }

        .admin-action-final-body {
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        /* Submit Buttons */
        .admin-submit-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.875rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.9375rem;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            text-align: center;
        }

        .admin-submit-btn.primary {
            background: linear-gradient(135deg, #1a7838, #27a345);
            color: white;
            box-shadow: 0 4px 12px rgba(26, 120, 56, 0.25);
        }

        .admin-submit-btn.primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(26, 120, 56, 0.35);
            color: white;
        }

        .admin-submit-btn.secondary {
            background: #f1f5f9;
            color: #64748b;
        }

        .admin-submit-btn.secondary:hover {
            background: #e2e8f0;
            color: #64748b;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .admin-page-header {
                padding: 1.5rem;
            }

            .admin-convert-gallery {
                grid-template-columns: repeat(2, 1fr);
            }

            .admin-form-section-header {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Image preview
        document.getElementById('images').addEventListener('change', function(e) {
            const preview = document.getElementById('image-preview');
            preview.innerHTML = '';

            if (e.target.files && e.target.files.length > 0) {
                const grid = document.createElement('div');
                grid.className = 'admin-convert-gallery mt-3';

                Array.from(e.target.files).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const item = document.createElement('div');
                        item.className = 'admin-convert-gallery-item';
                        item.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
                        grid.appendChild(item);
                    };
                    reader.readAsDataURL(file);
                });

                preview.appendChild(grid);

                // Update file upload label
                const label = document.querySelector('.admin-file-upload-label');
                label.style.borderColor = '#1a7838';
                label.style.background = '#f0fdf4';
            }
        });

        // Drag and drop
        const fileLabel = document.querySelector('.admin-file-upload-label');
        const fileInput = document.getElementById('images');

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            fileLabel.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            fileLabel.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            fileLabel.addEventListener(eventName, unhighlight, false);
        });

        function highlight(e) {
            fileLabel.style.borderColor = '#1a7838';
            fileLabel.style.background = '#f0fdf4';
        }

        function unhighlight(e) {
            fileLabel.style.borderColor = '#cbd5e1';
            fileLabel.style.background = '#f8fafc';
        }

        fileLabel.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            fileInput.files = files;
            fileInput.dispatchEvent(new Event('change'));
        }
    </script>
@endpush
