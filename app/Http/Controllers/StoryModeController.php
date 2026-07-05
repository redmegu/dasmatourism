<?php

namespace App\Http\Controllers;

use App\Models\StoryChapter;
use App\Models\UserStoryProgress;
use Illuminate\Http\Request;

class StoryModeController extends Controller
{
    public function index()
    {
        $firstChapter = StoryChapter::where('is_active', true)
            ->orderBy('chapter_number')
            ->first();

        $userProgress = null;
        if (auth()->check()) {
            $userProgress = UserStoryProgress::where('user_id', auth()->id())
                ->with('currentChapter')
                ->first();
        }

        return view('story-mode.index', compact('firstChapter', 'userProgress'));
    }

    public function start()
    {
        $firstChapter = StoryChapter::where('is_active', true)
            ->orderBy('chapter_number')
            ->first();

        if (!$firstChapter) {
            return redirect()->route('story-mode.index')
                ->with('error', 'Story mode is not available at the moment.');
        }

        // Create or reset progress
        UserStoryProgress::updateOrCreate(
            ['user_id' => auth()->id()],
            [
                'current_chapter_id' => $firstChapter->id,
                'visited_chapters' => [$firstChapter->id],
                'choices_made' => [],
                'is_completed' => false,
            ]
        );

        return redirect()->route('story-mode.chapter', $firstChapter->id);
    }

    public function showChapter(StoryChapter $chapter)
    {
        if (!$chapter->is_active) {
            abort(404);
        }

        $chapter->load(['choices', 'attraction']);

        $progress = UserStoryProgress::where('user_id', auth()->id())->first();

        return view('story-mode.chapter', compact('chapter', 'progress'));
    }

    public function makeChoice(Request $request)
    {
        $request->validate([
            'choice_id' => 'required|exists:story_choices,id',
        ]);

        $choice = \App\Models\StoryChoice::findOrFail($request->choice_id);

        $progress = UserStoryProgress::where('user_id', auth()->id())->first();

        if (!$progress) {
            return redirect()->route('story-mode.start');
        }

        // Update progress
        $visitedChapters = $progress->visited_chapters ?? [];
        $choicesMade = $progress->choices_made ?? [];

        if ($choice->next_chapter_id) {
            $visitedChapters[] = $choice->next_chapter_id;
            $choicesMade[] = [
                'chapter_id' => $choice->chapter_id,
                'choice_id' => $choice->id,
                'timestamp' => now()->toISOString(),
            ];

            $progress->update([
                'current_chapter_id' => $choice->next_chapter_id,
                'visited_chapters' => array_unique($visitedChapters),
                'choices_made' => $choicesMade,
            ]);

            return redirect()->route('story-mode.chapter', $choice->next_chapter_id);
        } else {
            // Story completed
            $progress->update(['is_completed' => true]);

            return redirect()->route('story-mode.index')
                ->with('success', 'Congratulations! You have completed the story mode.');
        }
    }
}
