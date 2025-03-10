@extends('admin.includes.app')
@section('title')
    Suppliers
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4 class="card-title">Suppliers</h4>
                    <a href="{{ route('suppliers.create') }}" class="btn btn-primary">Add + </a>
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
                                @foreach ($suppliers as $Index => $supp)
                                    <tr>
                                        <td>{{ $supp->name }}</td>
                                        <td>{{ $supp->email }}</td>
                                        <td>{{ $supp->phone }}</td>
                                        <td>{{ $supp->address }}</td>
                                        <td>{{ date('d M Y', strtotime($supp->created_at)) }}</td>
                                        <td>
                                            <a href="{{ route('suppliers.edit', [$supp->id]) }}"
                                                class="btn btn-round btn-link"><i class="fa fa-edit "></i>
                                            </a>
                                            <form action="{{ route('suppliers.delete', [$supp->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this Supplier?');"
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
