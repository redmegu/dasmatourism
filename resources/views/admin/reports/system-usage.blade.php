@extends('layouts.admin')

@section('page-title', 'System Usage Report')

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
                    <span class="admin-breadcrumb-current">System Usage</span>
                </div>
                <h2 class="admin-page-title">System Usage Report</h2>
                <p class="admin-page-subtitle">Track page views, visitors, and engagement metrics</p>
            </div>
        </div>
    </div>

    <!-- Period Selector and Export -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="admin-period-selector">
            <div class="admin-period-selector-label">
                <i class='bx bx-calendar'></i>
                <span>Time Period:</span>
            </div>
            <div class="admin-period-buttons">
                <a href="{{ route('admin.reports.system-usage', ['period' => '7days']) }}"
                    class="admin-period-btn {{ $period === '7days' ? 'active' : '' }}">
                    Last 7 Days
                </a>
                <a href="{{ route('admin.reports.system-usage', ['period' => '30days']) }}"
                    class="admin-period-btn {{ $period === '30days' ? 'active' : '' }}">
                    Last 30 Days
                </a>
                <a href="{{ route('admin.reports.system-usage', ['period' => '90days']) }}"
                    class="admin-period-btn {{ $period === '90days' ? 'active' : '' }}">
                    Last 90 Days
                </a>
            </div>
        </div>
        <a href="{{ route('admin.reports.system-usage.pdf', ['period' => request('period', '30days')]) }}"
            class="btn btn-danger">
            <i class='bx bxs-file-pdf me-2'></i>Download PDF Report
        </a>
    </div>

    <!-- Overview Stats -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="admin-metric-card primary">
                <div class="admin-metric-card-icon">
                    <i class='bx bxs-show'></i>
                </div>
                <div class="admin-metric-card-content">
                    <p class="admin-metric-card-label">Total Page Views</p>
                    <h3 class="admin-metric-card-value">{{ number_format($stats['total_page_views'] ?? 0) }}</h3>
                </div>
                <div class="admin-metric-card-decoration"></div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="admin-metric-card success">
                <div class="admin-metric-card-icon">
                    <i class='bx bxs-user'></i>
                </div>
                <div class="admin-metric-card-content">
                    <p class="admin-metric-card-label">Unique Visitors</p>
                    <h3 class="admin-metric-card-value">{{ number_format($stats['unique_visitors'] ?? 0) }}</h3>
                </div>
                <div class="admin-metric-card-decoration"></div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="admin-metric-card info">
                <div class="admin-metric-card-icon">
                    <i class='bx bxs-user-plus'></i>
                </div>
                <div class="admin-metric-card-content">
                    <p class="admin-metric-card-label">New Users</p>
                    <h3 class="admin-metric-card-value">{{ number_format($stats['registered_users'] ?? 0) }}</h3>
                </div>
                <div class="admin-metric-card-decoration"></div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="admin-metric-card warning">
                <div class="admin-metric-card-icon">
                    <i class='bx bxs-search'></i>
                </div>
                <div class="admin-metric-card-content">
                    <p class="admin-metric-card-label">Total Searches</p>
                    <h3 class="admin-metric-card-value">{{ number_format($stats['total_searches'] ?? 0) }}</h3>
                </div>
                <div class="admin-metric-card-decoration"></div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-4 mb-4">
        <!-- Daily Views Chart -->
        <div class="col-lg-8">
            <div class="admin-chart-card">
                <div class="admin-chart-card-header">
                    <div class="admin-chart-card-icon">
                        <i class='bx bx-line-chart'></i>
                    </div>
                    <div>
                        <h5 class="admin-chart-card-title">Daily Page Views</h5>
                        <p class="admin-chart-card-subtitle">View trends over time</p>
                    </div>
                </div>
                <div class="admin-chart-card-body">
                    <canvas id="dailyViewsChart" height="80"></canvas>
                </div>
            </div>
        </div>

        <!-- Top Pages -->
        <div class="col-lg-4">
            <div class="admin-chart-card">
                <div class="admin-chart-card-header">
                    <div class="admin-chart-card-icon">
                        <i class='bx bx-file'></i>
                    </div>
                    <div>
                        <h5 class="admin-chart-card-title">Top Pages</h5>
                        <p class="admin-chart-card-subtitle">Most visited pages</p>
                    </div>
                </div>
                <div class="admin-chart-card-body-list">
                    @forelse($topPages as $page)
                        <div class="admin-top-page-item">
                            <div class="admin-top-page-info">
                                <strong class="admin-top-page-name">{{ Str::limit($page->page, 30) }}</strong>
                                <span class="admin-top-page-count">{{ number_format($page->count) }} views</span>
                            </div>
                            <div class="admin-top-page-bar">
                                <div class="admin-top-page-bar-fill"
                                    style="width: {{ ($page->count / $topPages->first()->count) * 100 }}%">
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="admin-empty-chart">
                            <i class='bx bx-file'></i>
                            <p>No data available</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- User Activity Row -->
    <div class="row g-4">
        <!-- User Engagement -->
        <div class="col-lg-6">
            <div class="admin-engagement-card">
                <div class="admin-engagement-card-header">
                    <div class="admin-engagement-card-icon">
                        <i class='bx bx-user-check'></i>
                    </div>
                    <div>
                        <h5 class="admin-engagement-card-title">User Engagement</h5>
                        <p class="admin-engagement-card-subtitle">Active user statistics</p>
                    </div>
                </div>
                <div class="admin-engagement-card-body">
                    <div class="admin-engagement-item">
                        <div class="admin-engagement-item-header">
                            <span class="admin-engagement-label">Total Users</span>
                            <strong class="admin-engagement-value">{{ number_format(\App\Models\User::count()) }}</strong>
                        </div>
                        <div class="admin-engagement-bar">
                            <div class="admin-engagement-bar-fill primary" style="width: 100%"></div>
                        </div>
                    </div>

                    <div class="admin-engagement-item">
                        <div class="admin-engagement-item-header">
                            <span class="admin-engagement-label">Active Users</span>
                            <strong
                                class="admin-engagement-value">{{ number_format(\App\Models\User::where('is_active', true)->count()) }}</strong>
                        </div>
                        <div class="admin-engagement-bar">
                            <div class="admin-engagement-bar-fill success"
                                style="width: {{ (\App\Models\User::where('is_active', true)->count() / max(\App\Models\User::count(), 1)) * 100 }}%">
                            </div>
                        </div>
                    </div>

                    <div class="admin-engagement-item">
                        <div class="admin-engagement-item-header">
                            <span class="admin-engagement-label">Business Owners</span>
                            <strong
                                class="admin-engagement-value">{{ number_format(\App\Models\User::where('role', 'business_owner')->count()) }}</strong>
                        </div>
                        <div class="admin-engagement-bar">
                            <div class="admin-engagement-bar-fill info"
                                style="width: {{ (\App\Models\User::where('role', 'business_owner')->count() / max(\App\Models\User::count(), 1)) * 100 }}%">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Statistics -->
        <div class="col-lg-6">
            <div class="admin-content-stats-card">
                <div class="admin-content-stats-header">
                    <div class="admin-content-stats-icon">
                        <i class='bx bx-data'></i>
                    </div>
                    <div>
                        <h5 class="admin-content-stats-title">Content Statistics</h5>
                        <p class="admin-content-stats-subtitle">System content overview</p>
                    </div>
                </div>
                <div class="admin-content-stats-body">
                    <div class="admin-content-stat-modern primary">
                        <div class="admin-content-stat-icon-modern">
                            <i class='bx bx-map-pin'></i>
                        </div>
                        <div class="admin-content-stat-info">
                            <p class="admin-content-stat-label-modern">Attractions</p>
                            <h4 class="admin-content-stat-value-modern">
                                {{ number_format(\App\Models\Attraction::count()) }}</h4>
                        </div>
                    </div>

                    <div class="admin-content-stat-modern success">
                        <div class="admin-content-stat-icon-modern">
                            <i class='bx bx-store'></i>
                        </div>
                        <div class="admin-content-stat-info">
                            <p class="admin-content-stat-label-modern">Businesses</p>
                            <h4 class="admin-content-stat-value-modern">{{ number_format(\App\Models\Business::count()) }}
                            </h4>
                        </div>
                    </div>

                    <div class="admin-content-stat-modern info">
                        <div class="admin-content-stat-icon-modern">
                            <i class='bx bx-message-square-detail'></i>
                        </div>
                        <div class="admin-content-stat-info">
                            <p class="admin-content-stat-label-modern">Reviews</p>
                            <h4 class="admin-content-stat-value-modern">{{ number_format(\App\Models\Review::count()) }}
                            </h4>
                        </div>
                    </div>

                    <div class="admin-content-stat-modern warning">
                        <div class="admin-content-stat-icon-modern">
                            <i class='bx bx-bulb'></i>
                        </div>
                        <div class="admin-content-stat-info">
                            <p class="admin-content-stat-label-modern">Suggestions</p>
                            <h4 class="admin-content-stat-value-modern">
                                {{ number_format(\App\Models\LandmarkSuggestion::count()) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
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

        /* Period Selector */
        .admin-period-selector {
            background: white;
            border-radius: 14px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            gap: 1.5rem;
            flex-wrap: wrap;
        }

        .admin-period-selector-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
            color: #475569;
        }

        .admin-period-selector-label i {
            font-size: 1.25rem;
            color: #1a7838;
        }

        .admin-period-buttons {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .admin-period-btn {
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.9375rem;
            border: 2px solid #e2e8f0;
            background: white;
            color: #64748b;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .admin-period-btn:hover {
            border-color: #1a7838;
            color: #1a7838;
            background: #f0fdf4;
        }

        .admin-period-btn.active {
            background: linear-gradient(135deg, #1a7838, #27a345);
            border-color: #1a7838;
            color: white;
            box-shadow: 0 4px 12px rgba(26, 120, 56, 0.25);
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

        .admin-metric-card.info {
            border-color: rgba(2, 132, 199, 0.2);
            background: linear-gradient(135deg, #ffffff, #dbeafe);
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

        .admin-metric-card.info .admin-metric-card-icon {
            background: linear-gradient(135deg, #00A8E8, #0284c7);
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

        /* Top Pages */
        .admin-top-page-item {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #f1f5f9;
        }

        .admin-top-page-item:last-child {
            border-bottom: none;
        }

        .admin-top-page-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.75rem;
        }

        .admin-top-page-name {
            font-size: 0.9375rem;
            color: #1e293b;
        }

        .admin-top-page-count {
            font-size: 0.8125rem;
            color: #64748b;
            font-weight: 600;
        }

        .admin-top-page-bar {
            height: 6px;
            background: #f1f5f9;
            border-radius: 3px;
            overflow: hidden;
        }

        .admin-top-page-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, #1a7838, #27a345);
            border-radius: 3px;
            transition: width 0.3s ease;
        }

        /* Engagement Card */
        .admin-engagement-card {
            background: white;
            border-radius: 14px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #f1f5f9;
            overflow: hidden;
        }

        .admin-engagement-card-header {
            padding: 1.5rem;
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .admin-engagement-card-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            background: linear-gradient(135deg, #10b981, #059669);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }

        .admin-engagement-card-title {
            font-size: 1.125rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }

        .admin-engagement-card-subtitle {
            font-size: 0.8125rem;
            color: #64748b;
            margin: 0.25rem 0 0 0;
        }

        .admin-engagement-card-body {
            padding: 1.5rem;
        }

        .admin-engagement-item {
            padding: 1.25rem;
            background: #f8fafc;
            border-radius: 10px;
            margin-bottom: 1rem;
        }

        .admin-engagement-item:last-child {
            margin-bottom: 0;
        }

        .admin-engagement-item-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.75rem;
        }

        .admin-engagement-label {
            font-size: 0.9375rem;
            color: #475569;
            font-weight: 600;
        }

        .admin-engagement-value {
            font-size: 1.125rem;
            color: #1e293b;
        }

        .admin-engagement-bar {
            height: 8px;
            background: #e2e8f0;
            border-radius: 4px;
            overflow: hidden;
        }

        .admin-engagement-bar-fill {
            height: 100%;
            border-radius: 4px;
            transition: width 0.3s ease;
        }

        .admin-engagement-bar-fill.primary {
            background: linear-gradient(90deg, #1a7838, #27a345);
        }

        .admin-engagement-bar-fill.success {
            background: linear-gradient(90deg, #10b981, #059669);
        }

        .admin-engagement-bar-fill.info {
            background: linear-gradient(90deg, #00A8E8, #0284c7);
        }

        /* Content Stats Card */
        .admin-content-stats-card {
            background: white;
            border-radius: 14px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #f1f5f9;
            overflow: hidden;
        }

        .admin-content-stats-header {
            padding: 1.5rem;
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .admin-content-stats-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            background: linear-gradient(135deg, #00A8E8, #0284c7);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }

        .admin-content-stats-title {
            font-size: 1.125rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }

        .admin-content-stats-subtitle {
            font-size: 0.8125rem;
            color: #64748b;
            margin: 0.25rem 0 0 0;
        }

        .admin-content-stats-body {
            padding: 1.5rem;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        .admin-content-stat-modern {
            padding: 1.25rem;
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .admin-content-stat-modern.primary {
            background: linear-gradient(135deg, #f0fdf4, #dcfce7);
        }

        .admin-content-stat-modern.success {
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        }

        .admin-content-stat-modern.info {
            background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        }

        .admin-content-stat-modern.warning {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
        }

        .admin-content-stat-icon-modern {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            flex-shrink: 0;
        }

        .admin-content-stat-modern.primary .admin-content-stat-icon-modern {
            background: linear-gradient(135deg, #1a7838, #27a345);
        }

        .admin-content-stat-modern.success .admin-content-stat-icon-modern {
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .admin-content-stat-modern.info .admin-content-stat-icon-modern {
            background: linear-gradient(135deg, #00A8E8, #0284c7);
        }

        .admin-content-stat-modern.warning .admin-content-stat-icon-modern {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }

        .admin-content-stat-label-modern {
            font-size: 0.8125rem;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin: 0 0 0.25rem 0;
        }

        .admin-content-stat-value-modern {
            font-size: 1.5rem;
            font-weight: 800;
            color: #1e293b;
            margin: 0;
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

            .admin-period-selector {
                flex-direction: column;
                align-items: stretch;
            }

            .admin-period-buttons {
                flex-direction: column;
            }

            .admin-content-stats-body {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        const ctx = document.getElementById('dailyViewsChart');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($dailyViews->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('M d'))) !!},
                datasets: [{
                    label: 'Page Views',
                    data: {!! json_encode($dailyViews->pluck('count')) !!},
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.4,
                    fill: true,
                    borderWidth: 3,
                    pointBackgroundColor: '#10b981',
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
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        displayColors: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                            color: '#64748b'
                        },
                        grid: {
                            color: '#f1f5f9'
                        }
                    },
                    x: {
                        ticks: {
                            color: '#64748b'
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    </script>
@endpush
