<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('created_at', 'desc')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            Category::create([
                'name' => $request->name,
                'description' => $request->description,
                'is_active' => true,
            ]);

            return redirect()->route('admin.categories.index')
                ->with('success', 'Category created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'An error occurred while creating the category.')
                ->withInput();
        }
    }

    public function edit(Category $category)
    {
        return response()->json($category);
    }

    public function update(Request $request, Category $category)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $category->update([
                'name' => $request->name,
                'description' => $request->description,
                'is_active' => $request->has('is_active'),
            ]);

            return redirect()->route('admin.categories.index')
                ->with('success', 'Category updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'An error occurred while updating the category.')
                ->withInput();
        }
    }

    public function destroy(Category $category)
    {
        try {
            $category->delete();
            return redirect()->route('admin.categories.index')
                ->with('success', 'Category deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'An error occurred while deleting the category.');
        }
    }
}
