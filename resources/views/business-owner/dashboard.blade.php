@extends('layouts.business-owner')

@section('page-title', 'Business Dashboard')

@section('content')
    @if (!$business)
        <!-- Create Profile Prompt -->
        <div class="dashboard-welcome-card">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="fw-bold mb-3">
                        <i class='bx bx-store'></i> Welcome to Business Owner Dashboard!
                    </h2>
                    <p class="text-muted mb-4">
                        Start showcasing your business to thousands of tourists and locals in Dasmariñas City.
                        Create your business profile now and reach more customers!
                    </p>
                    <a href="{{ route('business-owner.profile.create') }}" class="btn btn-primary btn-lg">
                        <i class='bx bx-plus-circle me-2'></i>Create Business Profile
                    </a>
                </div>
                <div class="col-md-4 text-center">
                    <i class='bx bx-store-alt' style="font-size: 10rem; color: var(--dasma-green); opacity: 0.2;"></i>
                </div>
            </div>
        </div>
    @else
        <!-- Business Header Card -->
        <div class="business-header-card">
            <div class="row align-items-center">
                <div class="col-md-2 text-center">
                    <div class="business-logo-container">
                        @if ($business->logo)
                            <img src="{{ asset('storage/' . $business->logo) }}" alt="{{ $business->name }}"
                                class="business-logo">
                        @else
                            <div class="business-logo-placeholder">
                                <i class='bx bx-store'></i>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-md-8">
                    <h3 class="business-header-title">{{ $business->name }}</h3>
                    <p class="business-header-category">
                        <i class='bx bx-category'></i>
                        {{ $business->category->name }}
                    </p>
                    <div class="business-badges">
                        <span class="status-badge status-badge-{{ $business->status }}">
                            <i
                                class='bx {{ $business->status === 'approved' ? 'bx-check-circle' : ($business->status === 'pending' ? 'bx-time-five' : 'bx-x-circle') }}'></i>
                            {{ ucfirst($business->status) }}
                        </span>
                        @if ($business->is_verified)
                            <span class="status-badge status-badge-verified">
                                <i class='bx bxs-badge-check'></i>
                                Verified Business
                            </span>
                        @endif
                    </div>

                    @if ($business->status !== 'approved')
                        <div class="alert alert-warning mt-3">
                            <i class='bx bx-lock'></i>
                            Promotions will be unlocked once your business is approved by the admin.
                        </div>
                    @endif
                </div>

                <div class="col-md-2 text-end">
                    <div class="d-grid gap-2">
                        <a href="{{ route('business-owner.profile.show') }}" class="btn btn-outline-primary btn-sm">
                            <i class='bx bx-edit'></i> Edit Profile
                        </a>
                        <a href="{{ route('business-owner.profile.preview') }}" class="btn btn-outline-secondary btn-sm"
                            target="_blank">
                            <i class='bx bx-link-external'></i> View Public
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="dashboard-stat-card stat-card-primary">
                    <div class="stat-icon">
                        <i class='bx bx-show'></i>
                    </div>
                    <div class="stat-content">
                        <p class="stat-label">Total Views</p>
                        <h3 class="stat-value">{{ number_format($stats['total_views']) }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="dashboard-stat-card stat-card-warning">
                    <div class="stat-icon">
                        <i class='bx bxs-star'></i>
                    </div>
                    <div class="stat-content">
                        <p class="stat-label">Average Rating</p>
                        <h3 class="stat-value">{{ number_format($stats['average_rating'], 1) }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="dashboard-stat-card stat-card-success">
                    <div class="stat-icon">
                        <i class='bx bx-message-square-detail'></i>
                    </div>
                    <div class="stat-content">
                        <p class="stat-label">Total Reviews</p>
                        <h3 class="stat-value">{{ $stats['total_reviews'] }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="dashboard-stat-card stat-card-info">
                    <div class="stat-icon">
                        <i class='bx bx-badge-check'></i>
                    </div>
                    <div class="stat-content">
                        <p class="stat-label">Active Promotions</p>
                        <h3 class="stat-value">{{ $stats['active_promotions'] }}</h3>

                        @if ($business->status === 'approved')
                            <a href="{{ route('business-owner.promotions.create') }}" class="stat-link">
                                Create new <i class='bx bx-right-arrow-alt'></i>
                            </a>
                        @else
                            <span class="text-muted small">Awaiting approval</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="dashboard-card">
            <div class="dashboard-card-header">
                <h5 class="dashboard-card-title">
                    <i class='bx bx-bolt'></i> Quick Actions
                </h5>
            </div>
            <div class="dashboard-card-body">
                <div class="quick-action-list">

                    <a href="{{ route('business-owner.profile.edit') }}" class="quick-action-item">
                        <div class="quick-action-icon quick-action-icon-primary">
                            <i class='bx bx-edit'></i>
                        </div>
                        <div class="quick-action-content">
                            <h6>Update Profile</h6>
                            <p>Edit business information</p>
                        </div>
                        <i class='bx bx-chevron-right'></i>
                    </a>

                    @if ($business->status === 'approved')
                        <a href="{{ route('business-owner.promotions.create') }}" class="quick-action-item">
                            <div class="quick-action-icon quick-action-icon-success">
                                <i class='bx bx-plus-circle'></i>
                            </div>
                            <div class="quick-action-content">
                                <h6>Create Promotion</h6>
                                <p>Add special offers</p>
                            </div>
                            <i class='bx bx-chevron-right'></i>
                        </a>
                    @else
                        <div class="quick-action-item disabled" style="opacity:.5; cursor:not-allowed;">
                            <div class="quick-action-icon quick-action-icon-secondary">
                                <i class='bx bx-lock'></i>
                            </div>
                            <div class="quick-action-content">
                                <h6>Create Promotion</h6>
                                <p class="text-danger">Requires admin approval</p>
                            </div>
                        </div>
                    @endif

                    <a href="{{ route('business-owner.analytics') }}" class="quick-action-item">
                        <div class="quick-action-icon quick-action-icon-info">
                            <i class='bx bx-bar-chart'></i>
                        </div>
                        <div class="quick-action-content">
                            <h6>View Analytics</h6>
                            <p>Detailed insights</p>
                        </div>
                        <i class='bx bx-chevron-right'></i>
                    </a>

                </div>
            </div>
        </div>
    @endif
@endsection


@if (isset($business) && $viewsTrend->count() > 0)
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
        <script>
            const ctx = document.getElementById('viewsChart');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode(
                        $viewsTrend->pluck('date')->map(function ($date) {
                            return \Carbon\Carbon::parse($date)->format('M d');
                        }),
                    ) !!},
                    datasets: [{
                        label: 'Views',
                        data: {!! json_encode($viewsTrend->pluck('count')) !!},
                        borderColor: 'rgb(26, 120, 56)',
                        backgroundColor: 'rgba(26, 120, 56, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: 'rgb(26, 120, 56)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            borderRadius: 8,
                            titleFont: {
                                size: 14,
                                weight: 'bold'
                            },
                            bodyFont: {
                                size: 13
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                font: {
                                    size: 12
                                }
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 12
                                }
                            }
                        }
                    }
                }
            });
        </script>
    @endpush
@endif
