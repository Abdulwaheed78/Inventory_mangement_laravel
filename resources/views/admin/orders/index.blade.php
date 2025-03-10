@extends('admin.includes.app')
@section('title')
    Orders
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4 class="card-title">Orders</h4>
                    <a href="{{ route('orders.create') }}" class="btn btn-primary">Add + </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="basic-datatables" class="display table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Customer </th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Stage</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($orders as $Index => $order)
                                    <tr>
                                        <td>{{ $order->customer->name }}</td>
                                        <td>{{ date('d M Y', strtotime($order->order_date)) }}</td>
                                        <td>{{ $order->total_amount }}</td>
                                        <td>{{ $order->stage->name }}</td>
                                        <td>{{ $order->status }}</td>
                                        <td>{{ date('d M Y', strtotime($order->created_at)) }}</td>
                                        <td>
                                            <a href="{{ route('orders.edit', [$order->id]) }}"
                                                class="btn btn-round btn-link"><i class="fa fa-edit "></i>
                                            </a>
                                            <form action="{{ route('orders.delete', [$order->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this Order?');"
                                                style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-round btn-link">
                                                    <i class="fa fa-trash text-danger"></i>
                                                </button>
                                            </form>
                                            <a target="_blank" href="{{route('order_invoice',[$order->id])}}" class="btn btn-round btn-link"> Invoice</a>
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
