@extends('layouts.layout')

@section('content')
<section class="filler-div">

</section>

<div class="container">
    <div class="row">
        <div class="col-md-6">
            <h2>{{ $product->name }}</h2>
            <ul>
                <li>Stocks (per size): </li>
                <li>Small - {{ $product->small }}</li>
                <li>Medium - {{ $product->medium }}</li>
                <li>Large - {{ $product->large }}</li>
                <li>Extra Large - {{ $product->extralarge }}</li>
                <li>2 Extra Large - {{ $product->double_extralarge }}</li>
            </ul>
            
            <p><strong>Price:</strong> ${{ $product->price }}</p>

            @php
                $photos = json_decode($product->photos, true); // Decode the JSON column to an array
            @endphp

            @if(is_array($photos) && count($photos) > 0)
                @foreach($photos as $photo)
                    <img src="{{ asset('storage/' . $photo) }}" alt="Product Photo" style="max-width: 100%; height: auto;">
                @endforeach
            @else
                <p>No images available</p>
            @endif
            
            <!-- Size Selection -->
            <div class="size-selection mt-3">
                <label for="size">Select Size:</label>
                <select id="size" class="form-select">
                    <option value="">Choose a size</option>
                    @if($product->small > 0)
                        <option value="small">Small</option>
                    @endif
                    @if($product->medium > 0)
                        <option value="medium">Medium</option>
                    @endif
                    @if($product->large > 0)
                        <option value="large">Large</option>
                    @endif
                    @if($product->extralarge > 0)
                        <option value="extralarge">Extra Large</option>
                    @endif
                    @if($product->double_extralarge > 0)
                        <option value="double_extralarge">2 Extra Large</option>
                    @endif
                </select>
            </div>

            <!-- Quantity Input -->
            <div class="quantity-selection mt-3">
                <label for="quantity">Quantity:</label>
                <div class="input-group">
                    <button class="btn btn-outline-secondary" type="button" id="minus-btn">-</button>
                    <input type="number" id="quantity" class="form-control" value="1" min="1" style="width: 50px;">
                    <button class="btn btn-outline-secondary" type="button" id="plus-btn">+</button>
                </div>
            </div>

            <!-- Add to Cart Button -->
            <button class="btn btn-primary mt-3" id="add-to-cart">Add to Cart</button>
        </div>
    </div>
</div>

<script>
    document.getElementById('plus-btn').addEventListener('click', function() {
        let quantityInput = document.getElementById('quantity');
        quantityInput.value = parseInt(quantityInput.value) + 1;
    });

    document.getElementById('minus-btn').addEventListener('click', function() {
        let quantityInput = document.getElementById('quantity');
        if (quantityInput.value > 1) {
            quantityInput.value = parseInt(quantityInput.value) - 1;
        }
    });

    document.getElementById('add-to-cart').addEventListener('click', function() {
        const size = document.getElementById('size').value;
        const quantity = document.getElementById('quantity').value;
        // Here you would typically send the data to your backend to add the item to the cart
        alert(`Adding ${quantity} of size ${size} to cart!`);
    });
</script>
@endsection
