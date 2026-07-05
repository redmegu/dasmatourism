@extends('layouts.admin')

@section('page-title', 'Story Mode Chapters')

@section('content')
    <!-- Enhanced Page Header -->
    <div class="admin-page-header mb-4">
        <div class="admin-page-header-content">
            <div>
                <h2 class="admin-page-title">Story Mode Management</h2>
                <p class="admin-page-subtitle">Create and manage interactive story chapters</p>
            </div>
            <a href="{{ route('admin.story-chapters.create') }}" class="admin-header-btn">
                <i class='bx bx-plus-circle'></i>
                <span>Create Chapter</span>
            </a>
        </div>
    </div>

    <!-- Enhanced Stats -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="admin-stat-card-modern primary">
                <div class="admin-stat-card-icon-modern">
                    <i class='bx bx-book-reader'></i>
                </div>
                <div class="admin-stat-card-content-modern">
                    <p>Total Chapters</p>
                    <h3>{{ $chapters->total() }}</h3>
                </div>
                <div class="admin-stat-card-decoration"></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="admin-stat-card-modern success">
                <div class="admin-stat-card-icon-modern">
                    <i class='bx bx-check-circle'></i>
                </div>
                <div class="admin-stat-card-content-modern">
                    <p>Active Chapters</p>
                    <h3>{{ \App\Models\StoryChapter::where('is_active', true)->count() }}</h3>
                </div>
                <div class="admin-stat-card-decoration"></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="admin-stat-card-modern info">
                <div class="admin-stat-card-icon-modern">
                    <i class='bx bx-git-branch'></i>
                </div>
                <div class="admin-stat-card-content-modern">
                    <p>Total Choices</p>
                    <h3>{{ \App\Models\StoryChoice::count() }}</h3>
                </div>
                <div class="admin-stat-card-decoration"></div>
            </div>
        </div>
    </div>

    <!-- Chapters List -->
    <div class="admin-story-chapters-container">
        @forelse($chapters as $chapter)
            <div class="admin-story-chapter-card">
                <div class="admin-story-chapter-badge">
                    <span class="chapter-number">{{ $chapter->chapter_number }}</span>
                    <span class="chapter-label">Chapter</span>
                </div>

                <div class="admin-story-chapter-main">
                    <div class="admin-story-chapter-header-section">
                        <div class="admin-story-chapter-info">
                            <h3 class="admin-story-chapter-title">{{ $chapter->title }}</h3>
                            @if ($chapter->attraction)
                                <div class="admin-story-chapter-location">
                                    <i class='bx bx-map-pin'></i>
                                    <span>{{ $chapter->attraction->name }}</span>
                                </div>
                            @endif
                        </div>
                        <div class="admin-story-chapter-status">
                            <div class="admin-status-badge-pill {{ $chapter->is_active ? 'active' : 'inactive' }}">
                                <i class='bx {{ $chapter->is_active ? 'bx-check-circle' : 'bx-x-circle' }}'></i>
                                <span>{{ $chapter->is_active ? 'Active' : 'Inactive' }}</span>
                            </div>
                        </div>
                    </div>

                    <p class="admin-story-chapter-excerpt">
                        {{ Str::limit($chapter->content, 200) }}
                    </p>

                    @if ($chapter->background_image)
                        <div class="admin-story-chapter-preview-image">
                            <img src="{{ asset('storage/' . $chapter->background_image) }}" alt="{{ $chapter->title }}">
                            <div class="admin-image-overlay">
                                <i class='bx bx-image'></i>
                            </div>
                        </div>
                    @endif

                    <div class="admin-story-chapter-meta-bar">
                        <div class="admin-story-chapter-choices-info">
                            <div class="admin-choices-badge">
                                <i class='bx bx-git-branch'></i>
                                <span>{{ $chapter->choices->count() }}
                                    {{ Str::plural('choice', $chapter->choices->count()) }}</span>
                            </div>
                        </div>
                        <div class="admin-story-chapter-actions-group">
                            <a href="{{ route('admin.story-chapters.show', $chapter) }}" class="admin-action-btn-pill view"
                                title="View Details">
                                <i class='bx bx-show'></i>
                                <span>View</span>
                            </a>
                            <a href="{{ route('admin.story-chapters.edit', $chapter) }}" class="admin-action-btn-pill edit"
                                title="Edit Chapter">
                                <i class='bx bx-edit'></i>
                                <span>Edit</span>
                            </a>
                            <button type="button" class="admin-action-btn-pill delete"
                                onclick="deleteChapter({{ $chapter->id }})" title="Delete Chapter">
                                <i class='bx bx-trash'></i>
                                <span>Delete</span>
                            </button>
                            <form id="delete-form-{{ $chapter->id }}"
                                action="{{ route('admin.story-chapters.destroy', $chapter) }}" method="POST"
                                class="d-none">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="admin-empty-state-modern">
                <div class="admin-empty-state-icon">
                    <i class='bx bx-book-reader'></i>
                </div>
                <h4>No Story Chapters Yet</h4>
                <p>Start creating your interactive story experience</p>
                <a href="{{ route('admin.story-chapters.create') }}" class="admin-form-btn primary">
                    <i class='bx bx-plus-circle'></i>
                    <span>Create First Chapter</span>
                </a>
            </div>
        @endforelse
    </div>

    @if ($chapters->hasPages())
        <div class="admin-pagination-wrapper">
            {{ $chapters->links('vendor.pagination.admin') }}
        </div>
    @endif
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
            align-items: center;
            gap: 2rem;
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

        .admin-header-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.875rem 1.75rem;
            background: linear-gradient(135deg, #1a7838, #27a345);
            color: white;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            box-shadow: 0 4px 12px rgba(26, 120, 56, 0.25);
            transition: all 0.3s ease;
        }

        .admin-header-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(26, 120, 56, 0.35);
            color: white;
        }

        .admin-header-btn i {
            font-size: 1.25rem;
        }

        /* Modern Stats Cards */
        .admin-stat-card-modern {
            position: relative;
            padding: 1.75rem;
            border-radius: 16px;
            overflow: hidden;
            display: flex;
            align-items: center;
            gap: 1.5rem;
            transition: all 0.3s ease;
            border: 1px solid;
        }

        .admin-stat-card-modern.primary {
            background: linear-gradient(135deg, #e0f2e9, #d1fae5);
            border-color: rgba(26, 120, 56, 0.2);
        }

        .admin-stat-card-modern.success {
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
            border-color: rgba(16, 185, 129, 0.2);
        }

        .admin-stat-card-modern.info {
            background: linear-gradient(135deg, #dbeafe, #bfdbfe);
            border-color: rgba(0, 168, 232, 0.2);
        }

        .admin-stat-card-modern:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
        }

        .admin-stat-card-icon-modern {
            width: 70px;
            height: 70px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .admin-stat-card-modern.primary .admin-stat-card-icon-modern {
            background: linear-gradient(135deg, #1a7838, #27a345);
        }

        .admin-stat-card-modern.success .admin-stat-card-icon-modern {
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .admin-stat-card-modern.info .admin-stat-card-icon-modern {
            background: linear-gradient(135deg, #00A8E8, #0284c7);
        }

        .admin-stat-card-content-modern {
            flex: 1;
            z-index: 1;
        }

        .admin-stat-card-content-modern p {
            font-size: 0.875rem;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin: 0 0 0.5rem 0;
        }

        .admin-stat-card-content-modern h3 {
            font-size: 2.25rem;
            font-weight: 800;
            color: #1e293b;
            margin: 0;
        }

        .admin-stat-card-decoration {
            position: absolute;
            right: -20px;
            bottom: -20px;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            z-index: 0;
        }

        /* Pagination Styles */
        .pagination {
            margin: 0;
            gap: 0.25rem;
            flex-wrap: wrap;
        }

        .pagination .page-link {
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
            border-radius: 6px;
            border: 1px solid #e0e0e0;
            color: #667eea;
            transition: all 0.2s;
            min-width: 40px;
            text-align: center;
        }

        .pagination .page-link:hover {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }

        .pagination .page-item.active .page-link {
            background: #667eea;
            border-color: #667eea;
            color: white;
        }

        .pagination .page-item.disabled .page-link {
            color: #cbd5e0;
            background: #f8f9fa;
        }

        .pagination svg {
            width: 14px !important;
            height: 14px !important;
            vertical-align: middle;
        }

        .pagination .page-link svg,
        .pagination .page-item svg {
            width: 14px !important;
            height: 14px !important;
            max-width: 14px !important;
            max-height: 14px !important;
        }

        /* Story Chapters Container */
        .admin-story-chapters-container {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        /* Story Chapter Card */
        .admin-story-chapter-card {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #f1f5f9;
            display: flex;
            gap: 2rem;
            transition: all 0.3s ease;
        }

        .admin-story-chapter-card:hover {
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
            transform: translateY(-4px);
        }

        /* Chapter Badge */
        .admin-story-chapter-badge {
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 90px;
            height: 90px;
            border-radius: 16px;
            background: linear-gradient(135deg, #1a7838, #27a345);
            color: white;
            box-shadow: 0 4px 12px rgba(26, 120, 56, 0.25);
        }

        .chapter-number {
            font-size: 2rem;
            font-weight: 800;
            line-height: 1;
        }

        .chapter-label {
            font-size: 0.6875rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            opacity: 0.9;
            margin-top: 0.25rem;
        }

        /* Chapter Main Content */
        .admin-story-chapter-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        /* Chapter Header Section */
        .admin-story-chapter-header-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1.5rem;
        }

        .admin-story-chapter-info {
            flex: 1;
        }

        .admin-story-chapter-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: #1e293b;
            margin: 0 0 0.75rem 0;
        }

        .admin-story-chapter-location {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: #f1f5f9;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            color: #475569;
        }

        .admin-story-chapter-location i {
            font-size: 1.125rem;
            color: #1a7838;
        }

        /* Status Badge Pill */
        .admin-status-badge-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.625rem 1.125rem;
            border-radius: 10px;
            font-size: 0.875rem;
            font-weight: 700;
        }

        .admin-status-badge-pill.active {
            background: #d1fae5;
            color: #059669;
        }

        .admin-status-badge-pill.inactive {
            background: #f1f5f9;
            color: #64748b;
        }

        .admin-status-badge-pill i {
            font-size: 1.125rem;
        }

        /* Chapter Excerpt */
        .admin-story-chapter-excerpt {
            font-size: 0.9375rem;
            line-height: 1.7;
            color: #64748b;
            margin: 0;
        }

        /* Preview Image */
        .admin-story-chapter-preview-image {
            position: relative;
            max-width: 500px;
            border-radius: 12px;
            overflow: hidden;
            border: 2px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .admin-story-chapter-preview-image:hover {
            border-color: #1a7838;
        }

        .admin-story-chapter-preview-image img {
            width: 100%;
            height: auto;
            display: block;
        }

        .admin-image-overlay {
            position: absolute;
            top: 0.75rem;
            right: 0.75rem;
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(8px);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.25rem;
        }

        /* Meta Bar */
        .admin-story-chapter-meta-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 1.25rem;
            border-top: 1px solid #f1f5f9;
            gap: 1.5rem;
        }

        /* Choices Badge */
        .admin-choices-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.625rem;
            padding: 0.75rem 1.25rem;
            background: linear-gradient(135deg, #e0f2ff, #dbeafe);
            border-radius: 10px;
            font-weight: 700;
            color: #0284c7;
        }

        .admin-choices-badge i {
            font-size: 1.25rem;
        }

        /* Action Buttons Group */
        .admin-story-chapter-actions-group {
            display: flex;
            gap: 0.75rem;
        }

        .admin-action-btn-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.625rem 1.125rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.875rem;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.25s ease;
        }

        .admin-action-btn-pill.view {
            background: #e0f2ff;
            color: #0284c7;
        }

        .admin-action-btn-pill.view:hover {
            background: #0284c7;
            color: white;
            transform: translateY(-2px);
        }

        .admin-action-btn-pill.edit {
            background: #fef3c7;
            color: #d97706;
        }

        .admin-action-btn-pill.edit:hover {
            background: #d97706;
            color: white;
            transform: translateY(-2px);
        }

        .admin-action-btn-pill.delete {
            background: #fee2e2;
            color: #dc2626;
        }

        .admin-action-btn-pill.delete:hover {
            background: #dc2626;
            color: white;
            transform: translateY(-2px);
        }

        .admin-action-btn-pill i {
            font-size: 1.125rem;
        }

        /* Empty State */
        .admin-empty-state-modern {
            background: white;
            border-radius: 16px;
            padding: 5rem 2rem;
            text-align: center;
            border: 2px dashed #e2e8f0;
        }

        .admin-empty-state-icon {
            width: 120px;
            height: 120px;
            margin: 0 auto 2rem;
            border-radius: 50%;
            background: linear-gradient(135deg, #e0f2e9, #d1fae5);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 4rem;
            color: #1a7838;
        }

        .admin-empty-state-modern h4 {
            font-size: 1.5rem;
            font-weight: 800;
            color: #1e293b;
            margin-bottom: 0.75rem;
        }

        .admin-empty-state-modern p {
            font-size: 1rem;
            color: #64748b;
            margin-bottom: 2rem;
        }

        /* Form Button */
        .admin-form-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            padding: 0.875rem 1.75rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .admin-form-btn.primary {
            background: linear-gradient(135deg, #1a7838, #27a345);
            color: white;
            box-shadow: 0 4px 12px rgba(26, 120, 56, 0.25);
        }

        .admin-form-btn.primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(26, 120, 56, 0.35);
        }

        .admin-form-btn i {
            font-size: 1.25rem;
        }

        /* Pagination */
        .admin-pagination-wrapper {
            margin-top: 2rem;
            padding: 1.5rem;
            background: white;
            border-radius: 14px;
            border: 1px solid #f1f5f9;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .admin-page-header-content {
                flex-direction: column;
                align-items: stretch;
            }

            .admin-header-btn {
                width: 100%;
                justify-content: center;
            }

            .admin-story-chapter-card {
                flex-direction: column;
                gap: 1.5rem;
                padding: 1.5rem;
            }

            .admin-story-chapter-badge {
                width: 80px;
                height: 80px;
            }

            .chapter-number {
                font-size: 1.75rem;
            }

            .admin-story-chapter-header-section {
                flex-direction: column;
                gap: 1rem;
            }

            .admin-story-chapter-meta-bar {
                flex-direction: column;
                align-items: stretch;
                gap: 1rem;
            }

            .admin-story-chapter-actions-group {
                flex-wrap: wrap;
            }

            .admin-action-btn-pill {
                flex: 1;
                justify-content: center;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        function deleteChapter(id) {
            if (confirm(
                    'Are you sure you want to delete this chapter? All associated choices and progress will be permanently deleted.'
                )) {
                document.getElementById('delete-form-' + id).submit();
            }
        }
    </script>
@endpush
