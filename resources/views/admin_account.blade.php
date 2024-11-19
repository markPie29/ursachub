@extends('layouts.admin_layout')

@section('content')
<body>  
    <div class="account-ctn">

        <section class="filler-div">

        </section>

        <div class="main-details">
            <div>
                <h1>{{ $org_name }}</h1>
                
            </div>

            <div class="account-addbtn-ctn">
                <a href= "{{ route('addprodpage') }}"> <div class="main-button" > <i class='bx bxs-t-shirt'></i> Add Product <i class='bx bx-plus'></i></div> </a>
                <a href= "{{ route('addnewspage') }}"> <div class="main-button" > <i class='bx bx-news'></i> Add News <i class='bx bx-plus'></i></div> </a>
                <a href="{{ route('admin.orders') }}"> <div class="main-button"><i class='bx bx-list-ul'></i> Track Orders</div></a>
                <form action="{{ route('admin.logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-danger">Logout</button>
                </form>
            </div>
        </div>


        <div class="products-news-ctn-admin">
            <div class="prodnews-div-admin">
                <h1>Products</h1>
                @foreach($products as $product)
                <a href="{{ route('show_eachprodpage_admin', $product->id) }} ">
                    <div>
                        {{ $product->name }} - {{ $product->price }} - {{ $product->org }}
                    </div>
                </a>

                @endforeach
            </div>

            <div class="prodnews-div-admin">
                <h1>News</h1>
                @foreach($news as $news)
                <a href="{{ route('show_eachnewspage_admin', $news->id) }} ">
                    <div>
                        {{ $news->org }} - {{ $news->headline }} - {{ $news->content }}
                    </div>
                </a>
                @endforeach
            </div>
           
        </div>
       
    </div>
</body>
@endsection
