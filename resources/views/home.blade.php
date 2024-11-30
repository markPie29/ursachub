@extends('layouts.layout')

@section('content')

<body>

<div class="carousel-home hidden">
    <input type="radio" name="carousel" id="slide1" checked>
    <input type="radio" name="carousel" id="slide2">
    <input type="radio" name="carousel" id="slide3">
    <input type="radio" name="carousel" id="slide4">
    <input type="radio" name="carousel" id="slide5">
    
    <div class="carousel-content">
        <div class="slide slide1" data-slide="1">
            <div class="home-hero-background">
                <div class="home-hero-content">
                    <div class="home-hero-title">URSAC Hub</div>
                    <div class="home-hero-subtitle">Your One-Stop Shop for Campus Merchandise, Updates, and More</div>
                </div>
                <div class="click-area left"></div>
                <div class="click-area right"></div>
            </div>
        </div>
        <div class="slide slide2" data-slide="2">
            <div class="home-hero-background">
                <div class="home-hero-content">
                    <div class="home-hero-intro">
                        <div>
                            <img src="/img/news.png" alt="">
                        </div>

                        <div>
                            <h1>Stay Updated</h1>
                            <p>Keep yourself informed.</p>
                        </div>
                    </div>
 
                </div>
                <div class="click-area left"></div>
                <div class="click-area right"></div>
            </div>
        </div>
        <div class="slide slide3" data-slide="3">
            <div class="home-hero-background">
                <div class="home-hero-content">
                    <div class="home-hero-intro">
                         <div>
                            <img src="/img/prods.png" alt="">
                        </div>
                        <div>
                            <h1>Buy Products</h1>
                            <p>Browse and purchase campus merchandise.</p>
                        </div>
                    </div>
                </div>
                <div class="click-area left"></div>
                <div class="click-area right"></div>
            </div>
        </div>
        <div class="slide slide4" data-slide="4">
            <div class="home-hero-background">
                <div class="home-hero-content">
                    <div class="home-hero-intro">
                        <div>
                            <img src="/img/orgs.png" alt="">
                        </div>

                        <div>
                            <h1>Follow Orgs</h1>
                            <p>Support student organizations on campus.</p>
                        </div>
                    </div>
                </div>
                <div class="click-area left"></div>
                <div class="click-area right"></div>
            </div>
        </div>
        <div class="slide slide5" data-slide="5">
            <div class="home-hero-background">
                <div class="home-hero-content">
                    <div class="home-hero-devs">
                        <h1 class="home-hero-devs-title">About the Devs</h1>
                        <p class="home-hero-devs-subtitle">The student that developed this website.</p>
                        <div class="home-hero-links">
                            <a href="https://markpie.netlify.app" target="_blank" rel="noopener noreferrer">
                                <img src="/img/marky.png" alt="">
                                <h1>Mark Angelo A. Isulat</h1>
                                <h2>Full-Stack Dev</h2>
                                <p>Click to view Portfolio</p>
                            </a>
                            <a href="https://markpie.netlify.app" target="_blank" rel="noopener noreferrer">
                                <img src="/img/prince.png" alt="">
                                <h1>Prince Psalm Vivaz</h1>
                                <h2>Back-End Dev</h2>
                                <p>Click to view Portfolio</p>
                            </a>
                            <a href="https://markpie.netlify.app" target="_blank" rel="noopener noreferrer">
                                <img src="/img/brex.png" alt="">
                                <h1>Brexcel Joe Orias</h1>
                                <h2>Front-End Dev</h2>
                                <p>Click to view Portfolio</p>
                            </a>
                            <a href="https://markpie.netlify.app" target="_blank" rel="noopener noreferrer">
                                <img src="/img/tets.png" alt="">
                                <h1>Althea Lizette Palustre</h1>
                                <h2>Project Manager</h2>
                                <p>Click to view Portfolio</p>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="click-area left"></div>
                <div class="click-area right"></div>
            </div>
        </div>
    </div>
    
    <div class="carousel-nav">
        <label for="slide1"></label>
        <label for="slide2"></label>
        <label for="slide3"></label>
        <label for="slide4"></label>
        <label for="slide5"></label>
    </div>
</div>

<hr>

<div class="home-news hidden">
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

<div class="home-products hidden">
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

<!-- <div class="home-devs">
    <div class=devs-ctn>
        <h1>About the Developers</h1>
        <a href="">Click here to know more about the developers of this website</a>
    </div>
</div> -->

</body>
</html>

@endsection
