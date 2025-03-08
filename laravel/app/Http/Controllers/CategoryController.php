<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    // GET /api/categories
    public function getCategories()
    { 
        $categories = Category::all();
        return response()->json($categories);
    }

    // POST /api/categories
    public function createCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = Category::create([
            'name' => $request->name,
        ]);

        return response()->json(['message' => 'Category created successfully', 'category' => $category], 201);
    }

    // GET /api/categories/{categoryId}
    public function getCategory($categoryId)
    {
        $category = Category::find($categoryId);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        return response()->json($category);
    }

    // PATCH /api/categories/{categoryId}
    public function updateCategory(Request $request, $categoryId)
    {
        $category = Category::find($categoryId);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category->update([
            'name' => $request->name,
        ]);

        return response()->json(['message' => 'Category updated successfully', 'category' => $category]);
    }

    // DELETE /api/categories/{categoryId}
    public function deleteCategory($categoryId)
    {
        $category = Category::find($categoryId);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $category->delete();

        return response()->json(['message' => 'Category deleted successfully']);
    }
}
