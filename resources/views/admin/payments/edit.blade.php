@extends('admin.includes.app')
@section('title')
    Payment Edit
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <form action="{{ route('payments.update', [$supplier->id]) }}" method="POST">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Payment Edit</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @csrf
                            @method('PUT')
                            <div class="col-sm-6 mb-3">
                                <label for="name">Order No:</label>
                                <input type="text" oninput="getAmount(this.value)" value={{ $supplier->ordid }}
                                    name="order" id="order" class="form-control" required>
                            </div>

                            <div class="col-sm-12 mb-3">
                                <label for="name">Order Details:</label>
                                <div id="order_detail">
                                    <div class="card p-3">
                                        <table class="table table-bordered">
                                            <tbody>
                                                <tr>
                                                    <th>Order ID</th>
                                                    <td>{{ $order->id }}</td>
                                                    <th>Stage</th>
                                                    <td>{{ $order->stage ? $order->stage->name : 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Customer Name</th>
                                                    <td>{{ $order->customer ? $order->customer->name : 'N/A' }}</td>
                                                    <th>Email</th>
                                                    <td>{{ $order->customer ? $order->customer->email : 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Phone</th>
                                                    <td>{{ $order->customer ? $order->customer->phone : 'N/A' }}</td>
                                                    <th>Total Amount</th>
                                                    <td>Rs {{ number_format($order->total_amount, 2) }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Payment Status</th>
                                                    <td colspan="3">{{ $order->status }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>

                            <div class="col-sm-6 mb-3">
                                <label for="email">Payment Mode:</label>
                                <select name="mode" id="mode" class="form-control">
                                    @foreach ($modes as $key => $mode)
                                        <option value="{{ $mode['id'] }}"
                                            @if ($mode['id'] == $supplier->pmid) selected @endif>{{ $mode['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-sm-6 mb-3">
                                <label for="phone">Amount:</label>
                                <input type="number" name="amount" id="amount" value="{{ $supplier->amount }}"
                                    class="form-control" required>
                            </div>

                            <div class="col-sm-6 mb-3">
                                <label for="address">Payment Date:</label>
                                <input type="date" name="date" id="date"
                                    value="{{ isset($supplier->date) ? date('Y-m-d', strtotime($supplier->date)) : '' }}"
                                    class="form-control">

                            </div>

                        </div>
                    </div>
                    <div class="card-action text-end">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <a href="{{ route('payments.index') }}" class="btn btn-danger">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function getAmount(order) {
            $.ajax({
                url: '/payments/getdetails',
                type: 'POST',
                data: {
                    id: order,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.status === "success" && response.order) {
                        let order = response.order; // Extract order data
                        let orderHtml = `
    <div class="card p-3">
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <th>Order ID</th>
                    <td>${order.id}</td>
                    <th>Customer Name</th>
                    <td>${order.customer ? order.customer.name : 'N/A'}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>${order.customer ? order.customer.email : 'N/A'}</td>
                    <th>Phone</th>
                    <td>${order.customer ? order.customer.phone : 'N/A'}</td>
                </tr>
                <tr>
                    <th>Total Amount</th>
                    <td>Rs ${order.total_amount}</td>
                    <th>Stage</th>
                    <td>${order.stage ? order.stage.name : 'N/A'}</td>
                </tr>
                <tr>
                    <th>Payment Status</th>
                    <td colspan="3">${order.status}</td>
                </tr>
            </tbody>
        </table>
    </div>
`;



                        $("#order_detail").html(orderHtml);
                        $("#amount").val(order.total_amount).prop('readonly', true);
                    } else {
                        $("#order_detail").html("<p class='text-danger'>Order not found!</p>");
                        $("#amount").prop('readonly', false);
                    }
                },

                error: function(error) {
                    console.log(error);
                }
            });
        }
    </script>
@endsection
