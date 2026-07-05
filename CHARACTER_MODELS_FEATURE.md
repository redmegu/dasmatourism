# Character Models Feature - Story Mode

## Overview

This feature adds full-body character sprite functionality to the Story Mode, similar to visual novel games. Character models appear on the left and right sides of the screen (outside the main story frame), creating an immersive visual novel experience.

## Key Differences

-   **Visual Images**: Scene illustrations displayed in a horizontal row at the top (existing feature)
-   **Character Models**: Full-body character sprites displayed on the left and right sides of the screen (NEW)

## Implementation Details

### 1. Database Migration

**File**: `database/migrations/2025_11_05_000001_add_character_models_to_story_chapters.php`

Added `character_models` JSON column to `story_chapters` table to store an array of character sprite image paths.

```bash
php artisan migrate
```

### 2. Model Update

**File**: `app/Models/StoryChapter.php`

-   Added `character_models` to `$fillable` array
-   Added `character_models` => 'array' to `casts()` method

### 3. Controller Updates

**File**: `app/Http/Controllers/Admin/StoryModeController.php`

#### Validation

Added validation for character models in both `store()` and `update()` methods:

```php
'character_models.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
```

#### File Upload Handling

-   **Store**: Uploads character models to `storage/story/character-models/`
-   **Update**: Deletes old character models before uploading new ones
-   **Delete**: Removes all character model files when deleting a chapter

### 4. Admin Views

#### Create Form

**File**: `resources/views/admin/story-chapters/create.blade.php`

Added new card section:

-   **Title**: "Character Models"
-   **Icon**: Body icon (bx-body)
-   **Upload**: Multiple file upload with drag & drop
-   **Preview**: Grid preview with remove buttons
-   **Help Text**: "Upload full-body character sprites that will appear outside the main story frame"

Features:

-   Multiple file selection
-   Real-time preview
-   Individual remove buttons
-   Label showing "Character 1, 2, 3..."

#### Edit Form

**File**: `resources/views/admin/story-chapters/edit.blade.php`

Added two sections:

1. **Current Character Models**: Display existing uploaded sprites
2. **Upload New Character Models**: Replace all character models

Features:

-   Shows current character models with labels
-   Upload new files to replace all existing ones
-   Real-time preview of new uploads
-   Individual remove buttons for new uploads

#### JavaScript Functions

Both create and edit forms include:

```javascript
// Character models preview
let selectedCharacterFiles = [];

characterModelsInput.addEventListener("change", function (e) {
    const newFiles = Array.from(e.target.files);
    selectedCharacterFiles = [...selectedCharacterFiles, ...newFiles];
    renderCharacterModels();
});

function renderCharacterModels() {
    // Renders preview with remove buttons
}

function updateCharacterFileInput() {
    // Updates file input with remaining files
}

window.removeCharacterModel = function (index) {
    selectedCharacterFiles.splice(index, 1);
    renderCharacterModels();
};
```

### 5. Public Story View

**File**: `resources/views/story-mode/chapter.blade.php`

#### Layout Structure

```
┌─────────────────────────────────────────────────────────┐
│  Character Models (Left)  │  Main Content  │  Character Models (Right) │
│         Fixed              │   Scrollable   │        Fixed              │
└─────────────────────────────────────────────────────────┘
```

#### Character Distribution

-   **Even-indexed characters** (0, 2, 4...): Display on LEFT side
-   **Odd-indexed characters** (1, 3, 5...): Display on RIGHT side

#### HTML Structure

```blade
<div class="story-content-wrapper">
    <!-- Left Characters -->
    <div class="character-models-container character-models-left">
        @foreach ($chapter->character_models as $index => $characterModel)
            @if ($index % 2 == 0)
                <div class="character-model-sprite">
                    <img src="{{ asset('storage/' . $characterModel) }}" alt="Character">
                </div>
            @endif
        @endforeach
    </div>

    <!-- Main Content Center -->
    <div class="story-center-content">
        <!-- Visual images, story text, choices, etc. -->
    </div>

    <!-- Right Characters -->
    <div class="character-models-container character-models-right">
        @foreach ($chapter->character_models as $index => $characterModel)
            @if ($index % 2 != 0)
                <div class="character-model-sprite">
                    <img src="{{ asset('storage/' . $characterModel) }}" alt="Character">
                </div>
            @endif
        @endforeach
    </div>
</div>
```

#### CSS Styling

**Key Features**:

-   Fixed positioning on left/right sides
-   Centered vertically (50% from top)
-   Drop shadow for depth
-   Hover effect (scale 1.05)
-   Max dimensions: 400px width, 80vh height
-   Responsive: Hidden on screens < 992px

**Important Classes**:

-   `.story-content-wrapper`: Flex container
-   `.character-models-container`: Fixed position container for sprites
-   `.character-models-left`: Left-aligned sprites
-   `.character-models-right`: Right-aligned sprites
-   `.character-model-sprite`: Individual character sprite with animations

#### Animations

-   **Left characters**: `data-aos="fade-right"`
-   **Right characters**: `data-aos="fade-left"`
-   Staggered delay: `delay="{{ $index * 150 }}"`

### 6. Responsive Design

#### Breakpoints

-   **> 1400px**: Full size (400px max width)
-   **1200-1400px**: Medium size (300px max width)
-   **992-1200px**: Small size (250px max width)
-   **< 992px**: Character models HIDDEN (mobile-first approach)

#### Mobile Behavior

On mobile devices, character models are hidden to:

-   Prioritize content readability
-   Save screen space
-   Improve performance
-   Maintain clean layout

## Usage Guide

### For Administrators

#### Adding Character Models to a Chapter

1. **Navigate to**: Admin Panel → Story Chapters → Create/Edit Chapter
2. **Scroll to**: "Character Models" section
3. **Click**: Upload area or drag files
4. **Select**: Multiple PNG/JPG images (PNG with transparent background recommended)
5. **Preview**: Images appear in grid with "Character 1, 2, 3..." labels
6. **Remove**: Click X button on any image to remove before saving
7. **Save**: Submit the form

#### Best Practices

1. **Image Format**:

    - **Recommended**: PNG with transparent background
    - **Alternative**: JPG (will have background)
    - Max file size: 2MB per image

2. **Image Dimensions**:

    - Ideal: 400-800px width, 800-1600px height
    - Full-body character sprites work best
    - Vertical aspect ratio (portrait orientation)

3. **Character Distribution**:

    - Upload 2 characters: 1 left, 1 right
    - Upload 4 characters: 2 left, 2 right
    - Odd number: More on left side

4. **Visual Consistency**:
    - Use similar art style for all characters
    - Maintain consistent lighting/shading
    - Keep character sizes proportional

### For Content Creators

#### Example Scenarios

**Scenario 1: Dialogue Scene**

-   Upload 2 characters (guide on left, tourist on right)
-   Characters face each other
-   Story text contains their conversation

**Scenario 2: Group Scene**

-   Upload 4 characters (2 guides, 2 tourists)
-   Characters distributed evenly on both sides
-   Story describes group interaction

**Scenario 3: Solo Narration**

-   Upload 1 character (narrator on left)
-   Right side empty for cleaner look
-   Story text is first-person narrative

## File Storage Structure

```
storage/
└── app/
    └── public/
        └── story/
            ├── visual-images/         (Scene illustrations)
            └── character-models/      (Full-body sprites)
```

## Technical Notes

### Performance Considerations

-   Images are lazy-loaded (`loading="lazy"`)
-   Fixed positioning prevents layout reflow
-   Character models hidden on mobile to reduce bandwidth
-   Drop shadow uses CSS3 (GPU-accelerated)

### Browser Compatibility

-   Modern browsers (Chrome, Firefox, Edge, Safari)
-   CSS Grid and Flexbox support required
-   AOS (Animate On Scroll) library for animations
-   Fallback: Characters still visible without animations

### Accessibility

-   Alt text: "Character 1", "Character 2", etc.
-   Semantic HTML structure
-   Keyboard navigation supported in admin forms
-   Screen readers can skip decorative character sprites

## Troubleshooting

### Character Models Not Showing

1. Check if images were uploaded successfully
2. Verify `storage:link` is created: `php artisan storage:link`
3. Check browser console for 404 errors
4. Ensure screen width is > 992px (not mobile)

### Upload Errors

1. Check file size (must be < 2MB)
2. Verify file format (PNG, JPG, JPEG, WEBP only)
3. Check server upload limits in `php.ini`
4. Ensure storage folder is writable

### Layout Issues

1. Clear browser cache
2. Check CSS is loaded properly
3. Verify AOS library is included
4. Test on different screen sizes

## Future Enhancements

### Possible Improvements

1. **Character Positions**: Allow admin to choose left/right placement
2. **Character Emotions**: Multiple sprites per character for different expressions
3. **Character Names**: Label characters with names below sprites
4. **Animation Options**: Custom entrance/exit animations
5. **Mobile View**: Optional smaller character sprites on tablets
6. **Character Library**: Reusable character database across chapters
7. **Layering**: Z-index control for character overlap effects

## Summary

The Character Models feature transforms the Story Mode into a true visual novel experience by:

-   ✅ Adding full-body character sprites outside the main content frame
-   ✅ Distributing characters evenly on left and right sides
-   ✅ Providing easy admin upload interface with preview
-   ✅ Supporting multiple file formats (PNG recommended)
-   ✅ Including smooth animations and hover effects
-   ✅ Being fully responsive and mobile-friendly
-   ✅ Maintaining separation from existing visual images feature

This creates an immersive storytelling experience where characters "frame" the narrative, just like in professional visual novels!
