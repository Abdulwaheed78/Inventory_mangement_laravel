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
                    <form id="product_form" method="POST" action="{{ route('purchases.productstore') }}">
                        <input type="hidden" name="pid" value="{{$order_id}}">
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
                                        <th>Buy Price</th>
                                        <th>Sell Price</th>
                                        <th>Status</th>
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
                            <a href="{{ route('purchases.edit', [$order_id]) }}" class="btn btn-danger">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
    let row = 1;
    let products = @json($products); // Get products directly

    function addProduct() {
        let content = `
            <tr id="row${row}">
                <td width="20%"><input type="text" oninput="fetchDetails(this.value,'${row}')" onfocusout="fetchRemainning(this.value,'${row}')" name="name[]" id="name${row}" class="form-control"></td>
                <td  width="10%"><input type="text" name="sku[]" id="sku${row}" class="form-control"></td>
                <td>
                    <select name="category[]" id="category${row}" class="form-select ">
                    @foreach ($category as $ware)
                        <option value="{{ $ware->id }}">{{ $ware->name }}</option>
                    @endforeach
                </select>
                </td>
                <td>
                    <select name="warehouse[]" id="warehouse${row}" class="form-select">
                    @foreach ($warehouse as $ware)
                        <option value="{{ $ware->id }}">{{ $ware->name . ':' . $ware->location }}</option>
                    @endforeach
                </select>
                </td>
                <td><input type="text" name="qty[]" id="qty${row}" class="form-control"></td>
                <td><input type="text" name="b_price[]" id="b_price${row}" class="form-control"></td>
                <td><input type="text" name="s_price[]" id="s_price${row}" class="form-control"></td>
                <td><select name="status[]" id="status${row}" class="form-select">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select></td>
                <td><button type="button" id="remove${row}" class="btn btn-danger" onclick="removeProduct(${row})" data-row="row${row}">X</button></td>
            </tr>
            `;
        $("#product_div").append(content);
        row++;
    }

    function fetchDetails(name, place) {
        $.ajax({
            url: '/purchases/search',
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
            url: '/purchases/loadother',
            type: 'POST',
            data: {
                name: name,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                let data = response.data;
                $("#sku" + place).val(data);
            },

            error: function(xhr) {
                console.log(xhr.responseText);
            }
        });
    }

    function removeProduct(place) {
        $("#row"+place).remove();
    }
</script>
