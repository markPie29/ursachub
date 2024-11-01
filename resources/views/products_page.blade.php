@extends('layouts.layout')

@section('content')

<body>   
    <section class="filler-div"></section>

    <h1>Products</h1>

    <div class="products-ctn">
        @foreach($products as $product)
            <a href="{{ route('show_eachprodpage', $product->id) }}">
                <div class="products_card"> 
                    <span>{{ $product->name }}</span>
                    <p>{{ $product->org }}</p>
                    <strong>{{ $product->price }}</strong>
                    @php
                        $photos = json_decode($product->photos, true); 
                    @endphp
                    @if(is_array($photos) && count($photos) > 0)
                        <img src="{{ asset('storage/' . $photos[0]) }}" alt="Product Photo" style="max-width: 100%; height: auto;">
                    @else
                        <p>No images available</p>
                    @endif
                </div>
            </a>
        @endforeach
    </div>
</body>

@endsection
