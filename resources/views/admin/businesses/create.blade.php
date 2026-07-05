@extends('layouts.admin')

@section('page-title', 'Add New Business')

@section('content')
    <!-- Page Header -->
    <div class="admin-form-header mb-4">
        <div class="admin-form-header-content">
            <div>
                <a href="{{ route('admin.businesses.index') }}" class="admin-breadcrumb-link">
                    <i class='bx bx-chevron-left'></i> Back to Businesses
                </a>
                <h2 class="admin-form-title">Add New Business</h2>
                <p class="admin-form-subtitle">Create a new business listing in the directory</p>
            </div>
            <div class="admin-progress-circle">
                <i class='bx bx-store'></i>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger mb-4" style="border-radius:12px; border-left:4px solid #ef4444;">
            <strong><i class='bx bx-error-circle'></i> Please fix the following errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.businesses.store') }}" method="POST" enctype="multipart/form-data" id="businessForm">
        @csrf

        <div class="row g-4">
            <!-- Left Column: Main Info -->
            <div class="col-lg-8">

                <!-- Basic Information -->
                <div class="admin-form-card">
                    <div class="admin-form-card-header">
                        <div class="admin-form-card-icon primary">
                            <i class='bx bx-info-circle'></i>
                        </div>
                        <div>
                            <h5 class="admin-form-card-title">Basic Information</h5>
                            <p class="admin-form-card-subtitle">Core details about the business</p>
                        </div>
                    </div>
                    <div class="admin-form-card-body">

                        <div class="admin-form-group">
                            <label for="name" class="admin-form-label">
                                <i class='bx bx-rename'></i> Business Name <span class="admin-required">*</span>
                            </label>
                            <input type="text" class="admin-form-input @error('name') is-invalid @enderror"
                                id="name" name="name" value="{{ old('name') }}" required
                                placeholder="e.g. Juan's Bakery">
                            @error('name')
                                <div class="admin-form-error"><i class='bx bx-error-circle'></i> {{ $message }}</div>
                            @enderror
                        </div>

                        <div class="admin-form-row">
                            <div class="admin-form-col">
                                <label for="business_category_id" class="admin-form-label">
                                    <i class='bx bx-category'></i> Category <span class="admin-required">*</span>
                                </label>
                                <select class="admin-form-select @error('business_category_id') is-invalid @enderror"
                                    id="business_category_id" name="business_category_id" required>
                                    <option value="">Select Category</option>
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ old('business_category_id') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('business_category_id')
                                    <div class="admin-form-error"><i class='bx bx-error-circle'></i> {{ $message }}</div>
                                @enderror
                            </div>

                            <div class="admin-form-col">
                                <label for="owner_id" class="admin-form-label">
                                    <i class='bx bx-user'></i> Business Owner
                                </label>
                                <select class="admin-form-select @error('owner_id') is-invalid @enderror"
                                    id="owner_id" name="owner_id">
                                    <option value="">— No owner assigned —</option>
                                    @foreach ($owners as $owner)
                                        <option value="{{ $owner->id }}" {{ old('owner_id') == $owner->id ? 'selected' : '' }}>
                                            {{ $owner->name }} ({{ $owner->email }})
                                        </option>
                                    @endforeach
                                </select>
                                <div class="admin-form-help">
                                    <i class='bx bx-info-circle'></i> Only users with the Business Owner role are listed.
                                </div>
                                @error('owner_id')
                                    <div class="admin-form-error"><i class='bx bx-error-circle'></i> {{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="admin-form-group">
                            <label for="description" class="admin-form-label">
                                <i class='bx bx-detail'></i> Description <span class="admin-required">*</span>
                            </label>
                            <textarea class="admin-form-textarea @error('description') is-invalid @enderror"
                                id="description" name="description" rows="5" required
                                placeholder="Describe the business, what it offers, its specialties...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="admin-form-error"><i class='bx bx-error-circle'></i> {{ $message }}</div>
                            @enderror
                        </div>

                        <div class="admin-form-group">
                            <label for="services" class="admin-form-label">
                                <i class='bx bx-list-ul'></i> Services Offered
                            </label>
                            <textarea class="admin-form-textarea @error('services') is-invalid @enderror"
                                id="services" name="services" rows="4"
                                placeholder="One service per line:&#10;Dine-in&#10;Takeout&#10;Delivery">{{ old('services') }}</textarea>
                            <div class="admin-form-help">
                                <i class='bx bx-info-circle'></i> Enter one service per line.
                            </div>
                            @error('services')
                                <div class="admin-form-error"><i class='bx bx-error-circle'></i> {{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>

                <!-- Contact & Location -->
                <div class="admin-form-card">
                    <div class="admin-form-card-header">
                        <div class="admin-form-card-icon success">
                            <i class='bx bx-map-pin'></i>
                        </div>
                        <div>
                            <h5 class="admin-form-card-title">Contact & Location</h5>
                            <p class="admin-form-card-subtitle">Address and contact details</p>
                        </div>
                    </div>
                    <div class="admin-form-card-body">

                        <div class="admin-form-group">
                            <label for="address" class="admin-form-label">
                                <i class='bx bx-map'></i> Address <span class="admin-required">*</span>
                            </label>
                            <textarea class="admin-form-textarea @error('address') is-invalid @enderror"
                                id="address" name="address" rows="2" required
                                placeholder="Complete address in Dasmariñas, Cavite">{{ old('address') }}</textarea>
                            @error('address')
                                <div class="admin-form-error"><i class='bx bx-error-circle'></i> {{ $message }}</div>
                            @enderror
                        </div>

                        <div class="admin-form-row">
                            <div class="admin-form-col">
                                <label for="contact_number" class="admin-form-label">
                                    <i class='bx bx-phone'></i> Contact Number <span class="admin-required">*</span>
                                </label>
                                <input type="text" class="admin-form-input @error('contact_number') is-invalid @enderror"
                                    id="contact_number" name="contact_number" value="{{ old('contact_number') }}"
                                    required placeholder="e.g. 09XX-XXX-XXXX">
                                @error('contact_number')
                                    <div class="admin-form-error"><i class='bx bx-error-circle'></i> {{ $message }}</div>
                                @enderror
                            </div>

                            <div class="admin-form-col">
                                <label for="email" class="admin-form-label">
                                    <i class='bx bx-envelope'></i> Email Address
                                </label>
                                <input type="email" class="admin-form-input @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email') }}"
                                    placeholder="business@example.com">
                                @error('email')
                                    <div class="admin-form-error"><i class='bx bx-error-circle'></i> {{ $message }}</div>
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
                                    placeholder="https://www.example.com">
                                @error('website')
                                    <div class="admin-form-error"><i class='bx bx-error-circle'></i> {{ $message }}</div>
                                @enderror
                            </div>

                            <div class="admin-form-col">
                                <label for="google_maps_link" class="admin-form-label">
                                    <i class='bx bxl-google'></i> Google Maps Link
                                </label>
                                <input type="url" class="admin-form-input @error('google_maps_link') is-invalid @enderror"
                                    id="google_maps_link" name="google_maps_link" value="{{ old('google_maps_link') }}"
                                    placeholder="https://maps.google.com/...">
                                @error('google_maps_link')
                                    <div class="admin-form-error"><i class='bx bx-error-circle'></i> {{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="admin-form-group">
                            <label for="operating_hours" class="admin-form-label">
                                <i class='bx bx-time'></i> Operating Hours
                            </label>
                            <textarea class="admin-form-textarea @error('operating_hours') is-invalid @enderror"
                                id="operating_hours" name="operating_hours" rows="3"
                                placeholder="e.g. Mon-Fri: 8:00 AM – 6:00 PM&#10;Sat: 9:00 AM – 5:00 PM&#10;Sun: Closed">{{ old('operating_hours') }}</textarea>
                            @error('operating_hours')
                                <div class="admin-form-error"><i class='bx bx-error-circle'></i> {{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>

                <!-- Media -->
                <div class="admin-form-card">
                    <div class="admin-form-card-header">
                        <div class="admin-form-card-icon warning">
                            <i class='bx bx-image'></i>
                        </div>
                        <div>
                            <h5 class="admin-form-card-title">Media</h5>
                            <p class="admin-form-card-subtitle">Logo and gallery images</p>
                        </div>
                    </div>
                    <div class="admin-form-card-body">

                        <!-- Logo -->
                        <div class="admin-form-group">
                            <label class="admin-form-label">
                                <i class='bx bx-image-alt'></i> Business Logo
                            </label>
                            <div class="admin-upload-area">
                                <input type="file" class="admin-upload-input" id="logo" name="logo"
                                    accept="image/jpeg,image/png,image/jpg" onchange="previewLogo(event)">
                                <label for="logo" class="admin-upload-label" id="logoLabel">
                                    <div class="admin-upload-icon">
                                        <i class='bx bx-image-alt'></i>
                                    </div>
                                    <div class="admin-upload-text">
                                        <span class="admin-upload-title">Click to upload logo</span>
                                        <span class="admin-upload-subtitle">PNG, JPG up to 2MB</span>
                                    </div>
                                </label>
                                <div id="logoPreview" style="display:none; margin-top:1rem; text-align:center;">
                                    <img id="logoImg" src="" alt="Logo preview"
                                        style="max-height:120px; border-radius:12px; border:2px solid #e2e8f0;">
                                </div>
                            </div>
                            @error('logo')
                                <div class="admin-form-error"><i class='bx bx-error-circle'></i> {{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Gallery Images -->
                        <div class="admin-form-group">
                            <label class="admin-form-label">
                                <i class='bx bx-images'></i> Gallery Images
                            </label>
                            <div class="admin-upload-area">
                                <input type="file" class="admin-upload-input" id="images" name="images[]"
                                    accept="image/jpeg,image/png,image/jpg,image/webp" multiple
                                    onchange="previewGallery(event)">
                                <label for="images" class="admin-upload-label">
                                    <div class="admin-upload-icon">
                                        <i class='bx bx-images'></i>
                                    </div>
                                    <div class="admin-upload-text">
                                        <span class="admin-upload-title">Click to upload images</span>
                                        <span class="admin-upload-subtitle">PNG, JPG, WEBP — multiple files allowed</span>
                                    </div>
                                    <span class="admin-upload-info">First image will be set as primary</span>
                                </label>
                            </div>
                            <div id="galleryPreview" class="admin-image-preview-grid" style="display:none;"></div>
                            @error('images.*')
                                <div class="admin-form-error"><i class='bx bx-error-circle'></i> {{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>

            </div>

            <!-- Right Column: Settings -->
            <div class="col-lg-4">

                <!-- Status & Verification -->
                <div class="admin-form-card">
                    <div class="admin-form-card-header">
                        <div class="admin-form-card-icon secondary">
                            <i class='bx bx-shield'></i>
                        </div>
                        <div>
                            <h5 class="admin-form-card-title">Status & Verification</h5>
                            <p class="admin-form-card-subtitle">Listing visibility and trust settings</p>
                        </div>
                    </div>
                    <div class="admin-form-card-body">

                        <div class="admin-form-group">
                            <label for="status" class="admin-form-label">
                                <i class='bx bx-check-shield'></i> Listing Status <span class="admin-required">*</span>
                            </label>
                            <select class="admin-form-select @error('status') is-invalid @enderror"
                                id="status" name="status" required>
                                <option value="approved" {{ old('status', 'approved') === 'approved' ? 'selected' : '' }}>
                                    ✅ Approved
                                </option>
                                <option value="pending" {{ old('status') === 'pending' ? 'selected' : '' }}>
                                    ⏳ Pending
                                </option>
                                <option value="rejected" {{ old('status') === 'rejected' ? 'selected' : '' }}>
                                    ❌ Rejected
                                </option>
                            </select>
                            <div class="admin-form-help">
                                <i class='bx bx-info-circle'></i> Approved listings are visible to the public.
                            </div>
                            @error('status')
                                <div class="admin-form-error"><i class='bx bx-error-circle'></i> {{ $message }}</div>
                            @enderror
                        </div>

                        <div class="admin-toggle-group">
                            <div class="admin-toggle-item">
                                <div class="admin-toggle-info">
                                    <i class='bx bx-badge-check'></i>
                                    <div>
                                        <strong>Mark as Verified</strong>
                                        <p>Show the verified badge on this business listing.</p>
                                    </div>
                                </div>
                                <label class="admin-toggle-switch">
                                    <input type="checkbox" name="is_verified" value="1"
                                        {{ old('is_verified') ? 'checked' : '' }}>
                                    <span class="admin-toggle-slider"></span>
                                </label>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Business Permit -->
                <div class="admin-form-card">
                    <div class="admin-form-card-header">
                        <div class="admin-form-card-icon info">
                            <i class='bx bx-file'></i>
                        </div>
                        <div>
                            <h5 class="admin-form-card-title">Business Permit</h5>
                            <p class="admin-form-card-subtitle">Upload permit document (optional)</p>
                        </div>
                    </div>
                    <div class="admin-form-card-body">
                        <div class="admin-upload-area">
                            <input type="file" class="admin-upload-input" id="business_permit"
                                name="business_permit" accept=".pdf,image/jpeg,image/png,image/jpg"
                                onchange="showPermitName(event)">
                            <label for="business_permit" class="admin-upload-label">
                                <div class="admin-upload-icon" style="background: linear-gradient(135deg,#00A8E8,#0284c7);">
                                    <i class='bx bx-file'></i>
                                </div>
                                <div class="admin-upload-text">
                                    <span class="admin-upload-title">Click to upload permit</span>
                                    <span class="admin-upload-subtitle">PDF, PNG, JPG up to 5MB</span>
                                </div>
                            </label>
                        </div>
                        <div id="permitFilename" style="display:none; margin-top:0.75rem;"
                            class="admin-form-help">
                            <i class='bx bx-check-circle' style="color:#1a7838;"></i>
                            <span id="permitName"></span>
                        </div>
                        <div class="admin-form-help mt-2">
                            <i class='bx bx-info-circle'></i> If "Mark as Verified" is on and a permit is uploaded, the permit will be auto-verified.
                        </div>
                        @error('business_permit')
                            <div class="admin-form-error"><i class='bx bx-error-circle'></i> {{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Actions -->
                <div class="admin-form-card">
                    <div class="admin-form-card-body">
                        <div class="admin-form-actions">
                            <button type="submit" class="admin-form-btn primary">
                                <i class='bx bx-plus-circle'></i> Create Business
                            </button>
                            <a href="{{ route('admin.businesses.index') }}" class="admin-form-btn secondary">
                                <i class='bx bx-x'></i> Cancel
                            </a>
                        </div>
                    </div>
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
            box-shadow: 0 2px 8px rgba(0,0,0,.04);
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
            gap: .25rem;
            color: #64748b;
            text-decoration: none;
            font-size: .9375rem;
            font-weight: 600;
            margin-bottom: .75rem;
            transition: color .2s;
        }
        .admin-breadcrumb-link:hover { color: #1a7838; }
        .admin-form-title { font-size: 1.75rem; font-weight: 800; color: #1e293b; margin-bottom: .375rem; }
        .admin-form-subtitle { color: #64748b; margin: 0; font-size: .9375rem; }
        .admin-progress-circle {
            width: 70px; height: 70px; border-radius: 50%;
            background: linear-gradient(135deg,#1a7838,#27a345);
            display: flex; align-items: center; justify-content: center;
            font-size: 2rem; color: white;
            box-shadow: 0 4px 12px rgba(26,120,56,.25);
            flex-shrink: 0;
        }

        /* Cards */
        .admin-form-card {
            background: white; border-radius: 16px; margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,.06); border: 1px solid #f1f5f9; overflow: hidden;
        }
        .admin-form-card-header {
            padding: 1.75rem; border-bottom: 1px solid #f1f5f9;
            display: flex; align-items: center; gap: 1.25rem;
        }
        .admin-form-card-icon {
            width: 50px; height: 50px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem; color: white; flex-shrink: 0;
        }
        .admin-form-card-icon.primary  { background: linear-gradient(135deg,#1a7838,#27a345); }
        .admin-form-card-icon.success  { background: linear-gradient(135deg,#10b981,#059669); }
        .admin-form-card-icon.info     { background: linear-gradient(135deg,#00A8E8,#0284c7); }
        .admin-form-card-icon.warning  { background: linear-gradient(135deg,#f59e0b,#d97706); }
        .admin-form-card-icon.secondary{ background: linear-gradient(135deg,#64748b,#475569); }
        .admin-form-card-title  { font-size: 1.125rem; font-weight: 700; color: #1e293b; margin: 0 0 .25rem; }
        .admin-form-card-subtitle { font-size: .875rem; color: #64748b; margin: 0; }
        .admin-form-card-body { padding: 1.75rem; }

        /* Form elements */
        .admin-form-group { margin-bottom: 1.5rem; }
        .admin-form-row { display: grid; grid-template-columns: repeat(2,1fr); gap: 1.25rem; margin-bottom: 1.5rem; }
        .admin-form-col { display: flex; flex-direction: column; }
        .admin-form-label {
            display: flex; align-items: center; gap: .5rem;
            font-size: .9375rem; font-weight: 600; color: #475569; margin-bottom: .625rem;
        }
        .admin-form-label i { font-size: 1.125rem; color: #64748b; }
        .admin-required { color: #ef4444; font-weight: 700; }
        .admin-form-input,
        .admin-form-select,
        .admin-form-textarea {
            width: 100%; padding: .875rem 1.125rem;
            border: 2px solid #e2e8f0; border-radius: 10px;
            font-size: .9375rem; transition: all .25s; background: white; color: #1e293b;
        }
        .admin-form-input:focus,
        .admin-form-select:focus,
        .admin-form-textarea:focus {
            outline: none; border-color: #1a7838;
            box-shadow: 0 0 0 4px rgba(26,120,56,.1);
        }
        .admin-form-textarea { resize: vertical; min-height: 100px; }
        .admin-form-help {
            display: flex; align-items: center; gap: .5rem;
            font-size: .8125rem; color: #94a3b8; margin-top: .5rem;
        }
        .admin-form-error {
            display: flex; align-items: center; gap: .5rem;
            color: #ef4444; font-size: .875rem; margin-top: .5rem; font-weight: 600;
        }

        /* Upload */
        .admin-upload-area { position: relative; }
        .admin-upload-input { position: absolute; width: 1px; height: 1px; opacity: 0; pointer-events: none; }
        .admin-upload-label {
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            padding: 2.5rem 2rem; border: 2px dashed #cbd5e1; border-radius: 12px;
            background: #f8fafc; cursor: pointer; transition: all .3s;
        }
        .admin-upload-label:hover { border-color: #1a7838; background: #f0fdf4; }
        .admin-upload-icon {
            width: 60px; height: 60px; border-radius: 50%;
            background: linear-gradient(135deg,#e0f2e9,#d1fae5);
            display: flex; align-items: center; justify-content: center;
            font-size: 2rem; color: #1a7838; margin-bottom: 1rem;
        }
        .admin-upload-text { display: flex; flex-direction: column; align-items: center; gap: .25rem; margin-bottom: .75rem; }
        .admin-upload-title  { font-weight: 700; color: #1e293b; font-size: 1rem; }
        .admin-upload-subtitle { color: #64748b; font-size: .875rem; }
        .admin-upload-info { font-size: .8125rem; color: #94a3b8; }

        /* Gallery preview */
        .admin-image-preview-grid {
            display: grid; grid-template-columns: repeat(auto-fill,minmax(130px,1fr));
            gap: 1rem; margin-top: 1.25rem;
        }
        .admin-gallery-item {
            position: relative; aspect-ratio: 1;
            border-radius: 12px; overflow: hidden; border: 2px solid #e2e8f0; transition: all .3s;
        }
        .admin-gallery-item:hover { transform: scale(1.05); box-shadow: 0 8px 20px rgba(0,0,0,.15); }
        .admin-gallery-item img { width: 100%; height: 100%; object-fit: cover; }
        .admin-primary-badge {
            position: absolute; top: .5rem; left: .5rem;
            padding: .375rem .75rem;
            background: linear-gradient(135deg,#1a7838,#27a345);
            color: white; border-radius: 6px; font-size: .7rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: .05em;
        }

        /* Toggle */
        .admin-toggle-group { display: flex; flex-direction: column; gap: 1.25rem; }
        .admin-toggle-item {
            display: flex; justify-content: space-between; align-items: center;
            padding: 1.25rem; background: #f8fafc; border-radius: 12px; border: 1px solid #e2e8f0;
        }
        .admin-toggle-info { display: flex; align-items: flex-start; gap: 1rem; flex: 1; }
        .admin-toggle-info > i { font-size: 1.75rem; color: #1a7838; margin-top: .25rem; }
        .admin-toggle-info strong { display: block; font-size: .9375rem; color: #1e293b; margin-bottom: .25rem; }
        .admin-toggle-info p { font-size: .8125rem; color: #64748b; margin: 0; }
        .admin-toggle-switch { position: relative; display: inline-block; width: 52px; height: 28px; flex-shrink: 0; }
        .admin-toggle-switch input { opacity: 0; width: 0; height: 0; }
        .admin-toggle-slider {
            position: absolute; cursor: pointer; inset: 0;
            background-color: #cbd5e1; border-radius: 28px; transition: .3s;
        }
        .admin-toggle-slider:before {
            position: absolute; content: ""; height: 22px; width: 22px;
            left: 3px; bottom: 3px; background: white; border-radius: 50%;
            transition: .3s; box-shadow: 0 2px 4px rgba(0,0,0,.2);
        }
        .admin-toggle-switch input:checked + .admin-toggle-slider { background: linear-gradient(135deg,#1a7838,#27a345); }
        .admin-toggle-switch input:checked + .admin-toggle-slider:before { transform: translateX(24px); }

        /* Actions */
        .admin-form-actions { display: flex; flex-direction: column; gap: .875rem; }
        .admin-form-btn {
            display: flex; align-items: center; justify-content: center; gap: .75rem;
            padding: 1rem 1.5rem; border-radius: 12px; font-weight: 600; font-size: 1rem;
            text-decoration: none; border: none; cursor: pointer; transition: all .3s;
        }
        .admin-form-btn.primary {
            background: linear-gradient(135deg,#1a7838,#27a345); color: white;
            box-shadow: 0 4px 12px rgba(26,120,56,.25);
        }
        .admin-form-btn.primary:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(26,120,56,.35); }
        .admin-form-btn.secondary { background: #f1f5f9; color: #475569; }
        .admin-form-btn.secondary:hover { background: #e2e8f0; }
        .admin-form-btn i { font-size: 1.25rem; }

        @media (max-width: 768px) {
            .admin-form-header-content { flex-direction: column; }
            .admin-form-row { grid-template-columns: 1fr; }
        }
    </style>
@endpush

@push('scripts')
    <script>
        function previewLogo(event) {
            const file = event.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = e => {
                document.getElementById('logoImg').src = e.target.result;
                document.getElementById('logoPreview').style.display = 'block';
            };
            reader.readAsDataURL(file);
        }

        function previewGallery(event) {
            const files = Array.from(event.target.files);
            const grid = document.getElementById('galleryPreview');
            grid.innerHTML = '';
            grid.style.display = files.length ? 'grid' : 'none';
            files.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = e => {
                    const item = document.createElement('div');
                    item.className = 'admin-gallery-item';
                    item.innerHTML = `<img src="${e.target.result}" alt="Preview">
                        ${index === 0 ? '<span class="admin-primary-badge">Primary</span>' : ''}`;
                    grid.appendChild(item);
                };
                reader.readAsDataURL(file);
            });
        }

        function showPermitName(event) {
            const file = event.target.files[0];
            if (!file) return;
            document.getElementById('permitName').textContent = file.name;
            document.getElementById('permitFilename').style.display = 'flex';
        }
    </script>
@endpush
