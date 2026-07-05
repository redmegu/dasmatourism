@extends('layouts.app')

@section('title', 'My Suggestions - Dasmariñas Tourism')

@section('content')
    <section class="profile-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <!-- Header -->
                    <div class="profile-header-card">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <div>
                                <h2 class="profile-header-title">
                                    <i class='bx bx-map-pin'></i> My Suggestions
                                </h2>
                                <p class="profile-header-subtitle">Places you've suggested to be added</p>
                            </div>
                            <div class="d-flex gap-2 flex-wrap">
                                <a href="{{ route('user.suggestions.create') }}" class="btn btn-primary">
                                    <i class='bx bx-plus me-2'></i>New Suggestion
                                </a>
                                <a href="{{ route('user.profile.show') }}" class="btn btn-outline-primary">
                                    <i class='bx bx-arrow-back me-2'></i>Back
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Success/Error Messages -->
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class='bx bx-check-circle me-2'></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class='bx bx-error-circle me-2'></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if ($suggestions->count() > 0)
                        <!-- Stats -->
                        <div class="suggestion-stats mb-4">
                            <div class="stat-badge">
                                <i class='bx bx-map-pin'></i>
                                <span>{{ $suggestions->total() }} Total</span>
                            </div>
                            <div class="stat-badge stat-badge-success">
                                <i class='bx bx-check-circle'></i>
                                <span>{{ $suggestions->where('status', 'approved')->count() }} Approved</span>
                            </div>
                            <div class="stat-badge stat-badge-warning">
                                <i class='bx bx-time'></i>
                                <span>{{ $suggestions->where('status', 'pending')->count() }} Pending</span>
                            </div>
                            @if ($suggestions->where('status', 'rejected')->count() > 0)
                                <div class="stat-badge stat-badge-danger">
                                    <i class='bx bx-x-circle'></i>
                                    <span>{{ $suggestions->where('status', 'rejected')->count() }} Rejected</span>
                                </div>
                            @endif
                        </div>

                        <!-- Suggestions List -->
                        <div class="suggestions-list">
                            @foreach ($suggestions as $suggestion)
                                <div class="suggestion-item-card">
                                    <!-- Header -->
                                    <div class="suggestion-item-header">
                                        <div class="suggestion-item-info">
                                            <h5 class="suggestion-item-title">{{ $suggestion->name }}</h5>
                                            <p class="suggestion-item-location">
                                                <i class='bx bx-map-pin'></i>
                                                {{ $suggestion->address }}
                                            </p>
                                        </div>
                                        <div class="suggestion-status-badge status-{{ $suggestion->status }}">
                                            <i
                                                class='bx {{ $suggestion->status === 'approved' ? 'bx-check-circle' : ($suggestion->status === 'rejected' ? 'bx-x-circle' : 'bx-time-five') }}'></i>
                                            {{ ucfirst($suggestion->status) }}
                                        </div>
                                    </div>

                                    <!-- Body -->
                                    <div class="suggestion-item-body">
                                        <p class="suggestion-description">{{ Str::limit($suggestion->description, 150) }}
                                        </p>

                                        @if ($suggestion->is_historical)
                                            <span class="badge-historical-small">
                                                <i class='bx bx-landmark'></i> Historical Site
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Footer -->
                                    <div class="suggestion-item-footer">
                                        <div class="suggestion-meta">
                                            <i class='bx bx-calendar'></i>
                                            <span>Submitted {{ $suggestion->created_at->diffForHumans() }}</span>
                                        </div>
                                        <div class="d-flex gap-2 flex-wrap">
                                            <a href="{{ route('user.suggestions.show', $suggestion->id) }}"
                                                class="btn btn-outline-primary btn-sm">
                                                <i class='bx bx-show me-1'></i>View Details
                                            </a>

                                            @if (in_array($suggestion->status, ['pending', 'rejected']))
                                                <a href="{{ route('user.suggestions.edit', $suggestion->id) }}"
                                                    class="btn btn-outline-secondary btn-sm">
                                                    <i class='bx bx-edit me-1'></i>Edit
                                                </a>
                                            @endif

                                            @if ($suggestion->status !== 'approved')
                                                <form action="{{ route('user.suggestions.destroy', $suggestion->id) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Are you sure you want to delete this suggestion?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                                        <i class='bx bx-trash'></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Admin Response -->
                                    @if ($suggestion->admin_notes)
                                        <div class="suggestion-admin-response">
                                            <strong>
                                                <i class='bx bx-message-square-detail'></i> Admin Response:
                                            </strong>
                                            <p>{{ $suggestion->admin_notes }}</p>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-4">
                            {{ $suggestions->links('vendor.pagination.user') }}
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="suggestion-empty-state">
                            <i class='bx bx-map-pin'></i>
                            <h4>No Suggestions Yet</h4>
                            <p>Help us discover new places by suggesting attractions in Dasmariñas City!</p>
                            <a href="{{ route('user.suggestions.create') }}" class="btn btn-primary mt-3">
                                <i class='bx bx-plus me-2'></i>Submit Your First Suggestion
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
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
