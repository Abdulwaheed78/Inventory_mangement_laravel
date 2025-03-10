@extends('admin.includes.app')
@section('title')
    Add Payment Mode
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <form action="{{ route('modes.store') }}" method="POST">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">  Add Payment Mode</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @csrf
                            <div class="col-sm-6 mb-3">
                                <label for="name"> Name: </label>
                                <input type="text" name="name" id="name" class="form-control" required>
                            </div>

                            <div class="col-sm-6 mb-3">
                                <label for="name"> Initial: </label>
                                <input type="text" name="initial" id="initial" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="card-action text-end">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <a href="{{ route('modes.index') }}" class="btn btn-danger">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
