@extends('layouts.layout')

@section('content')

<section class="filler-div">

</section>

<body>

    <div class='news-products-header hidden'>
        <h1>News</h1>

        <form action="{{ route('search_news') }}" method="GET" class="search-form">
            <input type="text" name="query" placeholder="Search for news..." value="{{ request('query') }}">
            <button type="submit">Search</button>
        </form>
    </div>

    <div class="news-container hidden">
        @foreach($news as $newsx)
        <a href="{{ route('show_eachnewspage', $newsx->id) }}" class="news-card hidden">
            <!-- News Content at the Top -->
            <div class="news-card-content">
                <div class="news-profile">
                    @if ($newsx->logo)
                        <div class="news-logo">
                            <img src="{{ asset('storage/' . $newsx->logo) }}" alt="{{ $newsx->org }} Logo" class="logo">
                        </div>
                    @endif
                    <div>
                        <p class="news-org">{{ $newsx->org }}</p>
                        <p class="news-timestamp">{{ \Carbon\Carbon::parse($newsx->updated_at)->format('F d, Y h:i A') }}</p>
                    </div>
                </div>
              
                <h3 class="news-headline" id = "news-headline"> {{ $newsx->headline }} </h3>
                <p class="news-content">  {!! Str::of($newsx->content)->replaceMatches('/(https?:\/\/[^\s]+)/', '<a href="$1" target="_blank">$1</a>') !!}</p>
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

    <!-- Pagination -->
    <div class="pagination-container">
        <div class="pagination">
            <!-- Previous Button -->
            @if ($news->onFirstPage())
                <span class="disabled">Previous</span>
            @else
                <a href="{{ $news->previousPageUrl() }}" class="page-link">Previous</a>
            @endif

            <!-- Page Numbers with Limit of 5 -->
            @if ($news->lastPage() <= 5)
                @for ($i = 1; $i <= $news->lastPage(); $i++)
                    @if ($i == $news->currentPage())
                        <span class="current-page">{{ $i }}</span>
                    @else
                        <a href="{{ $news->url($i) }}" class="page-link">{{ $i }}</a>
                    @endif
                @endfor
            @else
                <a href="{{ $news->url(1) }}" class="page-link">1</a>

                @if ($news->currentPage() > 3)
                    <span class="ellipsis">...</span>
                @endif

                @for ($i = max(2, $news->currentPage() - 1); $i <= min($news->lastPage() - 1, $news->currentPage() + 1); $i++)
                    @if ($i == $news->currentPage())
                        <span class="current-page">{{ $i }}</span>
                    @else
                        <a href="{{ $news->url($i) }}" class="page-link">{{ $i }}</a>
                    @endif
                @endfor

                @if ($news->currentPage() < $news->lastPage() - 2)
                    <span class="ellipsis">...</span>
                @endif

                <a href="{{ $news->url($news->lastPage()) }}" class="page-link">{{ $news->lastPage() }}</a>
            @endif

            <!-- Next Button -->
            @if ($news->hasMorePages())
                <a href="{{ $news->nextPageUrl() }}" class="page-link">Next</a>
            @else
                <span class="disabled">Next</span>
            @endif
        </div>
    </div>
</body>

<script>
  document.addEventListener("DOMContentLoaded", () => {
    const headlines = document.querySelectorAll(".news-headline");
    const maxChars = 80; // Limit to 30 characters

    headlines.forEach((headline) => {
      if (headline.textContent.length > maxChars) {
        headline.textContent = headline.textContent.substring(0, maxChars) + "...";
      }
    });
  });
</script>
@endsection
