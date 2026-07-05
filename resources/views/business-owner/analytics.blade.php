@extends('layouts.business-owner')

@section('title', 'Analytics')
@section('page-title', 'Business Analytics')

@section('content')
    <!-- Overview Stats -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="analytics-stat-card analytics-stat-primary">
                <div class="stat-icon">
                    <i class='bx bx-show'></i>
                </div>
                <div class="stat-content">
                    <p class="stat-label">Total Views</p>
                    <h3 class="stat-value">{{ number_format($totalViews) }}</h3>
                    <span class="stat-change positive">
                        <i class='bx bx-trending-up'></i> {{ $viewsGrowth }}% vs last month
                    </span>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="analytics-stat-card analytics-stat-success">
                <div class="stat-icon">
                    <i class='bx bxs-star'></i>
                </div>
                <div class="stat-content">
                    <p class="stat-label">Average Rating</p>
                    <h3 class="stat-value">{{ number_format($averageRating, 1) }}</h3>
                    <div class="rating-stars rating-stars-small mt-2">
                        @for ($i = 1; $i <= 5; $i++)
                            <i class='bx {{ $i <= $averageRating ? 'bxs-star' : 'bx-star' }}'></i>
                        @endfor
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="analytics-stat-card analytics-stat-info">
                <div class="stat-icon">
                    <i class='bx bx-message-square-detail'></i>
                </div>
                <div class="stat-content">
                    <p class="stat-label">Total Reviews</p>
                    <h3 class="stat-value">{{ number_format($totalReviews) }}</h3>
                    <span class="stat-change positive">
                        <i class='bx bx-trending-up'></i> {{ $reviewsThisMonth }} this month
                    </span>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="analytics-stat-card analytics-stat-warning">
                <div class="stat-icon">
                    <i class='bx bx-badge-check'></i>
                </div>
                <div class="stat-content">
                    <p class="stat-label">Active Promotions</p>
                    <h3 class="stat-value">{{ number_format($activePromotions) }}</h3>
                    <span class="stat-meta">
                        {{ number_format($totalPromotionViews) }} promotion views
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-4 mb-4">
        <!-- Views Chart -->
        <div class="col-lg-8">
            <div class="dashboard-card">
                <div class="dashboard-card-header">
                    <h5 class="dashboard-card-title">
                        <i class='bx bx-line-chart'></i> Views Over Time
                    </h5>
                    <div class="analytics-period-selector">
                        <button class="period-btn active" data-period="30">30 Days</button>
                        <button class="period-btn" data-period="60">60 Days</button>
                        <button class="period-btn" data-period="90">90 Days</button>
                    </div>
                </div>
                <div class="dashboard-card-body">
                    @if ($viewsChart->count() > 0)
                        <canvas id="viewsChart" height="80"></canvas>
                    @else
                        <div class="empty-chart-state">
                            <i class='bx bx-line-chart'></i>
                            <p>No data available yet</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Rating Distribution -->
        <div class="col-lg-4">
            <div class="dashboard-card">
                <div class="dashboard-card-header">
                    <h5 class="dashboard-card-title">
                        <i class='bx bxs-star'></i> Rating Distribution
                    </h5>
                </div>
                <div class="dashboard-card-body">
                    @foreach ($ratingDistribution as $rating => $count)
                        <div class="rating-bar-item">
                            <div class="rating-label">
                                <span>{{ $rating }} <i class='bx bxs-star'></i></span>
                                <span class="rating-count">{{ $count }}</span>
                            </div>
                            <div class="rating-bar">
                                <div class="rating-bar-fill"
                                    style="width: {{ $totalReviews > 0 ? ($count / $totalReviews) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Reviews & Top Sources -->
    <div class="row g-4">
        <!-- Recent Reviews -->
        <div class="col-lg-8">
            <div class="dashboard-card">
                <div class="dashboard-card-header">
                    <h5 class="dashboard-card-title">
                        <i class='bx bx-message-square-detail'></i> Recent Reviews
                    </h5>
                </div>
                <div class="dashboard-card-body p-0">
                    @forelse($recentReviews as $review)
                        <div class="review-item">
                            <div class="review-header">
                                <div class="review-user">
                                    <div class="review-user-avatar">
                                        @if ($review->user->profile_picture)
                                            <img src="{{ asset('storage/' . $review->user->profile_picture) }}"
                                                alt="{{ $review->user->name }}">
                                        @else
                                            <i class='bx bx-user'></i>
                                        @endif
                                    </div>
                                    <div>
                                        <h6 class="mb-0">{{ $review->user->name }}</h6>
                                        <div class="rating-stars rating-stars-small">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i class='bx {{ $i <= $review->rating ? 'bxs-star' : 'bx-star' }}'></i>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                                <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                            </div>
                            @if ($review->comment)
                                <p class="review-comment">{{ $review->comment }}</p>
                            @endif
                        </div>
                    @empty
                        <div class="empty-state-compact">
                            <i class='bx bx-message-square-detail'></i>
                            <p>No reviews yet</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="col-lg-4">
            <div class="dashboard-card">
                <div class="dashboard-card-header">
                    <h5 class="dashboard-card-title">
                        <i class='bx bx-pie-chart-alt-2'></i> Quick Insights
                    </h5>
                </div>
                <div class="dashboard-card-body">
                    <div class="insight-item">
                        <div class="insight-icon insight-icon-primary">
                            <i class='bx bx-trending-up'></i>
                        </div>
                        <div class="insight-content">
                            <h6>Peak Day</h6>
                            <p>{{ $peakDay }}</p>
                        </div>
                    </div>

                    <div class="insight-item">
                        <div class="insight-icon insight-icon-success">
                            <i class='bx bx-calendar-check'></i>
                        </div>
                        <div class="insight-content">
                            <h6>Avg. Views/Day</h6>
                            <p>{{ number_format($avgViewsPerDay, 1) }}</p>
                        </div>
                    </div>

                    <div class="insight-item">
                        <div class="insight-icon insight-icon-warning">
                            <i class='bx bx-user-check'></i>
                        </div>
                        <div class="insight-content">
                            <h6>Total Reviewers</h6>
                            <p>{{ number_format($totalReviewers) }}</p>
                        </div>
                    </div>

                    <div class="insight-item">
                        <div class="insight-icon insight-icon-info">
                            <i class='bx bx-time-five'></i>
                        </div>
                        <div class="insight-content">
                            <h6>Response Rate</h6>
                            <p>Coming Soon</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        @if ($viewsChart->count() > 0)
            // Views Chart
            const ctx = document.getElementById('viewsChart');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode(
                        $viewsChart->pluck('date')->map(function ($date) {
                            return \Carbon\Carbon::parse($date)->format('M d');
                        }),
                    ) !!},
                    datasets: [{
                        label: 'Views',
                        data: {!! json_encode($viewsChart->pluck('count')) !!},
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
                    maintainAspectRatio: true,
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
        @endif
    </script>
@endpush
