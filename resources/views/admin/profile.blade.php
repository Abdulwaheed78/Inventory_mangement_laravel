@extends('admin.includes.app')
@section('title')
    Admin Profile
@endsection
@section('content')
    <div class="row">
        <?php
        $user = Auth::user();
        ?>
        <div class="col-md-12">
            <form action="{{ route('update_profile',[$user->id]) }}" method="POST" enctype="multipart/form-data">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Admin Profile</h4>
                    </div>

                    <div class="card-body">
                        <div class="text-end">
                            @if ($user->image != '')
                                <img src="{{ 'user/'.$user->image }}" alt="No IMG" height="120px" width="120px">
                            @else
                                <img src="{{ asset('admin/assets/img/profile.jpg') }}" alt="No IMG" height="120px"
                                    width="120px">
                            @endif
                        </div>
                        <div class="row mt-2">
                            @csrf
                            <div class="col-sm-6 mb-3">
                                <label for="name"> Name: </label>
                                <input type="text" name="name" value="{{ $user->name }}" id="name"
                                    class="form-control" required>
                            </div>

                            <div class="col-sm-6 mb-3">
                                <label for="name"> Email: </label>
                                <input type="email" name="email" value="{{ $user->email }}" id="email"
                                    class="form-control" required>
                            </div>

                            <div class="col-sm-6 mb-3">
                                <label for="name"> Phone: </label>
                                <input type="text" name="phone" maxlength="10" value="{{ $user->phone }}"
                                    id="phone" class="form-control" required>
                            </div>

                            <div class="col-sm-6 mb-3">
                                <label for="name"> Upload Image: </label>
                                <input type="file" name="image" id="image" class="form-control" >
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
