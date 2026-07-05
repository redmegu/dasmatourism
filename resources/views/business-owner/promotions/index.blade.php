@extends('layouts.business-owner')

@section('title', 'Promotions')
@section('page-title', 'My Promotions')

@section('content')
    <!-- Header -->
    <div class="dashboard-card mb-4">
        <div class="dashboard-card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-2">Manage Your Promotions</h4>
                    <p class="text-muted mb-0">Create and manage special offers for your business</p>
                </div>
                <a href="{{ route('business-owner.promotions.create') }}" class="btn btn-primary">
                    <i class='bx bx-plus-circle'></i> New Promotion
                </a>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class='bx bx-check-circle me-2'></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($promotions->count() > 0)
        <!-- Promotions Grid -->
        <div class="row g-4">
            @foreach ($promotions as $promotion)
                <div class="col-md-6 col-lg-4">
                    <div class="promotion-card">
                        <!-- Promotion Image -->
                        <div class="promotion-image">
                            @if ($promotion->image)
                                <img src="{{ asset('storage/' . $promotion->image) }}" alt="{{ $promotion->title }}">
                            @else
                                <div class="promotion-placeholder">
                                    <i class='bx bx-badge-check'></i>
                                </div>
                            @endif

                            <!-- Status Badge -->
                            <div class="promotion-status">
                                @if ($promotion->is_active && $promotion->start_date <= now() && $promotion->end_date >= now())
                                    <span class="badge bg-success">
                                        <i class='bx bx-check-circle'></i> Active
                                    </span>
                                @elseif($promotion->start_date > now())
                                    <span class="badge bg-info">
                                        <i class='bx bx-time'></i> Scheduled
                                    </span>
                                @elseif($promotion->end_date < now())
                                    <span class="badge bg-secondary">
                                        <i class='bx bx-history'></i> Expired
                                    </span>
                                @else
                                    <span class="badge bg-warning text-dark">
                                        <i class='bx bx-pause'></i> Inactive
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Promotion Details -->
                        <div class="promotion-body">
                            <h5 class="promotion-title">{{ $promotion->title }}</h5>
                            <p class="promotion-description">{{ Str::limit($promotion->description, 100) }}</p>

                            <div class="promotion-dates">
                                <div class="promotion-date-item">
                                    <i class='bx bx-calendar'></i>
                                    <span>{{ $promotion->start_date->format('M d, Y') }}</span>
                                </div>
                                <div class="promotion-date-item">
                                    <i class='bx bx-calendar-x'></i>
                                    <span>{{ $promotion->end_date->format('M d, Y') }}</span>
                                </div>
                            </div>

                            <div class="promotion-stats">
                                <div class="stat-badge-small">
                                    <i class='bx bx-show'></i>
                                    <span>{{ number_format($promotion->views) }} views</span>
                                </div>
                            </div>
                        </div>

                        <!-- Promotion Actions -->
                        <div class="promotion-actions">
                            <a href="{{ route('business-owner.promotions.edit', $promotion->id) }}"
                                class="btn btn-sm btn-outline-primary">
                                <i class='bx bx-edit'></i> Edit
                            </a>
                            <form action="{{ route('business-owner.promotions.destroy', $promotion->id) }}" method="POST"
                                class="d-inline"
                                onsubmit="return confirm('Are you sure you want to delete this promotion?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class='bx bx-trash'></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $promotions->links('vendor.pagination.user') }}
        </div>
    @else
        <!-- Empty State -->
        <div class="dashboard-card">
            <div class="dashboard-card-body text-center py-5">
                <div class="empty-promotions-state">
                    <i class='bx bx-badge-check'></i>
                    <h4>No Promotions Yet</h4>
                    <p>Start attracting more customers by creating your first promotion!</p>
                    <a href="{{ route('business-owner.promotions.create') }}" class="btn btn-primary mt-3">
                        <i class='bx bx-plus-circle me-2'></i>Create Your First Promotion
                    </a>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('styles')
    <style>
        /* User Pagination Styles */
        .user-pagination-nav {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
            padding: 0;
        }

        @media (min-width: 640px) {
            .user-pagination-nav {
                flex-direction: row;
                justify-content: space-between;
            }
        }

        .user-pagination-info {
            color: #64748b;
            font-size: 0.875rem;
        }

        .user-pagination-info p {
            margin: 0;
        }

        .user-pagination-info .font-semibold {
            font-weight: 600;
            color: #1e293b;
        }

        .user-pagination {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            list-style: none;
            padding: 0;
            margin: 0;
            flex-wrap: wrap;
            justify-content: center;
        }

        .user-page-item {
            display: inline-flex;
        }

        .user-page-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
            padding: 0 0.75rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: #475569;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .user-page-link:hover {
            background: #f1f5f9;
            border-color: #cbd5e1;
            color: #1e293b;
        }

        .user-page-item.active .user-page-link {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-color: #667eea;
            color: white;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
        }

        .user-page-item.disabled .user-page-link {
            color: #cbd5e1;
            background: #f8fafc;
            border-color: #e2e8f0;
            cursor: not-allowed;
            pointer-events: none;
        }

        .user-page-link i {
            font-size: 1.125rem;
        }
    </style>
@endpush
