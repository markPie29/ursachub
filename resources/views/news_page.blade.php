@extends('layouts.layout')

@section('content')

    <body>    
        <img class="hero-bg" src="URSAC.png" alt="">
        <div class="hero-text">
        <h2>News Page</h2>  
        </div>

        <div class="news-ctn">
        @foreach($news as $newsx)
        <a href="{{ route('show_eachnewspage', $newsx->id) }}">
            <div class="products_card"> 
                <p>{{ $newsx->headline }}</p>
                <p>{{ $newsx->org }}</p>
                <p>{{ $newsx->timestamp }}</p>
            </div>
        </a>
        @endforeach
        </div>


    </body>

@endsection