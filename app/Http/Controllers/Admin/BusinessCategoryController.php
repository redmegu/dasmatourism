<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BusinessCategory;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BusinessCategoryController extends Controller
{
    public function index()
    {
        $categories = BusinessCategory::withCount('businesses')->paginate(15);
        return view('admin.business-categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:business_categories,name',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $category = BusinessCategory::create($validated);

        ActivityLog::logActivity(
            'create',
            "Created business category: {$category->name}",
            BusinessCategory::class,
            $category->id
        );

        return back()->with('success', 'Business category created successfully.');
    }

    public function update(Request $request, BusinessCategory $businessCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:business_categories,name,' . $businessCategory->id,
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $businessCategory->update($validated);

        ActivityLog::logActivity(
            'update',
            "Updated business category: {$businessCategory->name}",
            BusinessCategory::class,
            $businessCategory->id
        );

        return back()->with('success', 'Business category updated successfully.');
    }

    public function destroy(BusinessCategory $businessCategory)
    {
        if ($businessCategory->businesses()->count() > 0) {
            return back()->with('error', 'Cannot delete category with existing businesses.');
        }

        $categoryName = $businessCategory->name;
        $businessCategory->delete();

        ActivityLog::logActivity(
            'delete',
            "Deleted business category: {$categoryName}",
            BusinessCategory::class,
            $businessCategory->id
        );

        return back()->with('success', 'Business category deleted successfully.');
    }
}
