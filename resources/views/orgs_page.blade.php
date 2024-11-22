@extends('layouts.layout')

@section('content')

<section class="filler-div">

</section>

<body>

    <div class='news-products-header'>
        <h1>Orgs</h1>

        <form action="{{ route('search_orgs') }}" method="GET" class="search-form">
            <input type="text" name="query" placeholder="Search for orgs..." value="{{ request('query') }}">
            <button type="submit">Search</button>
        </form>
    </div>

    <div class="orgs-ctn">
        @foreach($orgs as $org)
            <a href="{{ route('show_eachorgs', $org->id) }}" class="orgs-card">
                <!-- Image at the Bottom -->
                <div class="orgs-page-logo">
                    @if ($org ->logo)
                        <img src="{{ asset('storage/' . $org ->logo) }}" alt="{{ $org->org }} Logo" class="logo">
                    @else
                        <p>No images available</p>
                    @endif
                </div>
                <div class="orgs-page-name">
                    <p>{{ $org->org }}</p>
                </div>
            </a>
        @endforeach
    </div>
@endsection
