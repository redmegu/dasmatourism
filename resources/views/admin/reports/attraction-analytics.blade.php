@extends('layouts.admin')

@section('page-title', 'Attraction Analytics')

@section('content')
    <!-- Enhanced Page Header -->
    <div class="admin-page-header mb-4">
        <div class="admin-page-header-content">
            <div>
                <div class="admin-breadcrumb mb-2">
                    <a href="{{ route('admin.reports.index') }}" class="admin-breadcrumb-link">
                        <i class='bx bx-bar-chart'></i> Reports
                    </a>
                    <i class='bx bx-chevron-right'></i>
                    <span class="admin-breadcrumb-current">Attraction Analytics</span>
                </div>
                <h2 class="admin-page-title">Attraction Analytics</h2>
                <p class="admin-page-subtitle">Performance metrics for tourist attractions</p>
            </div>
            <a href="{{ route('admin.reports.attractions.pdf', ['period' => request('period', 30)]) }}"
                class="btn btn-danger">
                <i class='bx bxs-file-pdf me-2'></i>Download PDF Report
            </a>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="admin-metric-card primary">
                <div class="admin-metric-card-icon">
                    <i class='bx bxs-map-pin'></i>
                </div>
                <div class="admin-metric-card-content">
                    <p class="admin-metric-card-label">Total Attractions</p>
                    <h3 class="admin-metric-card-value">{{ number_format($topAttractions->count()) }}</h3>
                </div>
                <div class="admin-metric-card-decoration"></div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="admin-metric-card success">
                <div class="admin-metric-card-icon">
                    <i class='bx bxs-show'></i>
                </div>
                <div class="admin-metric-card-content">
                    <p class="admin-metric-card-label">Total Views</p>
                    <h3 class="admin-metric-card-value">{{ number_format($topAttractions->sum('views')) }}</h3>
                </div>
                <div class="admin-metric-card-decoration"></div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="admin-metric-card warning">
                <div class="admin-metric-card-icon">
                    <i class='bx bxs-star'></i>
                </div>
                <div class="admin-metric-card-content">
                    <p class="admin-metric-card-label">Total Reviews</p>
                    <h3 class="admin-metric-card-value">{{ number_format($topAttractions->sum('total_reviews')) }}</h3>
                </div>
                <div class="admin-metric-card-decoration"></div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-4 mb-4">
        <!-- Top Attractions -->
        <div class="col-lg-8">
            <div class="admin-chart-card">
                <div class="admin-chart-card-header">
                    <div class="admin-chart-card-icon">
                        <i class='bx bx-trending-up'></i>
                    </div>
                    <div>
                        <h5 class="admin-chart-card-title">Top Attractions by Views</h5>
                        <p class="admin-chart-card-subtitle">Most popular destinations</p>
                    </div>
                </div>
                <div class="admin-chart-card-body-list">
                    @forelse($topAttractions->take(10) as $attraction)
                        <div class="admin-attraction-item">
                            <div class="admin-attraction-rank">{{ $loop->iteration }}</div>
                            <div class="admin-attraction-details">
                                <strong class="admin-attraction-name">{{ $attraction->name }}</strong>
                                <div class="admin-attraction-meta">
                                    <span class="admin-attraction-category">
                                        <i class='bx bx-category'></i>
                                        {{ $attraction->category->name ?? 'N/A' }}
                                    </span>
                                    <span class="admin-attraction-views">
                                        <i class='bx bx-show'></i>
                                        {{ number_format($attraction->views) }} views
                                    </span>
                                    <span class="admin-attraction-rating">
                                        <i class='bx bxs-star'></i>
                                        {{ number_format($attraction->average_rating ?? 0, 1) }}
                                        ({{ $attraction->total_reviews }} reviews)
                                    </span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="admin-empty-chart">
                            <i class='bx bx-map-pin'></i>
                            <p>No attractions data available</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Category Distribution -->
        <div class="col-lg-4">
            <div class="admin-chart-card">
                <div class="admin-chart-card-header">
                    <div class="admin-chart-card-icon">
                        <i class='bx bx-category'></i>
                    </div>
                    <div>
                        <h5 class="admin-chart-card-title">By Category</h5>
                        <p class="admin-chart-card-subtitle">Distribution overview</p>
                    </div>
                </div>
                <div class="admin-chart-card-body">
                    <canvas id="categoryChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Full Attractions Table -->
    <div class="admin-table-card">
        <div class="admin-table-card-header">
            <div class="admin-table-card-icon">
                <i class='bx bx-list-ul'></i>
            </div>
            <div>
                <h5 class="admin-table-card-title">All Attractions</h5>
                <p class="admin-table-card-subtitle">Complete attraction list</p>
            </div>
        </div>
        <div class="admin-table-wrapper">
            <table class="admin-modern-table">
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Attraction</th>
                        <th>Category</th>
                        <th>Views</th>
                        <th>Reviews</th>
                        <th>Avg Rating</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($topAttractions as $attraction)
                        <tr>
                            <td><strong>#{{ $loop->iteration }}</strong></td>
                            <td>{{ $attraction->name }}</td>
                            <td>
                                <span class="admin-category-badge">
                                    {{ $attraction->category->name ?? 'N/A' }}
                                </span>
                            </td>
                            <td>{{ number_format($attraction->views) }}</td>
                            <td>{{ number_format($attraction->total_reviews) }}</td>
                            <td>
                                <div class="admin-rating-display">
                                    <i class='bx bxs-star'></i>
                                    <span>{{ number_format($attraction->average_rating ?? 0, 1) }}</span>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
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

        /* Metric Cards */
        .admin-metric-card {
            background: white;
            border-radius: 16px;
            padding: 1.75rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid;
            position: relative;
            overflow: visible;
            transition: all 0.3s ease;
        }

        .admin-metric-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
        }

        .admin-metric-card.primary {
            border-color: rgba(26, 120, 56, 0.2);
            background: linear-gradient(135deg, #ffffff, #f0fdf4);
        }

        .admin-metric-card.success {
            border-color: rgba(16, 185, 129, 0.2);
            background: linear-gradient(135deg, #ffffff, #d1fae5);
        }

        .admin-metric-card.warning {
            border-color: rgba(245, 158, 11, 0.2);
            background: linear-gradient(135deg, #ffffff, #fef3c7);
        }

        .admin-metric-card-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            color: white;
            margin-bottom: 1rem;
            position: relative;
            z-index: 2;
        }

        .admin-metric-card.primary .admin-metric-card-icon {
            background: linear-gradient(135deg, #1a7838, #27a345);
        }

        .admin-metric-card.success .admin-metric-card-icon {
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .admin-metric-card.warning .admin-metric-card-icon {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }

        .admin-metric-card-label {
            font-size: 0.875rem;
            font-weight: 600;
            color: #64748b;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .admin-metric-card-value {
            font-size: 2.25rem;
            font-weight: 800;
            color: #1e293b;
            margin: 0;
        }

        .admin-metric-card-decoration {
            position: absolute;
            right: -20px;
            bottom: -20px;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            z-index: 0;
        }

        /* Chart Cards */
        .admin-chart-card {
            background: white;
            border-radius: 14px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #f1f5f9;
            overflow: hidden;
        }

        .admin-chart-card-header {
            padding: 1.5rem;
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .admin-chart-card-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            background: linear-gradient(135deg, #1a7838, #27a345);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }

        .admin-chart-card-title {
            font-size: 1.125rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }

        .admin-chart-card-subtitle {
            font-size: 0.8125rem;
            color: #64748b;
            margin: 0.25rem 0 0 0;
        }

        .admin-chart-card-body {
            padding: 2rem;
        }

        .admin-chart-card-body-list {
            padding: 0;
        }

        /* Attraction Items */
        .admin-attraction-item {
            display: flex;
            align-items: flex-start;
            gap: 1.25rem;
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #f1f5f9;
            transition: all 0.2s ease;
        }

        .admin-attraction-item:hover {
            background: #f8fafc;
        }

        .admin-attraction-item:last-child {
            border-bottom: none;
        }

        .admin-attraction-rank {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            background: linear-gradient(135deg, #1a7838, #27a345);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.9375rem;
            flex-shrink: 0;
        }

        .admin-attraction-details {
            flex: 1;
        }

        .admin-attraction-name {
            display: block;
            font-size: 1rem;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }

        .admin-attraction-meta {
            display: flex;
            gap: 1.5rem;
            flex-wrap: wrap;
        }

        .admin-attraction-category,
        .admin-attraction-views,
        .admin-attraction-rating {
            display: flex;
            align-items: center;
            gap: 0.375rem;
            font-size: 0.8125rem;
            color: #64748b;
        }

        .admin-attraction-rating i {
            color: #fbbf24;
        }

        /* Table Card */
        .admin-table-card {
            background: white;
            border-radius: 14px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #f1f5f9;
            overflow: hidden;
        }

        .admin-table-card-header {
            padding: 1.5rem;
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .admin-table-card-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            background: linear-gradient(135deg, #1a7838, #27a345);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }

        .admin-table-card-title {
            font-size: 1.125rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }

        .admin-table-card-subtitle {
            font-size: 0.8125rem;
            color: #64748b;
            margin: 0.25rem 0 0 0;
        }

        .admin-table-wrapper {
            overflow-x: auto;
        }

        .admin-modern-table {
            width: 100%;
            border-collapse: collapse;
        }

        .admin-modern-table thead {
            background: #f8fafc;
        }

        .admin-modern-table th {
            padding: 1rem 1.5rem;
            text-align: left;
            font-size: 0.8125rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 2px solid #e2e8f0;
        }

        .admin-modern-table td {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        .admin-modern-table tbody tr {
            transition: all 0.2s ease;
        }

        .admin-modern-table tbody tr:hover {
            background: #f8fafc;
        }

        .admin-category-badge {
            display: inline-block;
            padding: 0.375rem 0.75rem;
            border-radius: 6px;
            font-size: 0.8125rem;
            font-weight: 600;
            background: #e0f2e9;
            color: #1a7838;
        }

        .admin-rating-display {
            display: flex;
            align-items: center;
            gap: 0.375rem;
            font-weight: 600;
        }

        .admin-rating-display i {
            color: #fbbf24;
            font-size: 1.125rem;
        }

        /* Empty Chart */
        .admin-empty-chart {
            text-align: center;
            padding: 4rem 2rem;
            color: #94a3b8;
        }

        .admin-empty-chart i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.3;
        }

        .admin-empty-chart p {
            margin: 0;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .admin-page-header {
                padding: 1.5rem;
            }

            .admin-attraction-meta {
                flex-direction: column;
                gap: 0.5rem;
            }
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        const ctx = document.getElementById('categoryChart');

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($categoryStats->pluck('category.name')) !!},
                datasets: [{
                    data: {!! json_encode($categoryStats->pluck('count')) !!},
                    backgroundColor: [
                        '#1a7838',
                        '#10b981',
                        '#00A8E8',
                        '#f59e0b',
                        '#dc2626',
                        '#8b5cf6',
                        '#ec4899',
                        '#14b8a6'
                    ],
                    borderWidth: 3,
                    borderColor: '#fff',
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            font: {
                                size: 12,
                                weight: '600'
                            },
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        borderRadius: 8,
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        bodyFont: {
                            size: 14
                        }
                    }
                }
            }
        });
    </script>
@endpush
