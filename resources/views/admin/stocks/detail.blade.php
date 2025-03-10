@extends('admin.includes.app')

@section('title')
    Stock Detail
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Stock Detail</h4>
                </div>

                <!-- Wrapped table inside a div with padding -->
                <div class="p-5">
                    @if (isset($categoryTotals))
                        <!-- Show total products & stock per category -->
                        <table class="table">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th>#</th>
                                    <th>Category</th>
                                    <th>Total Products</th>
                                    <th>Total Stock</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($categoryTotals as $key => $category)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $category->category->name ?? 'N/A' }}</td>
                                        <td>{{ $category->total_products }}</td>
                                        <td>{{ $category->total_stock }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <!-- Show product details when a category is selected -->
                        <table class="table">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th>#</th>
                                    <th>Product Name</th>
                                    <th>Category</th>
                                    <th>Stock Quantity</th>
                                    <th>Warehouse</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $totalStock = 0; @endphp
                                @foreach ($products as $key => $product)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->category->name ?? 'N/A' }}</td>
                                        <td>{{ $product->stock_quantity }}</td>
                                        <td>{{ $product->warehouse->name ?? 'N/A' }}</td>
                                    </tr>
                                    @php $totalStock += $product->stock_quantity; @endphp
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-right">Total Stock:</th>
                                    <th>{{ $totalStock }}</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    @endif
                    <div class="text-end">
                        <!-- Refresh Button -->
                        <form action="{{ route('stock_detail') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-primary">Refresh</button>
                        </form>

                        <!-- Cancel Button -->
                        <a href="{{ route('stocks') }}" class="btn btn-danger">Cancel</a>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
