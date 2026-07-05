@extends('layouts.admin')

@section('page-title', 'Manage Users')

@section('content')
    <!-- Enhanced Page Header -->
    <div class="admin-page-header mb-4">
        <div class="admin-page-header-content">
            <div>
                <h2 class="admin-page-title">Manage Users</h2>
                <p class="admin-page-subtitle">View and manage all registered users</p>
            </div>
            <div class="admin-header-actions">
                <a href="{{ route('admin.users.create') }}" class="admin-header-action-btn primary">
                    <i class='bx bx-plus'></i>
                    <span>Add New User</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Enhanced Filters -->
    <div class="admin-filter-card mb-4">
        <form method="GET" action="{{ route('admin.users.index') }}">
            <div class="admin-filter-grid-extended">
                <div class="admin-filter-item">
                    <label class="admin-filter-label">
                        <i class='bx bx-shield'></i> Role
                    </label>
                    <select name="role" class="admin-filter-select">
                        <option value="">All Roles</option>
                        <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>User</option>
                        <option value="business_owner" {{ request('role') === 'business_owner' ? 'selected' : '' }}>Business
                            Owner</option>
                        <option value="administrator" {{ request('role') === 'administrator' ? 'selected' : '' }}>
                            Administrator</option>
                    </select>
                </div>

                <div class="admin-filter-item">
                    <label class="admin-filter-label">
                        <i class='bx bx-check-circle'></i> Status
                    </label>
                    <select name="status" class="admin-filter-select">
                        <option value="">All Statuses</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div class="admin-filter-item">
                    <label class="admin-filter-label">
                        <i class='bx bx-search'></i> Search
                    </label>
                    <input type="text" name="search" class="admin-filter-input" placeholder="Search by name or email..."
                        value="{{ request('search') }}">
                </div>

                <div class="admin-filter-actions">
                    <button type="submit" class="admin-filter-btn primary">
                        <i class='bx bx-search'></i>
                        <span>Apply Filters</span>
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="admin-filter-btn secondary">
                        <i class='bx bx-reset'></i>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Enhanced Stats -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="admin-stat-card-modern primary">
                <div class="admin-stat-card-icon-modern">
                    <i class='bx bxs-user'></i>
                </div>
                <div class="admin-stat-card-content-modern">
                    <p>Total Users</p>
                    <h3>{{ $users->total() }}</h3>
                </div>
                <div class="admin-stat-card-decoration"></div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="admin-stat-card-modern success">
                <div class="admin-stat-card-icon-modern">
                    <i class='bx bxs-user-check'></i>
                </div>
                <div class="admin-stat-card-content-modern">
                    <p>Active Users</p>
                    <h3>{{ \App\Models\User::where('is_active', true)->count() }}</h3>
                </div>
                <div class="admin-stat-card-decoration"></div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="admin-stat-card-modern info">
                <div class="admin-stat-card-icon-modern">
                    <i class='bx bxs-store'></i>
                </div>
                <div class="admin-stat-card-content-modern">
                    <p>Business Owners</p>
                    <h3>{{ \App\Models\User::where('role', 'business_owner')->count() }}</h3>
                </div>
                <div class="admin-stat-card-decoration"></div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="admin-stat-card-modern warning">
                <div class="admin-stat-card-icon-modern">
                    <i class='bx bxs-shield'></i>
                </div>
                <div class="admin-stat-card-content-modern">
                    <p>Administrators</p>
                    <h3>{{ \App\Models\User::where('role', 'administrator')->count() }}</h3>
                </div>
                <div class="admin-stat-card-decoration"></div>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="admin-table-card">
        <div class="admin-table-card-header">
            <div class="admin-table-card-icon">
                <i class='bx bx-user'></i>
            </div>
            <div>
                <h5 class="admin-table-card-title">All Users</h5>
                <p class="admin-table-card-subtitle">{{ $users->total() }} total users</p>
            </div>
        </div>
        <div class="admin-table-wrapper">
            <table class="admin-modern-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Joined</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>
                                <div class="admin-user-info">
                                    <div class="admin-user-avatar">
                                        @if ($user->profile && $user->profile->profile_picture)
                                            <img src="{{ asset('storage/' . $user->profile->profile_picture) }}"
                                                alt="{{ $user->name }}" class="admin-user-avatar-img">
                                        @else
                                            <div class="admin-user-avatar-placeholder">
                                                {{ substr($user->name, 0, 1) }}
                                            </div>
                                        @endif
                                    </div>
                                    <span class="admin-user-name">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="admin-user-email">{{ $user->email }}</span>
                            </td>
                            <td>
                                @if ($user->role === 'administrator')
                                    <span class="admin-role-badge admin">
                                        <i class='bx bx-shield'></i>
                                        Administrator
                                    </span>
                                @elseif($user->role === 'business_owner')
                                    <span class="admin-role-badge business">
                                        <i class='bx bx-store'></i>
                                        Business Owner
                                    </span>
                                @else
                                    <span class="admin-role-badge user">
                                        <i class='bx bx-user'></i>
                                        User
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span class="admin-status-badge {{ $user->is_active ? 'active' : 'inactive' }}">
                                    <i class='bx {{ $user->is_active ? 'bx-check-circle' : 'bx-x-circle' }}'></i>
                                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <span class="admin-date-text">{{ $user->created_at->format('M d, Y') }}</span>
                            </td>
                            <td>
                                <div class="admin-table-actions">
                                    <a href="{{ route('admin.users.show', $user) }}" class="admin-table-action-btn view"
                                        title="View Details">
                                        <i class='bx bx-show'></i>
                                    </a>
                                    <a href="{{ route('admin.users.edit', $user) }}" class="admin-table-action-btn edit"
                                        title="Edit User">
                                        <i class='bx bx-edit'></i>
                                    </a>
                                    @if ($user->id !== auth()->id())
                                        <button type="button" class="admin-table-action-btn delete"
                                            onclick="deleteUser({{ $user->id }})" title="Delete User">
                                            <i class='bx bx-trash'></i>
                                        </button>
                                        <form id="delete-form-{{ $user->id }}"
                                            action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                            class="d-none">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="admin-empty-table">
                                    <i class='bx bx-user'></i>
                                    <p>No users found</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($users->hasPages())
            <div class="admin-table-card-footer">
                {{ $users->links('vendor.pagination.admin') }}
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

        .admin-header-actions {
            display: flex;
            gap: 0.75rem;
        }

        .admin-header-action-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.25rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.9375rem;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .admin-header-action-btn.primary {
            background: linear-gradient(135deg, #1a7838, #27a345);
            color: white;
            box-shadow: 0 4px 12px rgba(26, 120, 56, 0.25);
        }

        .admin-header-action-btn.primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(26, 120, 56, 0.35);
            color: white;
        }

        /* Filter Card */
        .admin-filter-card {
            background: white;
            border-radius: 14px;
            padding: 1.75rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #f1f5f9;
        }

        .admin-filter-grid-extended {
            display: grid;
            grid-template-columns: repeat(3, 1fr) auto;
            gap: 1.25rem;
            align-items: end;
        }

        .admin-filter-item {
            display: flex;
            flex-direction: column;
        }

        .admin-filter-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 0.625rem;
        }

        .admin-filter-input,
        .admin-filter-select {
            padding: 0.75rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 0.9375rem;
            transition: all 0.25s ease;
        }

        .admin-filter-input:focus,
        .admin-filter-select:focus {
            outline: none;
            border-color: #1a7838;
            box-shadow: 0 0 0 4px rgba(26, 120, 56, 0.1);
        }

        .admin-filter-actions {
            display: flex;
            gap: 0.5rem;
        }

        .admin-filter-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem 1.25rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.9375rem;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .admin-filter-btn.primary {
            background: linear-gradient(135deg, #1a7838, #27a345);
            color: white;
            box-shadow: 0 4px 12px rgba(26, 120, 56, 0.25);
        }

        .admin-filter-btn.primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(26, 120, 56, 0.35);
        }

        .admin-filter-btn.secondary {
            background: #f1f5f9;
            color: #64748b;
            padding: 0.75rem 1rem;
        }

        .admin-filter-btn.secondary:hover {
            background: #e2e8f0;
        }

        /* Modern Stat Cards */
        .admin-stat-card-modern {
            position: relative;
            padding: 1.75rem;
            border-radius: 16px;
            overflow: visible;
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
            border-color: rgba(2, 132, 199, 0.2);
        }

        .admin-stat-card-modern.warning {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            border-color: rgba(245, 158, 11, 0.2);
        }

        .admin-stat-card-modern:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
        }

        .admin-stat-card-icon-modern {
            min-width: 70px;
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
            position: relative;
            z-index: 2;
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

        .admin-stat-card-modern.warning .admin-stat-card-icon-modern {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }

        .admin-stat-card-content-modern {
            flex: 1;
            z-index: 2;
            position: relative;
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
            font-size: 0.875rem;
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

        /* User Info */
        .admin-user-info {
            display: flex;
            align-items: center;
            gap: 0.875rem;
        }

        .admin-user-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            overflow: hidden;
            flex-shrink: 0;
            border: 2px solid #e2e8f0;
        }

        .admin-user-avatar-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .admin-user-avatar-placeholder {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1a7838, #27a345);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.125rem;
        }

        .admin-user-name {
            font-weight: 600;
            color: #1e293b;
        }

        .admin-user-email {
            color: #64748b;
            font-size: 0.9375rem;
        }

        /* Role Badge */
        .admin-role-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.5rem 0.875rem;
            border-radius: 6px;
            font-size: 0.8125rem;
            font-weight: 600;
        }

        .admin-role-badge.admin {
            background: #fee2e2;
            color: #dc2626;
        }

        .admin-role-badge.business {
            background: #dbeafe;
            color: #0284c7;
        }

        .admin-role-badge.user {
            background: #f1f5f9;
            color: #64748b;
        }

        /* Status Badge */
        .admin-status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.5rem 0.875rem;
            border-radius: 6px;
            font-size: 0.8125rem;
            font-weight: 600;
        }

        .admin-status-badge.active {
            background: #d1fae5;
            color: #059669;
        }

        .admin-status-badge.inactive {
            background: #f1f5f9;
            color: #64748b;
        }

        .admin-date-text {
            color: #64748b;
            font-size: 0.9375rem;
        }

        /* Table Actions */
        .admin-table-actions {
            display: flex;
            gap: 0.5rem;
            justify-content: flex-end;
        }

        .admin-table-action-btn {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.125rem;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .admin-table-action-btn.view {
            background: #dbeafe;
            color: #0284c7;
        }

        .admin-table-action-btn.view:hover {
            background: #0284c7;
            color: white;
            transform: translateY(-2px);
        }

        .admin-table-action-btn.edit {
            background: #fef3c7;
            color: #d97706;
        }

        .admin-table-action-btn.edit:hover {
            background: #d97706;
            color: white;
            transform: translateY(-2px);
        }

        .admin-table-action-btn.delete {
            background: #fee2e2;
            color: #dc2626;
        }

        .admin-table-action-btn.delete:hover {
            background: #dc2626;
            color: white;
            transform: translateY(-2px);
        }

        /* Empty Table */
        .admin-empty-table {
            text-align: center;
            padding: 5rem 2rem;
            color: #94a3b8;
        }

        .admin-empty-table i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.3;
        }

        .admin-empty-table p {
            margin: 0;
            font-size: 1rem;
        }

        .admin-table-card-footer {
            padding: 1.5rem;
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .admin-filter-grid-extended {
                grid-template-columns: 1fr;
            }

            .admin-filter-actions {
                justify-content: stretch;
            }

            .admin-filter-btn.primary {
                flex: 1;
            }
        }

        @media (max-width: 768px) {
            .admin-page-header-content {
                flex-direction: column;
                align-items: stretch;
            }

            .admin-header-actions {
                width: 100%;
            }

            .admin-header-action-btn {
                flex: 1;
                justify-content: center;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        function deleteUser(id) {
            if (confirm('Are you sure you want to delete this user?\n\nThis action cannot be undone.')) {
                document.getElementById('delete-form-' + id).submit();
            }
        }
    </script>
@endpush
