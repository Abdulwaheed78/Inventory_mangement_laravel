@extends('admin.includes.app')

@section('title')
    Product Edit
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Product Edit</h4>
                </div>
                <div class="card-body">
                    <form id="product_form" method="POST" action="{{ route('orders.update_product', $product->id) }}">
                        <input type="hidden" name="pid" value="{{$product->products->id}}">
                        <input type="hidden" name="npid" id="npid" >
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name">Product Name</label>
                                <input type="text" oninput="fetchDetails(this.value)"
                                    onfocusout="fetchRemainning(this.value)" name="name" id="name"
                                    value="{{ $product->products->name ?? '' }}" class="form-control">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="sku">SKU</label>
                                <input readonly type="text" name="sku" id="sku"
                                    value="{{ $product->products->sku ?? '' }}" class="form-control">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="category">Category</label>
                                <input readonly type="text" value="{{ $product->products->category->name ?? '' }}" name="category" id="category" class="form-control">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="warehouse">Warehouse</label>
                                <input readonly type="text" value="{{ $product->products->warehouse->name ?? '' }}" name="warehouse" id="warehouse" class="form-control">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="qty">Quantity</label>
                                <input  type="text" name="qty" id="qty"
                                    value="{{ $product->qty ?? '' }}" class="form-control">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="b_price">Price</label>
                                <input readonly type="text" name="price" id="price" value="{{ $product->price ?? '' }}"
                                    class="form-control">
                            </div>


                        </div>

                        <div class="card-action text-end">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <a href="{{ route('orders.edit', [$product->order_id]) }}"
                                class="btn btn-danger">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
    function fetchDetails(name) {
        $.ajax({
            url: '/orders/search',
            type: 'POST',
            data: {
                name: name,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $("#name").autocomplete({
                    source: response.data
                });
            },
            error: function(xhr) {
                console.log(xhr.responseText);
            }
        });
    }

    function fetchRemainning(name) {
        $.ajax({
            url: '/orders/loadother',
            type: 'POST',
            data: {
                name: name,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                let data = response.data;
                $("#sku").val(data['sku']);
                $("#category").val(data.category['name']);
                $("#warehouse").val(data.warehouse['name']+':'+data.warehouse['location']);
                $("#qty").val(data['stock_quantity']);
                $("#price").val(data['price']);
                $("#npid").val(data['id']);
            },

            error: function(xhr) {
                console.log(xhr.responseText);
            }
        });
    }
</script>
