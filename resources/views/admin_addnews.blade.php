@extends('layouts.admin_layout')

@section('content')
    <div class="hero-text">
        <h3>Publish News</h3>
    </div>
    <div class="container">

        {{-- Display validation errors --}}
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Product Form --}}
        <form action="/admin/addnews" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div>
                <label for="org">Organization:</label>
                <input type="text" name="org" id="org" required>
            </div>

            <div>
                <label for="headline">News / Headline :</label>
                <input type="text" name="headline" id="headline" required>
            </div>

            <div>
                <label for="content">Content:</label>
                <input type="text" name="content" id="content" required>
            </div>

            <div>
                <label for="photos">Photos (min 1, max 5):</label>
                <input type="file" name="photos[]" id="photos" required multiple accept="image/*">
            </div>
            
            <button type="submit">Publish News</button>
        </form>

    </div>
@endsection
