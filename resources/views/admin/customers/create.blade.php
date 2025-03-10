@extends('admin.includes.app')
@section('title')
    Customer Add
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <form action="{{ route('customers.store') }}" method="POST">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Customer Add</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @csrf
                            <div class="col-sm-6 mb-3">
                                <label for="name">Name:</label>
                                <input type="text" name="name" id="name" class="form-control" required>
                            </div>

                            <div class="col-sm-6 mb-3">
                                <label for="email">Email:</label>
                                <input type="email" name="email" id="email" class="form-control" required>
                            </div>

                            <div class="col-sm-6 mb-3">
                                <label for="phone">Phone:</label>
                                <input type="tel" name="phone" id="phone" class="form-control" required>
                            </div>

                            <div class="col-sm-12 mb-3">
                                <label for="address">Address:</label>
                                <textarea name="address" id="address" class="form-control" required></textarea>
                            </div>

                        </div>
                    </div>
                    <div class="card-action text-end">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <a href="{{ route('customers.index') }}" class="btn btn-danger">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
