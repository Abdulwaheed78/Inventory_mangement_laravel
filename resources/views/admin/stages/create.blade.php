@extends('admin.includes.app')
@section('title')
    Stage Add
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <form action="{{ route('stages.store') }}" method="POST">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Stage Add</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @csrf
                            <div class="col-sm-6 mb-3">
                                <label for="name"> Name: </label>
                                <input type="text" name="name" id="name" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="card-action text-end">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <a href="{{ route('stages.index') }}" class="btn btn-danger">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
