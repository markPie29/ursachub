@extends('layouts.layout')

@section('content')


<div class="custom-container">
    <div class="image-section">
        @php
            $photos = json_decode($news->photos, true); // Decode the JSON column to an array
        @endphp

        @if(is_array($photos) && count($photos) > 0)
            @foreach($photos as $photo)
                <img src="{{ asset('storage/' . $photo) }}" alt="Product Photo">
            @endforeach
        @else
            <p>No images available</p>
        @endif
    </div>

    <div class="text-section">
        <h2>{{ $news->headline }}</h2>
        <p>{{ $news->org }}</p>
        <p>{{ $news->content }}</p>
    </div>
</div>
@endsection
