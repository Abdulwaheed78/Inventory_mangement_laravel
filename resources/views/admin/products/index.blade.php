@extends('admin.includes.app')
@section('title')
    Products
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4 class="card-title">Products</h4>
                    <a href="{{ route('products.create') }}" class="btn btn-primary">Add + </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="basic-datatables" class="display table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>SKU</th>
                                    <th>Category</th>
                                    <th>Quantity</th>
                                    <th>Warehouse</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($products as $Index => $product)
                                    <tr>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->sku }}</td>
                                        <td>
                                            @if (!empty($product->category) && !empty($product->category->name))
                                                {{ $product->category->name }}
                                            @else
                                                N/A
                                            @endif
                                        </td>

                                        <td>{{ $product->stock_quantity }}</td>
                                        <td>
                                            @if (!empty($product->warehouse) && !empty($product->warehouse->name))
                                                {{ $product->warehouse->name }}
                                            @else
                                                N/A
                                            @endif
                                        </td>

                                        <td>{{ $product->price }}</td>
                                        <td>{{ $product->status }}</td>
                                        <td>{{ date('d M Y', strtotime($product->created_at)) }}</td>
                                        <td>
                                            <a href="{{ route('products.edit', [$product->id]) }}"
                                                class="btn btn-round btn-link"><i class="fa fa-edit "></i>
                                            </a>
                                            <form action="{{ route('products.delete', [$product->id]) }}" method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this Product?');"
                                                style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-round btn-link">
                                                    <i class="fa fa-trash text-danger"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
