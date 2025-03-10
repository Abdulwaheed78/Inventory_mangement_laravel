@extends('admin.includes.app')
@section('title')
    Payment Modes
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4 class="card-title">Payment Modes</h4>
                    <a href="{{ route('modes.create') }}" class="btn btn-primary">Add + </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="basic-datatables" class="display table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Initial</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($modes as $Index => $stage)
                                    <tr>
                                        <td>{{ $stage->name }}</td>
                                        <td>{{ $stage->initial }}</td>
                                        <td>{{ date('d M Y', strtotime($stage->created_at)) }}</td>
                                        <td>
                                            <a href="{{ route('modes.edit', [$stage->id]) }}"
                                                class="btn btn-round btn-link"><i class="fa fa-edit "></i>
                                            </a>
                                            <form action="{{ route('modes.delete', [$stage->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this Payment Mode?');"
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
