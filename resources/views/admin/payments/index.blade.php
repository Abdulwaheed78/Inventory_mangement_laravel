@extends('admin.includes.app')
@section('title')
    Payments
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4 class="card-title">Payments</h4>
                    <a href="{{ route('payments.create') }}" class="btn btn-primary">Add + </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="basic-datatables" class="display table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Order</th>
                                    <th>Payment Mode</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($suppliers as $Index => $supp)
                                    <tr>
                                        <td>
                                            #000{{ $supp->order->id }} <br>
                                             {{ $supp->order->customer->name }} <br>
                                            Amount: {{ $supp->order->total_amount }} <br>
                                            Stage: {{ $supp->order->stage->name }}, Status: {{ $supp->order->status }} <br>
                                            Date: {{ date('d M Y', strtotime($supp->order->date)) }} <br>

                                         </td>
                                        <td>{{ $supp->pmode->name }}</td>
                                        <td>{{ $supp->amount }}</td>
                                        <td>{{ date('d M Y', strtotime($supp->date)) }}</td>
                                        <td>{{ date('d M Y', strtotime($supp->created_at)) }}</td>
                                        <td>
                                            <a href="{{ route('payments.edit', [$supp->id]) }}"
                                                class="btn btn-round btn-link"><i class="fa fa-edit "></i>
                                            </a>
                                            <form action="{{ route('payments.delete', [$supp->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this Payment?');"
                                                style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-round btn-link">
                                                    <i class="fa fa-trash text-danger"></i>
                                                </button>
                                            </form>
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
