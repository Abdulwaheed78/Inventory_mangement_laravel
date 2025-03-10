@extends('admin.includes.app')
@section('title')
    Category Edit
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <form action="{{ route('warehouses.update',[$warehouse->id]) }}" method="POST">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Warehouse Edit</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @csrf
                            @method('PUT')
                            <div class="col-sm-6 mb-3">
                                <label for="name"> Name: </label>
                                <input type="text" name="name" value="{{$warehouse->name}}" id="name" class="form-control" required>
                            </div>
                            <div class="col-sm-12">
                                <label for="description"> Description: </label>
                                <textarea name="location"  id="location" class="form-control">{{$warehouse->location}}</textarea>
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
