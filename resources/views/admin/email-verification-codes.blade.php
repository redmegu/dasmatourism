@extends('layouts.admin')

@section('page-title', 'Email Verification OTP Codes')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="bx bx-mail-send me-2"></i>
                            Email Verification - OTP Codes
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="bx bx-info-circle me-2"></i>
                            <strong>Development Mode:</strong> Email driver is set to <code>log</code>.
                            All emails are saved to <code>storage/logs/laravel.log</code> instead of being sent.
                        </div>

                        <h5>Recent Verification Codes:</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>User</th>
                                        <th>Email</th>
                                        <th>OTP Code</th>
                                        <th>Expires At</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($tokens as $token)
                                        <tr>
                                            <td>
                                                <strong>{{ $token->user->name }}</strong>
                                            </td>
                                            <td>{{ $token->user->email }}</td>
                                            <td>
                                                <span class="badge bg-primary fs-5 font-monospace">
                                                    {{ $token->otp }}
                                                </span>
                                            </td>
                                            <td>
                                                {{ $token->expires_at->format('M d, Y H:i:s') }}
                                                @if ($token->expires_at->isPast())
                                                    <span class="badge bg-danger ms-2">Expired</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($token->is_used)
                                                    <span class="badge bg-success">Used</span>
                                                @elseif($token->expires_at->isPast())
                                                    <span class="badge bg-danger">Expired</span>
                                                @else
                                                    <span class="badge bg-warning">Active</span>
                                                @endif
                                            </td>
                                            <td>{{ $token->created_at->diffForHumans() }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">
                                                No verification tokens found
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if ($tokens->hasPages())
                            <div class="admin-pagination-wrapper mt-3">
                                {{ $tokens->links('vendor.pagination.admin') }}
                            </div>
                        @endif

                        <hr class="my-4">

                        <div class="row">
                            <div class="col-md-6">
                                <h6>How to Test:</h6>
                                <ol>
                                    <li>Register a new user</li>
                                    <li>Check this page for the OTP code</li>
                                    <li>Use the code on the verification page</li>
                                    <li>User will be verified and can login</li>
                                </ol>
                            </div>
                            <div class="col-md-6">
                                <h6>To Enable Real Gmail Sending:</h6>
                                <ol>
                                    <li>Go to Google Account Settings</li>
                                    <li>Enable 2-Factor Authentication</li>
                                    <li>Generate App Password</li>
                                    <li>Update .env with App Password</li>
                                    <li>Change <code>MAIL_MAILER=smtp</code></li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
