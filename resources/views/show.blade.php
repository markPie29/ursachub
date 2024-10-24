@extends('layouts.layout')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <!-- <img src="{{ asset('storage/images/' . $product->image) }}" class="img-fluid" alt="{{ $product->name }}"> -->
            </div>
            <div class="col-md-6">
                <h2>{{ $product->name }}</h2>
                <p>{{ $product->description }}</p>
                <p><strong>Price:</strong> ${{ $product->price }}</p>
            </div>
        </div>
    </div>
@endsection