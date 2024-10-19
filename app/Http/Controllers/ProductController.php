<?php
namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        // Get all products with their current prices and categories
        return Product::with('categories', 'currentPrice')->get();
    }

    public function store(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'img' => 'nullable|string',
            'description' => 'required|string',
        ]);

        // Create the product
        $product = Product::create($validated);

        // If categories were provided, sync them
        if ($request->has('categories')) {
            $product->categories()->sync($request->categories);
        }

        return response()->json($product, 201);
    }

    public function show(Product $product)
    {
        // Load product with its categories and current price
        return $product->load('categories', 'currentPrice');
    }

    public function update(Request $request, Product $product)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'img' => 'nullable|string',
            'description' => 'required|string',
        ]);

        // Update the product
        $product->update($validated);

        // If categories were provided, sync them
        if ($request->has('categories')) {
            $product->categories()->sync($request->categories);
        }

        return response()->json($product);
    }

    public function destroy(Product $product)
    {
        // Delete the product
        $product->delete();
        return response()->noContent();
    }
}
