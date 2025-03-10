@extends('admin.includes.app')
@section('title')
    Admin Logs
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4 class="card-title">Admin Logs</h4>
                    {{-- <a href="{{ route('categories.create') }}" class="btn btn-primary">Add + </a> --}}
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="basic-datatables" class="display table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>By</th>
                                    <th>Action</th>
                                    <th>At Table</th>
                                    <th>ID</th>
                                    <th>Date</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($logs as $Index => $category)
                                    <tr>
                                        <td>{{ $category->user->name }}</td>
                                        <td>{{ $category->type }}</td>
                                        <td>{{ $category->table }}</td>
                                        <td>{{ $category->rid }}</td>
                                        <td>{{ date('d M Y', strtotime($category->created_at)) }}</td>
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
