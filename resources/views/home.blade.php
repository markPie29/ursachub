@extends('layouts.layout')

@section('content')


<body>

<div class="carousel-home">
    <input type="radio" name="carousel" id="slide1" checked>
    <input type="radio" name="carousel" id="slide2">
    <input type="radio" name="carousel" id="slide3">
    
    <div class="carousel-content">
        <div class="slide slide1">
            <div class="home-hero-background">
                <div class="home-hero-content">
                    <div class="home-hero-title">URSAC Hub</div>
                    <div class="home-hero-subtitle">Your One-Stop Shop for Campus Merchandise and Updates</div>
                </div>
            </div>
        </div>
        <div class="slide slide2">
            <div class="home-hero-background">
                <div class="home-hero-content">
                    <div class="home-hero-title">Campus Life</div>
                    <div class="home-hero-subtitle">Explore Events and Activities at URSAC</div>
                </div>
            </div>
        </div>
        <div class="slide slide3">
            <div class="home-hero-background">
                <div class="home-hero-content">
                    <div class="home-hero-title">Join URSAC</div>
                    <div class="home-hero-subtitle">Be Part of Our Vibrant Community</div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="carousel-nav">
        <label for="slide1"></label>
        <label for="slide2"></label>
        <label for="slide3"></label>
    </div>
</div>


<hr>

<div class="home-news">
    <div class='news-products-header'>
        <h1>News</h1>

        <form action="{{ route('search_news') }}" method="GET" class="search-form">
            <input type="text" name="query" placeholder="Search for news..." value="{{ request('query') }}">
            <button type="submit">Search</button>
        </form>
    </div>

    <div class="news-container">
        @foreach($news as $newsx)
        <a href="{{ route('show_eachnewspage', $newsx->id) }}" class="news-card">
            <!-- News Content at the Top -->
            <div class="news-card-content">
                <p class="news-org">{{ $newsx->org }}</p>
                <p class="news-timestamp">{{ $newsx->updated_at }}</p>
                <h3 class="news-headline" id = "news-headline"> {{ $newsx->headline }} </h3>
            </div>

            <!-- Image at the Bottom -->
            <div class="news-card-image">
                @php
                    // Decode the JSON-encoded image URLs
                    $images = json_decode($newsx->photos, true);
                @endphp

                @if(is_array($images) && count($images) > 0)
                    <!-- Display the first image in the array -->
                    <img src="{{ asset('storage/' . $images[0]) }}" alt="News Preview">
                @else
                    <!-- Fallback if no images are available -->
                    <p>No images available</p>
                @endif
            </div>
        </a>
        @endforeach
    </div>
</div>

<div class="home-products">
    <div class='news-products-header'>
        <h1>Products</h1>

        <form action="{{ route('search_products') }}" method="GET" class="search-form">
            <input type="text" name="query" placeholder="Search for products..." value="{{ request('query') }}">
            <button type="submit">Search</button>
        </form>
    </div>

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
</div>

<div class="home-devs">
    <div class=devs-ctn>
        <h1>About the Developers</h1>
        <a href="">Click here to know more about the developers of this website</a>
    </div>
</div>

</body>
</html>


@endsection