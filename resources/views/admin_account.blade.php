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
                
                <div>

                </div>
            </div>
           
        </div>
       
    </div>
</body>
@endsection
