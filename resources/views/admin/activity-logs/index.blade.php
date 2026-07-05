@extends('layouts.admin')

@section('title', 'Activity Logs')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Activity Logs</h2>
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#clearLogsModal">
                <i class='bx bx-trash'></i> Clear Old Logs
            </button>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Filters -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.activity-logs.index') }}">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Search</label>
                            <input type="text" name="search" class="form-control" placeholder="Search logs..."
                                value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">User Type</label>
                            <select name="user_type" class="form-select">
                                <option value="all">All Types</option>
                                @foreach ($userTypes as $type)
                                    <option value="{{ $type }}"
                                        {{ request('user_type') == $type ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('_', ' ', $type)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Action</label>
                            <select name="action" class="form-select">
                                <option value="all">All Actions</option>
                                @foreach ($actions as $action)
                                    <option value="{{ $action }}"
                                        {{ request('action') == $action ? 'selected' : '' }}>
                                        {{ ucfirst($action) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Model</label>
                            {{--
                                $modelTypes is a key→value map: full class name → basename label.
                                The option *value* is the full class name so the controller can do
                                an exact WHERE match; the displayed text is the human-readable basename.
                            --}}
                            <select name="model_type" class="form-select">
                                <option value="all">All Models</option>
                                @foreach ($modelTypes as $fullClass => $label)
                                    <option value="{{ $fullClass }}"
                                        {{ request('model_type') == $fullClass ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Date Range</label>
                            <div class="input-group">
                                <input type="date" name="date_from" class="form-control"
                                    value="{{ request('date_from') }}">
                                <span class="input-group-text">to</span>
                                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class='bx bx-filter'></i> Apply Filters
                        </button>
                        <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-secondary">
                            <i class='bx bx-reset'></i> Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Logs Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Date & Time</th>
                                <th>User</th>
                                <th>User Type</th>
                                <th>Action</th>
                                <th>Description</th>
                                <th>Model</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                                <tr>
                                    <td>{{ $log->id }}</td>
                                    <td>
                                        <small>{{ $log->created_at->format('M d, Y') }}</small><br>
                                        <small class="text-muted">{{ $log->created_at->format('h:i A') }}</small>
                                    </td>
                                    <td>
                                        @if ($log->user)
                                            <a href="{{ route('admin.users.show', $log->user) }}">
                                                {{ $log->user->name }}
                                            </a>
                                        @else
                                            <span class="text-muted">System</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($log->user_type)
                                            <span
                                                class="badge bg-{{ $log->user_type === 'administrator' ? 'danger' : ($log->user_type === 'business_owner' ? 'info' : 'primary') }}">
                                                {{ ucfirst(str_replace('_', ' ', $log->user_type)) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <span
                                            class="badge bg-{{ $log->action === 'create' ? 'success' : ($log->action === 'delete' ? 'danger' : 'warning') }}">
                                            {{ ucfirst($log->action) }}
                                        </span>
                                    </td>
                                    <td>{{ Str::limit($log->description, 60) }}</td>
                                    <td>
                                        @if ($log->model_type)
                                            <small class="text-muted">{{ class_basename($log->model_type) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.activity-logs.show', $log) }}"
                                            class="btn btn-sm btn-info">
                                            <i class='bx bx-show'></i>
                                        </a>
                                        <form action="{{ route('admin.activity-logs.destroy', $log) }}" method="POST"
                                            class="d-inline" onsubmit="return confirm('Delete this log?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class='bx bx-trash'></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <i class='bx bx-info-circle' style="font-size: 3rem; opacity: 0.3;"></i>
                                        <p class="text-muted mt-2">No activity logs found.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $logs->links('vendor.pagination.admin') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Clear Logs Modal -->
    <div class="modal fade" id="clearLogsModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.activity-logs.clear') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Clear Old Logs</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Delete logs older than:</label>
                            <select name="days" class="form-select" required>
                                <option value="30">30 days</option>
                                <option value="60">60 days</option>
                                <option value="90" selected>90 days</option>
                                <option value="180">6 months</option>
                                <option value="365">1 year</option>
                            </select>
                        </div>
                        <div class="alert alert-warning">
                            <i class='bx bx-warning'></i> This action cannot be undone.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Clear Logs</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
