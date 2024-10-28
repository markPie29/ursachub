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
        <form action="{{ route('addproduct') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div>
                <label for="org">Organization:</label>
                <input type="text" name="org" id="org" required>
            </div>

            <div>
                <label for="name">News / Headline :</label>
                <input type="text" name="name" id="name" required>
            </div>

            <div>
                <label for="content">Content:</label>
                <input type="text" name="content" id="content" required>
            </div>

            <div>
                <label for="photos">Photos (max 5):</label>
                <input type="file" name="photos[]" id="photos" multiple>
            </div>
            
            <button type="submit">Publish News</button>
        </form>

    </div>

    <script>
        function toggleStockInput(checkbox) {
            const stockInput = checkbox.parentElement.nextElementSibling; // Get the corresponding stock input
            if (checkbox.checked) {
                stockInput.style.display = 'inline'; // Show the stock input
            } else {
                stockInput.style.display = 'none'; // Hide the stock input
                stockInput.value = '0'; // Reset the input value
            }
        }
    </script>
@endsection  
