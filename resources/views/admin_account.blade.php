@extends('layouts.admin_layout')

@section('content')
<body>  
    <div class="account-ctn">

        <section class="filler-div">

        </section>

        <div class="main-details">
            <div>
                <h1>{{ $org_name }}</h1>
                <h3>{{ $org_name_full }}</h3>
            </div>

            <div class="account-addbtn-ctn">
                <a href= "{{ route('create') }}"> <div class="main-button" > <i class='bx bxs-t-shirt'></i> Add Product <i class='bx bx-plus'></i></div> </a>
                <a href= "addnews.php"> <div class="main-button" href="addnews.php"> <i class='bx bx-news'></i> Add News <i class='bx bx-plus'></i></div> </a>
            </div>
        </div>


        <div class="products-news-ctn-admin">
            <div class="prodnews-div-admin">
                <h1>Products</h1>
                @foreach($products as $product)
                <div>
                    {{ $product->name }} - {{ $product->price }} - {{ $product->org }}
                </div>
                @endforeach
            </div>

            <div class="prodnews-div-admin">
                <h1>News</h1>
                @foreach($news as $news)
                <div>
                    {{ $news->org }} - {{ $news->headline }} - {{ $news->conent }}
                    @php
                        $photos = json_decode($news->photos, true); // Decode the JSON column to an array
                    @endphp

                    @if(is_array($photos))
                        @foreach($photos as $photo)
                            <img src="{{ asset('storage/' . $photo) }}" alt="News Photo">
                        @endforeach
                    @else
                        <p>No images available</p>
                    @endif
                </div>
                @endforeach
            </div>
           
        </div>
       
    </div>
</body>
@endsection
