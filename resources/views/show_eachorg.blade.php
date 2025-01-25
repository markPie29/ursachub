@extends('layouts.layout')

@section('content')
<section class="filler-div">

</section>

<div class="org-ctn">
    <div class="org-main-div">
        <div class="org-main-logo">
            @if ($org ->logo)
                <img src="{{ asset('storage/' . $org ->logo) }}" alt="{{ $org->org }} Logo" class="logo">
            @else
                <p>No images available</p>
            @endif
        </div>
        <div class="org-main-name">
            <h1>{{ $org->org }}</h1>
            <a href="{{ $org->fb_link }}" target="_blank"> <i class='bx bxl-facebook-circle'></i> <span> {{ $org->org }}</span></a>
        </div>

    </div>

    <div class="each-org-student-events-ctn">
        <div class="student-events-header">
            <a href="{{ route('view_events')}}"><h1>Events</h1></a>
        </div>

        <div class="student-each-events">
            @php
                $todayDate = \Carbon\Carbon::now()->toDateString();

                // Get all events sorted by date
                $latestEvents = $events->sortBy('date')->filter(function ($event) use ($todayDate) {
                    return \Carbon\Carbon::parse($event->date)->toDateString() >= $todayDate;
                })->take(3);
            @endphp

            @if($latestEvents->isNotEmpty())
                @foreach($latestEvents as $event)
                    <div class="student-event-item {{ \Carbon\Carbon::parse($event->date)->toDateString() === $todayDate ? 'today' : 'upcoming' }}">
                        
                        <div class="event-item-profile">
                            <div class="news-logo">
                                <img src="{{ asset('storage/' . $event->logo) }}" alt="{{ $event->org }} Logo" class="logo">
                            </div>
                            
                            <div class="event-item-info">
                                <h3>{{ $event->name }}</h3>
                                <p>{{ \Carbon\Carbon::parse($event->date)->format('F j, Y, g:i A') }}</p> 
                                <p>{{ $event->venue }}</p>
                            </div>
                        </div>

                        <div class="event-item-status">
                            @if(\Carbon\Carbon::parse($event->date)->toDateString() === $todayDate)
                                <span>Event is Today!</span>
                            @else
                                <span>Upcoming Event!</span>
                            @endif
                        </div>


                    </div>
                @endforeach
            @else
                <p>No events available.</p>
            @endif
        </div>

    </div>

   

    <div class="org-news-prod-ctn">
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


@endsection
