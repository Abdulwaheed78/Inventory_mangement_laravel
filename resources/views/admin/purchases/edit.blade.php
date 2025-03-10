@extends('admin.includes.app')
@section('title')
    Order Edit
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <form action="{{ route('purchases.update', [$purchase->id]) }}" method="POST">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Order Edit</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @csrf
                            @method('PUT')
                            {{-- Customer Name --}}
                            <div class="col-sm-6 mb-3">
                                <label for="customer">Customer Name:</label>
                                <input type="text" name="name" id="customer" value="{{ $purchase->supplier->name }}"
                                    class="form-control" required>
                            </div>

                            {{-- Total Amount --}}
                            <div class="col-sm-6 mb-3">
                                <label for="date">Order Date:</label>
                                <input type="date" name="date" id="date" value="{{ $purchase->purchase_date }}"
                                    class="form-control" required>
                            </div>

                            {{-- Order Status --}}
                            <div class="col-sm-6 mb-3">
                                <label for="status">Payment Status:</label>
                                <select name="status" id="status" class="form-select" required>
                                    <option value="Pending" @if ($purchase->status == 'Pending') selected @endif>Pending
                                    </option>
                                    <option value="Paid" @if ($purchase->status == '') selected @endif>Paid</option>
                                </select>
                            </div>

                            <div class="col-sm-6 mb-3">
                                <label for="customer">Total Amount:</label>
                                <input type="text" name="amount" id="amount" value="{{ $purchase->total_amount }}"
                                    class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <h5 class="card-title p-4">Products</h5>

                    <div class="m-4">
                        <table class="table table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Basic Info</th>
                                    <th>Prices</th>
                                    <th>Stock Quantity</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            {{-- {{dd($products)}} --}}
                            <tbody>
                                @foreach ($products as $key => $product)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            <strong>Name:</strong> {{ $product->products->name ?? 'N/A' }}<br>
                                            <strong>SKU:</strong> {{ $product->products->sku ?? 'N/A' }}<br>
                                            <strong>Category:</strong> {{ $product->products->category->name ?? 'N/A' }}<br>
                                            <strong>Warehouse:</strong> {{ $product->products->warehouse->name ?? 'N/A' }}
                                        </td>
                                        <td>
                                            <strong>Buy:</strong> {{ $product->price ?? 'N/A' }} Per Piece<br>
                                            <strong>Sell:</strong> {{ $product->products->price ?? 'N/A' }} Per Piece
                                            <hr>
                                            <strong>Total Buy:</strong> {{ $product->price * $product->qty ?? 'N/A' }} Rs<br>
                                            <strong>Total Sell:</strong> {{ $product->products->price * $product->qty ?? 'N/A' }} Rs
                                        </td>
                                        <td>{{ $product->products->stock_quantity ?? 'N/A' }} Pieces</td>
                                        <td>{{ $product->products->status ?? 'N/A' }}</td>
                                        <td>
                                            <a href="{{ route('purchases.edit_product', $product->id) }}"
                                                class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i> <!-- Edit Icon -->
                                            </a>

                                            <a href="{{ route('purchases.del_product', $product->id) }}"
                                                class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?');">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="card-action text-end">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <a href="{{ route('purchases.index') }}" class="btn btn-danger">Cancel</a>
                        <a href="{{ route('purchases.add_product', [$purchase->id]) }}" class="btn btn-success">Add
                            Product</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

{{-- Load jQuery and jQuery UI for Autocomplete --}}
<link rel="stylesheet" href="https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://code.jquery.com/ui/1.14.1/jquery-ui.js"></script>

<script>
    jQuery.noConflict();
    jQuery(document).ready(function($) {
        var customers = @json($supplier);

        $("#customer").autocomplete({
            source: customers
        });
    });
</script>
