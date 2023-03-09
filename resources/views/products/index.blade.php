@extends('layouts.app')

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Products</h1>
    </div>


    <div class="card">
        <form action="" method="get" class="card-header">
            <div class="form-row justify-content-between">
                <div class="col-md-2">
                    <input type="text" name="title" placeholder="Product Title" class="form-control">
                </div>
                <div class="col-md-2">
                    <select name="variant" id="" class="form-control">

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
                        <th width="150px">Action</th>
                    </tr>
                    </thead>

                    <tbody>

                        @foreach($Products as $key=>$product)
                        <tr>
                            <td>{{ $key +1 }}</td>
                            <td>{{ $product->title }} <br>Created at: {{ \Carbon\Carbon::parse($product->created_at)->format('d-M-Y')}}</td>
                            <td>{{ nl2br($product->description) }}</td>
                            <td></td>
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
          Showing {{ $Products->firstItem() }} to {{ $Products->lastItem() }} of {{ $Products->total() }}
        </div>
        <div class="float-right" style="display: flex; align-items: center;">
          {{ $Products->links() }}
        </div>
        {{-- <div class="clearfix"></div> --}}
      </div>

      
   
    

@endsection
