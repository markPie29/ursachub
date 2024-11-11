@extends('layouts.layout')

@section('content')
<section class="filler-div">

</section>

    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h2>{{ $news->name }}</h2>
                <p>{{ $news->org }}</p>
                <p>{{ $news->content }}</p>
                @php
                    $photos = json_decode($news->photos, true); // Decode the JSON column to an array
                @endphp

                @if(is_array($photos) && count($photos) > 0)
                    @foreach($photos as $photo)
                        <img src="{{ asset('storage/' . $photo) }}" alt="Product Photo" style="max-width: 100%; height: auto;">
                    @endforeach
                @else
                    <p>No images available</p>
                @endif
            </div>
        </div>
    </div>
@endsection
