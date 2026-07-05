@extends('layouts.business-owner')

@section('title', 'Profile Status')
@section('page-title', 'Profile Status')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            @if ($business->status === 'pending')
                <!-- Pending Status -->
                <div class="status-message-card status-pending">
                    <div class="status-icon">
                        <i class='bx bx-time-five'></i>
                    </div>
                    <h2>Profile Under Review</h2>
                    <p class="status-description">
                        Thank you for submitting your business profile! Our team is currently reviewing your information
                        to ensure it meets our quality standards.
                    </p>

                    <div class="status-details">
                        <div class="status-detail-item">
                            <i class='bx bx-store'></i>
                            <div>
                                <strong>Business Name</strong>
                                <p>{{ $business->name }}</p>
                            </div>
                        </div>
                        <div class="status-detail-item">
                            <i class='bx bx-calendar'></i>
                            <div>
                                <strong>Submitted On</strong>
                                <p>{{ $business->created_at->format('F d, Y') }}</p>
                            </div>
                        </div>
                        <div class="status-detail-item">
                            <i class='bx bx-time'></i>
                            <div>
                                <strong>Review Time</strong>
                                <p>Usually within 2-3 business days</p>
                            </div>
                        </div>
                    </div>

                    <div class="status-info-box">
                        <h5><i class='bx bx-info-circle'></i> What Happens Next?</h5>
                        <ul>
                            <li>Our team will verify your business information</li>
                            <li>You'll receive a notification once the review is complete</li>
                            <li>Your business will be published in our directory after approval</li>
                            <li>You can edit your profile anytime, even during review</li>
                        </ul>
                    </div>

                    <div class="status-actions">
                        <a href="{{ route('business-owner.dashboard') }}" class="btn btn-primary">
                            <i class='bx bx-home'></i> Back to Dashboard
                        </a>
                        <a href="{{ route('business-owner.profile.edit') }}" class="btn btn-outline-secondary">
                            <i class='bx bx-edit'></i> Edit Profile
                        </a>
                    </div>
                </div>
            @elseif($business->status === 'rejected')
                <!-- Rejected Status -->
                <div class="status-message-card status-rejected">
                    <div class="status-icon">
                        <i class='bx bx-x-circle'></i>
                    </div>
                    <h2>Profile Not Approved</h2>
                    <p class="status-description">
                        Unfortunately, your business profile did not meet our current requirements.
                        Please review the reasons below and update your profile.
                    </p>

                    @if ($business->rejection_reason)
                        <div class="rejection-reason">
                            <h5><i class='bx bx-message-detail'></i> Reason for Rejection</h5>
                            <p>{{ $business->rejection_reason }}</p>
                        </div>
                    @endif

                    <div class="status-info-box status-info-danger">
                        <h5><i class='bx bx-error-circle'></i> Common Issues</h5>
                        <ul>
                            <li>Incomplete business information</li>
                            <li>Invalid contact details</li>
                            <li>Inappropriate content or images</li>
                            <li>Business category mismatch</li>
                        </ul>
                    </div>

                    <div class="status-actions">
                        <a href="{{ route('business-owner.profile.edit') }}" class="btn btn-primary">
                            <i class='bx bx-edit'></i> Update Profile
                        </a>
                        <a href="{{ route('business-owner.dashboard') }}" class="btn btn-outline-secondary">
                            <i class='bx bx-home'></i> Back to Dashboard
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
