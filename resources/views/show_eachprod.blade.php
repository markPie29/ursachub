@extends('layouts.layout')

@section('content')
<section class="filler-div"></section>

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
    let autoScrollInterval;

    // Function to show a specific image
    function showImage(index) {
        const images = document.querySelectorAll('.product-image');
        images.forEach((img, i) => {
            img.style.display = i === index ? 'block' : 'none';
        });
        currentIndex = index;
    }

    // Function to show the next image
    function showNextImage() {
        const images = document.querySelectorAll('.product-image');
        const nextIndex = (currentIndex + 1) % images.length;
        showImage(nextIndex);
    }

    // Function to show the previous image
    function showPreviousImage() {
        const images = document.querySelectorAll('.product-image');
        const prevIndex = (currentIndex - 1 + images.length) % images.length;
        showImage(prevIndex);
    }

    // Function to start auto-scroll
    function startAutoScroll() {
        autoScrollInterval = setInterval(showNextImage, 2000); // Change every 3 seconds
    }

    // Function to stop auto-scroll (optional, e.g., when interacting manually)
    function stopAutoScroll() {
        clearInterval(autoScrollInterval);
    }

    // Initialize the carousel
    document.addEventListener('DOMContentLoaded', () => {
        showImage(0); // Show the first image
        startAutoScroll(); // Start auto-scrolling

        // Optional: Pause auto-scroll when hovering over the carousel
        const carousel = document.querySelector('.carousel');
        carousel.addEventListener('mouseenter', stopAutoScroll);
        carousel.addEventListener('mouseleave', startAutoScroll);
    });


    // Customizable Error Message Function
    function showError(message) {
        alert(message);
    }

    // Size Selection and Quantity Script
    document.addEventListener('DOMContentLoaded', function() {
        const sizeSelect = document.getElementById('product-size');
        const stockDisplay = document.getElementById('product-stock');
        const addToCartButton = document.getElementById('product-add-to-cart');
        const quantityInput = document.getElementById('product-quantity');

        sizeSelect.addEventListener('change', function() {
            const selectedOption = sizeSelect.options[sizeSelect.selectedIndex];
            const stock = parseInt(selectedOption.dataset.stock) || 0;
            stockDisplay.textContent = stock;

            // Set quantity max based on stock
            quantityInput.max = stock;

            // Reset quantity if it exceeds stock
            if (parseInt(quantityInput.value) > stock) {
                quantityInput.value = stock;
            }
        });

        // Quantity controls with stock check and customizable error message
        document.getElementById('product-plus-btn').addEventListener('click', function() {
            let currentQuantity = parseInt(quantityInput.value);
            const maxQuantity = parseInt(quantityInput.max);

            if (currentQuantity < maxQuantity) {
                quantityInput.value = currentQuantity + 1;
            } else {
                showError('You cannot select more than the available stock.');
            }
        });

        document.getElementById('product-minus-btn').addEventListener('click', function() {
            let currentQuantity = parseInt(quantityInput.value);
            if (currentQuantity > 1) {
                quantityInput.value = currentQuantity - 1;
            }
        });

        // Add to Cart button with stock check and customizable error message
        addToCartButton.addEventListener('click', function() {
            const size = sizeSelect.value;
            const quantity = parseInt(quantityInput.value);
            const maxQuantity = parseInt(quantityInput.max);
            const productId = {{ $product->id }};

            if (!size) {
                showError('Please select a size.');
                return;
            }

            if (quantity > maxQuantity) {
                showError('Quantity cannot exceed available stock.');
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
                    showError(data.message || 'Error adding product to cart.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showError('Failed to add product to cart.');
            });
        });
    });
</script>
@endsection
