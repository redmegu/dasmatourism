@extends('layouts.admin')

@section('page-title', 'Reports & Analytics')

@section('content')
    <!-- Enhanced Page Header -->
    <div class="admin-analytics-header mb-4">
        <div class="admin-analytics-header-content">
            <div class="admin-analytics-header-text">
                <h2 class="admin-analytics-title">Reports & Analytics</h2>
                <p class="admin-analytics-subtitle">Comprehensive insights into your tourism management system</p>
            </div>
            <div class="admin-analytics-header-icon">
                <i class='bx bx-bar-chart-alt-2'></i>
            </div>
        </div>
    </div>

    <!-- Report Categories -->
    <div class="row g-4 mb-4">
        <!-- System Usage Report -->
        <div class="col-md-6">
            <div class="admin-report-card-modern primary">
                <div class="admin-report-card-header">
                    <div class="admin-report-card-icon">
                        <i class='bx bx-line-chart'></i>
                    </div>
                    <div class="admin-report-card-badge">Most Popular</div>
                </div>
                <div class="admin-report-card-body">
                    <h4 class="admin-report-card-title">System Usage Report</h4>
                    <p class="admin-report-card-description">Track page views, unique visitors, and user engagement metrics
                        across your platform</p>
                    <ul class="admin-report-card-features">
                        <li>
                            <i class='bx bx-check-circle'></i>
                            <span>Daily/Monthly page views</span>
                        </li>
                        <li>
                            <i class='bx bx-check-circle'></i>
                            <span>Unique visitor tracking</span>
                        </li>
                        <li>
                            <i class='bx bx-check-circle'></i>
                            <span>Most visited pages</span>
                        </li>
                        <li>
                            <i class='bx bx-check-circle'></i>
                            <span>Search analytics</span>
                        </li>
                    </ul>
                </div>
                <div class="admin-report-card-footer">
                    <a href="{{ route('admin.reports.system-usage') }}" class="admin-report-card-btn primary">
                        <i class='bx bx-show'></i>
                        <span>View Report</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Attraction Analytics -->
        <div class="col-md-6">
            <div class="admin-report-card-modern success">
                <div class="admin-report-card-header">
                    <div class="admin-report-card-icon">
                        <i class='bx bx-map-pin'></i>
                    </div>
                    <div class="admin-report-card-badge">Trending</div>
                </div>
                <div class="admin-report-card-body">
                    <h4 class="admin-report-card-title">Attraction Analytics</h4>
                    <p class="admin-report-card-description">Detailed performance metrics for all tourist attractions and
                        landmarks</p>
                    <ul class="admin-report-card-features">
                        <li>
                            <i class='bx bx-check-circle'></i>
                            <span>Most popular attractions</span>
                        </li>
                        <li>
                            <i class='bx bx-check-circle'></i>
                            <span>Category distribution</span>
                        </li>
                        <li>
                            <i class='bx bx-check-circle'></i>
                            <span>Average ratings</span>
                        </li>
                        <li>
                            <i class='bx bx-check-circle'></i>
                            <span>Review statistics</span>
                        </li>
                    </ul>
                </div>
                <div class="admin-report-card-footer">
                    <a href="{{ route('admin.reports.attractions') }}" class="admin-report-card-btn success">
                        <i class='bx bx-show'></i>
                        <span>View Report</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Business Analytics -->
        <div class="col-md-6">
            <div class="admin-report-card-modern info">
                <div class="admin-report-card-header">
                    <div class="admin-report-card-icon">
                        <i class='bx bx-store'></i>
                    </div>
                    <div class="admin-report-card-badge">Featured</div>
                </div>
                <div class="admin-report-card-body">
                    <h4 class="admin-report-card-title">Business Analytics</h4>
                    <p class="admin-report-card-description">Comprehensive insights into registered businesses and
                        partnerships</p>
                    <ul class="admin-report-card-features">
                        <li>
                            <i class='bx bx-check-circle'></i>
                            <span>Top performing businesses</span>
                        </li>
                        <li>
                            <i class='bx bx-check-circle'></i>
                            <span>Verification status</span>
                        </li>
                        <li>
                            <i class='bx bx-check-circle'></i>
                            <span>Customer engagement</span>
                        </li>
                        <li>
                            <i class='bx bx-check-circle'></i>
                            <span>Business growth trends</span>
                        </li>
                    </ul>
                </div>
                <div class="admin-report-card-footer">
                    <a href="{{ route('admin.reports.businesses') }}" class="admin-report-card-btn info">
                        <i class='bx bx-show'></i>
                        <span>View Report</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- User Demographics -->
        <div class="col-md-6">
            <div class="admin-report-card-modern warning">
                <div class="admin-report-card-header">
                    <div class="admin-report-card-icon">
                        <i class='bx bx-user'></i>
                    </div>
                    <div class="admin-report-card-badge">Updated</div>
                </div>
                <div class="admin-report-card-body">
                    <h4 class="admin-report-card-title">User Demographics</h4>
                    <p class="admin-report-card-description">Understanding your user base and engagement patterns</p>
                    <ul class="admin-report-card-features">
                        <li>
                            <i class='bx bx-check-circle'></i>
                            <span>User registration trends</span>
                        </li>
                        <li>
                            <i class='bx bx-check-circle'></i>
                            <span>Active user statistics</span>
                        </li>
                        <li>
                            <i class='bx bx-check-circle'></i>
                            <span>User type distribution</span>
                        </li>
                        <li>
                            <i class='bx bx-check-circle'></i>
                            <span>Engagement metrics</span>
                        </li>
                    </ul>
                </div>
                <div class="admin-report-card-footer">
                    <a href="{{ route('admin.reports.users') }}" class="admin-report-card-btn warning">
                        <i class='bx bx-show'></i>
                        <span>View Report</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats Overview -->
    <div class="admin-quick-stats-card">
        <div class="admin-quick-stats-header">
            <div class="admin-quick-stats-header-icon">
                <i class='bx bx-trending-up'></i>
            </div>
            <div>
                <h5 class="admin-quick-stats-title">Quick Overview</h5>
                <p class="admin-quick-stats-subtitle">Real-time system statistics</p>
            </div>
        </div>
        <div class="admin-quick-stats-body">
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="admin-quick-stat-item primary">
                        <div class="admin-quick-stat-icon-wrapper">
                            <i class='bx bx-map-pin'></i>
                        </div>
                        <div class="admin-quick-stat-content">
                            <h3 class="admin-quick-stat-value">{{ \App\Models\Attraction::count() }}</h3>
                            <p class="admin-quick-stat-label">Total Attractions</p>
                        </div>
                        <div class="admin-quick-stat-trend up">
                            <i class='bx bx-trending-up'></i>
                            <span>Active</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="admin-quick-stat-item success">
                        <div class="admin-quick-stat-icon-wrapper">
                            <i class='bx bx-store'></i>
                        </div>
                        <div class="admin-quick-stat-content">
                            <h3 class="admin-quick-stat-value">{{ \App\Models\Business::count() }}</h3>
                            <p class="admin-quick-stat-label">Total Businesses</p>
                        </div>
                        <div class="admin-quick-stat-trend up">
                            <i class='bx bx-trending-up'></i>
                            <span>Growing</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="admin-quick-stat-item warning">
                        <div class="admin-quick-stat-icon-wrapper">
                            <i class='bx bx-user'></i>
                        </div>
                        <div class="admin-quick-stat-content">
                            <h3 class="admin-quick-stat-value">{{ \App\Models\User::count() }}</h3>
                            <p class="admin-quick-stat-label">Total Users</p>
                        </div>
                        <div class="admin-quick-stat-trend up">
                            <i class='bx bx-trending-up'></i>
                            <span>Increasing</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="admin-quick-stat-item info">
                        <div class="admin-quick-stat-icon-wrapper">
                            <i class='bx bx-message-square-detail'></i>
                        </div>
                        <div class="admin-quick-stat-content">
                            <h3 class="admin-quick-stat-value">{{ \App\Models\Review::count() }}</h3>
                            <p class="admin-quick-stat-label">Total Reviews</p>
                        </div>
                        <div class="admin-quick-stat-trend up">
                            <i class='bx bx-trending-up'></i>
                            <span>Active</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Analytics Header */
        .admin-analytics-header {
            background: linear-gradient(135deg, #1a7838, #27a345);
            border-radius: 16px;
            padding: 2.5rem;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .admin-analytics-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .admin-analytics-header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 2rem;
            position: relative;
            z-index: 1;
        }

        .admin-analytics-title {
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            color: white;
        }

        .admin-analytics-subtitle {
            font-size: 1.125rem;
            margin: 0;
            opacity: 0.95;
        }

        .admin-analytics-header-icon {
            width: 100px;
            height: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            font-size: 3.5rem;
            flex-shrink: 0;
        }

        /* Modern Report Cards */
        .admin-report-card-modern {
            background: white;
            border-radius: 16px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #f1f5f9;
            overflow: hidden;
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .admin-report-card-modern:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.12);
        }

        .admin-report-card-modern.primary {
            border-top: 4px solid #1a7838;
        }

        .admin-report-card-modern.success {
            border-top: 4px solid #10b981;
        }

        .admin-report-card-modern.info {
            border-top: 4px solid #0284c7;
        }

        .admin-report-card-modern.warning {
            border-top: 4px solid #f59e0b;
        }

        .admin-report-card-header {
            padding: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .admin-report-card-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
        }

        .admin-report-card-modern.primary .admin-report-card-icon {
            background: linear-gradient(135deg, #1a7838, #27a345);
        }

        .admin-report-card-modern.success .admin-report-card-icon {
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .admin-report-card-modern.info .admin-report-card-icon {
            background: linear-gradient(135deg, #00A8E8, #0284c7);
        }

        .admin-report-card-modern.warning .admin-report-card-icon {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }

        .admin-report-card-badge {
            padding: 0.375rem 0.875rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .admin-report-card-modern.primary .admin-report-card-badge {
            background: #e0f2e9;
            color: #1a7838;
        }

        .admin-report-card-modern.success .admin-report-card-badge {
            background: #d1fae5;
            color: #059669;
        }

        .admin-report-card-modern.info .admin-report-card-badge {
            background: #dbeafe;
            color: #0284c7;
        }

        .admin-report-card-modern.warning .admin-report-card-badge {
            background: #fef3c7;
            color: #d97706;
        }

        .admin-report-card-body {
            padding: 0 1.5rem 1.5rem;
            flex: 1;
        }

        .admin-report-card-title {
            font-size: 1.375rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.75rem;
        }

        .admin-report-card-description {
            font-size: 0.9375rem;
            color: #64748b;
            line-height: 1.6;
            margin-bottom: 1.25rem;
        }

        .admin-report-card-features {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .admin-report-card-features li {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.9375rem;
            color: #475569;
        }

        .admin-report-card-features i {
            font-size: 1.125rem;
            flex-shrink: 0;
        }

        .admin-report-card-modern.primary .admin-report-card-features i {
            color: #1a7838;
        }

        .admin-report-card-modern.success .admin-report-card-features i {
            color: #10b981;
        }

        .admin-report-card-modern.info .admin-report-card-features i {
            color: #0284c7;
        }

        .admin-report-card-modern.warning .admin-report-card-features i {
            color: #f59e0b;
        }

        .admin-report-card-footer {
            padding: 1.5rem;
            border-top: 1px solid #f1f5f9;
        }

        .admin-report-card-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.875rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.9375rem;
            text-decoration: none;
            transition: all 0.3s ease;
            width: 100%;
        }

        .admin-report-card-btn.primary {
            background: linear-gradient(135deg, #1a7838, #27a345);
            color: white;
            box-shadow: 0 4px 12px rgba(26, 120, 56, 0.25);
        }

        .admin-report-card-btn.primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(26, 120, 56, 0.35);
            color: white;
        }

        .admin-report-card-btn.success {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25);
        }

        .admin-report-card-btn.success:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.35);
            color: white;
        }

        .admin-report-card-btn.info {
            background: linear-gradient(135deg, #00A8E8, #0284c7);
            color: white;
            box-shadow: 0 4px 12px rgba(2, 132, 199, 0.25);
        }

        .admin-report-card-btn.info:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(2, 132, 199, 0.35);
            color: white;
        }

        .admin-report-card-btn.warning {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.25);
        }

        .admin-report-card-btn.warning:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(245, 158, 11, 0.35);
            color: white;
        }

        /* Quick Stats Card */
        .admin-quick-stats-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #f1f5f9;
            overflow: hidden;
        }

        .admin-quick-stats-header {
            padding: 1.5rem;
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .admin-quick-stats-header-icon {
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

        .admin-quick-stats-title {
            font-size: 1.125rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }

        .admin-quick-stats-subtitle {
            font-size: 0.8125rem;
            color: #64748b;
            margin: 0.25rem 0 0 0;
        }

        .admin-quick-stats-body {
            padding: 2rem;
        }

        /* Quick Stat Items */
        .admin-quick-stat-item {
            background: white;
            border-radius: 14px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid;
            transition: all 0.3s ease;
            height: 100%;
        }

        .admin-quick-stat-item:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .admin-quick-stat-item.primary {
            border-color: rgba(26, 120, 56, 0.2);
            background: linear-gradient(135deg, #ffffff, #f0fdf4);
        }

        .admin-quick-stat-item.success {
            border-color: rgba(16, 185, 129, 0.2);
            background: linear-gradient(135deg, #ffffff, #d1fae5);
        }

        .admin-quick-stat-item.warning {
            border-color: rgba(245, 158, 11, 0.2);
            background: linear-gradient(135deg, #ffffff, #fef3c7);
        }

        .admin-quick-stat-item.info {
            border-color: rgba(2, 132, 199, 0.2);
            background: linear-gradient(135deg, #ffffff, #dbeafe);
        }

        .admin-quick-stat-icon-wrapper {
            width: 56px;
            height: 56px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            color: white;
            margin-bottom: 1rem;
        }

        .admin-quick-stat-item.primary .admin-quick-stat-icon-wrapper {
            background: linear-gradient(135deg, #1a7838, #27a345);
        }

        .admin-quick-stat-item.success .admin-quick-stat-icon-wrapper {
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .admin-quick-stat-item.warning .admin-quick-stat-icon-wrapper {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }

        .admin-quick-stat-item.info .admin-quick-stat-icon-wrapper {
            background: linear-gradient(135deg, #00A8E8, #0284c7);
        }

        .admin-quick-stat-value {
            font-size: 2.5rem;
            font-weight: 800;
            color: #1e293b;
            margin: 0 0 0.375rem 0;
            line-height: 1;
        }

        .admin-quick-stat-label {
            font-size: 0.9375rem;
            color: #64748b;
            margin: 0 0 0.75rem 0;
            font-weight: 600;
        }

        .admin-quick-stat-trend {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.375rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
        }

        .admin-quick-stat-trend.up {
            background: #d1fae5;
            color: #059669;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .admin-analytics-header {
                padding: 2rem;
            }

            .admin-analytics-header-icon {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .admin-analytics-header {
                padding: 1.5rem;
            }

            .admin-analytics-title {
                font-size: 1.5rem;
            }

            .admin-analytics-subtitle {
                font-size: 1rem;
            }

            .admin-quick-stats-body {
                padding: 1.5rem;
            }
        }
    </style>
@endpush
