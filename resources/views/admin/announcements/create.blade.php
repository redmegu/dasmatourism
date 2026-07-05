@extends('layouts.admin')
@section('page-title', 'Add Announcement')

@section('content')
    <!-- Enhanced Page Header -->
    <div
        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px; padding: 2rem; margin-bottom: 2rem; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.2);">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h3 class="text-white mb-2 fw-bold">
                    <i class='bx bxs-plus-circle me-2'></i>Create New Announcement
                </h3>
                <p class="text-white-50 mb-0">Share important updates with your community</p>
            </div>
            <a href="{{ route('admin.announcements.index') }}"
                style="display: inline-flex; align-items: center; padding: 0.625rem 1.25rem; background: rgba(255,255,255,0.2); color: white; border-radius: 8px; text-decoration: none; backdrop-filter: blur(10px); transition: background 0.2s;"
                onmouseover="this.style.background='rgba(255,255,255,0.3)'"
                onmouseout="this.style.background='rgba(255,255,255,0.2)'">
                <i class='bx bx-arrow-back me-2'></i>Back to List
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <form method="POST" action="{{ route('admin.announcements.store') }}" enctype="multipart/form-data">
                @csrf
                <div
                    style="background: white; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.08); border: none; padding: 2rem; margin-bottom: 1.5rem;">
                    <!-- Title Field -->
                    <div style="margin-bottom: 1.5rem;">
                        <label for="title"
                            style="display: flex; align-items: center; font-weight: 600; color: #1e293b; margin-bottom: 0.5rem; font-size: 0.875rem;">
                            <i class='bx bx-text me-2' style="font-size: 1.25rem; color: #667eea;"></i>
                            Announcement Title <span style="color: #ef4444; margin-left: 0.25rem;">*</span>
                        </label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" name="title"
                            id="title" required value="{{ old('title') }}"
                            placeholder="Enter a clear and concise title"
                            style="border: 2px solid #e2e8f0; border-radius: 8px; padding: 0.75rem 1rem; font-size: 0.9375rem; transition: border-color 0.2s;"
                            onfocus="this.style.borderColor='#667eea'" onblur="this.style.borderColor='#e2e8f0'">
                        @error('title')
                            <div class="invalid-feedback" style="display: flex; align-items: center; margin-top: 0.5rem;">
                                <i class='bx bx-error-circle me-1'></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Content Field -->
                    <div style="margin-bottom: 1.5rem;">
                        <label for="content"
                            style="display: flex; align-items: center; font-weight: 600; color: #1e293b; margin-bottom: 0.5rem; font-size: 0.875rem;">
                            <i class='bx bx-message-detail me-2' style="font-size: 1.25rem; color: #667eea;"></i>
                            Content <span style="color: #ef4444; margin-left: 0.25rem;">*</span>
                        </label>
                        <textarea class="form-control @error('content') is-invalid @enderror" name="content" id="content" rows="6"
                            required placeholder="Write your announcement content here..."
                            style="border: 2px solid #e2e8f0; border-radius: 8px; padding: 0.75rem 1rem; font-size: 0.9375rem; line-height: 1.6; transition: border-color 0.2s;"
                            onfocus="this.style.borderColor='#667eea'" onblur="this.style.borderColor='#e2e8f0'">{{ old('content') }}</textarea>
                        @error('content')
                            <div class="invalid-feedback" style="display: flex; align-items: center; margin-top: 0.5rem;">
                                <i class='bx bx-error-circle me-1'></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Photo Upload Field -->
                    <div style="margin-bottom: 1.5rem;">
                        <label
                            style="display: flex; align-items: center; font-weight: 600; color: #1e293b; margin-bottom: 0.5rem; font-size: 0.875rem;">
                            <i class='bx bx-image-add me-2' style="font-size: 1.25rem; color: #667eea;"></i>
                            Featured Photo
                        </label>
                        <div style="border: 2px dashed #cbd5e1; border-radius: 8px; padding: 1.5rem; text-align: center; background: #f8fafc; transition: border-color 0.2s;"
                            onmouseover="this.style.borderColor='#667eea'" onmouseout="this.style.borderColor='#cbd5e1'">
                            <i class='bx bx-cloud-upload'
                                style="font-size: 3rem; color: #94a3b8; margin-bottom: 0.5rem;"></i>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" name="image"
                                accept="image/*" style="margin-top: 0.75rem;">
                            <small style="color: #64748b; display: block; margin-top: 0.5rem;">
                                <i class='bx bx-info-circle me-1'></i>Recommended size: 800x400px (PNG, JPG, WEBP)
                            </small>
                        </div>
                        @error('image')
                            <div class="invalid-feedback" style="display: flex; align-items: center; margin-top: 0.5rem;">
                                <i class='bx bx-error-circle me-1'></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Status Field -->
                    <div style="margin-bottom: 2rem;">
                        <label
                            style="display: flex; align-items: center; font-weight: 600; color: #1e293b; margin-bottom: 0.5rem; font-size: 0.875rem;">
                            <i class='bx bx-radio-circle-marked me-2' style="font-size: 1.25rem; color: #667eea;"></i>
                            Publication Status
                        </label>
                        <select class="form-select" name="status" required
                            style="border: 2px solid #e2e8f0; border-radius: 8px; padding: 0.75rem 1rem; font-size: 0.9375rem; cursor: pointer; transition: border-color 0.2s;"
                            onfocus="this.style.borderColor='#667eea'" onblur="this.style.borderColor='#e2e8f0'">
                            <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>
                                ✅ Publish Now
                            </option>
                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>
                                📝 Save as Draft
                            </option>
                        </select>
                        <small style="color: #64748b; display: block; margin-top: 0.5rem;">
                            <i class='bx bx-info-circle me-1'></i>Published announcements will be visible on the homepage
                            immediately
                        </small>
                    </div>

                    <!-- Action Buttons -->
                    <div
                        style="display: flex; gap: 1rem; justify-content: flex-end; padding-top: 1.5rem; border-top: 2px solid #f1f5f9;">
                        <a href="{{ route('admin.announcements.index') }}"
                            style="display: inline-flex; align-items: center; padding: 0.75rem 1.5rem; background: #f1f5f9; color: #475569; border-radius: 8px; text-decoration: none; font-weight: 500; transition: background 0.2s;"
                            onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">
                            <i class='bx bx-x me-2'></i>Cancel
                        </a>
                        <button type="submit"
                            style="display: inline-flex; align-items: center; padding: 0.75rem 2rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3); transition: transform 0.2s, box-shadow 0.2s;"
                            onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(102, 126, 234, 0.4)'"
                            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(102, 126, 234, 0.3)'">
                            <i class='bx bx-paper-plane me-2'></i>Publish Announcement
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Focus styles for form inputs */
        .form-control:focus,
        .form-select:focus {
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1) !important;
        }
    </style>
@endpush
