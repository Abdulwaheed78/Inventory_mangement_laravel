@extends('admin.includes.app')
@section('title')
    Customers
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4 class="card-title">Customers</h4>
                    <a href="{{ route('customers.create') }}" class="btn btn-primary">Add + </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="basic-datatables" class="display table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Address</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($customers as $Index => $customer)
                                    <tr>
                                        <td>{{ $customer->name }}</td>
                                        <td>{{ $customer->email }}</td>
                                        <td>{{ $customer->phone }}</td>
                                        <td>{{ $customer->address }}</td>
                                        <td>{{ date('d M Y', strtotime($customer->created_at)) }}</td>
                                        <td>
                                            <a href="{{ route('customers.edit', [$customer->id]) }}"
                                                class="btn btn-round btn-link"><i class="fa fa-edit "></i>
                                            </a>
                                            <form action="{{ route('customers.delete', [$customer->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this Customer?');"
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
