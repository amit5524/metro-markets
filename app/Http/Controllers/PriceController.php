<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Price;

class PriceController extends Controller
{
    public function index() {
        return Price::all(['product_id', 'vendor_name as vendor', 'price', 'fetched_at']);
    }

    public function show($id) {
        $price = Price::where('product_id', $id)->firstOrFail();
        return [
            'product_id' => $price->product_id,
            'vendor' => $price->vendor_name,
            'price' => $price->price,
            'fetched_at' => $price->fetched_at
        ];
    }
}
