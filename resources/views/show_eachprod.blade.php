@extends('layouts.layout')

@section('content')
<section class="product-filler-div"></section>

<div class="product-container">
    <div class="product-row d-flex flex-row product-card">
        <!-- Product Image Column -->
        <div class="product-col-md-6 image-carousel">
            @php
                $photos = json_decode($product->photos, true);
            @endphp

            @if(is_array($photos) && count($photos) > 0)
                <div class="carousel">
                    @foreach($photos as $index => $photo)
                        <img src="{{ asset('storage/' . $photo) }}" alt="Product Photo" class="product-image" id="image-{{ $index }}" style="display: {{ $index === 0 ? 'block' : 'none' }};">
                    @endforeach
                </div>
                <button class="carousel-button left" onclick="showPreviousImage()">&#10094;</button>
                <button class="carousel-button right" onclick="showNextImage()">&#10095;</button>
            @else
                <p class="product-no-image">No images available</p>
            @endif
        </div>

        <!-- Product Details Column -->
        <div class="product-col-md-6 product-card-content text-center">
            <h2 class="product-title">{{ $product->name }}</h2>
            <p class="product-price"><strong>Price:</strong> ${{ number_format($product->price, 2) }}</p>

            <!-- Size Selection -->
            <label for="product-size" class="product-size-label">Select Size:</label>
            <div class="product-size-selection mt-3">
                <select id="product-size" class="product-form-select">
                    <option value="">Choose a size</option>
                    @if($product->small > 0)
                        <option value="small" data-stock="{{ $product->small }}">Small</option>
                    @endif
                    @if($product->medium > 0)
                        <option value="medium" data-stock="{{ $product->medium }}">Medium</option>
                    @endif
                    @if($product->large > 0)
                        <option value="large" data-stock="{{ $product->large }}">Large</option>
                    @endif
                    @if($product->extralarge > 0)
                        <option value="extralarge" data-stock="{{ $product->extralarge }}">Extra Large</option>
                    @endif
                    @if($product->double_extralarge > 0)
                        <option value="double_extralarge" data-stock="{{ $product->double_extralarge }}">2 Extra Large</option>
                    @endif
                </select>
            </div>

            <!-- Stock Display under Size Selection -->
            <ul class="product-stock-list">
                <li>Available Stock: <span id="product-stock" class="product-stock">-</span></li>
            </ul>

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
                <p>This product is not available for your course.</p>
            @endif
        </div>
    </div>
</div>

<script>
    // Image Carousel Script
    let currentIndex = 0;
    const images = document.querySelectorAll('.product-image');

    function showImage(index) {
        images.forEach((img, idx) => {
            img.style.display = idx === index ? 'block' : 'none';
        });
    }

    function showNextImage() {
        currentIndex = (currentIndex + 1) % images.length;
        showImage(currentIndex);
    }

    function showPreviousImage() {
        currentIndex = (currentIndex - 1 + images.length) % images.length;
        showImage(currentIndex);
    }

    // Size Selection Script
    document.addEventListener('DOMContentLoaded', function() {
        const sizeSelect = document.getElementById('product-size');
        const stockDisplay = document.getElementById('product-stock');

        sizeSelect.addEventListener('change', function() {
            const selectedOption = sizeSelect.options[sizeSelect.selectedIndex];
            const stock = selectedOption.dataset.stock || '-';
            stockDisplay.textContent = stock;
        });

        // Quantity controls
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

        // Add to Cart button
        document.getElementById('product-add-to-cart').addEventListener('click', function() {
            const size = sizeSelect.value;
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
    });
</script>
@endsection
