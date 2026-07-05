@extends('layouts.admin')

@section('page-title', 'Business Analytics')

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
                    <span class="admin-breadcrumb-current">Business Analytics</span>
                </div>
                <h2 class="admin-page-title">Business Analytics</h2>
                <p class="admin-page-subtitle">Performance insights for businesses</p>
            </div>
            <a href="{{ route('admin.reports.businesses.pdf') }}" class="btn btn-danger">
                <i class='bx bxs-file-pdf me-2'></i>Download PDF Report
            </a>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="admin-metric-card primary">
                <div class="admin-metric-card-icon">
                    <i class='bx bxs-store'></i>
                </div>
                <div class="admin-metric-card-content">
                    <p class="admin-metric-card-label">Total Businesses</p>
                    <h3 class="admin-metric-card-value">{{ number_format($stats['total_businesses']) }}</h3>
                </div>
                <div class="admin-metric-card-decoration"></div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="admin-metric-card success">
                <div class="admin-metric-card-icon">
                    <i class='bx bxs-badge-check'></i>
                </div>
                <div class="admin-metric-card-content">
                    <p class="admin-metric-card-label">Verified</p>
                    <h3 class="admin-metric-card-value">{{ number_format($stats['verified_businesses']) }}</h3>
                </div>
                <div class="admin-metric-card-decoration"></div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="admin-metric-card info">
                <div class="admin-metric-card-icon">
                    <i class='bx bxs-message-square-detail'></i>
                </div>
                <div class="admin-metric-card-content">
                    <p class="admin-metric-card-label">With Reviews</p>
                    <h3 class="admin-metric-card-value">{{ number_format($stats['businesses_with_reviews']) }}</h3>
                </div>
                <div class="admin-metric-card-decoration"></div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="admin-metric-card warning">
                <div class="admin-metric-card-icon">
                    <i class='bx bxs-show'></i>
                </div>
                <div class="admin-metric-card-content">
                    <p class="admin-metric-card-label">Total Views</p>
                    <h3 class="admin-metric-card-value">{{ number_format($topBusinesses->sum('views')) }}</h3>
                </div>
                <div class="admin-metric-card-decoration"></div>
            </div>
        </div>
    </div>

    <!-- Top Businesses Table -->
    <div class="admin-table-card">
        <div class="admin-table-card-header">
            <div class="admin-table-card-icon">
                <i class='bx bx-trending-up'></i>
            </div>
            <div>
                <h5 class="admin-table-card-title">Top Performing Businesses</h5>
                <p class="admin-table-card-subtitle">Ranked by views and engagement</p>
            </div>
        </div>
        <div class="admin-table-wrapper">
            <table class="admin-modern-table">
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Business Name</th>
                        <th>Category</th>
                        <th>Views</th>
                        <th>Reviews</th>
                        <th>Avg Rating</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topBusinesses as $business)
                        <tr>
                            <td><strong>#{{ $loop->iteration }}</strong></td>
                            <td>
                                <div class="admin-business-name-cell">
                                    <span class="admin-business-name">{{ $business->name }}</span>
                                    @if ($business->is_verified)
                                        <i class='bx bxs-badge-check admin-verified-icon'></i>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="admin-category-badge">
                                    {{ $business->category->name ?? 'N/A' }}
                                </span>
                            </td>
                            <td>{{ number_format($business->views) }}</td>
                            <td>{{ number_format($business->approved_reviews_count) }}</td>
                            <td>
                                <div class="admin-rating-display">
                                    <i class='bx bxs-star'></i>
                                    <span>{{ number_format($business->average_rating ?? 0, 1) }}</span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="admin-empty-table">
                                    <i class='bx bx-store'></i>
                                    <p>No businesses data available</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
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

        /* Business Name Cell */
        .admin-business-name-cell {
            display: flex;
            align-items: center;
            gap: 0.625rem;
        }

        .admin-business-name {
            font-weight: 600;
            color: #1e293b;
        }

        .admin-verified-icon {
            color: #0284c7;
            font-size: 1.25rem;
            flex-shrink: 0;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.7;
            }
        }

        .admin-category-badge {
            display: inline-block;
            padding: 0.375rem 0.75rem;
            border-radius: 6px;
            font-size: 0.8125rem;
            font-weight: 600;
            background: #dbeafe;
            color: #0284c7;
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

        /* Empty Table */
        .admin-empty-table {
            text-align: center;
            padding: 4rem 2rem;
            color: #94a3b8;
        }

        .admin-empty-table i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.3;
        }

        .admin-empty-table p {
            margin: 0;
            font-size: 1rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .admin-page-header {
                padding: 1.5rem;
            }

            .admin-metric-card {
                padding: 1.5rem;
            }

            .admin-metric-card-value {
                font-size: 1.75rem;
            }
        }
    </style>
@endpush
