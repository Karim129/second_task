<?php
namespace App\Http\Controllers;

use App\Models\Price;
use Illuminate\Http\Request;

class PriceController extends Controller
{
    public function index()
    {
        // Get all prices with their related products
        return Price::with('product')->get();
    }

    public function store(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
            'price' => 'required|numeric|min:0',
        ]);

        // Create the price
        return Price::create($validated);
    }

    public function show(Price $price)
    {
        // Load the price with its related product
        return $price->load('product');
    }

    public function update(Request $request, Price $price)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
            'price' => 'required|numeric|min:0',
        ]);

        // Update the price
        $price->update($validated);

        return $price;
    }

    public function destroy(Price $price)
    {
        // Delete the price
        $price->delete();
        return response()->noContent();
    }
}
