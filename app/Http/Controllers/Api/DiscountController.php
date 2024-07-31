<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\DiscountService;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    protected $discountService; // DiscountService sınıfını tutacak bir özellik

    // Controller'ı başlatırken DiscountService sınıfını dependency injection ile alır
    public function __construct(DiscountService $discountService)
    {
        $this->discountService = $discountService; // Alınan DiscountService'i sınıf özelliğine atar
    }

    // Belirtilen sipariş için indirimleri hesaplar
    public function calculateDiscounts($orderId)
    {
        // Belirtilen ID'ye sahip siparişi ve ilişkili ürünleri bulur
        $order = Order::with('items.product')->findOrFail($orderId);
        
        // DiscountService kullanarak indirimleri uygular
        $result = $this->discountService->applyDiscounts($order);

        // Sonuçları JSON formatında geri döner
        return response()->json($result);
    }
}


/*

--- AÇIKLAMALAR ---

DiscountController, belirtilen bir siparişin indirimlerini hesaplamak için kullanılır.
DiscountService, indirim hesaplama mantığını içerir ve bu hesaplamalar DiscountController üzerinden çağrılır.
Kullanıcı belirtilen URL'ye bir istek gönderdiğinde, calculateDiscounts metodu çalışır ve siparişin indirimleri hesaplanır, sonuçlar JSON formatında geri döner.

*/
