@extends('admin.includes.app')
@section('title')
    Edit Payment Mode
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <form action="{{ route('modes.update', [$stage->id]) }}" method="POST">
                <input type="hidden">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Edit Payment Mode</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @csrf
                            @method('PUT')
                            <div class="col-sm-6 mb-3">
                                <label for="name"> Name: </label>
                                <input type="text" name="name" value="{{ $stage->name }}" id="name"
                                    class="form-control" required>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label for="name"> Initial: </label>
                                <input type="text" name="initial" value="{{ $stage->initial }}" id="initial"
                                    class="form-control" required>
                            </div>
                            {{-- <div class="col-sm-6 mb-3">
                                <label for="name"> Qr Code: </label>
                                <input type="file" name="qr" id="qr"
                                    class="form-control" required>
                            </div> --}}
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
