@extends('layouts.admin')

@section('page-title', 'Hero Carousel Slides')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">Hero Carousel Slides</h2>
        <p class="text-muted mb-0">Manage the background images shown on the homepage hero section.</p>
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class='bx bx-check-circle me-2'></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Add New Slide --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white fw-semibold py-3">
        <i class='bx bx-image-add me-2 text-primary'></i>Add New Slide
    </div>
    <div class="card-body">
        <form action="{{ route('admin.hero-slides.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
                <div class="col-md-5">
                    <label class="form-label fw-medium">Image <span class="text-danger">*</span></label>
                    <input type="file" name="image" class="form-control @error('image') is-invalid @enderror"
                        accept="image/jpeg,image/png,image/jpg,image/webp" required>
                    <div class="form-text">JPEG, PNG, or WebP. Max 5 MB. Recommended size: 1920×1080px.</div>
                    @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-medium">Title <span class="text-muted">(optional)</span></label>
                    <input type="text" name="title" class="form-control" placeholder="e.g. Welcome to Dasmariñas"
                        value="{{ old('title') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-medium">Subtitle <span class="text-muted">(optional)</span></label>
                    <input type="text" name="subtitle" class="form-control" placeholder="Short caption"
                        value="{{ old('subtitle') }}">
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class='bx bx-plus'></i> Add
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Existing Slides --}}
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white fw-semibold py-3 d-flex justify-content-between align-items-center">
        <span><i class='bx bx-images me-2 text-primary'></i>Current Slides ({{ $slides->count() }})</span>
        <small class="text-muted">Drag rows to reorder</small>
    </div>
    <div class="card-body p-0">
        @if ($slides->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class='bx bx-image-alt' style="font-size:3rem;"></i>
                <p class="mt-2">No slides added yet. Upload your first image above.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle" id="slidesTable">
                    <thead class="table-light">
                        <tr>
                            <th width="40" class="text-center"><i class='bx bx-move'></i></th>
                            <th width="120">Preview</th>
                            <th>Title / Subtitle</th>
                            <th width="100" class="text-center">Status</th>
                            <th width="180" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="sortableSlides">
                        @foreach ($slides as $slide)
                            <tr data-id="{{ $slide->id }}">
                                <td class="text-center text-muted drag-handle" style="cursor:grab;">
                                    <i class='bx bx-grid-vertical fs-5'></i>
                                </td>
                                <td>
                                    <img src="{{ asset('storage/' . $slide->image_path) }}"
                                        alt="Slide {{ $slide->sort_order }}"
                                        style="width:100px;height:60px;object-fit:cover;border-radius:6px;">
                                </td>
                                <td>
                                    <div class="fw-medium">{{ $slide->title ?: '—' }}</div>
                                    <small class="text-muted">{{ $slide->subtitle ?: '' }}</small>
                                </td>
                                <td class="text-center">
                                    <form action="{{ route('admin.hero-slides.toggle', $slide) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="badge border-0 {{ $slide->is_active ? 'bg-success' : 'bg-secondary' }}"
                                            style="cursor:pointer;font-size:.8rem;padding:.45em .75em;">
                                            {{ $slide->is_active ? 'Active' : 'Hidden' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-secondary me-1"
                                        onclick="openEditModal({{ $slide->id }}, '{{ addslashes($slide->title) }}', '{{ addslashes($slide->subtitle) }}', {{ $slide->is_active ? 'true' : 'false' }})">
                                        <i class='bx bx-edit'></i> Edit
                                    </button>
                                    <form action="{{ route('admin.hero-slides.destroy', $slide) }}" method="POST"
                                        style="display:inline;"
                                        onsubmit="return confirm('Delete this slide?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class='bx bx-trash'></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

{{-- Edit Modal --}}
<div class="modal fade" id="editSlideModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Edit Slide</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editSlideForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-medium">Replace Image <span class="text-muted">(leave blank to keep current)</span></label>
                        <input type="file" name="image" class="form-control" accept="image/jpeg,image/png,image/jpg,image/webp">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Title</label>
                        <input type="text" name="title" id="editTitle" class="form-control" placeholder="Optional title overlay">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Subtitle</label>
                        <input type="text" name="subtitle" id="editSubtitle" class="form-control" placeholder="Optional subtitle">
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" id="editIsActive" value="1">
                        <label class="form-check-label" for="editIsActive">Active (show on homepage)</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
function openEditModal(id, title, subtitle, isActive) {
    const form = document.getElementById('editSlideForm');
    form.action = `/admin/hero-slides/${id}`;
    document.getElementById('editTitle').value = title;
    document.getElementById('editSubtitle').value = subtitle;
    document.getElementById('editIsActive').checked = isActive;
    new bootstrap.Modal(document.getElementById('editSlideModal')).show();
}

// Drag-to-reorder
const tbody = document.getElementById('sortableSlides');
if (tbody) {
    Sortable.create(tbody, {
        handle: '.drag-handle',
        animation: 150,
        onEnd: function() {
            const order = [...tbody.querySelectorAll('tr')].map(tr => tr.dataset.id);
            fetch('{{ route('admin.hero-slides.reorder') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ order })
            });
        }
    });
}
</script>
@endpush
