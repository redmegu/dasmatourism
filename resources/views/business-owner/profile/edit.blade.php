@extends('layouts.business-owner')

@section('title', 'Edit Business Profile')
@section('page-title', 'Edit Business Profile')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Header -->
            <div class="dashboard-card mb-4">
                <div class="dashboard-card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-2">Edit Your Business Profile</h4>
                            <p class="text-muted mb-0">Update your business information and settings</p>
                        </div>
                        <a href="{{ route('business-owner.profile.show') }}" class="btn btn-outline-secondary">
                            <i class='bx bx-x'></i> Cancel
                        </a>
                    </div>
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

            <form method="POST" action="{{ route('business-owner.profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Basic Information -->
                <div class="dashboard-card">
                    <div class="dashboard-card-header">
                        <h5 class="dashboard-card-title">
                            <i class='bx bx-info-circle'></i> Basic Information
                        </h5>
                    </div>
                    <div class="dashboard-card-body">
                        <div class="row g-3">
                            <!-- Business Logo -->
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">
                                    <i class='bx bx-image'></i> Business Logo
                                </label>
                                <div class="logo-upload-area">
                                    <div class="current-logo">
                                        @if ($business->logo)
                                            <img src="{{ asset('storage/' . $business->logo) }}" alt="Current Logo"
                                                id="logoPreview"
                                                style="width: 120px; height:120px; object-fit:cover; border-radius:12px;">
                                        @else
                                            <div class="logo-placeholder" id="logoPreview"
                                                style="width: 120px; height:120px; background:#f3f4f6; display:flex; align-items:center; justify-content:center; border-radius:12px;">
                                                <i class='bx bx-store' style="font-size:2.4rem; color:#64748b;"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="logo-upload-controls mt-2">
                                        <input type="file" class="form-control @error('logo') is-invalid @enderror"
                                            id="logo" name="logo" accept="image/*" onchange="previewLogo(event)">
                                        <small class="text-muted">Recommended: Square image, at least 500x500px</small>
                                        @error('logo')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Current Business Permit -->
                            @if ($business->business_permit)
                                <div class="col-md-12">
                                    <label class="form-label fw-semibold">
                                        <i class='bx bx-file-blank'></i> Current Business Permit
                                    </label>
                                    <div class="current-permit-display">
                                        <div class="permit-file-info">
                                            <div class="permit-file-icon">
                                                @if (Str::endsWith($business->business_permit, '.pdf'))
                                                    <i class='bx bxs-file-pdf'></i>
                                                @else
                                                    <i class='bx bxs-file-image'></i>
                                                @endif
                                            </div>
                                            <div class="permit-file-details">
                                                <p class="mb-1 fw-semibold">Business Permit</p>
                                                @if ($business->hasVerifiedPermit())
                                                    <span class="badge bg-success">
                                                        <i class='bx bx-check-circle'></i> Verified
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning">
                                                        <i class='bx bx-time'></i> Pending Verification
                                                    </span>
                                                @endif
                                            </div>
                                            <a href="{{ asset('storage/' . $business->business_permit) }}" target="_blank"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class='bx bx-show'></i> View
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Upload New Business Permit -->
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">
                                    <i class='bx bx-file-blank'></i>
                                    {{ $business->business_permit ? 'Replace Business Permit' : 'Upload Business Permit' }}
                                    @if (!$business->business_permit)
                                        <span class="text-danger">*</span>
                                    @endif
                                </label>
                                <div class="permit-upload-wrapper">
                                    <div class="permit-upload-area" id="permitUploadArea">
                                        <div class="permit-icon">
                                            <i class='bx bx-file-blank'></i>
                                        </div>
                                        <div class="permit-text">
                                            <p class="mb-1"><strong>Upload New Business Permit</strong></p>
                                            <p class="text-muted small mb-0">PDF, JPG, or PNG (Max 5MB)</p>
                                        </div>
                                    </div>
                                    <input type="file"
                                        class="form-control d-none @error('business_permit') is-invalid @enderror"
                                        id="business_permit" name="business_permit" accept=".pdf,.jpg,.jpeg,.png"
                                        {{ !$business->business_permit ? 'required' : '' }} onchange="previewPermit(event)">
                                    <label for="business_permit" class="upload-btn-permit">
                                        <i class='bx bx-upload'></i> Choose File
                                    </label>
                                </div>
                                @if ($business->business_permit)
                                    <small class="text-muted d-block mt-2">
                                        Leave empty to keep current permit. Uploading a new file will require
                                        re-verification.
                                    </small>
                                @endif
                                @error('business_permit')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                                <div id="permitFileName" class="mt-2 text-success fw-semibold" style="display: none;">
                                    <i class='bx bx-check-circle'></i> <span id="fileName"></span>
                                </div>
                            </div>


                            <!-- Business Images Upload -->
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">
                                    <i class='bx bx-images'></i> Business Images (You can select multiple)
                                </label>
                                <input type="file" class="form-control @error('images.*') is-invalid @enderror"
                                    id="images" name="images[]" accept="image/*" multiple>
                                <small class="text-muted">Upload photos to showcase your business environment, products, or
                                    services. You can select multiple images at once.</small>
                                @error('images.*')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- New Images Preview, with Remove and Primary selection -->
                                <input type="hidden" name="primary_new_image" id="primary_new_image" value="">
                                <div id="new-images-preview" class="current-images-grid mt-2"></div>
                            </div>

                            <!-- Other business fields... -->
                            <div class="col-md-12">
                                <label for="name" class="form-label fw-semibold">
                                    <i class='bx bx-store'></i> Business Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name', $business->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="business_category_id" class="form-label fw-semibold">
                                    <i class='bx bx-category'></i> Category <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('business_category_id') is-invalid @enderror"
                                    id="business_category_id" name="business_category_id" required>
                                    <option value="">Select a category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('business_category_id', $business->business_category_id) == $category->id ? 'selected' : '' }}>
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
                                    id="contact_number" name="contact_number"
                                    value="{{ old('contact_number', $business->contact_number) }}" required>
                                @error('contact_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label fw-semibold">
                                    <i class='bx bx-envelope'></i> Email Address (Optional)
                                </label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email', $business->email) }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="website" class="form-label fw-semibold">
                                    <i class='bx bx-globe'></i> Website (Optional)
                                </label>
                                <input type="url" class="form-control @error('website') is-invalid @enderror"
                                    id="website" name="website" value="{{ old('website', $business->website) }}"
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
                                    id="google_maps_link" name="google_maps_link"
                                    value="{{ old('google_maps_link', $business->google_maps_link) }}"
                                    placeholder="https://maps.app.goo.gl/...">
                                <small class="text-muted">Paste Google Maps share link for route assistance</small>
                                @error('google_maps_link')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label for="address" class="form-label fw-semibold">
                                    <i class='bx bx-map'></i> Address <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="2"
                                    required>{{ old('address', $business->address) }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label for="description" class="form-label fw-semibold">
                                    <i class='bx bx-message-square-detail'></i> Description <span
                                        class="text-danger">*</span>
                                </label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                    rows="5" required>{{ old('description', $business->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="dashboard-card">
                    <div class="dashboard-card-header">
                        <h5 class="dashboard-card-title">
                            <i class='bx bx-detail'></i> Additional Information
                        </h5>
                    </div>
                    <div class="dashboard-card-body">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label for="operating_hours" class="form-label fw-semibold">
                                    <i class='bx bx-time'></i> Operating Hours (Optional)
                                </label>
                                <textarea class="form-control @error('operating_hours') is-invalid @enderror" id="operating_hours"
                                    name="operating_hours" rows="4"
                                    placeholder="Monday - Friday: 8:00 AM - 5:00 PM
Saturday: 9:00 AM - 3:00 PM
Sunday: Closed">{{ old('operating_hours', $business->operating_hours) }}</textarea>
                                @error('operating_hours')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label for="services" class="form-label fw-semibold">
                                    <i class='bx bx-list-ul'></i> Services Offered (Optional)
                                </label>
                                <textarea class="form-control @error('services') is-invalid @enderror" id="services" name="services"
                                    rows="4" placeholder="List your services, one per line">{{ old('services', is_array($business->services) ? implode("\n", $business->services) : $business->services) }}</textarea>
                                <small class="text-muted">Enter each service on a new line</small>
                                @error('services')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Current Images -->
                @if ($business->images && count($business->images) > 0)
                    <div class="dashboard-card">
                        <div class="dashboard-card-header">
                            <h5 class="dashboard-card-title">
                                <i class='bx bx-image'></i> Current Business Photos
                            </h5>
                        </div>
                        <div class="dashboard-card-body">
                            <div class="current-images-grid">
                                @foreach ($business->images as $image)
                                    <div class="current-image-item">
                                        <img src="{{ asset('storage/' . $image->image_path) }}" alt="Business Photo">
                                        <button type="button" class="delete-image-btn"
                                            onclick="deleteImage({{ $image->id }})">
                                            <i class='bx bx-trash'></i>
                                        </button>
                                        <div style="position:absolute; bottom:8px; left:8px;">
                                            <label class="image-radio-label"
                                                style="display:flex; align-items:center; cursor:pointer; font-size:14px;">
                                                <input type="radio" name="primary_image_id"
                                                    value="{{ $image->id }}" style="margin-right:5px;"
                                                    {{ $image->is_primary ? 'checked' : '' }}>
                                                Primary
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="d-flex gap-2 justify-content-end">
                    <a href="{{ route('business-owner.profile.show') }}" class="btn btn-outline-secondary btn-lg">
                        <i class='bx bx-x me-2'></i>Cancel
                    </a>
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class='bx bx-save me-2'></i>Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .current-images-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 1.2em;
            margin-top: .25rem;
        }

        .current-image-item {
            position: relative;
            width: 120px;
            height: 120px;
            border-radius: 10px;
            overflow: hidden;
            background: #f4f4f5;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.09);
            margin-bottom: 0.5em;
        }

        .current-image-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .delete-image-btn {
            position: absolute;
            top: 7px;
            right: 7px;
            background: rgba(244, 63, 94, 0.92);
            border: none;
            color: #fff;
            border-radius: 7px;
            font-size: 1.1rem;
            padding: 2px 8px 2px 7px;
            cursor: pointer;
            z-index: 3;
            box-shadow: 0 1.5px 3.5px rgba(244, 63, 94, 0.18);
            transition: background .15s;
        }

        .delete-image-btn:hover {
            background: #be123c;
        }

        .image-radio-label input[type="radio"] {
            accent-color: #22d3ee;
        }

        /* Current Permit Display */
        .current-permit-display {
            background: #f8fafc;
            border-radius: 12px;
            padding: 1.5rem;
            border: 2px solid #e2e8f0;
        }

        .permit-file-info {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .permit-file-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            flex-shrink: 0;
        }

        .permit-file-details {
            flex: 1;
        }

        /* Reuse permit upload styles from create.blade.php */
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
        // Logo Preview
        function previewLogo(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('logoPreview');
                    if (preview.tagName === 'IMG') {
                        preview.src = e.target.result;
                    } else {
                        preview.outerHTML =
                            `<img src="${e.target.result}" alt="Logo Preview" id="logoPreview" style="width: 120px; height: 120px; object-fit: cover; border-radius: 12px;">`;
                    }
                };
                reader.readAsDataURL(file);
            }
        }

        // Preview Multiple New Images, Remove, and Select Primary
        document.addEventListener('DOMContentLoaded', function() {
            var imagesInput = document.getElementById('images');
            if (imagesInput) {
                imagesInput.addEventListener('change', function(event) {
                    const previewGrid = document.getElementById('new-images-preview');
                    previewGrid.innerHTML = '';
                    const filesArray = Array.from(event.target.files);

                    filesArray.forEach((file, index) => {
                        if (!file.type.startsWith('image/')) return;

                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const div = document.createElement('div');
                            div.className = 'current-image-item';
                            div.innerHTML =
                                `<img src="${e.target.result}" alt="Selected Image">
                            <button type="button" class="delete-image-btn" onclick="removeSelectedImage(${index})"><i class='bx bx-trash'></i></button>
                            <div style='position:absolute; bottom:8px; left:8px;'>
                                <label class="image-radio-label" style="display:flex; align-items:center; cursor:pointer; font-size:13px;">
                                    <input type="radio" name="new_primary_image"
                                           value="${index}" style="margin-right:5px;">Primary
                                </label>
                            </div>`;
                            previewGrid.appendChild(div);
                        };
                        reader.readAsDataURL(file);
                    });

                    // for global access in removeSelectedImage
                    window.selectedFiles = filesArray;
                });
            }
        });

        // Removes file at index from images input and preview
        function removeSelectedImage(index) {
            let input = document.getElementById('images');
            let dt = new DataTransfer();
            window.selectedFiles.forEach((file, i) => {
                if (i !== index) dt.items.add(file);
            });
            input.files = dt.files;
            // Trigger change to update preview and global selectedFiles
            input.dispatchEvent(new Event('change'));
        }

        // Delete Existing Image
        function deleteImage(imageId) {
            if (!confirm('Are you sure you want to delete this image?')) {
                return;
            }
            fetch(`/business-owner/profile/images/${imageId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error deleting image');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error deleting image');
                });
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
