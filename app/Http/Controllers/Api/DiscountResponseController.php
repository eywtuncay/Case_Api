<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DiscountResponse;
use App\Models\Order; // Order modelini dahil edin
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\DiscountResponseResource;

class DiscountResponseController extends Controller
{
    public function index()
    {
        return DiscountResponseResource::collection(DiscountResponse::with('discounts')->get());
    }

    public function show($id)
    {
        return new DiscountResponseResource(DiscountResponse::with('discounts')->findOrFail($id));
    }

    public function store(Request $request)
    {
        // Verileri doğrulama
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:orders,id',
            'total_discount' => 'required|numeric',
            'discounted_total' => 'required|numeric',
            'discounts' => 'required|array',
            'discounts.*.discount_reason' => 'required|string',
            'discounts.*.discount_amount' => 'required|numeric',
            'discounts.*.subtotal' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Verileri oluşturma
        $discountResponse = DiscountResponse::create($request->only('order_id', 'total_discount', 'discounted_total'));

        foreach ($request->discounts as $discount) {
            $discountResponse->discounts()->create($discount);
        }

        return new DiscountResponseResource($discountResponse->load('discounts'));
    }

    public function update(Request $request, $id)
    {
        // Verileri doğrulama
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:orders,id',
            'total_discount' => 'required|numeric',
            'discounted_total' => 'required|numeric',
            'discounts' => 'required|array',
            'discounts.*.discount_reason' => 'required|string',
            'discounts.*.discount_amount' => 'required|numeric',
            'discounts.*.subtotal' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Verileri güncelleme
        $discountResponse = DiscountResponse::findOrFail($id);

        $discountResponse->update($request->only('order_id', 'total_discount', 'discounted_total'));

        // Eski indirimleri sil
        $discountResponse->discounts()->delete();

        // Yeni indirimleri ekle
        foreach ($request->discounts as $discount) {
            $discountResponse->discounts()->create($discount);
        }

        return new DiscountResponseResource($discountResponse->load('discounts'));
    }

    public function destroy($id)
    {
        $discountResponse = DiscountResponse::findOrFail($id);
        $discountResponse->delete();

        return response()->json(['message' => 'Discount response and its discounts have been deleted successfully.'], 200);
    }
}
