@extends('admin.includes.app')

@section('title')
    Product Add
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Product Add</h4>
                </div>
                <div class="card-body">
                    <form id="product_form" method="POST" action="{{ route('orders.productstore') }}">
                        <input type="hidden" name="oid" value="{{ $order_id }}">
                        @csrf
                        <div class="row">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Sku</th>
                                        <th>Category</th>
                                        <th>Warehouse</th>
                                        <th>Qty</th>
                                        <th>Price</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="product_div">
                                    <!-- Dynamically added rows go here -->
                                </tbody>
                            </table>
                        </div>
                        <div class="card-action text-end">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <button type="button" class="btn btn-success" onclick="addProduct()" id="add_product">Add
                                Product</button>
                            <a href="{{ route('orders.edit', [$order_id]) }}" class="btn btn-danger">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
    let row = 1;

    function addProduct() {
        let content = `
            <tr id="row${row}">
                <input type="hidden" name="pid[]" id="pid${row}" >
                <td width="20%"><input type="text" oninput="fetchDetails(this.value,'${row}')" onfocusout="fetchRemainning(this.value,'${row}')" name="name[]" id="name${row}" class="form-control"></td>
                <td  width="10%"><input type="text" readonly name="sku[]" id="sku${row}" class="form-control"></td>
                <td>
                   <input  readonly type="text" name="category[]" id="category${row}" class="form-control">
                </td>
                <td>
                   <input  readonly type="text"name="warehouse[]" id="warehouse${row}" class="form-control">
                </td>
                <td><input type="text" name="qty[]" id="qty${row}" class="form-control"></td>
                <td><input type="text" readonly name="price[]" id="price${row}" class="form-control"></td>
                <td><button type="button" id="remove${row}" class="btn btn-danger" onclick="removeProduct(${row})" data-row="row${row}">X</button></td>
            </tr>
            `;
        $("#product_div").append(content);
        row++;
    }

    function fetchDetails(name, place) {
        $.ajax({
            url: '/orders/search',
            type: 'POST',
            data: {
                name: name,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $("#name" + place).autocomplete({
                    source: response.data
                });
            },
            error: function(xhr) {
                console.log(xhr.responseText);
            }
        });
    }

    function fetchRemainning(name, place) {
        $.ajax({
            url: '/orders/loadother',
            type: 'POST',
            data: {
                name: name,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                let data = response.data;
                $("#sku" + place).val(data['sku']);
                $("#category" + place).val(data.category['name']);
                $("#warehouse" + place).val(data.warehouse['name']+':'+data.warehouse['location']);
                $("#qty" + place).val(data['stock_quantity']);
                $("#price" + place).val(data['price']);
                $("#pid" + place).val(data['id']);
            },

            error: function(xhr) {
                console.log(xhr.responseText);
            }
        });
    }

    function removeProduct(place) {
        $("#row" + place).remove();
    }
</script>
