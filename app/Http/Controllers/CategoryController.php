<?php
namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        // Get all categories with their products
        return Category::with('products')->get();
    }

    public function store(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate(['name' => 'required|string|max:255']);

        // Create the category
        return Category::create($validated);
    }

    public function show(Category $category)
    {
        // Load the category with its products
        return $category->load('products');
    }

    public function update(Request $request, Category $category)
    {
        // Validate the incoming request
        $validated = $request->validate(['name' => 'required|string|max:255']);

        // Update the category
        $category->update($validated);

        return $category;
    }

    public function destroy(Category $category)
    {
        // Delete the category
        $category->delete();
        return response()->noContent();
    }
}
