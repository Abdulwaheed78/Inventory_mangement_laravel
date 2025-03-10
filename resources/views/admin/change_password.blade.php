@extends('admin.includes.app')
@section('title')
    Change Password
@endsection
@section('content')
    <div class="row">
        <?php
        $user = Auth::user();
        ?>
        <div class="col-md-12">
            <form action="{{ route('update_password', [$user->id]) }}" method="POST" enctype="multipart/form-data">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Change Password</h4>
                    </div>

                    <div class="card-body">
                        <div class="row mt-2">
                            @csrf
                            <div class="col-sm-6 mb-3">
                                <label for="name"> Username: </label>
                                <input type="email" name="name" value="{{ $user->email }}" id="name"
                                    class="form-control" required>
                            </div>
                            <div class="col-6"></div>
                            <div class="col-sm-6 mb-3">
                                <label for="name">New Password: </label>
                                <input type="password" name="password" id="password" class="form-control" required>
                            </div>

                            <div class="col-sm-6 mb-3">
                                <label for="name"> Confirm Password: </label>
                                <input type="password" name="cpassword" id="cpassword" class="form-control" required>
                            </div>

                        </div>
                    </div>
                    <div class="card-action text-end">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <a href="{{ route('dashboard') }}" class="btn btn-danger">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
