<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('attractions')->paginate(15);
        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $category = Category::create($validated);

        ActivityLog::logActivity(
            'create',
            "Created category: {$category->name}",
            Category::class,
            $category->id
        );

        return back()->with('success', 'Category created successfully.');
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $category->update($validated);

        ActivityLog::logActivity(
            'update',
            "Updated category: {$category->name}",
            Category::class,
            $category->id
        );

        return back()->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        if ($category->attractions()->count() > 0) {
            return back()->with('error', 'Cannot delete category with existing attractions.');
        }

        $categoryName = $category->name;
        $category->delete();

        ActivityLog::logActivity(
            'delete',
            "Deleted category: {$categoryName}",
            Category::class,
            $category->id
        );

        return back()->with('success', 'Category deleted successfully.');
    }
}
