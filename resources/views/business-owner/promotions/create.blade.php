@extends('layouts.business-owner')

@section('title', 'Create Promotion')
@section('page-title', 'Create New Promotion')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Header -->
            <div class="dashboard-card mb-4">
                <div class="dashboard-card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-2">Create a New Promotion</h4>
                            <p class="text-muted mb-0">Attract more customers with special offers</p>
                        </div>
                        <a href="{{ route('business-owner.promotions.index') }}" class="btn btn-outline-secondary">
                            <i class='bx bx-arrow-back'></i> Back
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

            <form method="POST" action="{{ route('business-owner.promotions.store') }}" enctype="multipart/form-data">
                @csrf

                <!-- Promotion Details -->
                <div class="dashboard-card">
                    <div class="dashboard-card-header">
                        <h5 class="dashboard-card-title">
                            <i class='bx bx-info-circle'></i> Promotion Details
                        </h5>
                    </div>
                    <div class="dashboard-card-body">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label for="title" class="form-label fw-semibold">
                                    <i class='bx bx-text'></i> Promotion Title <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                    id="title" name="title" value="{{ old('title') }}" required
                                    placeholder="e.g., 20% Off on All Services" maxlength="255">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label for="description" class="form-label fw-semibold">
                                    <i class='bx bx-message-square-detail'></i> Description <span
                                        class="text-danger">*</span>
                                </label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                    rows="5" required placeholder="Describe your promotion in detail. Include terms and conditions if any.">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label for="image" class="form-label fw-semibold">
                                    <i class='bx bx-image'></i> Promotion Image (Optional)
                                </label>
                                <input type="file" class="form-control @error('image') is-invalid @enderror"
                                    id="image" name="image" accept="image/*" onchange="previewPromotionImage(event)">
                                <small class="text-muted">JPG, PNG, or WebP. Max 2MB. Recommended: 800x600px</small>
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- Image Preview -->
                                <div id="imagePreviewContainer" class="mt-3 d-none">
                                    <img id="imagePreview" src="" alt="Preview" class="img-thumbnail"
                                        style="max-height: 200px;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Promotion Duration -->
                <div class="dashboard-card">
                    <div class="dashboard-card-header">
                        <h5 class="dashboard-card-title">
                            <i class='bx bx-calendar'></i> Promotion Duration
                        </h5>
                    </div>
                    <div class="dashboard-card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="start_date" class="form-label fw-semibold">
                                    <i class='bx bx-calendar-check'></i> Start Date <span class="text-danger">*</span>
                                </label>
                                <input type="date" class="form-control @error('start_date') is-invalid @enderror"
                                    id="start_date" name="start_date" value="{{ old('start_date', date('Y-m-d')) }}"
                                    min="{{ date('Y-m-d') }}" required>
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="end_date" class="form-label fw-semibold">
                                    <i class='bx bx-calendar-x'></i> End Date <span class="text-danger">*</span>
                                </label>
                                <input type="date" class="form-control @error('end_date') is-invalid @enderror"
                                    id="end_date" name="end_date" value="{{ old('end_date') }}" min="{{ date('Y-m-d') }}"
                                    required>
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex gap-2 justify-content-end">
                    <a href="{{ route('business-owner.promotions.index') }}" class="btn btn-outline-secondary btn-lg">
                        <i class='bx bx-x me-2'></i>Cancel
                    </a>
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class='bx bx-check me-2'></i>Create Promotion
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Image Preview
        function previewPromotionImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('imagePreview').src = e.target.result;
                    document.getElementById('imagePreviewContainer').classList.remove('d-none');
                };
                reader.readAsDataURL(file);
            } else {
                document.getElementById('imagePreviewContainer').classList.add('d-none');
            }
        }

        // Date validation
        document.getElementById('start_date').addEventListener('change', function() {
            const startDate = this.value;
            const endDateInput = document.getElementById('end_date');

            // Update end date minimum to start date
            endDateInput.min = startDate;

            // If end date is before start date, clear it
            if (endDateInput.value && endDateInput.value < startDate) {
                endDateInput.value = '';
            }
        });
    </script>
@endpush
