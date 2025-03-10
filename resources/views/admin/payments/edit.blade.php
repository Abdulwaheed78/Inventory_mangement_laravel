@extends('admin.includes.app')
@section('title')
    Payment Edit
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <form action="{{ route('payments.update', [$supplier->id]) }}" method="POST">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Payment Edit</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @csrf
                            @method('PUT')
                            <div class="col-sm-6 mb-3">
                                <label for="name">Order No:</label>
                                <input type="text" value={{ $supplier->ordid }} name="order" id="order"
                                    class="form-control" required>
                            </div>

                            <div class="col-sm-6 mb-3">
                                <label for="email">Payment Mode:</label>
                                <select name="mode" id="mode" class="form-control">
                                    @foreach ($modes as $key => $mode)
                                        <option value="{{ $mode['id'] }}" @if ($mode['id']==$supplier->pmid) selected

                                        @endif  >{{ $mode['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-sm-6 mb-3">
                                <label for="phone">Amount:</label>
                                <input type="number" name="amount" id="amount" value="{{ $supplier->amount }}"
                                    class="form-control" required>
                            </div>

                            <div class="col-sm-6 mb-3">
                                <label for="address">Payment Date:</label>
                                <input type="date" name="date" id="date"
                                    value="{{ isset($supplier->date) ? date('Y-m-d', strtotime($supplier->date)) : '' }}"
                                    class="form-control">

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
