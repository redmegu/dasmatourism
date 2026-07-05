@extends('layouts.app')

@section('title', 'Edit Suggestion - Dasmariñas Tourism')

@section('content')
    <section class="profile-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <!-- Header -->
                    <div class="profile-header-card">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <div>
                                <h2 class="profile-header-title">
                                    <i class='bx bx-edit'></i> Edit Suggestion
                                </h2>
                                <p class="profile-header-subtitle">Update your place suggestion</p>
                            </div>
                            <a href="{{ route('user.suggestions.index') }}" class="btn btn-outline-primary">
                                <i class='bx bx-arrow-back me-2'></i>Cancel
                            </a>
                        </div>
                    </div>

                    <!-- Info Alert -->
                    <div class="alert alert-info d-flex align-items-start" role="alert">
                        <i class='bx bx-info-circle me-2' style="font-size: 1.5rem;"></i>
                        <div>
                            <strong>Editing Suggestion</strong>
                            <p class="mb-0">Your edited suggestion will be reviewed again by our team.</p>
                        </div>
                    </div>

                    <!-- Validation Errors Summary -->
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

                    <!-- Form -->
                    <form method="POST" action="{{ route('user.suggestions.update', $suggestion->id) }}"
                        enctype="multipart/form-data" id="suggestionForm">
                        @csrf
                        @method('PUT')

                        <!-- Basic Information -->
                        <div class="profile-card">
                            <div class="profile-card-header">
                                <h5 class="profile-card-title">
                                    <i class='bx bx-info-circle'></i> Basic Information
                                </h5>
                                <p class="text-muted mb-0 small">Required fields are marked with *</p>
                            </div>
                            <div class="profile-card-body">
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <label for="name" class="form-label fw-semibold">
                                            <i class='bx bx-map-pin'></i> Place Name <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name', $suggestion->name) }}"
                                            required placeholder="e.g., Paliparan Cave" maxlength="255">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-12">
                                        <label for="description" class="form-label fw-semibold">
                                            <i class='bx bx-message-square-detail'></i> Description <span
                                                class="text-danger">*</span>
                                        </label>
                                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                            rows="5" required placeholder="Tell us about this place, what makes it special?">{{ old('description', $suggestion->description) }}</textarea>
                                        <small class="text-muted">Be as detailed as possible. Include unique features,
                                            history, or significance.</small>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-12">
                                        <label for="address" class="form-label fw-semibold">
                                            <i class='bx bx-map'></i> Address <span class="text-danger">*</span>
                                        </label>
                                        <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="2"
                                            required placeholder="Complete address of the place (e.g., Barangay, Street, Dasmariñas City, Cavite)">{{ old('address', $suggestion->address) }}</textarea>
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="latitude" class="form-label fw-semibold">
                                            <i class='bx bx-navigation'></i> Latitude (Optional)
                                        </label>
                                        <input type="text" class="form-control @error('latitude') is-invalid @enderror"
                                            id="latitude" name="latitude"
                                            value="{{ old('latitude', $suggestion->latitude) }}" placeholder="e.g., 14.3294"
                                            pattern="^-?([1-8]?[0-9]\.{1}\d+|90\.{1}0+)$">
                                        <small class="text-muted">Find coordinates on <a href="https://www.google.com/maps"
                                                target="_blank">Google Maps</a></small>
                                        @error('latitude')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="longitude" class="form-label fw-semibold">
                                            <i class='bx bx-navigation'></i> Longitude (Optional)
                                        </label>
                                        <input type="text" class="form-control @error('longitude') is-invalid @enderror"
                                            id="longitude" name="longitude"
                                            value="{{ old('longitude', $suggestion->longitude) }}"
                                            placeholder="e.g., 120.9367" pattern="^-?([1]?[0-7]?[0-9]\.{1}\d+|180\.{1}0+)$">
                                        @error('longitude')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Current Photos -->
                        @if ($suggestion->images && is_array($suggestion->images) && count($suggestion->images) > 0)
                            <div class="profile-card">
                                <div class="profile-card-header">
                                    <h5 class="profile-card-title">
                                        <i class='bx bx-image'></i> Current Photos
                                    </h5>
                                </div>
                                <div class="profile-card-body">
                                    <div class="row g-2">
                                        @foreach ($suggestion->images as $image)
                                            <div class="col-md-3">
                                                <img src="{{ asset('storage/' . $image) }}" alt="Current photo"
                                                    class="img-fluid rounded"
                                                    style="width: 100%; height: 150px; object-fit: cover;">
                                            </div>
                                        @endforeach
                                    </div>
                                    <small class="text-muted d-block mt-2">
                                        <i class='bx bx-info-circle'></i> Uploading new photos will replace all current
                                        photos.
                                    </small>
                                </div>
                            </div>
                        @endif

                        <!-- Photos -->
                        <div class="profile-card">
                            <div class="profile-card-header">
                                <h5 class="profile-card-title">
                                    <i class='bx bx-image'></i> Update Photos (Optional)
                                </h5>
                                <p class="text-muted mb-0 small">Upload new photos to replace existing ones</p>
                            </div>
                            <div class="profile-card-body">
                                <label for="images" class="form-label fw-semibold">
                                    Upload New Photos
                                </label>
                                <input type="file" class="form-control @error('images.*') is-invalid @enderror"
                                    id="images" name="images[]" accept="image/jpeg,image/png,image/jpg" multiple
                                    onchange="previewImages(event)">
                                <small class="text-muted d-block mt-2">
                                    <i class='bx bx-info-circle'></i> You can upload multiple photos. JPG, PNG only. Max
                                    2MB each.
                                </small>
                                @error('images.*')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror

                                <!-- Image Preview -->
                                <div id="imagePreview" class="mt-3 d-none">
                                    <p class="fw-semibold small mb-2">Selected Images:</p>
                                    <div class="d-flex flex-wrap gap-2" id="previewContainer"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div class="profile-card">
                            <div class="profile-card-header">
                                <h5 class="profile-card-title">
                                    <i class='bx bx-detail'></i> Additional Information
                                </h5>
                            </div>
                            <div class="profile-card-body">
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <div class="form-check custom-checkbox">
                                            <input class="form-check-input" type="checkbox" id="is_historical"
                                                name="is_historical" value="1"
                                                {{ old('is_historical', $suggestion->is_historical) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_historical">
                                                <i class='bx bx-landmark'></i> This is a historical site
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <label for="reason" class="form-label fw-semibold">
                                            <i class='bx bx-comment-detail'></i> Why should this be added? (Optional)
                                        </label>
                                        <textarea class="form-control @error('reason') is-invalid @enderror" id="reason" name="reason" rows="3"
                                            placeholder="Tell us why this place is important to Dasmariñas tourism">{{ old('reason', $suggestion->reason) }}</textarea>
                                        @error('reason')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-2 justify-content-end flex-wrap">
                            <a href="{{ route('user.suggestions.index') }}" class="btn btn-outline-secondary btn-lg">
                                <i class='bx bx-x me-2'></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class='bx bx-save me-2'></i>Update Suggestion
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        function previewImages(event) {
            const preview = document.getElementById('imagePreview');
            const container = document.getElementById('previewContainer');
            const files = event.target.files;

            if (files.length > 0) {
                preview.classList.remove('d-none');
                container.innerHTML = '';

                Array.from(files).forEach((file, index) => {
                    if (file.type.match('image.*')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const img = document.createElement('img');
                            img.src = e.target.result;
                            img.style.width = '100px';
                            img.style.height = '100px';
                            img.style.objectFit = 'cover';
                            img.style.borderRadius = '8px';
                            img.style.border = '2px solid #e5e7eb';
                            container.appendChild(img);
                        };
                        reader.readAsDataURL(file);
                    }
                });
            } else {
                preview.classList.add('d-none');
            }
        }
    </script>
@endpush
