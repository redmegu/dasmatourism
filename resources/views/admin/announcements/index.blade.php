@extends('layouts.admin')
@section('page-title', 'Announcements')

@section('content')
    <!-- Enhanced Page Header -->
    <div class="admin-page-header mb-4"
        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px; padding: 2rem; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.2);">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h3 class="text-white mb-2 fw-bold">
                    <i class='bx bxs-megaphone me-2'></i>Manage Announcements
                </h3>
                <p class="text-white-50 mb-0">Create and manage public announcements for your platform</p>
            </div>
            <a href="{{ route('admin.announcements.create') }}" class="btn btn-light btn-lg shadow-sm"
                style="padding: 0.75rem 1.5rem;">
                <i class="bx bx-plus me-2"></i>Add Announcement
            </a>
        </div>
    </div>

    <div class="admin-card" style="border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.08); border: none;">
        <div class="admin-card-body" style="padding: 1.5rem;">
            @if ($announcements->count())
                <div class="table-responsive">
                    <table class="table align-middle" style="margin-bottom: 0;">
                        <thead style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                            <tr>
                                <th style="padding: 1rem; font-weight: 600; color: #475569; border: none;">Photo</th>
                                <th style="padding: 1rem; font-weight: 600; color: #475569; border: none;">Title</th>
                                <th style="padding: 1rem; font-weight: 600; color: #475569; border: none;">Content Preview
                                </th>
                                <th style="padding: 1rem; font-weight: 600; color: #475569; border: none;">Status</th>
                                <th style="padding: 1rem; font-weight: 600; color: #475569; border: none;">Published At</th>
                                <th
                                    style="padding: 1rem; font-weight: 600; color: #475569; border: none; text-align: right;">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($announcements as $announcement)
                                <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s;">
                                    <td style="padding: 1rem;">
                                        @if ($announcement->image)
                                            <img src="{{ asset('storage/' . $announcement->image) }}" alt="Announcement"
                                                style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                                        @else
                                            <div
                                                style="width: 80px; height: 80px; background: #f1f5f9; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                                <i class='bx bx-image' style="font-size: 2rem; color: #94a3b8;"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td style="padding: 1rem;">
                                        <div style="font-weight: 600; color: #1e293b; margin-bottom: 0.25rem;">
                                            {{ $announcement->title }}
                                        </div>
                                    </td>
                                    <td style="padding: 1rem; max-width: 300px;">
                                        <div style="color: #64748b; font-size: 0.875rem; line-height: 1.5;">
                                            {{ Str::limit($announcement->content, 80) }}
                                        </div>
                                    </td>
                                    <td style="padding: 1rem;">
                                        @if ($announcement->status == 'published')
                                            <span
                                                style="display: inline-flex; align-items: center; padding: 0.375rem 0.75rem; background: #dcfce7; color: #166534; border-radius: 6px; font-size: 0.875rem; font-weight: 600;">
                                                <i class='bx bx-check-circle me-1'></i>Published
                                            </span>
                                        @else
                                            <span
                                                style="display: inline-flex; align-items: center; padding: 0.375rem 0.75rem; background: #f1f5f9; color: #475569; border-radius: 6px; font-size: 0.875rem; font-weight: 600;">
                                                <i class='bx bx-time me-1'></i>Draft
                                            </span>
                                        @endif
                                    </td>
                                    <td style="padding: 1rem; color: #64748b; font-size: 0.875rem;">
                                        @if ($announcement->published_at)
                                            <div style="display: flex; align-items: center;">
                                                <i class='bx bx-calendar me-2'></i>
                                                {{ $announcement->published_at->format('M d, Y') }}
                                            </div>
                                            <div style="color: #94a3b8; font-size: 0.75rem; margin-top: 0.25rem;">
                                                {{ $announcement->published_at->format('h:i A') }}
                                            </div>
                                        @else
                                            <span style="color: #94a3b8;">Not published</span>
                                        @endif
                                    </td>
                                    <td style="padding: 1rem; text-align: right;">
                                        <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                                            <a href="{{ route('admin.announcements.edit', $announcement) }}"
                                                style="display: inline-flex; align-items: center; padding: 0.5rem 1rem; background: #3b82f6; color: white; border-radius: 6px; text-decoration: none; font-size: 0.875rem; transition: background 0.2s;"
                                                onmouseover="this.style.background='#2563eb'"
                                                onmouseout="this.style.background='#3b82f6'">
                                                <i class="bx bx-edit me-1"></i>Edit
                                            </a>
                                            <form action="{{ route('admin.announcements.destroy', $announcement) }}"
                                                method="POST" style="display:inline; margin: 0;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    style="display: inline-flex; align-items: center; padding: 0.5rem 1rem; background: #ef4444; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 0.875rem; transition: background 0.2s;"
                                                    onmouseover="this.style.background='#dc2626'"
                                                    onmouseout="this.style.background='#ef4444'"
                                                    onclick="return confirm('Delete this announcement? This action cannot be undone.')">
                                                    <i class='bx bx-trash me-1'></i>Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div style="text-align: center; padding: 4rem 2rem;">
                    <div
                        style="width: 80px; height: 80px; background: #f1f5f9; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 1.5rem;">
                        <i class='bx bxs-megaphone' style="font-size: 2.5rem; color: #94a3b8;"></i>
                    </div>
                    <h5 style="color: #475569; margin-bottom: 0.5rem;">No Announcements Yet</h5>
                    <p style="color: #94a3b8; margin-bottom: 1.5rem;">Create your first announcement to get started</p>
                    <a href="{{ route('admin.announcements.create') }}" class="btn btn-primary">
                        <i class="bx bx-plus me-2"></i>Add Announcement
                    </a>
                </div>
            @endif

            @if ($announcements->count())
                <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid #f1f5f9;">
                    {{ $announcements->links('vendor.pagination.admin') }}
                </div>
            @endif
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Hover effect for table rows */
        tbody tr:hover {
            background: #f8fafc !important;
        }
    </style>
@endpush
