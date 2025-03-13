@extends('admin.includes.app')
@section('title')
    Purchase
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4 class="card-title">Purchase</h4>
                    <a href="{{ route('purchases.create') }}" class="btn btn-primary">Add + </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="basic-datatables" class="display table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Supplier</th>
                                    <th>Invoice</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($purchases as $Index => $purchase)
                                    <tr>
                                        <td>{{ $purchase->supplier->name }}</td>
                                        <td>{{ $purchase->invoice_no }}</td>
                                        <td>{{ date('d M Y', strtotime($purchase->purchase_date)) }}</td>
                                        <td>{{ $purchase->total_amount }}</td>
                                        <td>{{ $purchase->status }}</td>
                                        <td>{{ date('d M Y', strtotime($purchase->created_at)) }}</td>
                                        <td>
                                            <a href="{{ route('purchases.edit', [$purchase->id]) }}"
                                                class="btn btn-round btn-link"><i class="fa fa-edit "></i>
                                            </a>
                                            <form action="{{ route('purchases.delete', [$purchase->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this Purchase?');"
                                                style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-round btn-link">
                                                    <i class="fa fa-trash text-danger"></i>
                                                </button>
                                            </form>

                                            <a target="_blank" href="{{route('purchase_invoice',[$purchase->id])}}" class="btn btn-round btn-link"> Invoice</a>

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
