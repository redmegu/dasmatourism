@extends('layouts.admin')

@section('page-title', 'Edit Promotion')

@section('content')
    <!-- Enhanced Page Header -->
    <div class="admin-page-header mb-4">
        <div class="admin-page-header-content">
            <div>
                <div class="admin-breadcrumb mb-2">
                    <a href="{{ route('admin.promotions.index') }}" class="admin-breadcrumb-link">
                        <i class='bx bx-bullhorn'></i> Promotions
                    </a>
                    <i class='bx bx-chevron-right'></i>
                    <a href="{{ route('admin.promotions.show', $promotion) }}" class="admin-breadcrumb-link">
                        {{ $promotion->title }}
                    </a>
                    <i class='bx bx-chevron-right'></i>
                    <span class="admin-breadcrumb-current">Edit</span>
                </div>
                <h2 class="admin-page-title">Edit Promotion</h2>
                <p class="admin-page-subtitle">Update promotion information and settings</p>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.promotions.update', $promotion) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Hidden input for promotable_id --}}
        <input type="hidden" name="promotable_id" value="{{ $promotion->promotable_id }}" id="promotable_id_hidden">

        <div class="row g-4">
            <!-- Main Form -->
            <div class="col-lg-8">
                <!-- Target Selection -->
                <div class="admin-form-section-card mb-4">
                    <div class="admin-form-section-header">
                        <div class="admin-form-section-icon primary">
                            <i class='bx bx-target-lock'></i>
                        </div>
                        <div>
                            <h5 class="admin-form-section-title">Promotion Target</h5>
                            <p class="admin-form-section-subtitle">Select what you want to promote</p>
                        </div>
                    </div>
                    <div class="admin-form-section-body">
                        <div class="admin-form-group">
                            <label class="admin-form-label">
                                <i class='bx bx-category'></i>
                                Target Type
                                <span class="admin-required">*</span>
                            </label>
                            <select class="admin-form-select @error('promotable_type') is-invalid @enderror"
                                id="promotable_type" name="promotable_type" required>
                                <option value="">Select Type</option>
                                <option value="App\Models\Attraction"
                                    {{ old('promotable_type', $promotion->promotable_type) == 'App\Models\Attraction' ? 'selected' : '' }}>
                                    Attraction
                                </option>
                                <option value="App\Models\Business"
                                    {{ old('promotable_type', $promotion->promotable_type) == 'App\Models\Business' ? 'selected' : '' }}>
                                    Business
                                </option>
                            </select>
                            @error('promotable_type')
                                <div class="admin-form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="admin-form-group" id="attraction_select"
                            style="display: {{ $promotion->promotable_type == 'App\Models\Attraction' ? 'block' : 'none' }};">
                            <label class="admin-form-label">
                                <i class='bx bx-map-pin'></i>
                                Select Attraction
                            </label>
                            <select class="admin-form-select @error('promotable_id') is-invalid @enderror"
                                id="attraction_id" name="attraction_id">
                                <option value="">Choose an attraction</option>
                                @foreach ($attractions as $attraction)
                                    <option value="{{ $attraction->id }}"
                                        {{ old('promotable_id', $promotion->promotable_id) == $attraction->id ? 'selected' : '' }}>
                                        {{ $attraction->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="admin-form-group" id="business_select"
                            style="display: {{ $promotion->promotable_type == 'App\Models\Business' ? 'block' : 'none' }};">
                            <label class="admin-form-label">
                                <i class='bx bx-store'></i>
                                Select Business
                            </label>
                            <select class="admin-form-select @error('promotable_id') is-invalid @enderror" id="business_id"
                                name="business_id">
                                <option value="">Choose a business</option>
                                @foreach ($businesses as $business)
                                    <option value="{{ $business->id }}"
                                        {{ old('promotable_id', $promotion->promotable_id) == $business->id ? 'selected' : '' }}>
                                        {{ $business->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('promotable_id')
                            <div class="admin-form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Promotion Details -->
                <div class="admin-form-section-card mb-4">
                    <div class="admin-form-section-header">
                        <div class="admin-form-section-icon success">
                            <i class='bx bx-info-circle'></i>
                        </div>
                        <div>
                            <h5 class="admin-form-section-title">Promotion Details</h5>
                            <p class="admin-form-section-subtitle">Update title, description and dates</p>
                        </div>
                    </div>
                    <div class="admin-form-section-body">
                        <div class="admin-form-group">
                            <label class="admin-form-label">
                                <i class='bx bx-text'></i>
                                Promotion Title
                                <span class="admin-required">*</span>
                            </label>
                            <input type="text" class="admin-form-input @error('title') is-invalid @enderror"
                                id="title" name="title" value="{{ old('title', $promotion->title) }}"
                                placeholder="e.g., Summer Sale - 50% Off" required>
                            @error('title')
                                <div class="admin-form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="admin-form-group">
                            <label class="admin-form-label">
                                <i class='bx bx-message-square-detail'></i>
                                Description
                                <span class="admin-required">*</span>
                            </label>
                            <textarea class="admin-form-textarea @error('description') is-invalid @enderror" id="description" name="description"
                                rows="5" placeholder="Describe your promotion in detail..." required>{{ old('description', $promotion->description) }}</textarea>
                            @error('description')
                                <div class="admin-form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="admin-form-group">
                                    <label class="admin-form-label">
                                        <i class='bx bx-calendar-event'></i>
                                        Start Date
                                        <span class="admin-required">*</span>
                                    </label>
                                    <input type="date" class="admin-form-input @error('start_date') is-invalid @enderror"
                                        id="start_date" name="start_date"
                                        value="{{ old('start_date', $promotion->start_date->format('Y-m-d')) }}" required>
                                    @error('start_date')
                                        <div class="admin-form-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="admin-form-group">
                                    <label class="admin-form-label">
                                        <i class='bx bx-calendar-x'></i>
                                        End Date
                                        <span class="admin-required">*</span>
                                    </label>
                                    <input type="date" class="admin-form-input @error('end_date') is-invalid @enderror"
                                        id="end_date" name="end_date"
                                        value="{{ old('end_date', $promotion->end_date->format('Y-m-d')) }}" required>
                                    @error('end_date')
                                        <div class="admin-form-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Current Image -->
                @if ($promotion->image)
                    <div class="admin-form-section-card mb-4">
                        <div class="admin-form-section-header">
                            <div class="admin-form-section-icon info">
                                <i class='bx bx-image'></i>
                            </div>
                            <div>
                                <h5 class="admin-form-section-title">Current Image</h5>
                                <p class="admin-form-section-subtitle">Currently displayed promotion image</p>
                            </div>
                        </div>
                        <div class="admin-form-section-body">
                            <div class="admin-current-image-wrapper">
                                <img src="{{ asset('storage/' . $promotion->image) }}" alt="{{ $promotion->title }}"
                                    class="admin-current-image">
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Update Image -->
                <div class="admin-form-section-card mb-4">
                    <div class="admin-form-section-header">
                        <div class="admin-form-section-icon warning">
                            <i class='bx bx-image-add'></i>
                        </div>
                        <div>
                            <h5 class="admin-form-section-title">{{ $promotion->image ? 'Update' : 'Upload' }} Image</h5>
                            <p class="admin-form-section-subtitle">
                                {{ $promotion->image ? 'Replace the current image' : 'Add a promotional image' }}</p>
                        </div>
                    </div>
                    <div class="admin-form-section-body">
                        <div class="admin-form-group">
                            <label class="admin-form-label">
                                <i class='bx bx-cloud-upload'></i>
                                Upload New Image
                            </label>
                            <div class="admin-file-upload-wrapper">
                                <input type="file" class="admin-file-input @error('image') is-invalid @enderror"
                                    id="image" name="image" accept="image/*">
                                <label for="image" class="admin-file-upload-label">
                                    <div class="admin-file-upload-icon">
                                        <i class='bx bx-cloud-upload'></i>
                                    </div>
                                    <div class="admin-file-upload-text">
                                        <strong>Click to upload</strong> or drag and drop
                                    </div>
                                    <div class="admin-file-upload-hint">
                                        {{ $promotion->image ? 'Leave empty to keep current image' : 'Recommended: 1200x600px • Max 2MB' }}
                                    </div>
                                </label>
                            </div>
                            @error('image')
                                <div class="admin-form-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div id="image-preview" class="admin-image-preview-container"></div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="admin-sticky-sidebar">
                    <!-- Settings -->
                    <div class="admin-sidebar-card mb-4">
                        <div class="admin-sidebar-card-header">
                            <i class='bx bx-cog'></i>
                            <span>Settings</span>
                        </div>
                        <div class="admin-sidebar-card-body">
                            <div class="admin-toggle-setting">
                                <div class="admin-toggle-info">
                                    <strong>Active Promotion</strong>
                                    <span>Enable to show publicly</span>
                                </div>
                                <label class="admin-toggle-switch">
                                    <input type="checkbox" id="is_active" name="is_active" value="1"
                                        {{ old('is_active', $promotion->is_active) ? 'checked' : '' }}>
                                    <span class="admin-toggle-slider"></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Statistics -->
                    <div class="admin-stat-sidebar-card mb-4">
                        <div class="admin-stat-sidebar-header">
                            <i class='bx bx-bar-chart'></i>
                            <span>Performance Stats</span>
                        </div>
                        <div class="admin-stat-sidebar-body">
                            <div class="admin-stat-sidebar-item primary">
                                <div class="admin-stat-sidebar-icon">
                                    <i class='bx bx-show'></i>
                                </div>
                                <div class="admin-stat-sidebar-content">
                                    <span class="admin-stat-sidebar-label">Total Views</span>
                                    <span class="admin-stat-sidebar-value">{{ number_format($promotion->views) }}</span>
                                </div>
                            </div>

                            <div class="admin-stat-sidebar-item success">
                                <div class="admin-stat-sidebar-icon">
                                    <i class='bx bx-calendar'></i>
                                </div>
                                <div class="admin-stat-sidebar-content">
                                    <span class="admin-stat-sidebar-label">Days Running</span>
                                    <span
                                        class="admin-stat-sidebar-value">{{ $promotion->start_date->diffInDays(now()) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="admin-sidebar-card">
                        <div class="admin-sidebar-card-body">
                            <div class="admin-form-actions">
                                <button type="submit" class="admin-submit-btn primary">
                                    <i class='bx bx-save'></i>
                                    <span>Update Promotion</span>
                                </button>
                                <a href="{{ route('admin.promotions.show', $promotion) }}"
                                    class="admin-submit-btn secondary">
                                    <i class='bx bx-x'></i>
                                    <span>Cancel</span>
                                </a>
                            </div>
                        </div>
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

        /* Current & Preview Images */
        .admin-current-image-wrapper {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border: 2px solid #e2e8f0;
        }

        .admin-current-image {
            width: 100%;
            height: auto;
            display: block;
        }

        .admin-preview-wrapper {
            margin-top: 1.25rem;
        }

        .admin-preview-label {
            font-size: 0.875rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 0.75rem;
            display: block;
        }

        .admin-preview-image {
            width: 100%;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        /* Sidebar */
        .admin-sticky-sidebar {
            position: sticky;
            top: 2rem;
        }

        .admin-sidebar-card {
            background: white;
            border-radius: 14px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #f1f5f9;
            overflow: hidden;
        }

        .admin-sidebar-card-header {
            padding: 1.25rem 1.5rem;
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 700;
            color: #1e293b;
        }

        .admin-sidebar-card-header i {
            font-size: 1.25rem;
            color: #1a7838;
        }

        .admin-sidebar-card-body {
            padding: 1.5rem;
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

        /* Stats Sidebar */
        .admin-stat-sidebar-card {
            background: white;
            border-radius: 14px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #f1f5f9;
            overflow: hidden;
        }

        .admin-stat-sidebar-header {
            padding: 1.25rem 1.5rem;
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 700;
            color: #1e293b;
        }

        .admin-stat-sidebar-header i {
            font-size: 1.25rem;
            color: #1a7838;
        }

        .admin-stat-sidebar-body {
            padding: 1rem;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .admin-stat-sidebar-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            border-radius: 10px;
            transition: all 0.2s ease;
        }

        .admin-stat-sidebar-item.primary {
            background: linear-gradient(135deg, #e0f2e9, #d1fae5);
        }

        .admin-stat-sidebar-item.success {
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        }

        .admin-stat-sidebar-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: white;
            flex-shrink: 0;
        }

        .admin-stat-sidebar-item.primary .admin-stat-sidebar-icon {
            background: linear-gradient(135deg, #1a7838, #27a345);
        }

        .admin-stat-sidebar-item.success .admin-stat-sidebar-icon {
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .admin-stat-sidebar-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .admin-stat-sidebar-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .admin-stat-sidebar-value {
            font-size: 1.5rem;
            font-weight: 800;
            color: #1e293b;
        }

        /* Form Actions */
        .admin-form-actions {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

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
        }

        .admin-submit-btn.secondary {
            background: #f1f5f9;
            color: #64748b;
        }

        .admin-submit-btn.secondary:hover {
            background: #e2e8f0;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .admin-sticky-sidebar {
                position: static;
            }
        }

        @media (max-width: 768px) {
            .admin-page-header {
                padding: 1.5rem;
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
        // Target type selection handler
        document.getElementById('promotable_type').addEventListener('change', function() {
            const attractionSelect = document.getElementById('attraction_select');
            const businessSelect = document.getElementById('business_select');

            attractionSelect.style.display = 'none';
            businessSelect.style.display = 'none';

            if (this.value === 'App\\Models\\Attraction') {
                attractionSelect.style.display = 'block';
                document.getElementById('business_id').value = '';
            } else if (this.value === 'App\\Models\\Business') {
                businessSelect.style.display = 'block';
                document.getElementById('attraction_id').value = '';
            }
        });

        // Update hidden promotable_id when selection changes
        document.getElementById('attraction_id').addEventListener('change', function() {
            document.getElementById('promotable_id_hidden').value = this.value;
        });

        document.getElementById('business_id').addEventListener('change', function() {
            document.getElementById('promotable_id_hidden').value = this.value;
        });

        // Image preview
        document.getElementById('image').addEventListener('change', function(e) {
            const preview = document.getElementById('image-preview');
            preview.innerHTML = '';

            if (e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'admin-preview-wrapper';
                    div.innerHTML = `
                <p class="admin-preview-label">New Image Preview:</p>
                <img src="${e.target.result}" alt="Preview" class="admin-preview-image">
            `;
                    preview.appendChild(div);
                };
                reader.readAsDataURL(e.target.files[0]);

                // Update file upload label
                const label = document.querySelector('.admin-file-upload-label');
                label.style.borderColor = '#1a7838';
                label.style.background = '#f0fdf4';
            }
        });

        // Drag and drop functionality
        const fileLabel = document.querySelector('.admin-file-upload-label');
        const fileInput = document.getElementById('image');

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
