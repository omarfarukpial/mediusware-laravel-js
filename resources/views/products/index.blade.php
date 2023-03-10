@extends('layouts.app')

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Products</h1>
    </div>


    <div class="card">
        <form action="{{route('product.search')}}" method="post" class="card-header">
            @csrf
            <div class="form-row justify-content-between">
                <div class="col-md-2">
                    <input type="text" name="title" placeholder="Product Title" class="form-control">
                </div>
                <div class="col-md-2">
                    {{-- <select name="variant" id="" class="form-control">
                        @foreach ($variant_item as $variants)
                            <optgroup label="{{ dd($variants->variants_func)}}">
                                @foreach ($variants as $variant)
                                    <option value="{{ $variant->id }}">{{ $variant->variant }}</option>
                                @endforeach
                            </optgroup>
                        @endforeach

                    </select> --}}
                  
                    <select name="variant_id" class="form-control">
                        <option value="">--Select a variant--</option>
                    
                        @foreach ($options as $optionGroup => $optionValues)
                            <optgroup label="{{ $optionGroup }}">
                                @foreach ($optionValues as $optionLabel => $optionValue)
                                    <option value="{{ $optionValue }}">{{ $optionLabel }}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                    
                    

                    
                </div>

                <div class="col-md-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Price Range</span>
                        </div>
                        <input type="text" name="price_from" aria-label="First name" placeholder="From" class="form-control">
                        <input type="text" name="price_to" aria-label="Last name" placeholder="To" class="form-control">
                    </div>
                </div>
                <div class="col-md-2">
                    <input type="date" name="date" placeholder="Date" class="form-control">
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary float-right"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </form>

        <div class="card-body">
            <div class="table-response">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Variant</th>
                        <th width="100px">Action</th>
                    </tr>
                    </thead>

                    <tbody>

                        @foreach($products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>{{ $product->title }} <br>Created at: {{ \Carbon\Carbon::parse($product->created_at)->format('d-M-Y')}}</td>
                            <td>{{$product->description}}</td>
                            <td>
                                
                                <dl class="row mb-0 productRow" style="height: 80px; overflow: hidden" id="collapse-{{ $product->id }}">

                                    <dt class="col-sm-4 pb-0">
                                        @foreach($product->prices as $price)
                                        
                                            @if($price->product_variant_1 !== null)                                                                              
                                            {{$price->product_variant_1->variant}}/
                                            @endif
                                            @if($price->product_variant_2 !== null)
                                            {{ $price->product_variant_2->variant }}/
                                            @endif
                                            @if($price->product_variant_3 !== null)
                                            {{ $price->product_variant_3->variant}}/
                                            @endif
                                            <br>
                                        @endforeach
                       
                                      

                                    </dt>
                                    <span class="col-sm-8">
                                    @foreach($product->prices as $price)
                                        <span class="row mb-0">
                                            <span class="col-sm-6 pb-0">Price : {{ number_format($price->price,2) }}</span>
                                            <span class="col-sm-6 pb-0">InStock : {{ number_format($price->stock,2) }}</span>
                                        </span>
                                    @endforeach
                                    </span>
                                </dl>
                                {{-- <button onclick="toggleRow({{ $product->id }})" class="btn btn-sm btn-link">Show more</button> --}}
                                <button onclick="$('#collapse-{{ $product->id }}').toggleClass('h-auto')" class="btn btn-sm btn-link">Show more</button>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('product.edit',$product) }}" class="btn btn-primary">Edit</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    
                    
                   

                    </tbody>
                    {{-- {{$Products->links()}} --}}

                </table>
            </div>

        </div>

       
        

    </div>
    <div class="pagination mt-2" style="display: flex; justify-content: space-between; align-items: center;">
        <div class="float-left">
          Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }}
        </div>
        <div class="float-right" style="display: flex; align-items: center;">
          {{ $products->links() }}
        </div>
        {{-- <div class="clearfix"></div> --}}
      </div>
{{-- 
      <script>
        function toggleRow(productId) {
        var target = '#collapse-' + productId;
        $(target).collapse('toggle');
        document.getElementById(elementId)
        }
    </script> --}}

      
   
    

@endsection
