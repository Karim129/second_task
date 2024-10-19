<?php
// app/Http/Controllers/OrderController.php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        // Return all orders with associated product
        return Order::with('product')->get();
    }

    public function store(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        // Fetch the product to ensure the quantity is available
        $product = Product::findOrFail($validated['product_id']);

        if ($product->quantity < $validated['quantity']) {
            return response()->json(['error' => 'Not enough stock available'], 400);
        }

        // Create the order
        $order = Order::create([
            'product_id' => $validated['product_id'],
            'quantity' => $validated['quantity'],
            'price' => $validated['price'],
        ]);

        // Decrease the product stock
        $product->decrement('quantity', $validated['quantity']);

        return response()->json($order, 201);
    }

    public function show(Order $order)
    {
        // Return the order with the associated product
        return $order->load('product');
    }

    public function update(Request $request, Order $order)
    {
        // Validate the request data
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        // Fetch the product for availability check
        $product = Product::findOrFail($order->product_id);

        if ($product->quantity < $validated['quantity']) {
            return response()->json(['error' => 'Not enough stock available'], 400);
        }

        // Update the order
        $order->update($validated);

        // Adjust stock accordingly
        $product->decrement('quantity', $validated['quantity'] - $order->quantity);

        return response()->json($order);
    }

    public function destroy(Order $order)
    {
        // Return stock if an order is cancelled
        $product = Product::findOrFail($order->product_id);
        $product->increment('quantity', $order->quantity);

        // Delete the order
        $order->delete();

        return response()->noContent();
    }
}
