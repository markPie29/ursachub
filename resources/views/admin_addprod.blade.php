@extends('layouts.admin_layout')

@section('content')
    <div class="container">
        <h1>Add New Product</h1>

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
                <label for="name">Product Name:</label>
                <input type="text" name="name" id="name" required>
            </div>
            
            <div>
                <label for="org">Organization:</label>
                <input type="text" name="org" id="org" required>
            </div>

            <div>
                <h3>Select Sizes:</h3>
                <!-- Updated Sizes -->
                <div>
                    <label>
                        <input type="checkbox" class="size-checkbox" name="sizes[]" value="XS" onchange="toggleStockInput(this)"> XS
                    </label>
                    <input type="number" name="stocks[XS]" class="stock-input" placeholder="Enter stocks" style="display:none;">
                </div>
                <div>
                    <label>
                        <input type="checkbox" class="size-checkbox" name="sizes[]" value="S" onchange="toggleStockInput(this)"> S
                    </label>
                    <input type="number" name="stocks[S]" class="stock-input" placeholder="Enter stocks" style="display:none;">
                </div>
                <div>
                    <label>
                        <input type="checkbox" class="size-checkbox" name="sizes[]" value="M" onchange="toggleStockInput(this)"> M
                    </label>
                    <input type="number" name="stocks[M]" class="stock-input" placeholder="Enter stocks" style="display:none;">
                </div>
                <div>
                    <label>
                        <input type="checkbox" class="size-checkbox" name="sizes[]" value="L" onchange="toggleStockInput(this)"> L
                    </label>
                    <input type="number" name="stocks[L]" class="stock-input" placeholder="Enter stocks" style="display:none;">
                </div>
                <div>
                    <label>
                        <input type="checkbox" class="size-checkbox" name="sizes[]" value="XL" onchange="toggleStockInput(this)"> XL
                    </label>
                    <input type="number" name="stocks[XL]" class="stock-input" placeholder="Enter stocks" style="display:none;">
                </div>
                <div>
                    <label>
                        <input type="checkbox" class="size-checkbox" name="sizes[]" value="2XL" onchange="toggleStockInput(this)"> 2XL
                    </label>
                    <input type="number" name="stocks[2XL]" class="stock-input" placeholder="Enter stocks" style="display:none;">
                </div>
                <div>
                    <label>
                        <input type="checkbox" class="size-checkbox" name="sizes[]" value="3XL" onchange="toggleStockInput(this)"> 3XL
                    </label>
                    <input type="number" name="stocks[3XL]" class="stock-input" placeholder="Enter stocks" style="display:none;">
                </div>
            </div>

            <div>
                <label for="price">Price:</label>
                <input type="number" name="price" id="price" step="0.01" required>
            </div>

            <div>
                <label for="photos">Photos (max 5):</label>
                <input type="file" name="photos[]" id="photos" multiple>
            </div>
            
            <button type="submit">Add Product</button>
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
