@extends('layouts.app')

@section('title', $suggestion->name . ' - My Suggestions')

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
                                    <i class='bx bx-map-pin'></i> Suggestion Details
                                </h2>
                                <p class="profile-header-subtitle">View your submitted place suggestion</p>
                            </div>
                            <a href="{{ route('user.suggestions.index') }}" class="btn btn-outline-primary">
                                <i class='bx bx-arrow-back me-2'></i>Back to Suggestions
                            </a>
                        </div>
                    </div>

                    <!-- Status Alert -->
                    @if ($suggestion->status === 'approved')
                        <div class="alert alert-success d-flex align-items-start" role="alert">
                            <i class='bx bx-check-circle me-2' style="font-size: 1.5rem;"></i>
                            <div>
                                <strong>Approved!</strong>
                                <p class="mb-0">Your suggestion has been approved and will be added to the attractions
                                    list.</p>
                            </div>
                        </div>
                    @elseif($suggestion->status === 'rejected')
                        <div class="alert alert-danger d-flex align-items-start" role="alert">
                            <i class='bx bx-x-circle me-2' style="font-size: 1.5rem;"></i>
                            <div>
                                <strong>Rejected</strong>
                                <p class="mb-0">Unfortunately, your suggestion was not approved at this time.</p>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning d-flex align-items-start" role="alert">
                            <i class='bx bx-time-five me-2' style="font-size: 1.5rem;"></i>
                            <div>
                                <strong>Under Review</strong>
                                <p class="mb-0">Your suggestion is being reviewed by our team. We'll notify you once it's
                                    processed.</p>
                            </div>
                        </div>
                    @endif

                    <!-- Place Information -->
                    <div class="profile-card">
                        <div class="profile-card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="profile-card-title">
                                    <i class='bx bx-info-circle'></i> Place Information
                                </h5>
                                <div class="suggestion-status-badge status-{{ $suggestion->status }}">
                                    <i
                                        class='bx {{ $suggestion->status === 'approved' ? 'bx-check-circle' : ($suggestion->status === 'rejected' ? 'bx-x-circle' : 'bx-time-five') }}'></i>
                                    {{ ucfirst($suggestion->status) }}
                                </div>
                            </div>
                        </div>
                        <div class="profile-card-body">
                            <div class="info-row">
                                <div class="info-label">
                                    <i class='bx bx-map-pin'></i> Place Name
                                </div>
                                <div class="info-value">{{ $suggestion->name }}</div>
                            </div>

                            <div class="info-row">
                                <div class="info-label">
                                    <i class='bx bx-message-square-detail'></i> Description
                                </div>
                                <div class="info-value">{{ $suggestion->description }}</div>
                            </div>

                            <div class="info-row">
                                <div class="info-label">
                                    <i class='bx bx-map'></i> Address
                                </div>
                                <div class="info-value">{{ $suggestion->address }}</div>
                            </div>

                            @if ($suggestion->latitude && $suggestion->longitude)
                                <div class="info-row">
                                    <div class="info-label">
                                        <i class='bx bx-navigation'></i> Coordinates
                                    </div>
                                    <div class="info-value">
                                        <span class="badge bg-secondary">{{ $suggestion->latitude }},
                                            {{ $suggestion->longitude }}</span>
                                        <a href="https://www.google.com/maps?q={{ $suggestion->latitude }},{{ $suggestion->longitude }}"
                                            target="_blank" class="btn btn-sm btn-outline-primary ms-2">
                                            <i class='bx bx-map'></i> View on Map
                                        </a>
                                    </div>
                                </div>
                            @endif

                            @if ($suggestion->is_historical)
                                <div class="info-row">
                                    <div class="info-label">
                                        <i class='bx bx-landmark'></i> Site Type
                                    </div>
                                    <div class="info-value">
                                        <span class="badge-historical-small">
                                            <i class='bx bx-landmark'></i> Historical Site
                                        </span>
                                    </div>
                                </div>
                            @endif

                            @if ($suggestion->reason)
                                <div class="info-row">
                                    <div class="info-label">
                                        <i class='bx bx-comment-detail'></i> Reason for Suggestion
                                    </div>
                                    <div class="info-value">{{ $suggestion->reason }}</div>
                                </div>
                            @endif

                            <div class="info-row">
                                <div class="info-label">
                                    <i class='bx bx-calendar'></i> Submitted On
                                </div>
                                <div class="info-value">{{ $suggestion->created_at->format('F d, Y h:i A') }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Images -->
                    @if ($suggestion->images && is_array($suggestion->images) && count($suggestion->images) > 0)
                        <div class="profile-card">
                            <div class="profile-card-header">
                                <h5 class="profile-card-title">
                                    <i class='bx bx-image'></i> Submitted Photos
                                </h5>
                            </div>
                            <div class="profile-card-body">
                                <div class="row g-3">
                                    @foreach ($suggestion->images as $image)
                                        <div class="col-md-4">
                                            <div class="suggestion-image-container">
                                                <img src="{{ asset('storage/' . $image) }}" alt="{{ $suggestion->name }}"
                                                    class="suggestion-image"
                                                    onclick="viewImageModal('{{ asset('storage/' . $image) }}')">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif


                    <!-- Admin Response -->
                    @if ($suggestion->admin_notes)
                        <div class="profile-card">
                            <div class="profile-card-header">
                                <h5 class="profile-card-title">
                                    <i class='bx bx-message-square-detail'></i> Admin Response
                                </h5>
                            </div>
                            <div class="profile-card-body">
                                <div class="suggestion-admin-response">
                                    <p class="mb-2"><strong>Message from the Admin Team:</strong></p>
                                    <p class="mb-0">{{ $suggestion->admin_notes }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Actions -->
                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('user.suggestions.index') }}" class="btn btn-outline-primary btn-lg">
                            <i class='bx bx-arrow-back me-2'></i>Back to Suggestions
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Image Modal -->
    <div class="modal" id="imageModal" tabindex="-1" style="display: none;">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-body p-0 position-relative">
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-3 bg-white"
                        style="z-index: 10;" aria-label="Close"></button>
                    <img src="" alt="Preview" id="modalImage" class="w-100" style="border-radius: 8px;">
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        function viewImageModal(imageSrc) {
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');

            modalImage.src = imageSrc;
            modal.style.display = 'block';
            modal.classList.add('show');
            document.body.classList.add('modal-open');

            // Create backdrop
            const backdrop = document.createElement('div');
            backdrop.className = 'modal-backdrop fade show';
            backdrop.id = 'modalBackdrop';
            document.body.appendChild(backdrop);
        }

        // Close modal function
        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            const backdrop = document.getElementById('modalBackdrop');

            modal.style.display = 'none';
            modal.classList.remove('show');
            document.body.classList.remove('modal-open');

            if (backdrop) {
                backdrop.remove();
            }
        }

        // Close modal when clicking close button
        document.addEventListener('DOMContentLoaded', function() {
            const closeBtn = document.querySelector('#imageModal .btn-close');
            const modal = document.getElementById('imageModal');

            if (closeBtn) {
                closeBtn.addEventListener('click', closeImageModal);
            }

            // Close when clicking outside modal
            if (modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        closeImageModal();
                    }
                });
            }
        });
    </script>
@endpush
