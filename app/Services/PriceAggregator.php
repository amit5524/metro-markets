<?php
namespace App\Services;

use App\Models\Price;
use Carbon\Carbon;

class PriceAggregator {
    public function fetchAndStore() {
        $data1 = $this->fetchFromSource1();
        $data2 = $this->fetchFromSource2();

        $products = [];

        foreach ($data1 as $item) {
            $products[$item['product_id']][] = $item['prices'];
        }

        foreach ($data2 as $item) {
            $products[$item['id']][] = $item['competitor_data'];
        }

        foreach ($products as $productId => $priceGroups) {
            $flat = collect($priceGroups)->flatten(1)->map(function ($entry) {
                return [
                    'vendor' => $entry['vendor'] ?? $entry['name'],
                    'price' => $entry['price'] ?? $entry['amount']
                ];
            });

            $lowest = $flat->sortBy('price')->first();

            Price::updateOrCreate(
                ['product_id' => $productId],
                [
                    'vendor_name' => $lowest['vendor'],
                    'price' => $lowest['price'],
                    'fetched_at' => Carbon::now()
                ]
            );
        }
    }

    private function fetchFromSource1() {
        return [
            [
                "product_id" => "123",
                "prices" => [
                    [ "vendor" => "ShopA", "price" => 19.99 ],
                    [ "vendor" => "ShopB", "price" => 17.49 ]
                ]
            ]
        ];
    }

    private function fetchFromSource2() {
        return [
            [
                "id" => "123",
                "competitor_data" => [
                    [ "name" => "VendorOne", "amount" => 20.49 ],
                    [ "name" => "VendorTwo", "amount" => 18.99 ]
                ]
            ]
        ];
    }
}
