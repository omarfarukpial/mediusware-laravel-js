<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Variant;
use Illuminate\Http\Request;
use App\Models\ProductVariant;
use App\Http\Controllers\Controller;

class ProductSearchController extends Controller
{
    public function search(Request $request)
    {
       

        // dd('Hello');


        $title = $request->title;
        $variant = $request->variant_id;
        $price_from = $request->price_from;
        $price_to = $request->price_to;
        $date = $request->date;
        // $vp = [$price_from, $price_to, $variant];
        $vp = [$price_from, $price_to];
        $product_variants = ProductVariant::all();
        try{
            $products = Product::with('prices')->when($title, function ($query, $title) {
                    return $query->where('title', 'like', '%'.$title.'%');
                })->when($date, function ($query, $date) {
                    return $query->whereDate('created_at', $date);
                })->whereHas('prices', function($q) use($vp){

                    $price_from = $vp[0] ;
                    $price_to = $vp[1] ;
                    // $variant = $vp[2] ;

                    // $q->when($price_from, function ($query, $price_from) {
                    //     return $query->where('price', '>=', intval($price_from));
                    // })->when($price_to, function ($query, $price_to) {
                    //     return $query->where('price', '<=', intval($price_to));
                    // })->when($variant, function ($query, $variant) {
                    //     return $query->whereRaw("(product_variant_one = $variant or product_variant_two = $variant or product_variant_three = $variant)");
                    // });
                    $q->when($price_from, function ($query, $price_from) {
                        return $query->where('price', '>=', intval($price_from));
                    })->when($price_to, function ($query, $price_to) {
                        return $query->where('price', '<=', intval($price_to));
                    });
                })->paginate(5);
            $products->appends($request->all());

            $variants = Variant::with('product_variants')->get();

            $options = [];

            foreach ($variants as $variant) {
                $optionGroup = $variant->title;
                $options[$optionGroup] = [];

                foreach ($variant->product_variants as $productVariant) {
                    $options[$optionGroup][$productVariant->variant] = $productVariant->id;
                }
            }


        } catch (Exception $e) {
            return $e->getMessage();
        }
        // dd($products);
        return view('products.index', compact('products','variants', 'options'));
        
    }
}
