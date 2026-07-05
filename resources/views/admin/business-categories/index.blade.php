@extends('layouts.admin')

@section('page-title', 'Business Categories')

@section('content')
    <!-- Enhanced Page Header -->
    <div class="admin-page-header mb-4">
        <div class="admin-page-header-content">
            <div>
                <h2 class="admin-page-title">Business Categories</h2>
                <p class="admin-page-subtitle">Organize businesses by category types</p>
            </div>
            <div class="admin-header-stats">
                <div class="admin-mini-stat">
                    <i class='bx bx-category'></i>
                    <span>{{ $categories->total() }} Categories</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Section: Add Form + Stats -->
    <div class="row g-4 mb-4">
        <!-- Add New Category Form -->
        <div class="col-lg-6">
            <div class="admin-form-card">
                <div class="admin-form-card-header">
                    <div class="admin-form-card-icon">
                        <i class='bx bx-plus-circle'></i>
                    </div>
                    <div>
                        <h5 class="admin-form-card-title">Add New Category</h5>
                        <p class="admin-form-card-subtitle">Create a new business category</p>
                    </div>
                </div>
                <div class="admin-form-card-body">
                    <form action="{{ route('admin.business-categories.store') }}" method="POST">
                        @csrf
                        <div class="admin-form-group">
                            <label class="admin-form-label">
                                <i class='bx bx-tag'></i>
                                Category Name
                                <span class="admin-required">*</span>
                            </label>
                            <input type="text" class="admin-form-input @error('name') is-invalid @enderror"
                                name="name" value="{{ old('name') }}" placeholder="e.g., Restaurant, Hotel, Shopping"
                                required>
                            @error('name')
                                <div class="admin-form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="admin-form-group">
                            <label class="admin-form-label">
                                <i class='bx bx-message-square-detail'></i>
                                Description
                            </label>
                            <textarea class="admin-form-textarea @error('description') is-invalid @enderror" name="description" rows="3"
                                placeholder="Brief description of this category">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="admin-form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="admin-form-group">
                            <label class="admin-form-label">
                                <i class='bx bx-shape-circle'></i>
                                Icon Class
                            </label>
                            <input type="text" class="admin-form-input @error('icon') is-invalid @enderror"
                                name="icon" value="{{ old('icon', 'bx-store') }}"
                                placeholder="e.g., bx-store, bx-restaurant">
                            <div class="admin-form-hint">
                                <i class='bx bx-info-circle'></i>
                                Browse icons at <a href="https://boxicons.com" target="_blank"
                                    class="admin-link">boxicons.com</a>
                            </div>
                            @error('icon')
                                <div class="admin-form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="admin-submit-btn primary">
                            <i class='bx bx-plus'></i>
                            <span>Add Category</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="col-lg-6">
            <div class="admin-stat-grid-card">
                <div class="admin-stat-grid-header">
                    <div class="admin-stat-grid-icon">
                        <i class='bx bx-bar-chart'></i>
                    </div>
                    <div>
                        <h5 class="admin-stat-grid-title">Category Statistics</h5>
                        <p class="admin-stat-grid-subtitle">Overview of all business categories</p>
                    </div>
                </div>
                <div class="admin-stat-grid-body">
                    <div class="admin-stat-modern-item primary">
                        <div class="admin-stat-modern-icon">
                            <i class='bx bx-category'></i>
                        </div>
                        <div class="admin-stat-modern-content">
                            <span class="admin-stat-modern-label">Total Categories</span>
                            <span class="admin-stat-modern-value">{{ $categories->total() }}</span>
                        </div>
                    </div>

                    <div class="admin-stat-modern-item success">
                        <div class="admin-stat-modern-icon">
                            <i class='bx bx-check-circle'></i>
                        </div>
                        <div class="admin-stat-modern-content">
                            <span class="admin-stat-modern-label">Active Categories</span>
                            <span
                                class="admin-stat-modern-value">{{ \App\Models\BusinessCategory::where('is_active', true)->count() }}</span>
                        </div>
                    </div>

                    <div class="admin-stat-modern-item info">
                        <div class="admin-stat-modern-icon">
                            <i class='bx bx-store'></i>
                        </div>
                        <div class="admin-stat-modern-content">
                            <span class="admin-stat-modern-label">Total Businesses</span>
                            <span class="admin-stat-modern-value">{{ \App\Models\Business::count() }}</span>
                        </div>
                    </div>

                    <div class="admin-stat-modern-item warning">
                        <div class="admin-stat-modern-icon">
                            <i class='bx bx-pause-circle'></i>
                        </div>
                        <div class="admin-stat-modern-content">
                            <span class="admin-stat-modern-label">Inactive Categories</span>
                            <span
                                class="admin-stat-modern-value">{{ \App\Models\BusinessCategory::where('is_active', false)->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Categories Table -->
    <div class="admin-table-card">
        <div class="admin-table-card-header">
            <div>
                <h5 class="admin-table-card-title">All Business Categories</h5>
                <p class="admin-table-card-subtitle">Manage and organize business categories</p>
            </div>
        </div>

        <div class="admin-table-wrapper">
            <table class="admin-table-modern">
                <thead>
                    <tr>
                        <th style="width: 80px;">Icon</th>
                        <th style="min-width: 200px;">Category</th>
                        <th style="min-width: 250px;">Description</th>
                        <th style="width: 130px;">Businesses</th>
                        <th style="width: 100px;">Status</th>
                        <th style="width: 120px; text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr class="admin-table-row">
                            <td>
                                <div class="admin-category-icon-display">
                                    <i class='bx {{ $category->icon ?? 'bx-store' }}'></i>
                                </div>
                            </td>
                            <td>
                                <div class="admin-category-name-cell">
                                    <strong class="admin-category-name">{{ $category->name }}</strong>
                                    <span class="admin-category-slug">{{ $category->slug }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="admin-category-description">
                                    {{ Str::limit($category->description, 60) ?? '-' }}
                                </span>
                            </td>
                            <td>
                                <div class="admin-business-count-badge">
                                    <i class='bx bx-store'></i>
                                    <span>{{ $category->businesses_count }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="admin-status-pill {{ $category->is_active ? 'active' : 'inactive' }}">
                                    <span class="admin-status-dot"></span>
                                    <span>{{ $category->is_active ? 'Active' : 'Inactive' }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="admin-action-buttons">
                                    <button type="button" class="admin-action-btn edit" data-bs-toggle="modal"
                                        data-bs-target="#editModal{{ $category->id }}" title="Edit">
                                        <i class='bx bx-edit'></i>
                                    </button>
                                    <button type="button" class="admin-action-btn delete"
                                        onclick="deleteCategory({{ $category->id }})" title="Delete">
                                        <i class='bx bx-trash'></i>
                                    </button>
                                </div>

                                <form id="delete-form-{{ $category->id }}"
                                    action="{{ route('admin.business-categories.destroy', $category) }}" method="POST"
                                    class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editModal{{ $category->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content admin-modal">
                                    <div class="modal-header admin-modal-header">
                                        <div class="admin-modal-title-wrapper">
                                            <div class="admin-modal-icon">
                                                <i class='bx bx-edit'></i>
                                            </div>
                                            <div>
                                                <h5 class="modal-title">Edit Business Category</h5>
                                                <p class="admin-modal-subtitle">Update category information</p>
                                            </div>
                                        </div>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('admin.business-categories.update', $category) }}"
                                        method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body admin-modal-body">
                                            <div class="admin-form-group">
                                                <label class="admin-form-label">
                                                    <i class='bx bx-tag'></i>
                                                    Category Name
                                                </label>
                                                <input type="text" class="admin-form-input" name="name"
                                                    value="{{ $category->name }}" required>
                                            </div>

                                            <div class="admin-form-group">
                                                <label class="admin-form-label">
                                                    <i class='bx bx-message-square-detail'></i>
                                                    Description
                                                </label>
                                                <textarea class="admin-form-textarea" name="description" rows="3">{{ $category->description }}</textarea>
                                            </div>

                                            <div class="admin-form-group">
                                                <label class="admin-form-label">
                                                    <i class='bx bx-shape-circle'></i>
                                                    Icon Class
                                                </label>
                                                <input type="text" class="admin-form-input" name="icon"
                                                    value="{{ $category->icon }}">
                                            </div>

                                            <div class="admin-form-switch">
                                                <input class="form-check-input" type="checkbox" name="is_active"
                                                    id="is_active_{{ $category->id }}" value="1"
                                                    {{ $category->is_active ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_active_{{ $category->id }}">
                                                    Active Category
                                                </label>
                                            </div>
                                        </div>
                                        <div class="modal-footer admin-modal-footer">
                                            <button type="button" class="admin-modal-btn secondary"
                                                data-bs-dismiss="modal">
                                                <i class='bx bx-x'></i>
                                                Cancel
                                            </button>
                                            <button type="submit" class="admin-modal-btn primary">
                                                <i class='bx bx-save'></i>
                                                Update Category
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="admin-empty-state-table">
                                    <div class="admin-empty-icon">
                                        <i class='bx bx-category'></i>
                                    </div>
                                    <h6>No Categories Found</h6>
                                    <p>Create your first business category to get started</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($categories->hasPages())
            <div class="admin-table-pagination">
                {{ $categories->links('vendor.pagination.admin') }}
            </div>
        @endif
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
            align-items: center;
            gap: 2rem;
            flex-wrap: wrap;
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

        .admin-header-stats {
            display: flex;
            gap: 1rem;
        }

        .admin-mini-stat {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.875rem 1.5rem;
            background: linear-gradient(135deg, #1a7838, #27a345);
            color: white;
            border-radius: 12px;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(26, 120, 56, 0.2);
        }

        .admin-mini-stat i {
            font-size: 1.5rem;
        }

        /* Form Card */
        .admin-form-card {
            background: white;
            border-radius: 14px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #f1f5f9;
            overflow: hidden;
        }

        .admin-form-card-header {
            padding: 1.5rem;
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .admin-form-card-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            background: linear-gradient(135deg, #1a7838, #27a345);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .admin-form-card-title {
            font-size: 1.125rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }

        .admin-form-card-subtitle {
            font-size: 0.8125rem;
            color: #64748b;
            margin: 0.25rem 0 0 0;
        }

        .admin-form-card-body {
            padding: 1.5rem;
        }

        /* Form Elements */
        .admin-form-group {
            margin-bottom: 1.25rem;
        }

        .admin-form-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 0.625rem;
        }

        .admin-form-label i {
            font-size: 1rem;
            color: #64748b;
        }

        .admin-required {
            color: #dc2626;
            font-weight: 700;
        }

        .admin-form-input,
        .admin-form-textarea {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 0.9375rem;
            transition: all 0.25s ease;
        }

        .admin-form-input:focus,
        .admin-form-textarea:focus {
            outline: none;
            border-color: #1a7838;
            box-shadow: 0 0 0 4px rgba(26, 120, 56, 0.1);
        }

        .admin-form-textarea {
            resize: vertical;
            line-height: 1.6;
        }

        .admin-form-hint {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 0.5rem;
            font-size: 0.8125rem;
            color: #64748b;
        }

        .admin-form-hint i {
            font-size: 1rem;
        }

        .admin-link {
            color: #1a7838;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s ease;
        }

        .admin-link:hover {
            color: #27a345;
            text-decoration: underline;
        }

        .admin-form-error {
            margin-top: 0.5rem;
            font-size: 0.8125rem;
            color: #dc2626;
            display: flex;
            align-items: center;
            gap: 0.375rem;
        }

        .admin-submit-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.875rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.9375rem;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
        }

        .admin-submit-btn.primary {
            background: linear-gradient(135deg, #1a7838, #27a345);
            color: white;
            box-shadow: 0 4px 12px rgba(26, 120, 56, 0.25);
        }

        .admin-submit-btn.primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(26, 120, 56, 0.35);
        }

        /* Stat Grid Card */
        .admin-stat-grid-card {
            background: white;
            border-radius: 14px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #f1f5f9;
            overflow: hidden;
            height: 100%;
        }

        .admin-stat-grid-header {
            padding: 1.5rem;
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .admin-stat-grid-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            background: linear-gradient(135deg, #00A8E8, #0284c7);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .admin-stat-grid-title {
            font-size: 1.125rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }

        .admin-stat-grid-subtitle {
            font-size: 0.8125rem;
            color: #64748b;
            margin: 0.25rem 0 0 0;
        }

        .admin-stat-grid-body {
            padding: 1.25rem;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        .admin-stat-modern-item {
            padding: 1.25rem;
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: all 0.2s ease;
        }

        .admin-stat-modern-item.primary {
            background: linear-gradient(135deg, #e0f2e9, #d1fae5);
        }

        .admin-stat-modern-item.success {
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        }

        .admin-stat-modern-item.info {
            background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        }

        .admin-stat-modern-item.warning {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
        }

        .admin-stat-modern-item:hover {
            transform: translateY(-2px);
        }

        .admin-stat-modern-icon {
            width: 48px;
            height: 48px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            flex-shrink: 0;
        }

        .admin-stat-modern-item.primary .admin-stat-modern-icon {
            background: linear-gradient(135deg, #1a7838, #27a345);
        }

        .admin-stat-modern-item.success .admin-stat-modern-icon {
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .admin-stat-modern-item.info .admin-stat-modern-icon {
            background: linear-gradient(135deg, #00A8E8, #0284c7);
        }

        .admin-stat-modern-item.warning .admin-stat-modern-icon {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }

        .admin-stat-modern-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .admin-stat-modern-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .admin-stat-modern-value {
            font-size: 1.75rem;
            font-weight: 800;
            color: #1e293b;
        }

        /* Table Card */
        .admin-table-card {
            background: white;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #f1f5f9;
        }

        .admin-table-card-header {
            padding: 1.75rem;
            border-bottom: 1px solid #f1f5f9;
        }

        .admin-table-card-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0 0 0.25rem 0;
        }

        .admin-table-card-subtitle {
            font-size: 0.875rem;
            color: #64748b;
            margin: 0;
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

        .admin-table-wrapper {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .admin-table-modern {
            width: 100%;
            min-width: 800px;
            border-collapse: separate;
            border-spacing: 0;
        }

        .admin-table-modern thead tr {
            background: #f8fafc;
        }

        .admin-table-modern th {
            padding: 1.125rem 1.25rem;
            text-align: left;
            font-size: 0.8125rem;
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 2px solid #e2e8f0;
            white-space: nowrap;
        }

        .admin-table-row {
            transition: all 0.2s ease;
        }

        .admin-table-row:hover {
            background: #f8fafc;
        }

        .admin-table-row td {
            padding: 1.25rem;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        /* Category Icon Display */
        .admin-category-icon-display {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            background: linear-gradient(135deg, #1a7838, #27a345);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
        }

        /* Category Name Cell */
        .admin-category-name-cell {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .admin-category-name {
            font-size: 0.9375rem;
            font-weight: 700;
            color: #1e293b;
        }

        .admin-category-slug {
            font-size: 0.8125rem;
            color: #94a3b8;
        }

        .admin-category-description {
            font-size: 0.875rem;
            color: #64748b;
            line-height: 1.5;
        }

        /* Business Count Badge */
        .admin-business-count-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: linear-gradient(135deg, #dbeafe, #bfdbfe);
            color: #0284c7;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.875rem;
        }

        .admin-business-count-badge i {
            font-size: 1rem;
        }

        /* Status Pill */
        .admin-status-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.875rem;
        }

        .admin-status-pill.active {
            background: #d1fae5;
            color: #059669;
        }

        .admin-status-pill.inactive {
            background: #f1f5f9;
            color: #64748b;
        }

        .admin-status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        .admin-status-pill.active .admin-status-dot {
            background: #059669;
        }

        .admin-status-pill.inactive .admin-status-dot {
            background: #94a3b8;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }

        /* Action Buttons */
        .admin-action-buttons {
            display: flex;
            justify-content: flex-end;
            gap: 0.5rem;
        }

        .admin-action-btn {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.125rem;
            cursor: pointer;
            transition: all 0.25s ease;
            flex-shrink: 0;
        }

        .admin-action-btn.edit {
            background: #dbeafe;
            color: #0284c7;
        }

        .admin-action-btn.edit:hover {
            background: #0284c7;
            color: white;
            transform: translateY(-2px);
        }

        .admin-action-btn.delete {
            background: #fee2e2;
            color: #dc2626;
        }

        .admin-action-btn.delete:hover {
            background: #dc2626;
            color: white;
            transform: translateY(-2px);
        }

        /* Modal Styling */
        .admin-modal {
            border: none;
            border-radius: 16px;
            overflow: hidden;
        }

        .admin-modal-header {
            padding: 1.5rem;
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border-bottom: 1px solid #e2e8f0;
        }

        .admin-modal-title-wrapper {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .admin-modal-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            background: linear-gradient(135deg, #00A8E8, #0284c7);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
        }

        .admin-modal-subtitle {
            font-size: 0.8125rem;
            color: #64748b;
            margin: 0.25rem 0 0 0;
        }

        .admin-modal-body {
            padding: 1.5rem;
        }

        .admin-form-switch {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem;
            background: #f8fafc;
            border-radius: 10px;
        }

        .admin-form-switch .form-check-input {
            width: 48px;
            height: 24px;
            cursor: pointer;
        }

        .admin-form-switch .form-check-label {
            font-weight: 600;
            color: #1e293b;
            cursor: pointer;
            margin: 0;
        }

        .admin-modal-footer {
            padding: 1.25rem 1.5rem;
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            display: flex;
            gap: 0.75rem;
            justify-content: flex-end;
        }

        .admin-modal-btn {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.25rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.9375rem;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .admin-modal-btn.secondary {
            background: #e2e8f0;
            color: #475569;
        }

        .admin-modal-btn.secondary:hover {
            background: #cbd5e1;
        }

        .admin-modal-btn.primary {
            background: linear-gradient(135deg, #1a7838, #27a345);
            color: white;
            box-shadow: 0 4px 12px rgba(26, 120, 56, 0.25);
        }

        .admin-modal-btn.primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(26, 120, 56, 0.35);
        }

        /* Empty State */
        .admin-empty-state-table {
            text-align: center;
            padding: 4rem 2rem;
        }

        .admin-empty-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 1.5rem;
            border-radius: 50%;
            background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            color: #94a3b8;
        }

        .admin-empty-state-table h6 {
            font-size: 1.125rem;
            font-weight: 700;
            color: #475569;
            margin-bottom: 0.5rem;
        }

        .admin-empty-state-table p {
            color: #94a3b8;
        }

        /* Pagination */
        .admin-table-pagination {
            padding: 1.5rem;
            border-top: 1px solid #f1f5f9;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .admin-stat-grid-body {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .admin-page-header-content {
                flex-direction: column;
                align-items: stretch;
            }

            .admin-stat-grid-body {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        function deleteCategory(id) {
            if (confirm(
                    'Are you sure you want to delete this category?\n\nThis action cannot be undone and may affect associated businesses.'
                )) {
                document.getElementById('delete-form-' + id).submit();
            }
        }
    </script>
@endpush
