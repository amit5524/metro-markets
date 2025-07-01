<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    protected $fillable = ['product_id', 'vendor_name', 'price', 'fetched_at'];
}
