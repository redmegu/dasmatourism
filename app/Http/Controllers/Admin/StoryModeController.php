<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StoryChapter;
use App\Models\StoryChoice;
use App\Models\Attraction;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StoryModeController extends Controller
{
    public function index()
    {
        $chapters = StoryChapter::with(['attraction', 'choices'])
            ->orderBy('chapter_number')
            ->paginate(15);

        return view('admin.story-chapters.index', compact('chapters')); // Changed
    }

    public function create()
    {
        $attractions = Attraction::approved()->get();
        $chapters = StoryChapter::orderBy('chapter_number')->get();

        return view('admin.story-chapters.create', compact('attractions', 'chapters')); // Changed
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'chapter_number' => 'required|integer|unique:story_chapters,chapter_number',
            'content' => 'required|string',
            'background_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'visual_images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'character_models.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'attraction_id' => 'nullable|exists:attractions,id',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('background_image')) {
            $validated['background_image'] = $request->file('background_image')
                ->store('story-mode/backgrounds', 'public');
        }

        // Handle multiple visual images
        if ($request->hasFile('visual_images')) {
            $visualImagePaths = [];
            foreach ($request->file('visual_images') as $image) {
                $visualImagePaths[] = $image->store('story/visual-images', 'public');
            }
            $validated['visual_images'] = $visualImagePaths;
        }

        // Handle multiple character models
        if ($request->hasFile('character_models')) {
            $characterModelPaths = [];
            foreach ($request->file('character_models') as $image) {
                $characterModelPaths[] = $image->store('story/character-models', 'public');
            }
            $validated['character_models'] = $characterModelPaths;
        }

        $chapter = StoryChapter::create($validated);

        ActivityLog::logActivity(
            'create',
            "Created story chapter: {$chapter->title}",
            StoryChapter::class,
            $chapter->id
        );

        return redirect()->route('admin.story-chapters.show', $chapter)
            ->with('success', 'Story chapter created successfully.');
    }

    public function show(StoryChapter $storyChapter)
    {
        $storyChapter->load(['attraction', 'choices.nextChapter']);
        $availableChapters = StoryChapter::where('id', '!=', $storyChapter->id)
            ->orderBy('chapter_number')
            ->get();

        return view('admin.story-chapters.show', compact('storyChapter', 'availableChapters')); // Changed
    }

    public function edit(StoryChapter $storyChapter)
    {
        $attractions = Attraction::approved()->get();
        $chapters = StoryChapter::where('id', '!=', $storyChapter->id)
            ->orderBy('chapter_number')
            ->get();

        return view('admin.story-chapters.edit', compact('storyChapter', 'attractions', 'chapters')); // Changed
    }

    public function update(Request $request, StoryChapter $storyChapter)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'chapter_number' => 'required|integer|unique:story_chapters,chapter_number,' . $storyChapter->id,
            'content' => 'required|string',
            'background_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'visual_images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'character_models.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'attraction_id' => 'nullable|exists:attractions,id',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('background_image')) {
            if ($storyChapter->background_image) {
                Storage::disk('public')->delete($storyChapter->background_image);
            }
            $validated['background_image'] = $request->file('background_image')
                ->store('story-mode/backgrounds', 'public');
        }

        // Handle multiple visual images
        if ($request->hasFile('visual_images')) {
            // Delete old visual images
            if ($storyChapter->visual_images) {
                foreach ($storyChapter->visual_images as $oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }
            }

            $visualImagePaths = [];
            foreach ($request->file('visual_images') as $image) {
                $visualImagePaths[] = $image->store('story/visual-images', 'public');
            }
            $validated['visual_images'] = $visualImagePaths;
        }

        // Handle multiple character models
        if ($request->hasFile('character_models')) {
            // Delete old character models
            if ($storyChapter->character_models) {
                foreach ($storyChapter->character_models as $oldModel) {
                    Storage::disk('public')->delete($oldModel);
                }
            }

            $characterModelPaths = [];
            foreach ($request->file('character_models') as $image) {
                $characterModelPaths[] = $image->store('story/character-models', 'public');
            }
            $validated['character_models'] = $characterModelPaths;
        }

        $storyChapter->update($validated);

        ActivityLog::logActivity(
            'update',
            "Updated story chapter: {$storyChapter->title}",
            StoryChapter::class,
            $storyChapter->id
        );

        return redirect()->route('admin.story-chapters.index')
            ->with('success', 'Story chapter updated successfully.');
    }


    public function destroy(StoryChapter $storyChapter)
    {
        if ($storyChapter->background_image) {
            Storage::disk('public')->delete($storyChapter->background_image);
        }

        // Delete visual images
        if ($storyChapter->visual_images) {
            foreach ($storyChapter->visual_images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        // Delete character models
        if ($storyChapter->character_models) {
            foreach ($storyChapter->character_models as $model) {
                Storage::disk('public')->delete($model);
            }
        }

        // Delete all choices
        $storyChapter->choices()->delete();

        $chapterTitle = $storyChapter->title;
        $storyChapter->delete();

        ActivityLog::logActivity(
            'delete',
            "Deleted story chapter: {$chapterTitle}",
            StoryChapter::class,
            $storyChapter->id
        );

        return redirect()->route('admin.story-chapters.index')
            ->with('success', 'Story chapter deleted successfully.');
    }

    public function storeChoice(Request $request, StoryChapter $chapter)
    {
        $validated = $request->validate([
            'choice_text' => 'required|string|max:500',
            'next_chapter_id' => 'nullable|exists:story_chapters,id',
            'order' => 'required|integer|min:0',
        ]);

        $validated['chapter_id'] = $chapter->id;

        $storyChoice = StoryChoice::create($validated);

        ActivityLog::logActivity(
            'create',
            "Added choice to story chapter: {$chapter->title}",
            StoryChoice::class,
            $storyChoice->id
        );

        return back()->with('success', 'Choice added successfully.');
    }

    public function destroyChoice(StoryChoice $choice)
    {
        $choiceText = $choice->choice_text;
        $choice->delete();

        ActivityLog::logActivity(
            'delete',
            "Deleted story choice: {$choiceText}",
            StoryChoice::class,
            $choice->id
        );

        return back()->with('success', 'Choice deleted successfully.');
    }
}
