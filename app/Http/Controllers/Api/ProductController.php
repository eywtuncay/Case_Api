<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Validator;


class ProductController extends Controller
{
    public function index()
    {
        $products = Product::get();
        if($products->count() > 0)
        {
            return ProductResource::collection($products);
        }
        else
        {
            return response()->json(['message' => 'Kayıt Yok.'],200);
        }
    }

    public function store(Request $request)
	{
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'category' => 'required|integer',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Tüm alanlar zorunludur.',
                'error' => $validator->errors(),
            ], 422);
        }

        $product = Product::create([
            'name' => $request->name,
            'category' => $request->category,
            'price' => $request->price,
            'stock' => $request->stock,
        ]);

        return response()->json([
            'message' => 'Ürün Başarıyla Eklendi',
            'data' => new ProductResource($product)
        ], 201);
    }


    public function show(Product $product)
    {
        return new ProductResource($product);
    }


    
    public function update(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'category' => 'required|integer',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Tüm alanlar zorunludur.',
                'error' => $validator->errors(),
            ], 422);
        }
    
        $product->update([
            'name' => $request->name,
            'category' => $request->category,
            'price' => $request->price,
            'stock' => $request->stock,
        ]);
    
        return response()->json([
            'message' => 'Ürün Başarıyla Güncellendi',
            'data' => new ProductResource($product)
        ], 201);
    }


    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json([
            'message' => 'Ürün Başarıyla Silindi',
        ], 201);
    }
}
