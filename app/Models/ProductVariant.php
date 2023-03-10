<?php

namespace App\Models;

use App\Models\Product;
use App\Models\Variant;
use App\Models\ProductVariantPrice;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $guarded = [];
    protected $fillable = [
        'variant', 'variant_id', 'product_id'
    ];
    public function products()
    {
        return $this->belongsTo(Product::class);
    }
    public function products_variant_price()
    {
        return $this->hasMany(ProductVariantPrice::class,'id');
    }

    public function variants_func()
    {
        return $this->belongsTo(Variant::class);
    }

}
