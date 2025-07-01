<?php

namespace App\Services;

use App\Models\Price;
use Carbon\Carbon;

class PriceAggregator
{
    public function fetchAndStore()
    {
        $data1 = $this->fetchFromSource1();
        $data2 = $this->fetchFromSource2();

        $products = [];

        // Source 1
        foreach ($data1 as $item) {
            $products[$item['product_id']][] = $item['prices'];
        }

        // Source 2
        foreach ($data2 as $item) {
            $products[$item['id']][] = $item['competitor_data'];
        }

        // Aggregate lowest price for each product
        foreach ($products as $productId => $priceGroups) {
            $flat = collect($priceGroups)
                ->flatten(1)
                ->map(function ($entry) {
                    return [
                        'vendor' => $entry['vendor'] ?? $entry['name'],
                        'price' => $entry['price'] ?? $entry['amount']
                    ];
                });

            $lowest = $flat->sortBy('price')->first();

            // Insert or update lowest price
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

    // Mocked source 1 data with multiple products
    private function fetchFromSource1()
    {
        return [
            [
                "product_id" => "123",
                "prices" => [
                    [ "vendor" => "ShopA", "price" => 19.99 ],
                    [ "vendor" => "ShopB", "price" => 17.49 ]
                ]
            ],
            [
                "product_id" => "456",
                "prices" => [
                    [ "vendor" => "ShopC", "price" => 25.99 ],
                    [ "vendor" => "ShopD", "price" => 23.49 ]
                ]
            ]
        ];
    }

    // Mocked source 2 data with multiple products
    private function fetchFromSource2()
    {
        return [
            [
                "id" => "123",
                "competitor_data" => [
                    [ "name" => "VendorOne", "amount" => 20.49 ],
                    [ "name" => "VendorTwo", "amount" => 18.99 ]
                ]
            ],
            [
                "id" => "789",
                "competitor_data" => [
                    [ "name" => "VendorX", "amount" => 15.99 ],
                    [ "name" => "VendorY", "amount" => 14.49 ]
                ]
            ]
        ];
    }
}
