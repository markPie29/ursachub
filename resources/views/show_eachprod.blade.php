@extends('layouts.layout')

@section('content')
<section class="product-filler-div">
</section>

<div class="product-container">
    <div class="product-row">
        <div class="product-col-md-6">
            <h2 class="product-title">{{ $product->name }}</h2>
            
            
            <p class="product-price"><strong>Price:</strong> ${{ $product->price }}</p>

            @php
                $photos = json_decode($product->photos, true); // Decode the JSON column to an array
            @endphp

            @if(is_array($photos) && count($photos) > 0)
                @foreach($photos as $photo)
                    <img src="{{ asset('storage/' . $photo) }}" alt="Product Photo" class="product-image">
                @endforeach
            @else
                <p class="product-no-image">No images available</p>
            @endif
            
            <!-- Size Selection -->
            <div class="product-size-selection mt-3">
                <label for="product-size" class="product-size-label">Select Size:</label>
                <select id="product-size" class="product-form-select">
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
                </select>
                <small id="stock-info" class="text-muted mt-1"></small>
            </div>

            <!-- Quantity Input -->
            <div class="product-quantity-selection mt-3">
                <label for="product-quantity" class="product-quantity-label">Quantity:</label>
                <div class="product-input-group">
                    <button class="product-btn-minus" type="button" id="product-minus-btn">-</button>
                    <input type="number" id="product-quantity" class="product-quantity-input" value="1" min="1">
                    <button class="product-btn-plus" type="button" id="product-plus-btn">+</button>
                </div>
            </div>

            <!-- Add to Cart Button -->
            <button class="product-add-to-cart" id="product-add-to-cart">Add to Cart</button>
        </div>
    </div>
</div>

<script>
    // Quantity Increment/Decrement
    document.getElementById('product-plus-btn').addEventListener('click', function() {
        let quantityInput = document.getElementById('product-quantity');
        quantityInput.value = parseInt(quantityInput.value) + 1;
    });

    document.getElementById('product-minus-btn').addEventListener('click', function() {
        let quantityInput = document.getElementById('product-quantity');
        if (quantityInput.value > 1) {
            quantityInput.value = parseInt(quantityInput.value) - 1;
        }
    });

    // Update Stock Info Based on Selected Size
    document.getElementById('product-size').addEventListener('change', function() {
        const stockInfo = document.getElementById('stock-info');
        const size = this.value;
        
        // Define the stock data
        const stockData = {
            small: {{ $product->small }},
            medium: {{ $product->medium }},
            large: {{ $product->large }},
            extralarge: {{ $product->extralarge }},
        };

        // Update the stock information based on the selected size
        if (size && stockData[size] !== undefined) {
            stockInfo.textContent = `Available stock: ${stockData[size]}`;
        } else {
            stockInfo.textContent = ''; // Clear text if no valid size is selected
        }
    });

    // Add to Cart Button
    document.getElementById('product-add-to-cart').addEventListener('click', function() {
        const size = document.getElementById('product-size').value;
        const quantity = document.getElementById('product-quantity').value;
        alert(`Adding ${quantity} of size ${size} to cart!`);
    });
</script>
@endsection
