<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    protected $fillable = [
        'name',
        'sku',
        'barcode',
        'stock',
        'list_price',
        'cost_unit',
        'total_value',
        'potencial_revenue',
        'potencial_profit',
        'profit_margin',
        'markup',
        'warehouse_id',
        'category_id',
        'variant_id',
        'vendor_id',

    ];



    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }


    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function variant()
    {
        return $this->belongsTo(Variant::class);
    }
}
