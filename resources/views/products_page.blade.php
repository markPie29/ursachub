@extends('layouts.layout')

@section('content')
<head>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>   
    <section class="filler-div"></section>

    <h1>Products</h1>

<div class="products-ctn">
    @foreach($products as $product)
        <a href="{{ route('show_eachprodpage', $product->id) }}" class="products-card">
            <div class="card-image">
                @php
                    $photos = json_decode($product->photos, true); 
                @endphp 
                @if(is_array($photos) && count($photos) > 0)
                    <img src="{{ asset('storage/' . $photos[0]) }}" alt="Product Photo">
                @else
                    <p>No images available</p>
                @endif
            </div>
            <div class="card-text">
                <p class="product-category">{{ $product->org }}</p>
                <span class="product-name">{{ $product->name }}</span>
                
                <strong class="product-price">Php{{ number_format($product->price, 2) }}</strong>
            </div>
        </a>
    @endforeach
</div>

    
    <div class="pagination">
    <!-- Previous Button -->
    @if ($products->onFirstPage())
        <span class="disabled">Previous</span>
    @else
        <a href="{{ $products->previousPageUrl() }}" class="page-link">Previous</a>
    @endif

    <!-- Page Numbers with Limit of 5 -->
    @if ($products->lastPage() <= 5)
        <!-- Show all pages if total is 5 or less -->
        @for ($i = 1; $i <= $products->lastPage(); $i++)
            @if ($i == $products->currentPage())
                <span class="current-page">{{ $i }}</span>
            @else
                <a href="{{ $products->url($i) }}" class="page-link">{{ $i }}</a>
            @endif
        @endfor
    @else
        <!-- Show first page -->
        <a href="{{ $products->url(1) }}" class="page-link">1</a>

        @if ($products->currentPage() > 3)
            <!-- Ellipsis if current page is beyond the first few -->
            <span>...</span>
        @endif

        <!-- Display 2 pages before and after the current page -->
        @for ($i = max(2, $products->currentPage() - 1); $i <= min($products->lastPage() - 1, $products->currentPage() + 1); $i++)
            @if ($i == $products->currentPage())
                <span class="current-page">{{ $i }}</span>
            @else
                <a href="{{ $products->url($i) }}" class="page-link">{{ $i }}</a>
            @endif
        @endfor

        @if ($products->currentPage() < $products->lastPage() - 2)
            <!-- Ellipsis if not near the last page -->
            <span>...</span>
        @endif

        <!-- Show last page -->
        <a href="{{ $products->url($products->lastPage()) }}" class="page-link">{{ $products->lastPage() }}</a>
    @endif

    <!-- Next Button -->
    @if ($products->hasMorePages())
        <a href="{{ $products->nextPageUrl() }}" class="page-link">Next</a>
    @else
        <span class="disabled">Next</span>
    @endif
</div>
</body>
@endsection



