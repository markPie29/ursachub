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
                        <input type="checkbox" 
                                class="item-checkbox" 
                                data-item-id="{{ $item->id }}" 
                                data-name="{{ $item->name }}" 
                                data-size="{{ $item->size }}" 
                                data-quantity="{{ $item->quantity }}" 
                                data-price="{{ $item->price }}" 
                                data-org="{{ $item->org }}">
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

    <!-- Order message div (hidden initially) -->
    <div id="order-message" style="display: none; margin-top: 20px;">
        <p>Your order is being processed. Please wait...</p>
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
    document.addEventListener("DOMContentLoaded", function() {
        const checkboxes = document.querySelectorAll('.item-checkbox');
        const totalPriceElement = document.getElementById('total-price');
        let selectedOrg = null; // Variable to track the selected organization

        // Function to calculate the total price
        function updateTotalPrice() {
            let totalPrice = 0;
            checkboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    totalPrice += parseFloat(checkbox.getAttribute('data-price'));
                }
            });
            totalPriceElement.textContent = totalPrice.toFixed(2);
        }

        // Function to handle checkbox selection
        function handleCheckboxChange(event) {
            const checkbox = event.target;
            const itemOrg = checkbox.getAttribute('data-org');

            if (checkbox.checked) {
                // If no organization is selected, set the current one
                if (!selectedOrg) {
                    selectedOrg = itemOrg;
                }

                // If the selected item is from a different org, uncheck it and alert the user
                if (itemOrg !== selectedOrg) {
                    alert(`You can only select products from the same organization (${selectedOrg}).`);
                    checkbox.checked = false;
                }
            } else {
                // If unchecked, check if all items from the current org are deselected
                const anyChecked = Array.from(checkboxes).some(
                    cb => cb.checked && cb.getAttribute('data-org') === selectedOrg
                );

                // If no items from the org are checked, reset the selectedOrg
                if (!anyChecked) {
                    selectedOrg = null;
                }
            }

            // Update the total price
            updateTotalPrice();
        }

        // Add event listeners to all checkboxes
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', handleCheckboxChange);
        });

        // Initial calculation of total price
        updateTotalPrice();
    });

    document.addEventListener("DOMContentLoaded", function() {
        const paymentCash = document.getElementById('payment-cash');
        const paymentGcash = document.getElementById('payment-gcash');
        const gcashRefContainer = document.getElementById('gcash-ref-container');

        // Function to toggle GCash reference number field
        function toggleGcashRefField() {
            if (paymentGcash.checked) {
                gcashRefContainer.style.display = 'block';
            } else {
                gcashRefContainer.style.display = 'none';
            }
        }

        // Add event listeners to payment method radio buttons
        paymentCash.addEventListener('change', toggleGcashRefField);
        paymentGcash.addEventListener('change', toggleGcashRefField);

        // Initial toggle based on the default selected option
        toggleGcashRefField();
    });

    document.addEventListener("DOMContentLoaded", function() {
        const placeOrderButton = document.getElementById('placeorder-button');
        const checkboxes = document.querySelectorAll('.item-checkbox');

        placeOrderButton.addEventListener('click', function() {
            const selectedItems = [];

            // Gather details of selected items
            checkboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    const itemId = checkbox.getAttribute('data-item-id') || 'Unknown ID';
                    const itemName = checkbox.getAttribute('data-name') || 'Unknown Name';
                    const itemPrice = parseFloat(checkbox.getAttribute('data-price')) || 0.0;
                    const itemOrg = checkbox.getAttribute('data-org') || 'Unknown Organization';
                    const itemSize = checkbox.getAttribute('data-size') || 'N/A';
                    const itemQuantity = parseInt(checkbox.getAttribute('data-quantity'), 10) || 1;

                    selectedItems.push({
                        id: itemId,
                        name: itemName,
                        price: itemPrice,
                        organization: itemOrg,
                        size: itemSize,
                        quantity: itemQuantity
                    });
                }
            });

            // Check if at least one item is selected
            if (selectedItems.length === 0) {
                console.error("No items selected. Please select at least one item to place an order.");
            } else {
                console.log("Selected Items:", selectedItems);
            }
        });
    });


</script>

@endsection
