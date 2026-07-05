@extends('layouts.admin')

@section('page-title', 'Dashboard')

@section('content')
    <!-- Enhanced Welcome Banner -->
    <div class="admin-welcome-banner-enhanced">
        <div class="admin-welcome-content-enhanced">
            <div class="admin-welcome-greeting">
                <h1>Welcome back, <span class="highlight-name">{{ Auth::user()->name }}</span>! 👋</h1>
                <p>{{ now()->format('l, F d, Y') }}</p>
            </div>
            <p class="admin-welcome-subtitle">Here's what's happening with your tourism system today.</p>
        </div>
        <div class="admin-welcome-decoration">
            <div class="admin-welcome-circle circle-1"></div>
            <div class="admin-welcome-circle circle-2"></div>
            <div class="admin-welcome-circle circle-3"></div>
        </div>
    </div>
    <a href="{{ route('admin.database.backup') }}" class="btn btn-success">
    <i class="bx bx-download"></i> Download SQL Backup
</a>

    

    <!-- Enhanced Stats Cards with Animations -->
    <div class="row g-4 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="admin-stat-card-enhanced admin-stat-primary" data-animate>
                <div class="admin-stat-icon-enhanced">
                    <i class='bx bx-map-pin'></i>
                </div>
                <div class="admin-stat-content-enhanced">
                    <p class="admin-stat-label-enhanced">Total Attractions</p>
                    <h3 class="admin-stat-value-enhanced">{{ $stats['total_attractions'] }}</h3>
                    <div class="admin-stat-footer">
                        <span class="admin-stat-badge-enhanced success">
                            <i class='bx bx-check-circle'></i> {{ $stats['approved_attractions'] }} active
                        </span>
                    </div>
                </div>
                <div class="admin-stat-progress">
                    <div class="admin-stat-progress-bar"
                        style="width: {{ ($stats['approved_attractions'] / max($stats['total_attractions'], 1)) * 100 }}%">
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="admin-stat-card-enhanced admin-stat-success" data-animate>
                <div class="admin-stat-icon-enhanced">
                    <i class='bx bx-store'></i>
                </div>
                <div class="admin-stat-content-enhanced">
                    <p class="admin-stat-label-enhanced">Total Businesses</p>
                    <h3 class="admin-stat-value-enhanced">{{ $stats['total_businesses'] }}</h3>
                    <div class="admin-stat-footer">
                        <span class="admin-stat-badge-enhanced success">
                            <i class='bx bx-check-circle'></i> {{ $stats['approved_businesses'] }} active
                        </span>
                    </div>
                </div>
                <div class="admin-stat-progress">
                    <div class="admin-stat-progress-bar"
                        style="width: {{ ($stats['approved_businesses'] / max($stats['total_businesses'], 1)) * 100 }}%">
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="admin-stat-card-enhanced admin-stat-warning" data-animate>
                <div class="admin-stat-icon-enhanced">
                    <i class='bx bx-message-square-detail'></i>
                </div>
                <div class="admin-stat-content-enhanced">
                    <p class="admin-stat-label-enhanced">Pending Reviews</p>
                    <h3 class="admin-stat-value-enhanced">{{ $stats['pending_reviews'] }}</h3>
                    <div class="admin-stat-footer">
                        <span class="admin-stat-badge-enhanced warning">
                            <i class='bx bx-time'></i> Needs attention
                        </span>
                    </div>
                </div>
                @if ($stats['pending_reviews'] > 0)
                    <div class="admin-stat-alert-pulse"></div>
                @endif
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="admin-stat-card-enhanced admin-stat-info" data-animate>
                <div class="admin-stat-icon-enhanced">
                    <i class='bx bx-user'></i>
                </div>
                <div class="admin-stat-content-enhanced">
                    <p class="admin-stat-label-enhanced">Total Users</p>
                    <h3 class="admin-stat-value-enhanced">{{ $stats['total_users'] }}</h3>
                    <div class="admin-stat-footer">
                        <span class="admin-stat-badge-enhanced info">
                            <i class='bx bx-briefcase'></i> {{ $stats['business_owners'] }} owners
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Bar -->
    <div class="admin-quick-actions mb-4">
        <h6 class="admin-quick-actions-title">Quick Actions</h6>
        <div class="admin-quick-actions-grid">
            <a href="{{ route('admin.attractions.create') }}" class="admin-quick-action-btn">
                <i class='bx bx-plus-circle'></i>
                <span>Add Attraction</span>
            </a>
            <a href="{{ route('admin.businesses.index', ['status' => 'pending']) }}" class="admin-quick-action-btn">
                <i class='bx bx-check-double'></i>
                <span>Approve Businesses</span>
            </a>
            <a href="{{ route('admin.reviews.index', ['status' => 'pending']) }}" class="admin-quick-action-btn">
                <i class='bx bx-message-square-check'></i>
                <span>Moderate Reviews</span>
            </a>
            <a href="{{ route('admin.reports.index') }}" class="admin-quick-action-btn">
                <i class='bx bx-bar-chart-alt-2'></i>
                <span>View Reports</span>
            </a>
        </div>
    </div>

    <!-- Content Grid -->
    <div class="row g-4 mb-4">
        <!-- Pending Approvals -->
        <div class="col-lg-6">
            <div class="admin-card-enhanced">
                <div class="admin-card-header-enhanced">
                    <div class="admin-card-title-group">
                        <i class='bx bx-time-five admin-card-icon'></i>
                        <h5 class="admin-card-title-enhanced">Pending Approvals</h5>
                    </div>
                    <span
                        class="admin-badge-pill warning">{{ $stats['pending_attractions'] + $stats['pending_businesses'] }}</span>
                </div>
                <div class="admin-card-body-enhanced">
                    <div class="admin-pending-list">
                        <a href="{{ route('admin.attractions.index', ['status' => 'pending']) }}"
                            class="admin-pending-item">
                            <div class="admin-pending-icon primary">
                                <i class='bx bx-map-pin'></i>
                            </div>
                            <div class="admin-pending-content">
                                <h6>Pending Attractions</h6>
                                <p>Awaiting review and approval</p>
                            </div>
                            <span class="admin-pending-count">{{ $stats['pending_attractions'] }}</span>
                            <i class='bx bx-chevron-right admin-pending-arrow'></i>
                        </a>

                        <a href="{{ route('admin.businesses.index', ['status' => 'pending']) }}"
                            class="admin-pending-item">
                            <div class="admin-pending-icon success">
                                <i class='bx bx-store'></i>
                            </div>
                            <div class="admin-pending-content">
                                <h6>Pending Businesses</h6>
                                <p>Waiting for verification</p>
                            </div>
                            <span class="admin-pending-count">{{ $stats['pending_businesses'] }}</span>
                            <i class='bx bx-chevron-right admin-pending-arrow'></i>
                        </a>

                        <a href="{{ route('admin.reviews.index', ['status' => 'pending']) }}" class="admin-pending-item">
                            <div class="admin-pending-icon warning">
                                <i class='bx bx-message-square-detail'></i>
                            </div>
                            <div class="admin-pending-content">
                                <h6>Pending Reviews</h6>
                                <p>Requires moderation</p>
                            </div>
                            <span class="admin-pending-count">{{ $stats['pending_reviews'] }}</span>
                            <i class='bx bx-chevron-right admin-pending-arrow'></i>
                        </a>

                        <a href="{{ route('admin.suggestions.index', ['status' => 'pending']) }}"
                            class="admin-pending-item">
                            <div class="admin-pending-icon info">
                                <i class='bx bx-bulb'></i>
                            </div>
                            <div class="admin-pending-content">
                                <h6>Pending Suggestions</h6>
                                <p>New landmark proposals</p>
                            </div>
                            <span class="admin-pending-count">{{ $stats['pending_suggestions'] }}</span>
                            <i class='bx bx-chevron-right admin-pending-arrow'></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Popular Attractions -->
        <div class="col-lg-6">
            <div class="admin-card-enhanced">
                <div class="admin-card-header-enhanced">
                    <div class="admin-card-title-group">
                        <i class='bx bx-trending-up admin-card-icon'></i>
                        <h5 class="admin-card-title-enhanced">Popular Attractions</h5>
                    </div>
                </div>
                <div class="admin-card-body-enhanced">
                    <div class="admin-popular-list">
                        @forelse($popularAttractions as $index => $attraction)
                            <div class="admin-popular-item">
                                <div class="admin-popular-rank">{{ $index + 1 }}</div>
                                <div class="admin-popular-content">
                                    <a href="{{ route('admin.attractions.show', $attraction) }}"
                                        class="admin-popular-name">
                                        {{ Str::limit($attraction->name, 35) }}
                                    </a>
                                    <div class="admin-popular-meta">
                                        <span class="admin-popular-views">
                                            <i class='bx bx-show'></i> {{ number_format($attraction->views) }}
                                        </span>
                                        <span class="admin-popular-rating">
                                            <i class='bx bxs-star'></i>
                                            {{ number_format($attraction->getAverageRating(), 1) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="admin-empty-state-enhanced">
                                <i class='bx bx-map-pin'></i>
                                <p>No attraction data yet</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="row g-4 mb-4">
        <div class="col-lg-6">
            <div class="admin-card-enhanced">
                <div class="admin-card-header-enhanced">
                    <div class="admin-card-title-group">
                        <i class='bx bx-time admin-card-icon'></i>
                        <h5 class="admin-card-title-enhanced">Recent Attractions</h5>
                    </div>
                </div>
                <div class="admin-card-body-enhanced">
                    @forelse($recentAttractions as $attraction)
                        <div class="admin-activity-item-enhanced">
                            <div class="admin-activity-icon {{ $attraction->status }}">
                                <i class='bx bx-map-pin'></i>
                            </div>
                            <div class="admin-activity-content-enhanced">
                                <h6>{{ Str::limit($attraction->name, 40) }}</h6>
                                <p>{{ $attraction->category->name }}</p>
                                <span class="admin-status-badge-enhanced {{ $attraction->status }}">
                                    {{ ucfirst($attraction->status) }}
                                </span>
                            </div>
                            <span
                                class="admin-activity-time-enhanced">{{ $attraction->created_at->diffForHumans() }}</span>
                        </div>
                    @empty
                        <div class="admin-empty-state-enhanced">
                            <i class='bx bx-map-pin'></i>
                            <p>No recent attractions</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="admin-card-enhanced">
                <div class="admin-card-header-enhanced">
                    <div class="admin-card-title-group">
                        <i class='bx bx-message-square-detail admin-card-icon'></i>
                        <h5 class="admin-card-title-enhanced">Recent Reviews</h5>
                    </div>
                </div>
                <div class="admin-card-body-enhanced">
                    @forelse($recentReviews as $review)
                        <div class="admin-activity-item-enhanced">
                            <div class="admin-activity-icon {{ $review->status }}">
                                <i class='bx bx-message-square-detail'></i>
                            </div>
                            <div class="admin-activity-content-enhanced">
                                <h6>{{ $review->user->name }}</h6>
                                <p>{{ Str::limit($review->reviewable->name ?? 'N/A', 35) }}</p>
                                <div class="admin-rating-stars-enhanced">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class='bx {{ $i <= $review->rating ? 'bxs-star' : 'bx-star' }}'></i>
                                    @endfor
                                </div>
                                <span class="admin-status-badge-enhanced {{ $review->status }}">
                                    {{ ucfirst($review->status) }}
                                </span>
                            </div>
                            <span class="admin-activity-time-enhanced">{{ $review->created_at->diffForHumans() }}</span>
                        </div>
                    @empty
                        <div class="admin-empty-state-enhanced">
                            <i class='bx bx-message-square-detail'></i>
                            <p>No recent reviews</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics Overview -->
    <div class="admin-card-enhanced">
        <div class="admin-card-header-enhanced">
            <div class="admin-card-title-group">
                <i class='bx bx-bar-chart admin-card-icon'></i>
                <h5 class="admin-card-title-enhanced">System Analytics</h5>
            </div>
            <a href="{{ route('admin.reports.index') }}" class="admin-btn-enhanced primary">
                View Reports <i class='bx bx-right-arrow-alt'></i>
            </a>
        </div>
        <div class="admin-card-body-enhanced">
            <div class="admin-analytics-grid">
                <div class="admin-analytics-item-enhanced">
                    <div class="admin-analytics-icon-enhanced primary">
                        <i class='bx bx-show'></i>
                    </div>
                    <div class="admin-analytics-content-enhanced">
                        <h4>{{ number_format($pageViews) }}</h4>
                        <p>Page Views (30 days)</p>
                    </div>
                </div>

                <div class="admin-analytics-item-enhanced">
                    <div class="admin-analytics-icon-enhanced success">
                        <i class='bx bx-user'></i>
                    </div>
                    <div class="admin-analytics-content-enhanced">
                        <h4>{{ $stats['total_users'] }}</h4>
                        <p>Registered Users</p>
                    </div>
                </div>

                <div class="admin-analytics-item-enhanced">
                    <div class="admin-analytics-icon-enhanced warning">
                        <i class='bx bx-map-pin'></i>
                    </div>
                    <div class="admin-analytics-content-enhanced">
                        <h4>{{ $stats['approved_attractions'] }}</h4>
                        <p>Active Attractions</p>
                    </div>
                </div>

                <div class="admin-analytics-item-enhanced">
                    <div class="admin-analytics-icon-enhanced info">
                        <i class='bx bx-store'></i>
                    </div>
                    <div class="admin-analytics-content-enhanced">
                        <h4>{{ $stats['approved_businesses'] }}</h4>
                        <p>Active Businesses</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Enhanced Welcome Banner */
        .admin-welcome-banner-enhanced {
            background: linear-gradient(135deg, #1a7838 0%, #27a345 100%);
            border-radius: 20px;
            padding: 2.5rem;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(26, 120, 56, 0.25);
        }

        .admin-welcome-content-enhanced {
            position: relative;
            z-index: 2;
            color: white;
        }

        .admin-welcome-greeting h1 {
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            color: white;
        }

        .highlight-name {
            color: #FFD700;
        }

        .admin-welcome-greeting p {
            font-size: 0.875rem;
            opacity: 0.9;
            margin-bottom: 0.5rem;
        }

        .admin-welcome-subtitle {
            font-size: 1.125rem;
            opacity: 0.95;
            margin-top: 0.5rem;
        }

        .admin-welcome-decoration {
            position: absolute;
            top: 0;
            right: 0;
            width: 300px;
            height: 100%;
        }

        .admin-welcome-circle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
        }

        .circle-1 {
            width: 200px;
            height: 200px;
            top: -50px;
            right: -50px;
        }

        .circle-2 {
            width: 150px;
            height: 150px;
            top: 50px;
            right: 100px;
        }

        .circle-3 {
            width: 100px;
            height: 100px;
            bottom: -30px;
            right: 50px;
        }

        /* Enhanced Stat Cards */
        .admin-stat-card-enhanced {
            background: white;
            border-radius: 16px;
            padding: 1.75rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid #f1f5f9;
        }

        .admin-stat-card-enhanced:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.12);
        }

        .admin-stat-icon-enhanced {
            width: 60px;
            height: 60px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin-bottom: 1.25rem;
        }

        .admin-stat-primary .admin-stat-icon-enhanced {
            background: linear-gradient(135deg, #1a7838, #27a345);
            color: white;
        }

        .admin-stat-success .admin-stat-icon-enhanced {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }

        .admin-stat-warning .admin-stat-icon-enhanced {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
        }

        .admin-stat-info .admin-stat-icon-enhanced {
            background: linear-gradient(135deg, #00A8E8, #0284c7);
            color: white;
        }

        .admin-stat-label-enhanced {
            font-size: 0.875rem;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.5rem;
        }

        .admin-stat-value-enhanced {
            font-size: 2.5rem;
            font-weight: 800;
            color: #1e293b;
            margin: 0 0 1rem 0;
            line-height: 1;
        }

        .admin-stat-badge-enhanced {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.8125rem;
            font-weight: 600;
        }

        .admin-stat-badge-enhanced.success {
            background: #d1fae5;
            color: #059669;
        }

        .admin-stat-badge-enhanced.warning {
            background: #fef3c7;
            color: #d97706;
        }

        .admin-stat-badge-enhanced.info {
            background: #dbeafe;
            color: #0284c7;
        }

        .admin-stat-progress {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: #f1f5f9;
        }

        .admin-stat-progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #1a7838, #27a345);
            transition: width 1s ease;
        }

        .admin-stat-alert-pulse {
            position: absolute;
            top: 1rem;
            right: 1rem;
            width: 12px;
            height: 12px;
            background: #ef4444;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: 0.5;
                transform: scale(1.2);
            }
        }

        /* Quick Actions */
        .admin-quick-actions {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .admin-quick-actions-title {
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 1rem;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.1em;
        }

        .admin-quick-actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .admin-quick-action-btn {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem 1.25rem;
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            color: #334155;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9375rem;
            transition: all 0.25s ease;
        }

        .admin-quick-action-btn:hover {
            background: linear-gradient(135deg, #1a7838, #27a345);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(26, 120, 56, 0.2);
        }

        .admin-quick-action-btn i {
            font-size: 1.5rem;
        }

        /* Enhanced Cards */
        .admin-card-enhanced {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            border: 1px solid #f1f5f9;
            transition: all 0.3s ease;
        }

        .admin-card-enhanced:hover {
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
        }

        .admin-card-header-enhanced {
            padding: 1.5rem;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .admin-card-title-group {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .admin-card-icon {
            font-size: 1.5rem;
            color: #1a7838;
        }

        .admin-card-title-enhanced {
            font-size: 1.125rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }

        .admin-badge-pill {
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.875rem;
        }

        .admin-badge-pill.warning {
            background: #fef3c7;
            color: #d97706;
        }

        .admin-card-body-enhanced {
            padding: 1.5rem;
        }

        /* Pending Items */
        .admin-pending-list {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .admin-pending-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1.25rem;
            background: #f8fafc;
            border-radius: 12px;
            text-decoration: none;
            transition: all 0.25s ease;
            border: 1px solid #e2e8f0;
        }

        .admin-pending-item:hover {
            background: #f1f5f9;
            transform: translateX(4px);
        }

        .admin-pending-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            flex-shrink: 0;
        }

        .admin-pending-icon.primary {
            background: linear-gradient(135deg, #1a7838, #27a345);
        }

        .admin-pending-icon.success {
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .admin-pending-icon.warning {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }

        .admin-pending-icon.info {
            background: linear-gradient(135deg, #00A8E8, #0284c7);
        }

        .admin-pending-content {
            flex: 1;
        }

        .admin-pending-content h6 {
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.25rem;
            font-size: 1rem;
        }

        .admin-pending-content p {
            font-size: 0.875rem;
            color: #64748b;
            margin: 0;
        }

        .admin-pending-count {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: #1a7838;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .admin-pending-arrow {
            font-size: 1.5rem;
            color: #cbd5e1;
        }

        /* Popular Items */
        .admin-popular-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .admin-popular-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: #f8fafc;
            border-radius: 12px;
            transition: all 0.25s ease;
        }

        .admin-popular-item:hover {
            background: #f1f5f9;
        }

        .admin-popular-rank {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: linear-gradient(135deg, #1a7838, #27a345);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 1.125rem;
        }

        .admin-popular-content {
            flex: 1;
        }

        .admin-popular-name {
            font-weight: 700;
            color: #1e293b;
            text-decoration: none;
            display: block;
            margin-bottom: 0.5rem;
            font-size: 1rem;
        }

        .admin-popular-name:hover {
            color: #1a7838;
        }

        .admin-popular-meta {
            display: flex;
            gap: 1.5rem;
            font-size: 0.875rem;
            color: #64748b;
        }

        .admin-popular-meta i {
            margin-right: 0.25rem;
        }

        .admin-popular-rating {
            color: #f59e0b;
        }

        /* Activity Items */
        .admin-activity-item-enhanced {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            padding: 1.25rem;
            border-bottom: 1px solid #f1f5f9;
            transition: all 0.25s ease;
        }

        .admin-activity-item-enhanced:last-child {
            border-bottom: none;
        }

        .admin-activity-item-enhanced:hover {
            background: #f8fafc;
        }

        .admin-activity-icon {
            width: 44px;
            height: 44px;
            border-radius: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: white;
            flex-shrink: 0;
        }

        .admin-activity-icon.approved {
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .admin-activity-icon.pending {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }

        .admin-activity-icon.rejected {
            background: linear-gradient(135deg, #ef4444, #dc2626);
        }

        .admin-activity-content-enhanced {
            flex: 1;
        }

        .admin-activity-content-enhanced h6 {
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.375rem;
            font-size: 0.9375rem;
        }

        .admin-activity-content-enhanced p {
            font-size: 0.875rem;
            color: #64748b;
            margin-bottom: 0.5rem;
        }

        .admin-rating-stars-enhanced {
            color: #fbbf24;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
        }

        .admin-status-badge-enhanced {
            display: inline-block;
            padding: 0.375rem 0.875rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .admin-status-badge-enhanced.approved {
            background: #d1fae5;
            color: #059669;
        }

        .admin-status-badge-enhanced.pending {
            background: #fef3c7;
            color: #d97706;
        }

        .admin-status-badge-enhanced.rejected {
            background: #fee2e2;
            color: #dc2626;
        }

        .admin-activity-time-enhanced {
            font-size: 0.8125rem;
            color: #94a3b8;
            white-space: nowrap;
        }

        /* Analytics Grid */
        .admin-analytics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
        }

        .admin-analytics-item-enhanced {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1.5rem;
            background: #f8fafc;
            border-radius: 12px;
        }

        .admin-analytics-icon-enhanced {
            width: 56px;
            height: 56px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            color: white;
        }

        .admin-analytics-icon-enhanced.primary {
            background: linear-gradient(135deg, #1a7838, #27a345);
        }

        .admin-analytics-icon-enhanced.success {
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .admin-analytics-icon-enhanced.warning {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }

        .admin-analytics-icon-enhanced.info {
            background: linear-gradient(135deg, #00A8E8, #0284c7);
        }

        .admin-analytics-content-enhanced h4 {
            font-size: 2rem;
            font-weight: 800;
            color: #1e293b;
            margin-bottom: 0.25rem;
        }

        .admin-analytics-content-enhanced p {
            font-size: 0.875rem;
            color: #64748b;
            margin: 0;
        }

        /* Button */
        .admin-btn-enhanced {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.25s ease;
        }

        .admin-btn-enhanced.primary {
            background: linear-gradient(135deg, #1a7838, #27a345);
            color: white;
        }

        .admin-btn-enhanced.primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(26, 120, 56, 0.3);
        }

        /* Empty State */
        .admin-empty-state-enhanced {
            text-align: center;
            padding: 3rem 1rem;
            color: #94a3b8;
        }

        .admin-empty-state-enhanced i {
            font-size: 3rem;
            opacity: 0.3;
            margin-bottom: 1rem;
        }

        .admin-empty-state-enhanced p {
            margin: 0;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .admin-welcome-banner-enhanced {
                padding: 2rem 1.5rem;
            }

            .admin-welcome-greeting h1 {
                font-size: 1.5rem;
            }

            .admin-stat-value-enhanced {
                font-size: 2rem;
            }

            .admin-quick-actions-grid {
                grid-template-columns: 1fr;
            }

            .admin-analytics-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Animate stats on load
        document.addEventListener('DOMContentLoaded', function() {
            const stats = document.querySelectorAll('[data-animate]');
            stats.forEach((stat, index) => {
                setTimeout(() => {
                    stat.style.opacity = '0';
                    stat.style.transform = 'translateY(20px)';
                    setTimeout(() => {
                        stat.style.transition = 'all 0.5s ease';
                        stat.style.opacity = '1';
                        stat.style.transform = 'translateY(0)';
                    }, 50);
                }, index * 100);
            });
        });
    </script>
@endpush
