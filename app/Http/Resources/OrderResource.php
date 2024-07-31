<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'customerId' => $this->customer_id,
            'items' => $this->items->map(function ($item) {
                return [
                    'productId' => $item->product_id,
                    'quantity' => $item->quantity,
                    'unitPrice' => $item->unit_price,
                    'total' => $item->total,
                ];
            }),
            'total' => $this->total,
        ];
    }
}
