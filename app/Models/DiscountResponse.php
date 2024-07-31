<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountResponse extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'total_discount', 'discounted_total'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function discounts()
    {
        return $this->hasMany(Discount::class);
    }
}
