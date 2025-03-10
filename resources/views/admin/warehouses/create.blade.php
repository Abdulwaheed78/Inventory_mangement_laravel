@extends('admin.includes.app')
@section('title')
    Warehouse Add
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <form action="{{ route('warehouses.store') }}" method="POST">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Warehouse Add</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @csrf
                            <div class="col-sm-6 mb-3">
                                <label for="name"> Name: </label>
                                <input type="text" name="name" id="name" class="form-control" required>
                            </div>
                            <div class="col-sm-12">
                                <label for="description"> Location: </label>
                                <textarea name="location" id="location" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="card-action text-end">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <a href="{{ route('warehouses.index') }}" class="btn btn-danger">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
