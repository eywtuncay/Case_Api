<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;

    protected $fillable = ['discount_response_id', 'discount_reason', 'discount_amount', 'subtotal'];

    public function discountResponse()
    {
        return $this->belongsTo(DiscountResponse::class);
    }
}
