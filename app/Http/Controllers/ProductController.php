<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Product;
use App\Models\Variant;
use Illuminate\Http\Request;
use App\Models\ProductVariant;
use App\Models\ProductVariantPrice;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {
        $products = Product::with(['prices', 'product_variants'])->paginate(10);
        // $product_variants = ProductVariant::all();
        
        $variants = Variant::with('product_variants')->get();

        $options = [];

        foreach ($variants as $variant) {
            $optionGroup = $variant->title;
            $options[$optionGroup] = [];

            foreach ($variant->product_variants as $productVariant) {
                $options[$optionGroup][$productVariant->variant] = $productVariant->id;
            }
        }


      


        

        return view('products.index', compact('products', 'variants', 'options'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function create()
    {
        $variants = Variant::all();
        return view('products.create', compact('variants'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {

        

        try {

     
            $product = Product::create(['title' => $request->product_name, 'sku' => $request->product_sku, 'description' =>$request->product_description]);
            // $product_image = new ProductImage();
            // if($request->hasFile('product_image')){
            //     foreach($request->file('product_image') as $img){
            //         $file = $img;
            //         $filename = time().'-'.uniqid().'.'.$file->getClientOriginalExtension();
            //         $file->move(public_path('uploads/products'), $filename);
            //         // save filename to database
            //         $product_image->create(['product_id' => $product->id, 'file_path' => $filename]);
            //     }
            // }

            $product_variant = new ProductVariant();
            foreach($request->product_variant as $variant){
               
                // $variant = json_decode($variant);
                foreach($variant->variant as $tag){
                    $product_variant->create(['variant'=>$tag, 'variant_id'=>$variant->option, 'product_id'=>$product->id]);
                }
                
            }

            foreach($request->product_preview as $price){
                $pv_prices = new ProductVariantPrice();
                // $price = json_decode($price);
                $attrs = explode("/", $price->title);

                $product_variant_ids= [];
                for( $i=0; $i<count($attrs)-1; $i++){
                    $product_variant_ids[] = ProductVariant::select('id')->where('variant', $attrs[$i])->latest()->first()->id;
                }

                for( $i=1; $i<=count($product_variant_ids); $i++){
                    $pv_prices->{'product_variant_'.$i} = $product_variant_ids[$i-1];
                }
                $pv_prices->price = $price->price;
                $pv_prices->stock = $price->stock;
                $pv_prices->product_id = $product->id;
                $pv_prices->save();
            }
        } catch (Exception $e) {
            return response($e, 500);
        }
        return response('product added successfully');

    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function show($product)
    {
        

    }

    // For Search Purpose
    


    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        // $product_info = Product::findOrFail($id);
        // return view('products.edit', compact('product_info'));
        $product = Product::with(['prices','product_variants'])->find($product->id);
        $variants = Variant::all();
        return view('products.edit', compact('variants', 'product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        $product->title = $request->input('product_name');
        $product->sku = $request->input('product_sku');
        $product->description = $request->input('product_description');
        $product->save();


        $products = Product::with(['prices', 'product_variants'])->paginate(10);
        // $product_variants = ProductVariant::all();
        
        $variants = Variant::with('product_variants')->get();

        $options = [];

        foreach ($variants as $variant) {
            $optionGroup = $variant->title;
            $options[$optionGroup] = [];

            foreach ($variant->product_variants as $productVariant) {
                $options[$optionGroup][$productVariant->variant] = $productVariant->id;
            }
        }


      


        

        return view('products.index', compact('products', 'variants', 'options'));
       
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
