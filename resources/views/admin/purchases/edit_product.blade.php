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
                    <form id="product_form" method="POST" action="{{ route('purchases.update_product', $product->id) }}">
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
                                <input type="text" name="sku" id="sku"
                                    value="{{ $product->products->sku ?? '' }}" class="form-control">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="category">Category</label>
                                <select name="category" id="category" class="form-select">
                                    @foreach ($category as $cat)
                                        <option value="{{ $cat->id }}"
                                            {{ isset($product->products->category_id) && $product->products->category_id == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="warehouse">Warehouse</label>
                                <select name="warehouse" id="warehouse" class="form-select">
                                    @foreach ($warehouse as $ware)
                                        <option value="{{ $ware->id }}"
                                            {{ isset($product->products->warehouse_id) && $product->products->warehouse_id == $ware->id ? 'selected' : '' }}>
                                            {{ $ware->name . ':' . $ware->location }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="qty">Quantity</label>
                                <input type="text" name="qty" id="qty"
                                    value="{{ $product->products->stock_quantity ?? '' }}" class="form-control">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="b_price">Buy Price</label>
                                <input type="text" name="b_price" id="b_price" value="{{ $product->price ?? '' }}"
                                    class="form-control">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="s_price">Sell Price</label>
                                <input type="text" name="s_price" id="s_price"
                                    value="{{ $product->products->price ?? '' }}" class="form-control">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-select">
                                    <option value="active"
                                        {{ isset($product->status) && $product->products->status == 'active' ? 'selected' : '' }}>
                                        Active
                                    </option>
                                    <option value="inactive"
                                        {{ isset($product->status) && $product->products->status == 'inactive' ? 'selected' : '' }}>
                                        Inactive
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="card-action text-end">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <a href="{{ route('purchases.edit', [$product->purchase_id]) }}"
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
            url: '/purchases/search',
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
            url: '/purchases/loadother',
            type: 'POST',
            data: {
                name: name,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                let data = response.data;
                $("#sku").val(data);
            },

            error: function(xhr) {
                console.log(xhr.responseText);
            }
        });
    }
</script>
