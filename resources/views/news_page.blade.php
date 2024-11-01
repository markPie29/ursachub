@extends('layouts.layout')

@section('content')

    <body>    
        <img class="hero-bg" src="URSAC.png" alt="">
        <div class="hero-text">
        <h1>News Page</h1>  
        </div>

        @foreach($news as $news)
        <a href="{{ route('show_eachnewspage', $news->id) }}">
            <div class="products_card"> 
                <p>{{ $news->name }}</p>
                <p>{{ $news->org }}</p>
            </div>
        </a>
        @endforeach


    </body>

@endsection