@extends('admin.includes.app')
@section('title')
    Product Edit
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <form action="{{ route('products.update',[$product->id]) }}" method="POST">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Product Edit</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @csrf
                            @method('PUT')
                            <div class="col-sm-6 mb-3">
                                <label for="name"> Name: </label>
                                <input type="text" value="{{$product->name}}" name="name" id="name" class="form-control" required>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label for="name"> SKU: </label>
                                <input type="text" name="sku" value="{{$product->sku}}" id="sku" class="form-control" required>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label for="name"> Category: </label>
                                <select name="category" id="category" class="form-select">
                                    <option value="0"></option>
                                    @foreach ($category as $Index => $cat)
                                        <option value="{{ $cat->id }}" @if ($product->category_id == $cat->id) selected

                                        @endif>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label for="name"> Warehouse: </label>
                                <select name="warehouse" id="warehouse" class="form-select">
                                    <option value="0"></option>
                                    @foreach ($warehouse as $Index => $war)
                                        <option value="{{ $war->id }}" @if ($product->warehouse_id == $war->id) selected

                                            @endif>{{ $war->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label for="name"> Stock Quantity: </label>
                                <input type="text" name="qty" value="{{$product->stock_quantity}}" id="qty" class="form-control" required>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label for="name"> Price: </label>
                                <input type="number" name="price" value="{{$product->price}}" id="price" class="form-control" required>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label for="name"> Status: </label>
                                <select name="status" id="status" class="form-select">
                                    <option value=""></option>
                                    <option value="active" @if ($product->status == 'active') selected

                                        @endif>Active</option>
                                    <option value="passive" @if ($product->status == 'passive') selected

                                        @endif>Passive</option>
                                </select>
                            </div>

                            <div class="col-sm-12">
                                <label for="description"> Description: </label>
                                <textarea name="description" id="description" class="form-control">{{$product->description}}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="card-action text-end">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <a href="{{ route('products.index') }}" class="btn btn-danger">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
