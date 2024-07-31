<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;

class DiscountService
{
    // Verilen bir siparişe tüm indirimleri uygular
    public function applyDiscounts(Order $order)
    {
        $totalDiscount = 0; // Toplam indirim miktarını başlatır
        $discountDetails = []; // İndirim detaylarını saklamak için bir dizi başlatır

        // Toplu indirim kuralını uygula
        $bulkDiscount = $this->applyBulkDiscount($order);
        $totalDiscount += $bulkDiscount['totalDiscount'] ?? 0; // Toplu indirimi toplam indirime ekler
        $discountDetails = array_merge($discountDetails, $bulkDiscount['discountDetails'] ?? []); // İndirim detaylarını birleştirir

        // Kategori indirimi kuralını uygula
        $categoryDiscount = $this->applyCategoryDiscount($order);
        $totalDiscount += $categoryDiscount['totalDiscount'] ?? 0; // Kategori indirimini toplam indirime ekler
        $discountDetails = array_merge($discountDetails, $categoryDiscount['discountDetails'] ?? []); // İndirim detaylarını birleştirir

        // Sipariş toplamı 1000 TL üzerindeyse %10 indirim uygula
        if ($order->total >= 1000) {
            $orderDiscount = $order->total * 0.10; // Toplam sipariş tutarının %10'unu hesaplar
            $totalDiscount += $orderDiscount; // Bu indirimi toplam indirime ekler
            $discountDetails[] = [
                'discountReason' => '10_PERCENT_OVER_1000', // İndirim nedeni
                'discountAmount' => $orderDiscount, // İndirim miktarı
                'subtotal' => $order->total // Ara toplam
            ];
        }

        $discountedTotal = $order->total - $totalDiscount; // İndirim sonrası toplam tutarı hesaplar

        // İndirim detaylarını ve toplamları döndürür
        return [
            'orderId' => $order->id,
            'discounts' => $discountDetails,
            'totalDiscount' => $totalDiscount,
            'discountedTotal' => max(0, $discountedTotal) // Toplamın negatif olmamasını sağlar
        ];
    }

    // Toplu indirim kuralını uygula (Kategori 2'den 6 veya daha fazla ürün alındığında)
    private function applyBulkDiscount(Order $order)
    {
        $totalDiscount = 0; // Toplu indirim miktarını başlatır
        $discountDetails = []; // Toplu indirim detaylarını saklamak için bir dizi başlatır

        foreach ($order->items as $item) { // Siparişteki her bir ürünü döngüye sokar
            $product = Product::find($item->productId); // Ürünü ID'sine göre bulur

            if ($product && $product->category == 2 && $item->quantity >= 6) {
                // Ürün kategori 2'de ve miktarı 6 veya daha fazlaysa
                $discountAmount = $item->unitPrice; // Bir birim fiyatı kadar indirim yapılır
                $totalDiscount += $discountAmount; // İndirim toplam indirime eklenir
                $discountDetails[] = [
                    'discountReason' => 'BUY_6_GET_1_FREE', // İndirim nedeni
                    'discountAmount' => $discountAmount, // İndirim miktarı
                    'subtotal' => $order->total // Ara toplam
                ];
            }
        }

        // Toplu indirim detaylarını ve toplamı döndürür
        return ['totalDiscount' => $totalDiscount, 'discountDetails' => $discountDetails];
    }

    // Kategori indirimi kuralını uygula (Kategori 1'den iki veya daha fazla ürün alındığında en ucuz ürüne %20 indirim)
    private function applyCategoryDiscount(Order $order)
    {
        $totalDiscount = 0; // Kategori indirim miktarını başlatır
        $discountDetails = []; // Kategori indirim detaylarını saklamak için bir dizi başlatır
        $category1Products = []; // Kategori 1 ürünlerini saklamak için bir dizi başlatır

        foreach ($order->items as $item) { // Siparişteki her bir ürünü döngüye sokar
            $product = Product::find($item->productId); // Ürünü ID'sine göre bulur

            if ($product && $product->category == 1) {
                // Ürün kategori 1'deyse
                $category1Products[] = $item; // Ürünü kategori 1 ürünleri dizisine ekler
            }
        }

        if (count($category1Products) >= 2) {
            // Eğer kategori 1'den 2 veya daha fazla ürün varsa
            usort($category1Products, function ($a, $b) {
                return $a->unitPrice <=> $b->unitPrice; // Ürünleri birim fiyatına göre artan sırada sıralar
            });

            $cheapestItem = $category1Products[0]; // En ucuz ürünü alır
            $discountAmount = $cheapestItem->unitPrice * 0.20; // En ucuz ürüne %20 indirim uygular
            $totalDiscount += $discountAmount; // Bu indirimi toplam indirime ekler
            $discountDetails[] = [
                'discountReason' => '20_PERCENT_OFF_CHEAPEST_CATEGORY_1_ITEM', // İndirim nedeni
                'discountAmount' => $discountAmount, // İndirim miktarı
                'subtotal' => $order->total // Ara toplam
            ];
        }

        // Kategori indirim detaylarını ve toplamı döndürür
        return ['totalDiscount' => $totalDiscount, 'discountDetails' => $discountDetails];
    }
}


/*

    --- AÇIKLAMALAR ---

applyDiscounts metodu:
    Bu metod, verilen bir siparişe tüm indirimleri uygular.
    Toplam indirimi ve indirim detaylarını tutan değişkenler oluşturulur.
    applyBulkDiscount ve applyCategoryDiscount metodları çağrılarak ilgili indirimler uygulanır ve toplam indirime eklenir.
    Sipariş toplamı 1000 TL üzerinde ise %10 indirim uygulanır.
    Toplam indirim ve indirim sonrası tutar hesaplanarak döndürülür.

applyBulkDiscount metodu:
    Bu metod, kategori 2'deki ürünlerden 6 veya daha fazla adet satın alındığında bir adet ücretsiz olarak verilmesi indirimini uygular.
    Sipariş ürünleri döngüye sokularak, kategori 2'deki ürünlerin miktarları kontrol edilir ve indirim hesaplanır.
    İndirim detayları ve toplam indirim döndürülür.

applyCategoryDiscount metodu:
    Bu metod, kategori 1'deki ürünlerden iki veya daha fazla adet satın alındığında en ucuz ürüne %20 indirim uygular.
    Sipariş ürünleri döngüye sokularak, kategori 1'deki ürünler toplanır ve en ucuz ürün bulunur.
    En ucuz ürüne %20 indirim uygulanır, indirim detayları ve toplam indirim döndürülür.

*/


