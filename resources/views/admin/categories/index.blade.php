@extends('layouts.admin')

@section('page-title', 'Manage Categories')

@section('content')
    <!-- Enhanced Page Header -->
    <div class="admin-page-header mb-4">
        <div class="admin-page-header-content">
            <div>
                <h2 class="admin-page-title">Categories Management</h2>
                <p class="admin-page-subtitle">Organize attractions by categories</p>
            </div>
            <div class="admin-header-stats">
                <div class="admin-mini-stat">
                    <i class='bx bx-category'></i>
                    <span>{{ $categories->total() }} Categories</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <!-- Add New Category Form -->
        <div class="col-lg-5">
            <div class="admin-form-card">
                <div class="admin-form-card-header">
                    <div class="admin-form-card-icon primary">
                        <i class='bx bx-plus-circle'></i>
                    </div>
                    <div>
                        <h5 class="admin-form-card-title">Add New Category</h5>
                        <p class="admin-form-card-subtitle">Create a new attraction category</p>
                    </div>
                </div>
                <div class="admin-form-card-body">
                    <form action="{{ route('admin.categories.store') }}" method="POST">
                        @csrf
                        <div class="admin-form-group">
                            <label for="name" class="admin-form-label">
                                <i class='bx bx-rename'></i> Category Name
                                <span class="admin-required">*</span>
                            </label>
                            <input type="text" class="admin-form-input @error('name') is-invalid @enderror"
                                id="name" name="name" value="{{ old('name') }}" required
                                placeholder="Enter category name">
                            @error('name')
                                <div class="admin-form-error">
                                    <i class='bx bx-error-circle'></i> {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="admin-form-group">
                            <label for="description" class="admin-form-label">
                                <i class='bx bx-detail'></i> Description
                            </label>
                            <textarea class="admin-form-textarea @error('description') is-invalid @enderror" id="description" name="description"
                                rows="3" placeholder="Brief description...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="admin-form-error">
                                    <i class='bx bx-error-circle'></i> {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="admin-form-group">
                            <label for="icon" class="admin-form-label">
                                <i class='bx bx-palette'></i> Icon (Boxicons)
                            </label>
                            <input type="text" class="admin-form-input @error('icon') is-invalid @enderror"
                                id="icon" name="icon" value="{{ old('icon', 'bx-category') }}"
                                placeholder="e.g., bx-map-pin">
                            <div class="admin-form-help">
                                <i class='bx bx-info-circle'></i> Browse icons at <a href="https://boxicons.com"
                                    target="_blank">boxicons.com</a>
                            </div>
                            @error('icon')
                                <div class="admin-form-error">
                                    <i class='bx bx-error-circle'></i> {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <button type="submit" class="admin-form-btn primary w-100">
                            <i class='bx bx-plus-circle'></i>
                            <span>Add Category</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="col-lg-7">
            <div class="admin-form-card">
                <div class="admin-form-card-header">
                    <div class="admin-form-card-icon info">
                        <i class='bx bx-bar-chart'></i>
                    </div>
                    <div>
                        <h5 class="admin-form-card-title">Category Statistics</h5>
                        <p class="admin-form-card-subtitle">Overview of category data</p>
                    </div>
                </div>
                <div class="admin-form-card-body">
                    <div class="admin-stats-grid-simple">
                        <div class="admin-stat-card-simple primary">
                            <div class="admin-stat-card-icon">
                                <i class='bx bx-category'></i>
                            </div>
                            <div class="admin-stat-card-content">
                                <h3>{{ $categories->total() }}</h3>
                                <p>Total Categories</p>
                            </div>
                        </div>

                        <div class="admin-stat-card-simple success">
                            <div class="admin-stat-card-icon">
                                <i class='bx bx-check-circle'></i>
                            </div>
                            <div class="admin-stat-card-content">
                                <h3>{{ \App\Models\Category::where('is_active', true)->count() }}</h3>
                                <p>Active Categories</p>
                            </div>
                        </div>

                        <div class="admin-stat-card-simple info">
                            <div class="admin-stat-card-icon">
                                <i class='bx bx-map-pin'></i>
                            </div>
                            <div class="admin-stat-card-content">
                                <h3>{{ \App\Models\Attraction::count() }}</h3>
                                <p>Total Attractions</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Categories List -->
    <div class="admin-table-card">
        <div class="admin-table-card-header">
            <div>
                <h5 class="admin-table-card-title">All Categories</h5>
                <p class="admin-table-card-subtitle">Manage and organize your categories</p>
            </div>
        </div>

        <div class="admin-table-wrapper">
            <table class="admin-table-enhanced">
                <thead>
                    <tr>
                        <th style="width: 80px;">Icon</th>
                        <th>Category Details</th>
                        <th style="width: 150px;">Attractions</th>
                        <th style="width: 120px;">Status</th>
                        <th style="width: 150px;" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr class="admin-table-row" id="category-{{ $category->id }}">
                            <td>
                                <div class="admin-category-icon-enhanced">
                                    <i class='bx {{ $category->icon ?? 'bx-category' }}'></i>
                                </div>
                            </td>
                            <td>
                                <div class="admin-category-details">
                                    <h6 class="admin-category-name">{{ $category->name }}</h6>
                                    <p class="admin-category-slug">
                                        <i class='bx bx-link'></i> {{ $category->slug }}
                                    </p>
                                    @if ($category->description)
                                        <p class="admin-category-desc">{{ Str::limit($category->description, 60) }}</p>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="admin-count-badge-enhanced">
                                    <i class='bx bx-map-pin'></i>
                                    <span>{{ $category->attractions_count }}</span>
                                </div>
                            </td>
                            <td>
                                <div
                                    class="admin-status-badge-enhanced {{ $category->is_active ? 'active' : 'inactive' }}">
                                    <i class='bx {{ $category->is_active ? 'bx-check-circle' : 'bx-x-circle' }}'></i>
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
                                    action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                                    class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>

                        <!-- Enhanced Edit Modal -->
                        <div class="modal fade" id="editModal{{ $category->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content admin-modal-enhanced">
                                    <div class="admin-modal-header">
                                        <div class="admin-modal-icon">
                                            <i class='bx bx-edit'></i>
                                        </div>
                                        <div>
                                            <h5 class="admin-modal-title">Edit Category</h5>
                                            <p class="admin-modal-subtitle">Update category information</p>
                                        </div>
                                        <button type="button" class="admin-modal-close" data-bs-dismiss="modal">
                                            <i class='bx bx-x'></i>
                                        </button>
                                    </div>
                                    <form action="{{ route('admin.categories.update', $category) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="admin-modal-body">
                                            <div class="admin-form-group">
                                                <label class="admin-form-label">
                                                    <i class='bx bx-rename'></i> Category Name
                                                    <span class="admin-required">*</span>
                                                </label>
                                                <input type="text" class="admin-form-input" name="name"
                                                    value="{{ $category->name }}" required
                                                    placeholder="Enter category name">
                                            </div>

                                            <div class="admin-form-group">
                                                <label class="admin-form-label">
                                                    <i class='bx bx-detail'></i> Description
                                                </label>
                                                <textarea class="admin-form-textarea" name="description" rows="3" placeholder="Brief description...">{{ $category->description }}</textarea>
                                            </div>

                                            <div class="admin-form-group">
                                                <label class="admin-form-label">
                                                    <i class='bx bx-palette'></i> Icon (Boxicons)
                                                </label>
                                                <input type="text" class="admin-form-input" name="icon"
                                                    value="{{ $category->icon }}" placeholder="e.g., bx-map-pin">
                                            </div>

                                            <div class="admin-toggle-item">
                                                <div class="admin-toggle-info">
                                                    <i class='bx bx-check-circle'></i>
                                                    <div>
                                                        <strong>Active Status</strong>
                                                        <p>Make category visible</p>
                                                    </div>
                                                </div>
                                                <label class="admin-toggle-switch">
                                                    <input type="checkbox" name="is_active" value="1"
                                                        {{ $category->is_active ? 'checked' : '' }}>
                                                    <span class="admin-toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="admin-modal-footer">
                                            <button type="button" class="admin-form-btn secondary"
                                                data-bs-dismiss="modal">
                                                <i class='bx bx-x'></i>
                                                <span>Cancel</span>
                                            </button>
                                            <button type="submit" class="admin-form-btn primary">
                                                <i class='bx bx-save'></i>
                                                <span>Update Category</span>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="admin-empty-state-table">
                                    <div class="admin-empty-icon">
                                        <i class='bx bx-category'></i>
                                    </div>
                                    <h6>No Categories Found</h6>
                                    <p>Get started by adding your first category</p>
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

        /* Form Cards - Reuse from previous pages */
        .admin-form-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #f1f5f9;
            overflow: hidden;
        }

        .admin-form-card-header {
            padding: 1.75rem;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            gap: 1.25rem;
        }

        .admin-form-card-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            flex-shrink: 0;
        }

        .admin-form-card-icon.primary {
            background: linear-gradient(135deg, #1a7838, #27a345);
        }

        .admin-form-card-icon.info {
            background: linear-gradient(135deg, #00A8E8, #0284c7);
        }

        .admin-form-card-title {
            font-size: 1.125rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0 0 0.25rem 0;
        }

        .admin-form-card-subtitle {
            font-size: 0.875rem;
            color: #64748b;
            margin: 0;
        }

        .admin-form-card-body {
            padding: 1.75rem;
        }

        /* Form Elements */
        .admin-form-group {
            margin-bottom: 1.5rem;
        }

        .admin-form-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9375rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 0.625rem;
        }

        .admin-form-label i {
            font-size: 1.125rem;
            color: #64748b;
        }

        .admin-required {
            color: #ef4444;
            font-weight: 700;
        }

        .admin-form-input,
        .admin-form-textarea {
            width: 100%;
            padding: 0.875rem 1.125rem;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 0.9375rem;
            transition: all 0.25s ease;
            background: white;
            color: #1e293b;
        }

        .admin-form-input:focus,
        .admin-form-textarea:focus {
            outline: none;
            border-color: #1a7838;
            box-shadow: 0 0 0 4px rgba(26, 120, 56, 0.1);
        }

        .admin-form-textarea {
            resize: vertical;
        }

        .admin-form-help {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.8125rem;
            color: #94a3b8;
            margin-top: 0.5rem;
        }

        .admin-form-help a {
            color: #1a7838;
            font-weight: 600;
        }

        .admin-form-error {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 0.5rem;
            font-weight: 600;
        }

        /* Stats Grid */
        .admin-stats-grid-simple {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1.25rem;
        }

        .admin-stat-card-simple {
            padding: 1.5rem;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 1.25rem;
            transition: transform 0.3s ease;
        }

        .admin-stat-card-simple:hover {
            transform: translateY(-4px);
        }

        .admin-stat-card-simple.primary {
            background: linear-gradient(135deg, #e0f2e9, #d1fae5);
        }

        .admin-stat-card-simple.success {
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        }

        .admin-stat-card-simple.info {
            background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        }

        .admin-stat-card-icon {
            width: 56px;
            height: 56px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            flex-shrink: 0;
        }

        .admin-stat-card-simple.primary .admin-stat-card-icon {
            background: linear-gradient(135deg, #1a7838, #27a345);
            color: white;
        }

        .admin-stat-card-simple.success .admin-stat-card-icon {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }

        .admin-stat-card-simple.info .admin-stat-card-icon {
            background: linear-gradient(135deg, #00A8E8, #0284c7);
            color: white;
        }

        .admin-stat-card-content h3 {
            font-size: 2rem;
            font-weight: 800;
            color: #1e293b;
            margin: 0 0 0.25rem 0;
        }

        .admin-stat-card-content p {
            font-size: 0.875rem;
            font-weight: 600;
            color: #64748b;
            margin: 0;
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

        /* Enhanced Table */
        .admin-table-wrapper {
            overflow-x: auto;
        }

        .admin-table-enhanced {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .admin-table-enhanced thead tr {
            background: #f8fafc;
        }

        .admin-table-enhanced th {
            padding: 1.125rem 1.25rem;
            text-align: left;
            font-size: 0.8125rem;
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 2px solid #e2e8f0;
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

        /* Category Icon */
        .admin-category-icon-enhanced {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            background: linear-gradient(135deg, #e0f2e9, #d1fae5);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            color: #1a7838;
        }

        /* Category Details */
        .admin-category-details {
            display: flex;
            flex-direction: column;
            gap: 0.375rem;
        }

        .admin-category-name {
            font-size: 1rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }

        .admin-category-slug {
            font-size: 0.8125rem;
            color: #94a3b8;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.375rem;
        }

        .admin-category-desc {
            font-size: 0.875rem;
            color: #64748b;
            margin: 0;
        }

        /* Count Badge */
        .admin-count-badge-enhanced {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.625rem 1rem;
            background: #f1f5f9;
            border-radius: 8px;
            font-weight: 600;
            color: #475569;
        }

        .admin-count-badge-enhanced i {
            font-size: 1.125rem;
            color: #64748b;
        }

        /* Status Badge Enhanced */
        .admin-status-badge-enhanced {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 700;
        }

        .admin-status-badge-enhanced.active {
            background: #d1fae5;
            color: #059669;
        }

        .admin-status-badge-enhanced.inactive {
            background: #f1f5f9;
            color: #64748b;
        }

        .admin-status-badge-enhanced i {
            font-size: 1.125rem;
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
        }

        .admin-action-btn.edit {
            background: #fef3c7;
            color: #d97706;
        }

        .admin-action-btn.edit:hover {
            background: #d97706;
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

        /* Enhanced Modal */
        .admin-modal-enhanced {
            border: none;
            border-radius: 16px;
            overflow: hidden;
        }

        .admin-modal-header {
            padding: 1.75rem;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            gap: 1.25rem;
            background: #f8fafc;
        }

        .admin-modal-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            background: linear-gradient(135deg, #1a7838, #27a345);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .admin-modal-title {
            font-size: 1.125rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0 0 0.25rem 0;
        }

        .admin-modal-subtitle {
            font-size: 0.875rem;
            color: #64748b;
            margin: 0;
        }

        .admin-modal-close {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            border: none;
            background: #f1f5f9;
            color: #64748b;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            cursor: pointer;
            margin-left: auto;
            transition: all 0.25s ease;
        }

        .admin-modal-close:hover {
            background: #e2e8f0;
            color: #475569;
        }

        .admin-modal-body {
            padding: 1.75rem;
        }

        .admin-modal-footer {
            padding: 1.5rem 1.75rem;
            border-top: 1px solid #f1f5f9;
            display: flex;
            justify-content: flex-end;
            gap: 0.875rem;
            background: #f8fafc;
        }

        /* Form Buttons */
        .admin-form-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            padding: 0.875rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.9375rem;
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

        .admin-form-btn.secondary {
            background: #f1f5f9;
            color: #475569;
        }

        .admin-form-btn.secondary:hover {
            background: #e2e8f0;
        }

        .admin-form-btn i {
            font-size: 1.125rem;
        }

        /* Toggle Switch */
        .admin-toggle-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.25rem;
            background: #f8fafc;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
        }

        .admin-toggle-info {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            flex: 1;
        }

        .admin-toggle-info>i {
            font-size: 1.75rem;
            color: #1a7838;
            margin-top: 0.25rem;
        }

        .admin-toggle-info strong {
            display: block;
            font-size: 0.9375rem;
            color: #1e293b;
            margin-bottom: 0.25rem;
        }

        .admin-toggle-info p {
            font-size: 0.8125rem;
            color: #64748b;
            margin: 0;
        }

        .admin-toggle-switch {
            position: relative;
            display: inline-block;
            width: 52px;
            height: 28px;
            flex-shrink: 0;
        }

        .admin-toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .admin-toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #cbd5e1;
            border-radius: 28px;
            transition: 0.3s;
        }

        .admin-toggle-slider:before {
            position: absolute;
            content: "";
            height: 22px;
            width: 22px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            border-radius: 50%;
            transition: 0.3s;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .admin-toggle-switch input:checked+.admin-toggle-slider {
            background: linear-gradient(135deg, #1a7838, #27a345);
        }

        .admin-toggle-switch input:checked+.admin-toggle-slider:before {
            transform: translateX(24px);
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
        @media (max-width: 768px) {
            .admin-page-header-content {
                flex-direction: column;
                align-items: stretch;
            }

            .admin-stats-grid-simple {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        function deleteCategory(id) {
            if (confirm(
                    'Are you sure you want to delete this category? This action cannot be undone and will affect all associated attractions.'
                )) {
                document.getElementById('delete-form-' + id).submit();
            }
        }
    </script>
@endpush
