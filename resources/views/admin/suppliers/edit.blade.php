@extends('admin.includes.app')
@section('title')
    Supplier Edit
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <form action="{{ route('suppliers.update',[$supplier->id]) }}" method="POST">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Supplier Edit</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @csrf
                            @method('PUT')
                            <div class="col-sm-6 mb-3">
                                <label for="name">Name:</label>
                                <input type="text" value={{$supplier->name}} name="name" id="name" class="form-control" required>
                            </div>

                            <div class="col-sm-6 mb-3">
                                <label for="email">Email:</label>
                                <input type="email" name="email" value={{$supplier->email}} id="email" class="form-control" required>
                            </div>

                            <div class="col-sm-6 mb-3">
                                <label for="phone">Phone:</label>
                                <input type="tel" name="phone" value={{$supplier->phone}} id="phone" class="form-control" required>
                            </div>

                            <div class="col-sm-12 mb-3">
                                <label for="address">Address:</label>
                                <textarea name="address" id="address" class="form-control" required>{{$supplier->address}}</textarea>
                            </div>

                        </div>
                    </div>
                    <div class="card-action text-end">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <a href="{{ route('suppliers.index') }}" class="btn btn-danger">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
