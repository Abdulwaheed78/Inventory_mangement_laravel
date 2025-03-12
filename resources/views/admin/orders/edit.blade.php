@extends('admin.includes.app')
@section('title')
    Order Edit
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <form action="{{ route('orders.update', [$order->id]) }}" method="POST">
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
                                <input type="text" name="name" id="customer" onblur="loadDetails(this.value)" value="{{ $order->customer->name }}"
                                    class="form-control" required>
                            </div>

                            <div class="col-sm-6 mb-3">
                                <label for="phone"> Email:</label>
                                <input type="email" name="email" value="{{ $order->customer->email }}" id="email"
                                    class="form-control" required>
                            </div>

                            <div class="col-sm-6 mb-3">
                                <label for="phone">Phone:</label>
                                <input type="number" name="phone" value="{{ $order->customer->phone }}" id="phone"
                                    class="form-control">
                            </div>

                            <div class="col-sm-6 mb-3">
                                <label for="customer">Address:</label>
                                <textarea name="address" id="address" class="form-control">{{ $order->customer->address }}</textarea>
                            </div>

                            <hr>

                            {{-- Total Amount --}}
                            <div class="col-sm-6 mb-3">
                                <label for="date">Order Date:</label>
                                <input type="date" name="date" id="date" value="{{ $order->order_date }}"
                                    class="form-control" required>
                            </div>

                            {{-- Stage Selection --}}
                            <div class="col-sm-6 mb-3">
                                <label for="stage">Stage:</label>
                                <select name="stage" id="stage" class="form-select" required>
                                    <option value="0"></option>
                                    @foreach ($stage as $s)
                                        <option value="{{ $s->id }}"
                                            @if ($order->stage_id == $s->id) selected @endif>{{ $s->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Order Status --}}
                            <div class="col-sm-6 mb-3">
                                <label for="status">Payment Status:</label>
                                <select name="status" id="status" class="form-select" required>
                                    <option value="Pending" @if ($order->status == 'Pending') selected @endif>Pending
                                    </option>
                                    <option value="Received" @if ($order->status == '') selected @endif>Received
                                    </option>
                                </select>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label for="customer">Total Amount:</label>
                                <input type="text" name="amount" id="amount" value="{{ $order->total_amount }}"
                                    class="form-control" required>
                            </div>
                        </div>

                        <h5 class="card-title">Products</h5>

                        <table class="table table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Basic Info</th>
                                    <th>Prices</th>
                                    <th>Quantity</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $key => $product)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            <strong>Name:</strong> {{ $product->products->name ?? 'N/A' }}<br>
                                            <strong>SKU:</strong> {{ $product->products->sku ?? 'N/A' }}<br>
                                            <strong>Category:</strong>
                                            {{ $product->products->category->name ?? 'N/A' }}<br>
                                            <strong>Warehouse:</strong>
                                            {{ $product->products->warehouse->name ?? 'N/A' }}
                                        </td>
                                        <td>
                                            <strong>Per Piece:</strong> {{ $product->price ?? 'N/A' }}<br>
                                            <hr>
                                            <strong>Total:</strong>
                                            {{ $product->products->price * $product->qty ?? 'N/A' }} Rs
                                        </td>
                                        <td>{{ $product->qty ?? 'N/A' }} Pieces</td>
                                        <td>
                                            <a href="{{ route('orders.edit_product', $product->id) }}"
                                                class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <a href="{{ route('orders.del_product', $product->id) }}"
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
                        <a href="{{ route('orders.index') }}" class="btn btn-danger">Cancel</a>
                        <a href="{{ route('orders.add_product', [$order->id]) }}" class="btn btn-success">Add Product</a>
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
        var customers = @json($customer);

        $("#customer").autocomplete({
            source: customers
        });
    });

    function loadDetails(name) {
        $.ajax({
            url: "/orders/getdetails", // Replace with the correct route if needed
            type: "POST",
            data: {
                name: name,
                _token: $('meta[name="csrf-token"]').attr('content')
            },

            success: function(response) {
                if (response && response.email) { // Ensure response is not null and has an email field
                    $("#email").val(response.email);
                    $("#email").prop("readonly", true);
                    $("#phone").val(response.phone);
                    $("#address").text(response.address);
                } else {
                    $("#email").prop("readonly", false); // Make it editable if response is null or empty
                    $("#email").val('');
                    $("#phone").val('');
                    $("#address").text('');
                }

            },
            error: function(xhr, status, error) {
                console.error("Error:", error);
            }
        });
    }
</script>
