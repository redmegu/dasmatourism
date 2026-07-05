<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::orderBy('published_at', 'desc')->paginate(12);
        return view('admin.announcements.index', compact('announcements'));
    }

    public function create()
    {
        return view('admin.announcements.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'status' => 'required|in:draft,published'
        ]);

        $validated['published_at'] = $request->status === 'published' ? now() : null;

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('announcements', 'public');
        }

        $announcement = Announcement::create($validated);

        ActivityLog::logActivity(
            'create',
            "Created announcement: {$announcement->title}",
            Announcement::class,
            $announcement->id
        );

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Announcement created successfully!');
    }

    public function edit(Announcement $announcement)
    {
        return view('admin.announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'status' => 'required|in:draft,published'
        ]);

        if ($request->hasFile('image')) {
            if ($announcement->image) {
                Storage::disk('public')->delete($announcement->image);
            }
            $validated['image'] = $request->file('image')->store('announcements', 'public');
        }

        if ($request->status === 'published' && !$announcement->published_at) {
            $validated['published_at'] = now();
        }

        $announcement->update($validated);

        ActivityLog::logActivity(
            'update',
            "Updated announcement: {$announcement->title}",
            Announcement::class,
            $announcement->id
        );

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Announcement updated successfully!');
    }

    public function destroy(Announcement $announcement)
    {
        if ($announcement->image) {
            Storage::disk('public')->delete($announcement->image);
        }

        $announcementTitle = $announcement->title;
        $announcement->delete();

        ActivityLog::logActivity(
            'delete',
            "Deleted announcement: {$announcementTitle}",
            Announcement::class,
            $announcement->id
        );

        return back()->with('success', 'Announcement deleted successfully!');
    }
}
