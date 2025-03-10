@extends('admin.includes.app')
@section('title')
    Order Add
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <form action="{{ route('orders.store') }}" method="POST">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Order Add</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @csrf

                            {{-- Customer Name --}}
                            <div class="col-sm-6 mb-3">
                                <label for="customer">Customer Name:</label>
                                <input type="text" name="name" id="customer" class="form-control" required>
                            </div>

                            {{-- Total Amount --}}
                            <div class="col-sm-6 mb-3">
                                <label for="date">Order Date:</label>
                                <input type="date" name="date" id="date"
                                    value="{{ \Carbon\Carbon::now()->toDateString() }}" class="form-control" required>
                            </div>

                            {{-- Stage Selection --}}
                            <div class="col-sm-6 mb-3">
                                <label for="stage">Stage:</label>
                                <select name="stage" id="stage" class="form-select" required>
                                    <option value="0"></option>
                                    @foreach ($stage as $s)
                                        <option value="{{ $s->id }}">{{ $s->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Order Status --}}
                            <div class="col-sm-6 mb-3">
                                <label for="status">Payment Status:</label>
                                <select name="status" id="status" class="form-select" required>
                                    <option value="Pending">Pending</option>
                                    <option value="Received">Received</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="card-action text-end">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <a href="{{ route('orders.index') }}" class="btn btn-danger">Cancel</a>
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
</script>
