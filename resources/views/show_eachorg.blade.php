@extends('layouts.layout')

@section('content')
<section class="filler-div">

</section>

<div class="account-ctn">
    <div class="main-details">
        <h1>{{ $org->org }}</h1>
    </div>

    <div class="products-news-ctn-admin">
            <div class="prodnews-div-admin">
                <h1>Products</h1>
                @foreach($products as $product)
                <a href="{{ route('show_eachprodpage', $product->id) }} ">
                    <div>
                        {{ $product->name }} - Php {{ $product->price }}
                    </div>
                </a>

                @endforeach
            </div>

            <div class="prodnews-div-admin">
                <h1>News</h1>
                @foreach($news as $news)
                <a href="{{ route('show_eachnewspage', $news->id) }} ">
                    <div>
                    {{ $news->headline }}
                    </div>
                </a>
                @endforeach
            </div>
           
        </div>
</div>

@endsection
