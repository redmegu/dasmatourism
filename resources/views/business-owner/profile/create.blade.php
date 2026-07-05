@extends('layouts.business-owner')

@section('title', 'Create Business Profile')
@section('page-title', 'Create Business Profile')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Welcome Banner -->
            <div class="create-profile-banner">
                <div class="banner-icon">
                    <i class='bx bx-store'></i>
                </div>
                <div class="banner-content">
                    <h3>Register Your Business</h3>
                    <p>Showcase your business to thousands of tourists and locals in Dasmariñas City. Fill in the details
                        below to get started.</p>
                </div>
            </div>

            <!-- Info Alert -->
            <div class="alert alert-info d-flex align-items-start mb-4" role="alert">
                <i class='bx bx-info-circle me-3 banner-alert-icon'></i>
                <div>
                    <strong>What happens next?</strong>
                    <p class="mb-0">After submission, our team will review your business profile. You'll be notified once
                        it's approved and published in our directory.</p>
                </div>
            </div>

            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class='bx bx-error-circle me-2'></i>
                    <strong>Please fix the following errors:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Business Profile Form -->
            <form method="POST" action="{{ route('business-owner.profile.store') }}" enctype="multipart/form-data"
                class="business-create-form">
                @csrf

                <div class="dashboard-card">
                    <div class="dashboard-card-header">
                        <h5 class="dashboard-card-title">
                            <i class='bx bx-info-circle'></i> Basic Information
                        </h5>
                        <span class="badge bg-danger">Required</span>
                    </div>
                    <div class="dashboard-card-body">
                        <div class="row gy-4 gx-4">
                            <!-- Business Logo -->
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">
                                    <i class='bx bx-image'></i> Business Logo (Optional)
                                </label>
                                <div class="logo-upload-wrapper">
                                    <div class="logo-preview" id="logoPreview" tabindex="0">
                                        <i class='bx bx-image-add'></i>
                                        <p>Click to upload logo</p>
                                    </div>
                                    <input type="file" class="form-control d-none @error('logo') is-invalid @enderror"
                                        id="logo" name="logo" accept="image/*" onchange="previewLogo(event)">
                                    <label for="logo" class="upload-btn">
                                        <i class='bx bx-upload'></i> Choose Logo
                                    </label>
                                    <small class="text-muted d-block mt-2">Recommended: Square image, at least 500x500px
                                        (JPG, PNG, WebP)</small>
                                </div>
                                @error('logo')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Business Permit (REQUIRED) -->
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">
                                    <i class='bx bx-file-blank'></i> Business Permit <span class="text-danger">*</span>
                                </label>
                                <div class="permit-upload-wrapper">
                                    <div class="permit-upload-area" id="permitUploadArea">
                                        <div class="permit-icon">
                                            <i class='bx bx-file-blank'></i>
                                        </div>
                                        <div class="permit-text">
                                            <p class="mb-1"><strong>Upload Business Permit</strong></p>
                                            <p class="text-muted small mb-0">PDF, JPG, or PNG (Max 5MB)</p>
                                        </div>
                                    </div>
                                    <input type="file"
                                        class="form-control d-none @error('business_permit') is-invalid @enderror"
                                        id="business_permit" name="business_permit" accept=".pdf,.jpg,.jpeg,.png" required
                                        onchange="previewPermit(event)">
                                    <label for="business_permit" class="upload-btn-permit">
                                        <i class='bx bx-upload'></i> Choose File
                                    </label>
                                </div>
                                <div class="alert alert-warning mt-3 d-flex align-items-start" role="alert">
                                    <i class='bx bx-info-circle me-2 mt-1'></i>
                                    <small><strong>Required:</strong> Please upload a clear copy of your valid business
                                        permit issued by your city/municipality.</small>
                                </div>
                                @error('business_permit')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                                <div id="permitFileName" class="mt-2 text-success fw-semibold" style="display: none;">
                                    <i class='bx bx-check-circle'></i> <span id="fileName"></span>
                                </div>
                            </div>


                            <div class="col-md-12">
                                <label for="name" class="form-label fw-semibold">
                                    <i class='bx bx-store'></i> Business Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name') }}" required
                                    placeholder="e.g., Joe's Coffee Shop" maxlength="255">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="business_category_id" class="form-label fw-semibold">
                                    <i class='bx bx-category'></i> Business Category <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('business_category_id') is-invalid @enderror"
                                    id="business_category_id" name="business_category_id" required>
                                    <option value="">Select a category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('business_category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('business_category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="contact_number" class="form-label fw-semibold">
                                    <i class='bx bx-phone'></i> Contact Number <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('contact_number') is-invalid @enderror"
                                    id="contact_number" name="contact_number" value="{{ old('contact_number') }}"
                                    required placeholder="e.g., (046) 123-4567">
                                @error('contact_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label fw-semibold">
                                    <i class='bx bx-envelope'></i> Email Address (Optional)
                                </label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email') }}"
                                    placeholder="business@example.com">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="website" class="form-label fw-semibold">
                                    <i class='bx bx-globe'></i> Website (Optional)
                                </label>
                                <input type="url" class="form-control @error('website') is-invalid @enderror"
                                    id="website" name="website" value="{{ old('website') }}"
                                    placeholder="https://www.yourbusiness.com">
                                @error('website')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="google_maps_link" class="form-label fw-semibold">
                                    <i class='bx bx-map-pin'></i> Google Maps Link (Optional)
                                </label>
                                <input type="url" class="form-control @error('google_maps_link') is-invalid @enderror"
                                    id="google_maps_link" name="google_maps_link" value="{{ old('google_maps_link') }}"
                                    placeholder="https://maps.app.goo.gl/...">
                                <small class="text-muted">Paste Google Maps share link for route assistance</small>
                                @error('google_maps_link')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label for="address" class="form-label fw-semibold">
                                    <i class='bx bx-map'></i> Complete Address <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="2"
                                    required placeholder="Street, Barangay, City, Province">{{ old('address') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label for="description" class="form-label fw-semibold">
                                    <i class='bx bx-message-square-detail'></i> Business Description <span
                                        class="text-danger">*</span>
                                </label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                    rows="5" required
                                    placeholder="Tell customers about your business, products, services, and what makes you special...">{{ old('description') }}</textarea>
                                <small class="text-muted">A detailed description helps customers understand your business
                                    better</small>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>



                <!-- Additional Info -->
                <div class="dashboard-card">
                    <div class="dashboard-card-header">
                        <h5 class="dashboard-card-title">
                            <i class='bx bx-detail'></i> Additional Information
                        </h5>
                        <span class="badge bg-secondary">Optional</span>
                    </div>
                    <div class="dashboard-card-body">
                        <div class="row gy-4">
                            <div class="col-md-12">
                                <label for="operating_hours" class="form-label fw-semibold">
                                    <i class='bx bx-time'></i> Operating Hours
                                </label>
                                <textarea class="form-control @error('operating_hours') is-invalid @enderror" id="operating_hours"
                                    name="operating_hours" rows="4"
                                    placeholder="Monday - Friday: 8:00 AM - 5:00 PM&#10;Saturday: 9:00 AM - 3:00 PM&#10;Sunday: Closed">{{ old('operating_hours') }}</textarea>
                                @error('operating_hours')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label for="services" class="form-label fw-semibold">
                                    <i class='bx bx-list-ul'></i> Services Offered
                                </label>
                                <textarea class="form-control @error('services') is-invalid @enderror" id="services" name="services"
                                    rows="4" placeholder="List your services, one per line">{{ old('services') }}</textarea>
                                <small class="text-muted">Enter each service on a new line</small>
                                @error('services')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="d-flex gap-3 justify-content-end flex-wrap mt-3">
                    <a href="{{ route('business-owner.dashboard') }}" class="btn btn-outline-secondary btn-lg">
                        <i class='bx bx-x me-2'></i>Cancel
                    </a>
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class='bx bx-check me-2'></i>Submit for Review
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .create-profile-banner {
            background: linear-gradient(135deg, #e0f7fa, #f0fdfa);
            border-radius: 14px;
            padding: 2.25rem 1.75rem;
            display: flex;
            align-items: center;
            gap: 2rem;
            margin-bottom: 2rem;
            border: 1px solid #e3e9ef;
        }

        .banner-icon {
            background: linear-gradient(135deg, #06b6d4 60%, #16a34a 100%);
            color: #fff;
            width: 68px;
            height: 68px;
            border-radius: 16px;
            font-size: 2.45rem;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 16px rgba(22, 163, 74, 0.11);
        }

        .banner-content h3 {
            font-weight: 700;
            font-size: 1.45rem;
            margin-bottom: .5rem;
            color: #14b8a6;
        }

        .banner-content p {
            color: #64748b;
            margin-bottom: 0;
        }

        .banner-alert-icon {
            color: #0284c7;
        }

        .business-create-form .dashboard-card {
            background: #fff;
            border-radius: 1.25rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #e5e7eb;
            margin-bottom: 2rem;
        }

        .dashboard-card-header {
            padding: 1.5rem 1rem .75rem 1.5rem;
            background: linear-gradient(135deg, #f0fdfa, #f8fafc);
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .dashboard-card-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: #134e4a;
            margin: 0;
        }

        .dashboard-card-body {
            padding: 1.5rem;
        }

        .logo-upload-wrapper {
            display: flex;
            align-items: flex-start;
            gap: 1.25rem;
            flex-wrap: wrap;
        }

        .logo-preview {
            width: 84px;
            height: 84px;
            border-radius: 16px;
            background: #f0fdfa;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #94a3b8;
            border: 2px solid #e2e8f0;
            transition: border 0.25s, box-shadow .25s;
            padding: 7px 2px 3px 2px;
            gap: 3px;
        }

        .logo-preview i {
            font-size: 2.1rem;
            display: block;
            line-height: 1;
            margin-bottom: 0.1rem;
        }


        .logo-preview:focus,
        .logo-preview:hover {
            border: 2px solid #14b8a6;
            box-shadow: 0 6px 18px rgba(20, 184, 166, 0.06);
        }

        .logo-preview img {
            border-radius: 14px;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .upload-btn {
            background: linear-gradient(135deg, #06b6d4, #22c55e);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 8px 18px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 1px 5px rgba(20, 184, 166, 0.07);
            display: inline-flex;
            gap: 0.7em;
            align-items: center;
            font-size: 1em;
            transition: filter .12s;
        }

        .upload-btn:hover {
            filter: brightness(0.98);
            box-shadow: 0 4px 12px rgba(34, 197, 94, 0.10);
        }

        label.form-label,
        .fw-semibold {
            color: #0f172a;
        }

        textarea.form-control,
        input.form-control,
        select.form-select {
            border-radius: 10px;
            font-size: 1.08rem;
        }

        textarea.form-control:focus,
        input.form-control:focus,
        select.form-select:focus {
            border-color: #22c55e;
            box-shadow: 0 0 3pt 1pt #bbf7d0;
        }

        .dashboard-card .badge {
            margin-left: auto;
            letter-spacing: 0.03em;
            font-size: .95em;
        }

        .btn-lg i {
            font-size: 1.20em;
        }

        @media (max-width: 768px) {
            .create-profile-banner {
                flex-direction: column;
                align-items: flex-start;
            }

            .dashboard-card-header {
                flex-direction: column;
                gap: .6rem;
            }
        }

        /* Business Permit Upload Styles */
        .permit-upload-wrapper {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .permit-upload-area {
            border: 2px dashed #cbd5e1;
            border-radius: 12px;
            padding: 2rem;
            background: #f8fafc;
            display: flex;
            align-items: center;
            gap: 1.5rem;
            transition: all 0.3s ease;
        }

        .permit-upload-area:hover {
            border-color: #22c55e;
            background: #f0fdf4;
        }

        .permit-upload-area.has-file {
            border-color: #22c55e;
            background: #f0fdf4;
        }

        .permit-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            flex-shrink: 0;
        }

        .permit-text {
            flex: 1;
        }

        .permit-text p {
            margin: 0;
        }

        .upload-btn-permit {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 10px 24px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(245, 158, 11, 0.2);
            display: inline-flex;
            gap: 0.6em;
            align-items: center;
            font-size: 1em;
            transition: all 0.2s;
            align-self: flex-start;
        }

        .upload-btn-permit:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
        }
    </style>
@endpush

@push('scripts')
    <script>
        function previewLogo(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('logoPreview');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML =
                        `<img src="${e.target.result}" alt="Logo Preview" style="width: 100%; height: 100%; object-fit: cover;">`;
                };
                reader.readAsDataURL(file);
            }
        }

        function previewPermit(event) {
            const file = event.target.files[0];
            const uploadArea = document.getElementById('permitUploadArea');
            const fileNameDisplay = document.getElementById('permitFileName');
            const fileNameText = document.getElementById('fileName');

            if (file) {
                // Validate file size (5MB)
                if (file.size > 5120 * 1024) {
                    alert('File size must be less than 5MB');
                    event.target.value = '';
                    return;
                }

                // Validate file type
                const allowedTypes = ['application/pdf', 'image/jpeg', 'image/png', 'image/jpg'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Only PDF, JPG, and PNG files are allowed');
                    event.target.value = '';
                    return;
                }

                uploadArea.classList.add('has-file');
                fileNameDisplay.style.display = 'block';
                fileNameText.textContent = file.name;
            }
        }
    </script>
@endpush
