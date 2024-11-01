@extends('layouts.layout')

@section('content')

    <body>   
    
    <section class="filler-div">

    </section>

    <h1>Products</h1>

    <div class="products-ctn">
        @foreach($products as $product)
        <a href="{{ route('show_eachprodpage', $product->id) }}">
            <div class="products_card"> 
                <span>{{ $product->name }}</span>
                <p>{{ $product->org }}</p>
                <strong>{{ $product->price }}</strong>
            </div>
        </a>
        @endforeach
    </div>

   

    </body>

@endsection