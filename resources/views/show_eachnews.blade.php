@extends('layouts.layout')

@section('content')
<section class="filler-div"></section>

<div class="custom-container">
    <div>
        <a href="{{ route('news_page' )}}" class="backbutton">
            <i class='bx bx-x'></i>
        </a>
    </div>
    <!-- Image Section with Carousel -->
    <div class="image-section carousel">
        @php
            $photos = json_decode($news->photos, true); // Decode the JSON column to an array
        @endphp

        @if(is_array($photos) && count($photos) > 0)
            @foreach($photos as $index => $photo)
                <img 
                    src="{{ asset('storage/' . $photo) }}" 
                    alt="News Photo" 
                    class="news-image" 
                    style="display: {{ $index === 0 ? 'block' : 'none' }};">
            @endforeach
            <button class="carousel-btn prev" id="carousel-prev">&#10094;</button>
            <button class="carousel-btn next" id="carousel-next">&#10095;</button>
        @else
            <p>No images available</p>
        @endif
    </div>

    <!-- Text Section -->
    <div class="text-section">
        <h1>{{ $news->org }}</h1>
        <h3>{{ $news->updated_at }}</h3>
        <h2>{{ $news->headline }}</h2>
        <p>{!! Str::of($news->content)->replaceMatches('/(https?:\/\/[^\s]+)/', '<a href="$1" target="_blank">$1</a>') !!}</p>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Image Carousel Script for News
        let currentIndex = 0;
        const images = document.querySelectorAll('.news-image');
        const prevButton = document.getElementById('carousel-prev');
        const nextButton = document.getElementById('carousel-next');

        function showImage(index) {
            images.forEach((img, idx) => {
                img.style.display = idx === index ? 'block' : 'none';
            });
        }

        function showNextImage() {
            currentIndex = (currentIndex + 1) % images.length;
            showImage(currentIndex);
        }

        function showPreviousImage() {
            currentIndex = (currentIndex - 1 + images.length) % images.length;
            showImage(currentIndex);
        }

        // Attach event listeners to carousel buttons
        prevButton.addEventListener('click', showPreviousImage);
        nextButton.addEventListener('click', showNextImage);
    });
</script>
@endsection
