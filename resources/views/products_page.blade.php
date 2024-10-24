@extends('layouts.layout')

@section('content')

    <body>   
    
    <section class="filler-div">

    </section>

    <h1>Products</h1>

    <div class="products-ctn">
        @foreach($products as $product)
        <a href="{{ route('show_prodpage', $product->id) }}">
            <div class="products_card">
                {{ $product->name }} - {{ $product->price }} - {{ $product->org }} - {{ $product->size }}
            </div>
        </a>
        @endforeach
    </div>

   

    </body>

@endsection