<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSlide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HeroSlideController extends Controller
{
    public function index()
    {
        $slides = HeroSlide::orderBy('sort_order')->get();
        return view('admin.hero-slides.index', compact('slides'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'image'    => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            'title'    => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
        ]);

        $path = $request->file('image')->store('hero-slides', 'public');

        $maxOrder = HeroSlide::max('sort_order') ?? 0;

        HeroSlide::create([
            'image_path'  => $path,
            'title'       => $request->title,
            'subtitle'    => $request->subtitle,
            'sort_order'  => $maxOrder + 1,
            'is_active'   => true,
        ]);

        return redirect()->route('admin.hero-slides.index')
            ->with('success', 'Slide added successfully.');
    }

    public function update(Request $request, HeroSlide $heroSlide)
    {
        $request->validate([
            'title'    => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $data = [
            'title'      => $request->title,
            'subtitle'   => $request->subtitle,
            'is_active'  => $request->boolean('is_active', true),
        ];

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($heroSlide->image_path);
            $data['image_path'] = $request->file('image')->store('hero-slides', 'public');
        }

        $heroSlide->update($data);

        return redirect()->route('admin.hero-slides.index')
            ->with('success', 'Slide updated successfully.');
    }

    public function destroy(HeroSlide $heroSlide)
    {
        Storage::disk('public')->delete($heroSlide->image_path);
        $heroSlide->delete();

        return redirect()->route('admin.hero-slides.index')
            ->with('success', 'Slide deleted successfully.');
    }

    public function reorder(Request $request)
    {
        $request->validate(['order' => 'required|array']);

        foreach ($request->order as $index => $id) {
            HeroSlide::where('id', $id)->update(['sort_order' => $index]);
        }

        return response()->json(['success' => true]);
    }

    public function toggleActive(HeroSlide $heroSlide)
    {
        $heroSlide->update(['is_active' => !$heroSlide->is_active]);

        return redirect()->route('admin.hero-slides.index')
            ->with('success', 'Slide status updated.');
    }
}
