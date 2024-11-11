@extends('layouts.layout')

@section('content')
<section class="product-filler-div">
</section>

<div class="product-container">
    <div class="product-row">
        <div class="product-col-md-6">
            <h2 class="product-title">{{ $product->name }}</h2>
            <ul class="product-stock-list">
                <li>Stocks (per size): </li>
                <li>Small - {{ $product->small }}</li>
                <li>Medium - {{ $product->medium }}</li>
                <li>Large - {{ $product->large }}</li>
                <li>Extra Large - {{ $product->extralarge }}</li>
                <li>2 Extra Large - {{ $product->double_extralarge }}</li>
            </ul>
            
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
                    @if($product->double_extralarge > 0)
                        <option value="double_extralarge">2 Extra Large</option>
                    @endif
                </select>
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
            @if($canAddToCart)
                <button class="product-add-to-cart" id="product-add-to-cart">Add to Cart</button>
            @else
                <p style="text-align: center">This product is not available for your course.</p>
            @endif
            
        </div>
    </div>
</div>

<script>
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

    document.getElementById('product-add-to-cart').addEventListener('click', function() {
        const size = document.getElementById('product-size').value;
        const quantity = document.getElementById('product-quantity').value;
        const productId = {{ $product->id }};

        if (!size) {
            alert('Please select a size.');
            return;
        }

        fetch("{{ route('cart.add', $product->id) }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                size: size,
                quantity: quantity,
                product_id: productId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Redirect to the cart page on success
                window.location.href = "{{ route('student.cart') }}";
            } else {
                alert(data.message || 'Error adding product to cart.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to add product to cart.');
        });
    });



</script>
@endsection
