@extends('layouts.admin')

@section('title', 'Activity Log Details')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Activity Log Details</h2>
            <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-secondary">
                <i class='bx bx-arrow-back'></i> Back to Logs
            </a>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Log Information</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <th width="30%">ID:</th>
                                <td>{{ $activityLog->id }}</td>
                            </tr>
                            <tr>
                                <th>Date & Time:</th>
                                <td>{{ $activityLog->created_at->format('F d, Y h:i:s A') }}</td>
                            </tr>
                            <tr>
                                <th>User:</th>
                                <td>
                                    @if ($activityLog->user)
                                        <a href="{{ route('admin.users.show', $activityLog->user) }}">
                                            {{ $activityLog->user->name }} ({{ $activityLog->user->email }})
                                        </a>
                                    @else
                                        <span class="text-muted">System</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>User Type:</th>
                                <td>
                                    @if ($activityLog->user_type)
                                        <span
                                            class="badge bg-{{ $activityLog->user_type === 'administrator' ? 'danger' : ($activityLog->user_type === 'business_owner' ? 'info' : 'primary') }}">
                                            {{ ucfirst(str_replace('_', ' ', $activityLog->user_type)) }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Action:</th>
                                <td>
                                    <span
                                        class="badge bg-{{ $activityLog->action === 'create' ? 'success' : ($activityLog->action === 'delete' ? 'danger' : 'warning') }}">
                                        {{ ucfirst($activityLog->action) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Model Type:</th>
                                <td>{{ $activityLog->model_type ? class_basename($activityLog->model_type) : 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Model ID:</th>
                                <td>{{ $activityLog->model_id ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Description:</th>
                                <td>{{ $activityLog->description }}</td>
                            </tr>
                            <tr>
                                <th>IP Address:</th>
                                <td>{{ $activityLog->ip_address ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>User Agent:</th>
                                <td><small>{{ $activityLog->user_agent ?? 'N/A' }}</small></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Actions</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.activity-logs.destroy', $activityLog) }}" method="POST"
                            onsubmit="return confirm('Are you sure you want to delete this log?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class='bx bx-trash'></i> Delete Log
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @if ($activityLog->old_values || $activityLog->new_values)
            <div class="row mt-4">
                @if ($activityLog->old_values && $activityLog->action === 'update')
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-danger text-white">
                                <h5 class="mb-0"><i class='bx bx-minus-circle'></i> Old Values</h5>
                            </div>
                            <div class="card-body">
                                <pre class="mb-0">{{ json_encode($activityLog->old_values, JSON_PRETTY_PRINT) }}</pre>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($activityLog->new_values)
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0"><i class='bx bx-plus-circle'></i> New Values</h5>
                            </div>
                            <div class="card-body">
                                <pre class="mb-0">{{ json_encode($activityLog->new_values, JSON_PRETTY_PRINT) }}</pre>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($activityLog->old_values && $activityLog->action === 'delete')
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header bg-danger text-white">
                                <h5 class="mb-0"><i class='bx bx-trash'></i> Deleted Data</h5>
                            </div>
                            <div class="card-body">
                                <pre class="mb-0">{{ json_encode($activityLog->old_values, JSON_PRETTY_PRINT) }}</pre>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endif
    </div>
@endsection
