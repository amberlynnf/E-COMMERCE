@extends('templates.app')


@section('content-dinamis')
{{-- <div class="container">
    <h1>Create</h1>
</div> --}}

<form action="{{ route('data_produk.tambah.proses')}}" class="card p-5" method="POST">
    @csrf
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if (Session::get('success'))
    <div class="alert alert-success">
        {{ Session::get('success') }}
    </div>
    @endif
    <div class="mb-3 row">
        <label for="name" class="col-sm-2 col-form-label">Product Name: </label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name')}}">
        </div>
    </div>
    <div class="mb-3 row">
        <label for="type" class="col-sm-2 col-form-label">Product Type: </label>
        <div class="col-sm-10">
            <select class="form-select" name="type" id="type">
                <option selected disabled hidden>Pick</option>
                <option value="clothing" {{ old('type') == "clothing" ? 'selected' : ''}}>Clothing</option>
                <option value="accessories" {{ old('type') == "accessories" ? 'selected' : ''}}>Accessories</option>
                <option value="footwear" {{ old('type') == "footwear" ? 'selected' : ''}}>Footwear</option>
                <option value="headwear" {{ old('type') == "headwear" ? 'selected' : ''}}>Headwear</option>
                <option value="homegoods" {{ old('type') == "homegoods" ? 'selected' : ''}}>Home Goods</option>
                <option value="others" {{ old('type') == "others" ? 'selected' : ''}}>Others</option>
            </select>
        </div>
    </div>
    <div class="mb-3 row">
        <label for="price" class="col-sm-2 col-form-label">Price: </label>
        <div class="col-sm-10">
            <input type="number" class="form-control" id="price" name="price" value="{{ old('price')}}">
        </div>
    </div>
    <div class="mb-3 row">
        <label for="stock" class="col-sm-2 col-form-label">Stock: </label>
        <div class="col-sm-10">
            <input type="number" class="form-control" id="stock" name="stock" value="{{ old('stock')}}">
        </div>
    </div>
    <button type="submit" class="btn btn-primary mt-3">Confirm</button>
</form>
@endsection
