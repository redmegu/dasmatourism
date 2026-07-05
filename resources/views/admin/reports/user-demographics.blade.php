@extends('layouts.admin')

@section('page-title', 'User Demographics')

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
                    <span class="admin-breadcrumb-current">User Demographics</span>
                </div>
                <h2 class="admin-page-title">User Demographics</h2>
                <p class="admin-page-subtitle">Understanding your user base</p>
            </div>
            <a href="{{ route('admin.reports.users.pdf') }}" class="btn btn-danger">
                <i class='bx bxs-file-pdf me-2'></i>Download PDF Report
            </a>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="admin-metric-card primary">
                <div class="admin-metric-card-icon">
                    <i class='bx bxs-user'></i>
                </div>
                <div class="admin-metric-card-content">
                    <p class="admin-metric-card-label">Total Users</p>
                    <h3 class="admin-metric-card-value">{{ number_format($stats['total_users']) }}</h3>
                </div>
                <div class="admin-metric-card-decoration"></div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="admin-metric-card success">
                <div class="admin-metric-card-icon">
                    <i class='bx bxs-user-check'></i>
                </div>
                <div class="admin-metric-card-content">
                    <p class="admin-metric-card-label">Active Users</p>
                    <h3 class="admin-metric-card-value">{{ number_format($stats['active_users']) }}</h3>
                </div>
                <div class="admin-metric-card-decoration"></div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="admin-metric-card info">
                <div class="admin-metric-card-icon">
                    <i class='bx bxs-store'></i>
                </div>
                <div class="admin-metric-card-content">
                    <p class="admin-metric-card-label">Business Owners</p>
                    <h3 class="admin-metric-card-value">{{ number_format($stats['business_owners']) }}</h3>
                </div>
                <div class="admin-metric-card-decoration"></div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="admin-metric-card warning">
                <div class="admin-metric-card-icon">
                    <i class='bx bxs-message-square-detail'></i>
                </div>
                <div class="admin-metric-card-content">
                    <p class="admin-metric-card-label">With Reviews</p>
                    <h3 class="admin-metric-card-value">{{ number_format($stats['users_with_reviews']) }}</h3>
                </div>
                <div class="admin-metric-card-decoration"></div>
            </div>
        </div>
    </div>

    <!-- Registration Trend Chart -->
    <div class="admin-chart-card">
        <div class="admin-chart-card-header">
            <div class="admin-chart-card-icon">
                <i class='bx bx-line-chart'></i>
            </div>
            <div>
                <h5 class="admin-chart-card-title">User Registration Trend</h5>
                <p class="admin-chart-card-subtitle">Last 30 days registration activity</p>
            </div>
        </div>
        <div class="admin-chart-card-body">
            <canvas id="registrationChart" height="80"></canvas>
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

        /* Chart Card */
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

            .admin-chart-card-body {
                padding: 1.5rem;
            }
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        const ctx = document.getElementById('registrationChart');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($registrationTrend->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('M d'))) !!},
                datasets: [{
                    label: 'New Users',
                    data: {!! json_encode($registrationTrend->pluck('count')) !!},
                    backgroundColor: 'rgba(26, 120, 56, 0.8)',
                    borderColor: '#1a7838',
                    borderWidth: 2,
                    borderRadius: 8,
                    hoverBackgroundColor: 'rgba(26, 120, 56, 1)'
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
                        bodyFont: {
                            size: 14
                        },
                        callbacks: {
                            label: function(context) {
                                return 'New Users: ' + context.parsed.y;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                            color: '#64748b',
                            font: {
                                size: 12
                            }
                        },
                        grid: {
                            color: '#f1f5f9',
                            borderDash: [5, 5]
                        },
                        border: {
                            display: false
                        }
                    },
                    x: {
                        ticks: {
                            color: '#64748b',
                            font: {
                                size: 12
                            }
                        },
                        grid: {
                            display: false
                        },
                        border: {
                            display: false
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });
    </script>
@endpush
