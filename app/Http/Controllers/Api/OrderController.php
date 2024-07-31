<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('items')->get();

        return OrderResource::collection($orders);
    }

    public function store(Request $request)
    {
        try {
            $orderData = $request->validate([
                'customer_id' => 'required|exists:customers,id',
                'total' => 'required|numeric',
                'items' => 'required|array',
                'items.*.product_id' => 'required|exists:products,id',
                'items.*.quantity' => 'required|integer|min:1',
                'items.*.unit_price' => 'required|numeric',
                'items.*.total' => 'required|numeric',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Validation failed',
                'message' => $e->errors()
            ], 422);
        }

        foreach ($orderData['items'] as $item) {
            $product = Product::find($item['product_id']);
            if ($product->stock < $item['quantity']) {
                return response()->json([
                    'error' => 'Not enough stock for product ID: ' . $item['product_id']
                ], 400);
            }
        }

        $order = Order::create([
            'customer_id' => $orderData['customer_id'],
            'total' => $orderData['total'],
        ]);

        foreach ($orderData['items'] as $item) {
            $order->items()->create([
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total' => $item['total'],
            ]);

            $product = Product::find($item['product_id']);
            $product->stock -= $item['quantity'];
            $product->save();
        }

        return new OrderResource($order);
    }

    public function show($id)
    {
        $order = Order::with('items')->findOrFail($id);

        return new OrderResource($order);
    }

    public function update(Request $request, $id)
    {
        try {
            $orderData = $request->validate([
                'customer_id' => 'required|exists:customers,id',
                'total' => 'required|numeric',
                'items' => 'required|array',
                'items.*.product_id' => 'required|exists:products,id',
                'items.*.quantity' => 'required|integer|min:1',
                'items.*.unit_price' => 'required|numeric',
                'items.*.total' => 'required|numeric',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Validation failed',
                'message' => $e->errors()
            ], 422);
        }

        $order = Order::findOrFail($id);
        $order->update([
            'customer_id' => $orderData['customer_id'],
            'total' => $orderData['total'],
        ]);

        $order->items()->delete();

        foreach ($orderData['items'] as $item) {
            $product = Product::find($item['product_id']);
            if ($product->stock < $item['quantity']) {
                return response()->json([
                    'error' => 'Not enough stock for product ID: ' . $item['product_id']
                ], 400);
            }

            $order->items()->create([
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total' => $item['total'],
            ]);

            $product->stock -= $item['quantity'];
            $product->save();
        }

        return new OrderResource($order);
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);

        $order->items()->delete();
        $order->delete();

        return response()->json(['message' => 'Order başarıyla silindi.'], 200);
    }
}
