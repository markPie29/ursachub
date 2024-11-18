@extends('layouts.layout')

@section('content')

<section class='filler'></section>

<div class="cart-container">
    <div class="cart-items-ctn">
        <h1>Your Cart</h1>
        @if($cartItems->isEmpty())
            <p>Your cart is currently empty.</p>
        @else
            @foreach($cartItems as $item)
                <div class="cart-item">
                    <div class="cart-item-details">
                        <p><strong>Product:</strong> {{ $item->name }}</p>
                        <p><strong>Organization:</strong> {{ $item->org }}</p>
                        <p><strong>Size:</strong> {{ ucfirst($item->size) }}</p>
                        <p><strong>Quantity:</strong> {{ $item->quantity }}</p>
                        <p><strong>Price:</strong> ${{ number_format($item->price, 2) }}</p>
                        <input type="checkbox" class="item-checkbox" data-price="{{ $item->price }}" data-item-id="{{ $item->id }}" data-org="{{ $item->org }}">
                    </div>

                    <div class="cart-item-images">
                        @php
                            $photos = json_decode($item->photos, true);
                        @endphp
                        @if(is_array($photos) && count($photos) > 0)
                            @foreach($photos as $photo)
                                <img src="{{ asset('storage/' . $photo) }}" alt="Product Image" width="50">
                            @endforeach
                        @else
                            <span>No images available</span>
                        @endif
                    </div>

                    <div class="cart-item-remove">
                        <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger">Remove</button>
                        </form>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <div class="checkout-ctn">
        <div class="user-details">
            <h4>Student Information</h4>
            <p><strong>Name:</strong>{{ $lastname }}, {{ $firstname }} {{ $middlename }}</p>
            <p><strong>Course:</strong> {{ $course->name }}</p>
            <p><strong>Student ID:</strong> {{ $student_id }}</p>
        </div>

        <div class="mt-3">
            <h4>Total Price: $<span id="total-price">0.00</span></h4>
        </div>

        <div class="payment-options mt-3">
            <h4>Payment Method</h4>
            <div>
                <input type="radio" name="payment_method" id="payment-cash" value="cash" checked>
                <label for="payment-cash">Cash</label>
            </div>
            <div>
                <input type="radio" name="payment_method" id="payment-gcash" value="gcash">
                <label for="payment-gcash">GCash</label>
            </div>
            <div id="gcash-ref-container" style="display: none; margin-top: 10px;">
                <label for="gcash-ref">Reference Number:</label>
                <input type="text" id="gcash-ref" name="gcash_ref" class="form-control" placeholder="Enter reference number">
            </div>
        </div>

        <!-- Hidden input to store selected payment method -->
        <input type="hidden" id="selected-payment-method" name="payment_method">

        <button type="button" class="btn btn-primary mt-3" id="placeorder-button">Place Order</button>
    </div>
</div>

@if($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkboxes = document.querySelectorAll('.item-checkbox');
        const totalPriceDisplay = document.getElementById('total-price');
        const checkoutButton = document.getElementById('placeorder-button');
        const selectedItemsInput = document.getElementById('checkout-selected-items');
        const paymentCash = document.getElementById('payment-cash');
        const paymentGcash = document.getElementById('payment-gcash');
        const gcashRefContainer = document.getElementById('gcash-ref-container');
        const selectedPaymentMethod = document.getElementById('selected-payment-method');

        let selectedOrg = null;
        let selectedItemIds = []; // Store selected item IDs

        function updateTotalPrice() {
            let total = 0;
            let selectedItems = [];

            checkboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    const itemId = checkbox.dataset.itemId;
                    const size = checkbox.closest('.cart-item').querySelector('.cart-item-details p:nth-child(3)').textContent.split(': ')[1];
                    const quantity = parseInt(checkbox.closest('.cart-item').querySelector('.cart-item-details p:nth-child(4)').textContent.split(': ')[1]);
                    const productId = checkbox.dataset.itemId;

                    if (quantity <= 0) {
                        alert('Quantity must be greater than 0.');
                        checkbox.checked = false;
                        return;
                    }

                    total += parseFloat(checkbox.dataset.price) * quantity;

                    selectedItems.push({
                        item_id: itemId,
                        size: size,
                        quantity: quantity,
                        product_id: productId,
                    });

                    // Add item ID to the array if it's checked
                    if (checkbox.checked) {
                        selectedItemIds.push(itemId); // Store the selected item's ID
                    }
                }
            });

            totalPriceDisplay.textContent = total.toFixed(2);

            // Update hidden input for the selected items
            selectedItemsInput.value = JSON.stringify(selectedItems);

            // Enable/disable checkout button
            checkoutButton.disabled = selectedItems.length === 0;
        }

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                const currentOrg = checkbox.dataset.org;

                if (checkbox.checked) {
                    if (selectedOrg === null) {
                        selectedOrg = currentOrg;
                    } else if (selectedOrg !== currentOrg) {
                        alert('You can only select products from the same organization.');
                        checkbox.checked = false;
                        return;
                    }
                }

                if (![...checkboxes].some(cb => cb.checked)) {
                    selectedOrg = null;
                }

                updateTotalPrice();
            });
        });

        // Payment option toggle
        paymentCash.addEventListener('change', function () {
            if (this.checked) {
                gcashRefContainer.style.display = 'none';
                if (selectedPaymentMethod) {
                    selectedPaymentMethod.value = 'cash';  // Only set value if element exists
                }
            }
        });

        paymentGcash.addEventListener('change', function () {
            if (this.checked) {
                gcashRefContainer.style.display = 'block';
                if (selectedPaymentMethod) {
                    selectedPaymentMethod.value = 'gcash';  // Only set value if element exists
                }
            }
        });

        // Initialize total price
        updateTotalPrice();

        // When the checkout button is clicked, submit the selected item IDs with JS
        checkoutButton.addEventListener('click', function () {
            const selectedItems = selectedItemIds;

            if (selectedItems.length === 0) {
                alert('Please select items to proceed.');
                return;
            }

            // Send the selected items and payment method to the server via AJAX
            fetch('{{ route("cart.checkout") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({
                    selected_items: selectedItems,
                    payment_method: selectedPaymentMethod.value,
                    gcash_ref: document.getElementById('gcash-ref').value, // Include the GCash reference if applicable
                }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = data.redirect_url; // Redirect on success
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
        });
    });
</script>

@endsection
