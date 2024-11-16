@extends('layouts.layout')

@section('content')

<body>
    
    <div class="news-page-header">
        <h2>News Page</h2>  
    </div>

    <div class="news-container">
    @foreach($news as $newsx)
    <a href="{{ route('show_eachnewspage', $newsx->id) }}" class="news-card">
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
        <div class="news-card-content">
            <h3 class="news-headline">{{ $newsx->headline }}</h3>
            <p class="news-org">{{ $newsx->org }}</p>
            <p class="news-timestamp">{{ $newsx->timestamp }}</p>
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

@endsection
