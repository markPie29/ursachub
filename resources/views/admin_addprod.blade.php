@extends('layouts.admin_layout')

@section('content')
    <div class="container">
        <h1>Add New Product</h1>

        {{-- Display validation errors --}}
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Product Form --}}
        <form action="/addprod" method="POST" enctype="multipart/form-data">
            @csrf
            <div>
                <label for="name">Product Name:</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required>
            </div>

            <div>
                <label for="org">Organization:</label>
                <input type="text" name="org" id="org" value="{{ old('org') }}" required>
            </div>

            <div>
                <h3>Enter Stocks for Sizes:</h3>
                <div>
                    <label for="small">Small:</label>
                    <input type="number" name="small" id="small" value="{{ old('small') }}" min="0" required placeholder="Enter stocks for Small">
                </div>
                <div>
                    <label for="medium">Medium:</label>
                    <input type="number" name="medium" id="medium" value="{{ old('medium') }}" min="0" required placeholder="Enter stocks for Medium">
                </div>
                <div>
                    <label for="large">Large:</label>
                    <input type="number" name="large" id="large" value="{{ old('large') }}" min="0" required placeholder="Enter stocks for Large">
                </div>
                <div>
                    <label for="extralarge">Extra Large:</label>
                    <input type="number" name="extralarge" id="extralarge" value="{{ old('extralarge') }}" min="0" required placeholder="Enter stocks for Extra Large">
                </div>
                <div>
                    <label for="double_extralarge">Double Extra Large:</label>
                    <input type="number" name="double_extralarge" id="double_extralarge" value="{{ old('double_extralarge') }}" min="0" required placeholder="Enter stocks for Double Extra Large">
                </div>
            </div>

            <div>
                <label for="price">Price:</label>
                <input type="number" name="price" id="price" step="0.01" value="{{ old('price') }}" required>
            </div>

            <div>
                <label for="photos">Photos (max 5):</label>
                <input type="file" name="photos[]" id="photos" multiple>
            </div>
            
            <button type="submit">Add Product</button>
        </form>

    </div>
@endsection
