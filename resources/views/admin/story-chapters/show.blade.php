@extends('layouts.admin')

@section('page-title', 'Chapter Details')

@section('content')
    <!-- Enhanced Page Header -->
    <div class="admin-detail-header mb-4">
        <div class="admin-detail-header-content">
            <div>
                <a href="{{ route('admin.story-chapters.index') }}" class="admin-breadcrumb-link">
                    <i class='bx bx-chevron-left'></i> Back to Chapters
                </a>
                <div class="admin-chapter-title-section">
                    <div class="admin-chapter-badge-large">
                        <span class="chapter-number">{{ $storyChapter->chapter_number }}</span>
                        <span class="chapter-label">Chapter</span>
                    </div>
                    <div>
                        <h2 class="admin-detail-title">{{ $storyChapter->title }}</h2>
                        <div class="admin-detail-meta">
                            <span><i class='bx bx-calendar'></i> Created
                                {{ $storyChapter->created_at->format('M d, Y') }}</span>
                            <span><i class='bx bx-time'></i> Updated {{ $storyChapter->updated_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="admin-detail-badges">
                <span class="admin-status-badge-large {{ $storyChapter->is_active ? 'active' : 'inactive' }}">
                    @if ($storyChapter->is_active)
                        <i class='bx bx-check-circle'></i>
                    @else
                        <i class='bx bx-x-circle'></i>
                    @endif
                    {{ $storyChapter->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Chapter Content -->
            <div class="admin-detail-card">
                <div class="admin-detail-card-header">
                    <div class="admin-detail-card-icon primary">
                        <i class='bx bx-book-open'></i>
                    </div>
                    <h5>Chapter Content</h5>
                </div>
                <div class="admin-detail-card-body">
                    @if ($storyChapter->attraction)
                        <div class="admin-attraction-link">
                            <i class='bx bx-map-pin'></i>
                            <div>
                                <strong>Related Attraction</strong>
                                <p>{{ $storyChapter->attraction->name }}</p>
                            </div>
                        </div>
                    @endif

                    @if ($storyChapter->background_image)
                        <div class="admin-chapter-image">
                            <img src="{{ asset('storage/' . $storyChapter->background_image) }}"
                                alt="{{ $storyChapter->title }}">
                            <div class="admin-image-badge">
                                <i class='bx bx-image'></i> Background Image
                            </div>
                        </div>
                    @endif

                    <!-- Visual Novel Images Section -->
                    @if ($storyChapter->visual_images && count($storyChapter->visual_images) > 0)
                        <div class="admin-visual-images-section">
                            <div class="admin-visual-images-header">
                                <div class="admin-visual-icon">
                                    <i class='bx bx-user'></i>
                                </div>
                                <div>
                                    <strong>Visual Novel Images</strong>
                                    <p>{{ count($storyChapter->visual_images) }}
                                        {{ Str::plural('image', count($storyChapter->visual_images)) }}</p>
                                </div>
                            </div>
                            <div class="admin-visual-images-grid-show">
                                @foreach ($storyChapter->visual_images as $index => $visualImage)
                                    <div class="visual-image-item-show">
                                        <img src="{{ asset('storage/' . $visualImage) }}"
                                            alt="Visual {{ $index + 1 }}">
                                        <div class="visual-image-label-show">
                                            <i class='bx bx-image'></i> Image {{ $index + 1 }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="admin-story-content-display">
                        <p>{{ $storyChapter->content }}</p>
                    </div>
                </div>
            </div>

            <!-- Story Choices -->
            <div class="admin-detail-card">
                <div class="admin-detail-card-header">
                    <div class="admin-detail-card-icon info">
                        <i class='bx bx-git-branch'></i>
                    </div>
                    <h5>Story Choices</h5>
                    <span class="admin-count-badge">{{ $storyChapter->choices->count() }}</span>
                </div>
                <div class="admin-detail-card-body">
                    @forelse($storyChapter->choices as $choice)
                        <div class="admin-choice-card">
                            <div class="admin-choice-header">
                                <div class="admin-choice-number-badge">
                                    <span>{{ $choice->order + 1 }}</span>
                                </div>
                                <div class="admin-choice-text-section">
                                    <p class="admin-choice-text">{{ $choice->choice_text }}</p>
                                    @if ($choice->nextChapter)
                                        <div class="admin-choice-destination">
                                            <i class='bx bx-right-arrow-alt'></i>
                                            <span>Leads to <strong>Chapter {{ $choice->nextChapter->chapter_number }}:
                                                    {{ $choice->nextChapter->title }}</strong></span>
                                        </div>
                                    @else
                                        <div class="admin-choice-destination end">
                                            <i class='bx bx-check-circle'></i>
                                            <span>Story End</span>
                                        </div>
                                    @endif
                                </div>
                                <button type="button" class="admin-choice-delete-btn"
                                    onclick="deleteChoice({{ $choice->id }})" title="Delete choice">
                                    <i class='bx bx-trash'></i>
                                </button>
                                <form id="delete-choice-{{ $choice->id }}"
                                    action="{{ route('admin.story-chapters.choices.destroy', $choice) }}" method="POST"
                                    class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="admin-empty-choices">
                            <div class="admin-empty-icon">
                                <i class='bx bx-git-branch'></i>
                            </div>
                            <h6>No Choices Yet</h6>
                            <p>Add choices below to create story branches</p>
                        </div>
                    @endforelse

                    <!-- Add New Choice Form -->
                    <div class="admin-add-choice-section">
                        <h6 class="admin-add-choice-title">
                            <i class='bx bx-plus-circle'></i> Add New Choice
                        </h6>
                        <form action="{{ route('admin.story-chapters.choices.store', $storyChapter) }}" method="POST">
                            @csrf
                            <div class="admin-choice-form-grid">
                                <div class="admin-form-group" style="grid-column: 1 / -1;">
                                    <label for="choice_text" class="admin-form-label">
                                        <i class='bx bx-message-square-detail'></i> Choice Text
                                        <span class="admin-required">*</span>
                                    </label>
                                    <input type="text"
                                        class="admin-form-input @error('choice_text') is-invalid @enderror" id="choice_text"
                                        name="choice_text" placeholder="e.g., Explore the historical site" required>
                                    @error('choice_text')
                                        <div class="admin-form-error">
                                            <i class='bx bx-error-circle'></i> {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="admin-form-group">
                                    <label for="next_chapter_id" class="admin-form-label">
                                        <i class='bx bx-right-arrow-alt'></i> Next Chapter
                                    </label>
                                    <select class="admin-form-select @error('next_chapter_id') is-invalid @enderror"
                                        id="next_chapter_id" name="next_chapter_id">
                                        <option value="">Story End</option>
                                        @foreach ($availableChapters as $chapter)
                                            <option value="{{ $chapter->id }}">
                                                Chapter {{ $chapter->chapter_number }}: {{ $chapter->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('next_chapter_id')
                                        <div class="admin-form-error">
                                            <i class='bx bx-error-circle'></i> {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="admin-form-group" style="flex: 0 0 120px;">
                                    <label for="order" class="admin-form-label">
                                        <i class='bx bx-sort'></i> Order
                                    </label>
                                    <input type="number" class="admin-form-input @error('order') is-invalid @enderror"
                                        id="order" name="order" value="{{ $storyChapter->choices->count() }}"
                                        required>
                                    @error('order')
                                        <div class="admin-form-error">
                                            <i class='bx bx-error-circle'></i> {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <button type="submit" class="admin-form-btn primary mt-3">
                                <i class='bx bx-plus-circle'></i>
                                <span>Add Choice</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="admin-detail-card">
                <div class="admin-detail-card-header">
                    <div class="admin-detail-card-icon secondary">
                        <i class='bx bx-cog'></i>
                    </div>
                    <h5>Quick Actions</h5>
                </div>
                <div class="admin-detail-card-body">
                    <div class="admin-action-buttons-stacked">
                        <a href="{{ route('admin.story-chapters.edit', $storyChapter) }}"
                            class="admin-action-btn-large primary">
                            <i class='bx bx-edit'></i>
                            <span>Edit Chapter</span>
                        </a>

                        <button type="button" class="admin-action-btn-large danger" onclick="deleteChapter()">
                            <i class='bx bx-trash'></i>
                            <span>Delete Chapter</span>
                        </button>

                        <form id="delete-chapter-form"
                            action="{{ route('admin.story-chapters.destroy', $storyChapter) }}" method="POST"
                            class="d-none">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </div>
            </div>

            <!-- Chapter Information -->
            <div class="admin-detail-card">
                <div class="admin-detail-card-header">
                    <div class="admin-detail-card-icon info">
                        <i class='bx bx-info-circle'></i>
                    </div>
                    <h5>Chapter Information</h5>
                </div>
                <div class="admin-detail-card-body">
                    <div class="admin-info-list-modern">
                        <div class="admin-info-item-modern">
                            <div class="admin-info-icon-modern">
                                <i class='bx bx-hash'></i>
                            </div>
                            <div>
                                <label>Chapter Number</label>
                                <p>{{ $storyChapter->chapter_number }}</p>
                            </div>
                        </div>

                        <div class="admin-info-item-modern">
                            <div class="admin-info-icon-modern">
                                <i class='bx {{ $storyChapter->is_active ? 'bx-check-circle' : 'bx-x-circle' }}'></i>
                            </div>
                            <div>
                                <label>Status</label>
                                <p>
                                    <span class="badge bg-{{ $storyChapter->is_active ? 'success' : 'secondary' }}">
                                        {{ $storyChapter->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </p>
                            </div>
                        </div>

                        <div class="admin-info-item-modern">
                            <div class="admin-info-icon-modern">
                                <i class='bx bx-git-branch'></i>
                            </div>
                            <div>
                                <label>Total Choices</label>
                                <p>{{ $storyChapter->choices->count() }}
                                    {{ Str::plural('choice', $storyChapter->choices->count()) }}</p>
                            </div>
                        </div>

                        <div class="admin-info-item-modern">
                            <div class="admin-info-icon-modern">
                                <i class='bx bx-calendar'></i>
                            </div>
                            <div>
                                <label>Created</label>
                                <p>{{ $storyChapter->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>

                        <div class="admin-info-item-modern">
                            <div class="admin-info-icon-modern">
                                <i class='bx bx-time'></i>
                            </div>
                            <div>
                                <label>Last Updated</label>
                                <p>{{ $storyChapter->updated_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* [KEEP ALL YOUR EXISTING STYLES] */

        /* ADD THESE NEW STYLES FOR VISUAL IMAGES */

        /* Visual Images Section */
        .admin-visual-images-section {
            margin-bottom: 1.5rem;
            padding: 1.5rem;
            background: linear-gradient(135deg, #f0fdf4, #dcfce7);
            border-radius: 12px;
            border-left: 4px solid #10b981;
        }

        .admin-visual-images-header {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.25rem;
            align-items: center;
        }

        .admin-visual-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(16, 185, 129, 0.25);
        }

        .admin-visual-images-header strong {
            display: block;
            font-size: 0.9375rem;
            color: #065f46;
            margin-bottom: 0.25rem;
            font-weight: 700;
        }

        .admin-visual-images-header p {
            font-size: 0.875rem;
            color: #059669;
            margin: 0;
            font-weight: 600;
        }

        /* Visual Images Grid for Show Page */
        .admin-visual-images-grid-show {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 1rem;
        }

        .visual-image-item-show {
            position: relative;
            border-radius: 12px;
            overflow: hidden;
            border: 2px solid #10b981;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
            transition: all 0.3s ease;
            background: white;
        }

        .visual-image-item-show:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
        }

        .visual-image-item-show img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            display: block;
        }

        .visual-image-label-show {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(16, 185, 129, 0.95), transparent);
            color: white;
            padding: 0.75rem;
            font-size: 0.8125rem;
            font-weight: 700;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        /* [KEEP ALL YOUR OTHER EXISTING STYLES BELOW] */
        /* Detail Header */
        .admin-detail-header {
            background: linear-gradient(135deg, #ffffff, #f8fafc);
            border-radius: 16px;
            padding: 2rem;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .admin-detail-header-content {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 2rem;
        }

        .admin-chapter-title-section {
            display: flex;
            gap: 1.5rem;
            align-items: center;
        }

        .admin-chapter-badge-large {
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 80px;
            height: 80px;
            border-radius: 14px;
            background: linear-gradient(135deg, #1a7838, #27a345);
            color: white;
            box-shadow: 0 4px 12px rgba(26, 120, 56, 0.25);
        }

        .chapter-number {
            font-size: 1.75rem;
            font-weight: 800;
            line-height: 1;
        }

        .chapter-label {
            font-size: 0.625rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            opacity: 0.9;
            margin-top: 0.25rem;
        }

        .admin-detail-title {
            font-size: 1.75rem;
            font-weight: 800;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }

        .admin-detail-meta {
            display: flex;
            gap: 1.5rem;
            flex-wrap: wrap;
            font-size: 0.875rem;
            color: #64748b;
        }

        .admin-detail-meta span {
            display: flex;
            align-items: center;
            gap: 0.375rem;
        }

        .admin-status-badge-large {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.25rem;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.9375rem;
        }

        .admin-status-badge-large.active {
            background: #d1fae5;
            color: #059669;
        }

        .admin-status-badge-large.inactive {
            background: #f1f5f9;
            color: #64748b;
        }

        /* Detail Cards */
        .admin-detail-card {
            background: white;
            border-radius: 16px;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #f1f5f9;
            overflow: hidden;
        }

        .admin-detail-card-header {
            padding: 1.5rem;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .admin-detail-card-icon {
            width: 44px;
            height: 44px;
            border-radius: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.375rem;
            color: white;
            flex-shrink: 0;
        }

        .admin-detail-card-icon.primary {
            background: linear-gradient(135deg, #1a7838, #27a345);
        }

        .admin-detail-card-icon.info {
            background: linear-gradient(135deg, #00A8E8, #0284c7);
        }

        .admin-detail-card-icon.secondary {
            background: linear-gradient(135deg, #64748b, #475569);
        }

        .admin-detail-card-header h5 {
            flex: 1;
            font-size: 1.125rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }

        .admin-count-badge {
            padding: 0.5rem 1rem;
            background: #f1f5f9;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            color: #64748b;
        }

        .admin-detail-card-body {
            padding: 1.5rem;
        }

        /* Attraction Link */
        .admin-attraction-link {
            display: flex;
            gap: 1rem;
            padding: 1.25rem;
            background: linear-gradient(135deg, #e0f2ff, #dbeafe);
            border-radius: 12px;
            border-left: 4px solid #0284c7;
            margin-bottom: 1.5rem;
        }

        .admin-attraction-link>i {
            font-size: 1.75rem;
            color: #0284c7;
            margin-top: 0.25rem;
        }

        .admin-attraction-link strong {
            display: block;
            font-size: 0.875rem;
            color: #0369a1;
            margin-bottom: 0.25rem;
        }

        .admin-attraction-link p {
            font-size: 1rem;
            font-weight: 600;
            color: #1e293b;
            margin: 0;
        }

        /* Chapter Image */
        .admin-chapter-image {
            position: relative;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 1.5rem;
            border: 2px solid #e2e8f0;
        }

        .admin-chapter-image img {
            width: 100%;
            height: auto;
            display: block;
        }

        .admin-image-badge {
            position: absolute;
            top: 1rem;
            right: 1rem;
            padding: 0.625rem 1rem;
            background: rgba(0, 0, 0, 0.75);
            backdrop-filter: blur(8px);
            color: white;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Story Content Display */
        .admin-story-content-display {
            padding: 1.75rem;
            background: #f8fafc;
            border-radius: 12px;
            border-left: 4px solid #1a7838;
        }

        .admin-story-content-display p {
            font-size: 1rem;
            line-height: 1.8;
            color: #475569;
            margin: 0;
            white-space: pre-wrap;
        }

        /* Choice Card */
        .admin-choice-card {
            margin-bottom: 1rem;
            border-radius: 12px;
            border: 2px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .admin-choice-card:hover {
            border-color: #1a7838;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .admin-choice-header {
            display: flex;
            gap: 1.25rem;
            padding: 1.25rem;
            align-items: flex-start;
        }

        .admin-choice-number-badge {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            background: linear-gradient(135deg, #1a7838, #27a345);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            font-weight: 800;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(26, 120, 56, 0.25);
        }

        .admin-choice-text-section {
            flex: 1;
        }

        .admin-choice-text {
            font-size: 1rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0 0 0.75rem 0;
        }

        .admin-choice-destination {
            display: flex;
            align-items: center;
            gap: 0.625rem;
            padding: 0.625rem 1rem;
            background: #f8fafc;
            border-radius: 8px;
            font-size: 0.875rem;
            color: #64748b;
        }

        .admin-choice-destination i {
            font-size: 1.125rem;
            color: #1a7838;
        }

        .admin-choice-destination.end {
            background: #fef3c7;
        }

        .admin-choice-destination.end i {
            color: #d97706;
        }

        .admin-choice-delete-btn {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background: #fee2e2;
            color: #dc2626;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.25s ease;
            flex-shrink: 0;
            font-size: 1.125rem;
        }

        .admin-choice-delete-btn:hover {
            background: #dc2626;
            color: white;
            transform: scale(1.05);
        }

        /* Empty Choices */
        .admin-empty-choices {
            text-align: center;
            padding: 3rem 2rem;
        }

        .admin-empty-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 1rem;
            border-radius: 50%;
            background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            color: #94a3b8;
        }

        .admin-empty-choices h6 {
            font-size: 1.125rem;
            font-weight: 700;
            color: #475569;
            margin-bottom: 0.5rem;
        }

        .admin-empty-choices p {
            color: #94a3b8;
        }

        /* Add Choice Section */
        .admin-add-choice-section {
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 2px solid #f1f5f9;
        }

        .admin-add-choice-title {
            font-size: 1rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.625rem;
        }

        .admin-add-choice-title i {
            font-size: 1.25rem;
            color: #1a7838;
        }

        .admin-choice-form-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 1rem;
        }

        /* Form Elements (reuse from previous) */
        .admin-form-group {
            margin-bottom: 0;
        }

        .admin-form-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 0.5rem;
        }

        .admin-form-label i {
            font-size: 1rem;
            color: #64748b;
        }

        .admin-required {
            color: #ef4444;
            font-weight: 700;
        }

        .admin-form-input,
        .admin-form-select {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 0.9375rem;
            transition: all 0.25s ease;
        }

        .admin-form-input:focus,
        .admin-form-select:focus {
            outline: none;
            border-color: #1a7838;
            box-shadow: 0 0 0 4px rgba(26, 120, 56, 0.1);
        }

        .admin-form-error {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #ef4444;
            font-size: 0.8125rem;
            margin-top: 0.5rem;
            font-weight: 600;
        }

        .admin-form-btn {
            display: inline-flex;
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

        /* Action Buttons */
        .admin-action-buttons-stacked {
            display: flex;
            flex-direction: column;
            gap: 0.875rem;
        }

        .admin-action-btn-large {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            padding: 1rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .admin-action-btn-large.primary {
            background: linear-gradient(135deg, #1a7838, #27a345);
            color: white;
            box-shadow: 0 4px 12px rgba(26, 120, 56, 0.25);
        }

        .admin-action-btn-large.primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(26, 120, 56, 0.35);
            color: white;
        }

        .admin-action-btn-large.danger {
            background: #fee2e2;
            color: #dc2626;
        }

        .admin-action-btn-large.danger:hover {
            background: #dc2626;
            color: white;
        }

        /* Info List */
        .admin-info-list-modern {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .admin-info-item-modern {
            display: flex;
            gap: 1rem;
            padding: 1rem;
            background: #f8fafc;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
        }

        .admin-info-icon-modern {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background: linear-gradient(135deg, #e0f2e9, #d1fae5);
            color: #1a7838;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        .admin-info-item-modern label {
            display: block;
            font-size: 0.75rem;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.25rem;
        }

        .admin-info-item-modern p {
            font-size: 0.9375rem;
            font-weight: 600;
            color: #1e293b;
            margin: 0;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .admin-detail-header-content {
                flex-direction: column;
            }

            .admin-chapter-title-section {
                flex-direction: column;
                align-items: flex-start;
            }

            .admin-choice-form-grid {
                grid-template-columns: 1fr;
            }

            .admin-visual-images-grid-show {
                grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        function deleteChapter() {
            if (confirm('Are you sure you want to delete this chapter? All choices will also be permanently deleted.')) {
                document.getElementById('delete-chapter-form').submit();
            }
        }

        function deleteChoice(id) {
            if (confirm('Are you sure you want to delete this choice?')) {
                document.getElementById('delete-choice-' + id).submit();
            }
        }
    </script>
@endpush
