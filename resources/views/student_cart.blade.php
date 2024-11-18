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
            <p><strong>Name:</strong> {{ $lastname }}, {{ $firstname }} {{ $middlename }}</p>
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
    const placeOrderButton = document.getElementById('placeorder-button');
    const paymentMethodInputs = document.querySelectorAll('input[name="payment_method"]');
    const gcashRefInput = document.getElementById('gcash-ref');
    const gcashRefContainer = document.getElementById('gcash-ref-container');

    let selectedOrg = null;

    function updateTotalPrice() {
        let totalPrice = 0;
        checkboxes.forEach(checkbox => {
            if (checkbox.checked) {
                totalPrice += parseFloat(checkbox.getAttribute('data-price'));
            }
        });
        totalPriceElement.textContent = totalPrice.toFixed(2);
    }

    function handleCheckboxChange(event) {
        const checkbox = event.target;
        const itemOrg = checkbox.getAttribute('data-org');

        if (checkbox.checked) {
            if (!selectedOrg) {
                selectedOrg = itemOrg;
            }

            if (itemOrg !== selectedOrg) {
                alert(`You can only select products from the same organization (${selectedOrg}).`);
                checkbox.checked = false;
            }
        } else {
            const anyChecked = Array.from(checkboxes).some(
                cb => cb.checked && cb.getAttribute('data-org') === selectedOrg
            );

            if (!anyChecked) {
                selectedOrg = null;
            }
        }

        updateTotalPrice();
    }

    function toggleGcashRefField() {
        gcashRefContainer.style.display = paymentMethodInputs[1].checked ? 'block' : 'none';
    }

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', handleCheckboxChange);
    });

    paymentMethodInputs.forEach(input => {
        input.addEventListener('change', toggleGcashRefField);
    });

    toggleGcashRefField();
    updateTotalPrice();

    placeOrderButton.addEventListener('click', function() {
        const selectedItems = [];
        let paymentMethod = null;
        let referenceNumber = null;

        paymentMethodInputs.forEach(input => {
            if (input.checked) {
                paymentMethod = input.value;
            }
        });

        if (paymentMethod === 'gcash') {
            referenceNumber = gcashRefInput.value.trim();
        }

        checkboxes.forEach(checkbox => {
            if (checkbox.checked) {
                const itemId = checkbox.getAttribute('data-item-id') || null;
                const itemName = checkbox.getAttribute('data-name') || null;
                const itemPrice = parseFloat(checkbox.getAttribute('data-price')) || 0.0;
                const itemOrg = checkbox.getAttribute('data-org') || null;
                const itemSize = checkbox.getAttribute('data-size') || null;
                const itemQuantity = parseInt(checkbox.getAttribute('data-quantity'), 10) || 1;

                if (itemId && itemName && itemOrg) {
                    selectedItems.push({
                        id: itemId,
                        name: itemName,
                        price: itemPrice,
                        organization: itemOrg,
                        size: itemSize,
                        quantity: itemQuantity
                    });
                } else {
                    console.warn("Missing data attributes for item:", checkbox);
                }
            }
        });

        if (selectedItems.length === 0) {
            alert("No items selected. Please select at least one item to place an order.");
            return;
        }
        if (!paymentMethod) {
            alert("Please select a payment method.");
            return;
        }
        if (paymentMethod === 'gcash' && !referenceNumber) {
            alert("Please enter the GCash reference number.");
            return;
        }

        const orderData = {
            items: selectedItems,
            payment_method: paymentMethod,
            gcash_ref: referenceNumber || null,
        };

        console.log("Submitting Order:", orderData);

        fetch("{{ route('order.place') }}", {
            method: "POST",
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(orderData)
        })
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => {
                    throw new Error(`HTTP error! Status: ${response.status}, Body: ${text}`);
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert("Order placed successfully!");
                location.reload();
            } else {
                alert("Failed to place order: " + (data.message || "Unknown error"));
            }
        })
        .catch(error => {
            console.error("Error placing order:", error);
            alert("An error occurred while placing your order. Please try again. Error details: " + error.message);
        });
    });
});
</script>


@endsection
