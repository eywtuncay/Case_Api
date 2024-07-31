<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DiscountResponseResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'orderId' => $this->order_id,
            'totalDiscount' => $this->total_discount,
            'discountedTotal' => $this->discounted_total,
            'discounts' => $this->discounts->map(function ($discount) {
                return [
                    'discountReason' => $discount->discount_reason,
                    'discountAmount' => $discount->discount_amount,
                    'subtotal' => $discount->subtotal,
                ];
            }),
        ];
    }
}

