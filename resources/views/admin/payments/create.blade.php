@extends('admin.includes.app')
@section('title')
    Payment Add
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <form action="{{ route('payments.store') }}" method="POST">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Payment Add</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @csrf
                            <div class="col-sm-6 mb-3">
                                <label for="name">Order NO:</label>
                                <input type="text" name="order" id="order" class="form-control" required>
                            </div>

                            <div class="col-sm-6 mb-3">
                                <label for="email">Payment Mode:</label>
                                <select name="mode" id="mode" class="form-control">
                                    @foreach ($modes as $key => $mode)
                                        <option value="{{ $mode['id'] }}">{{ $mode['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-sm-6 mb-3">
                                <label for="phone">Amount:</label>
                                <input type="number" name="amount" id="amount" class="form-control" required>
                            </div>

                            <div class="col-sm-6 mb-3">
                                <label for="address">Payment Date:</label>
                                <input type="date" name="date" id="date" class="form-control">
                            </div>

                        </div>
                    </div>
                    <div class="card-action text-end">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <a href="{{ route('payments.index') }}" class="btn btn-danger">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
