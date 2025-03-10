@extends('admin.includes.app')
@section('title')
    Categories
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4 class="card-title"> Select Category</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('stock_detail') }}" method="POST">
                        @csrf
                        <div class="d-flex ">
                            <div class="col-3"></div>
                            <div class="col-6 mb-3 mt-5">
                                <select name="category" id="category" class="form-select">
                                    <option value="0"></option>
                                    @foreach ($data as $index => $da)
                                        <option value="{{ $da->id }}">{{ $da->name }}</option>
                                    @endforeach
                                </select>
                                <label for="category " class="mt-2">Category:</label>

                            </div>
                            <div class="col-3"></div>

                        </div>
                        <div class="d-flex justify-content-center text-center mt-3">
                            <div class="me-2">
                                <button class="btn btn-primary">
                                    Submit
                                </button>
                            </div>
                            <div>
                                <a href="{{ route('export_stock') }}" class="btn btn-success">
                                    <i class="fas fa-file-export"></i> Export
                                </a>
                            </div>
                        </div>


                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection
