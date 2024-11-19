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

<!-- Confirmation Modal -->
<div id="confirmation-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 9999; justify-content: center; align-items: center;">
    <div style="background: white; padding: 20px; border-radius: 8px; text-align: center; width: 300px;">
        <h3>Confirm Your Order</h3>
        <p>Are you sure you want to place this order?</p>
        <button id="confirm-proceed" class="btn btn-success" disabled>Proceed</button>
        <button id="confirm-cancel" class="btn btn-danger">Cancel</button>
        <p id="timer-message" style="margin-top: 10px; color: gray;">You can proceed in <span id="timer-countdown">5</span> seconds...</p>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
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

        // Modal and order confirmation logic
        const placeOrderButton = document.getElementById("placeorder-button");
        const confirmationModal = document.getElementById("confirmation-modal");
        const confirmProceedButton = document.getElementById("confirm-proceed");
        const confirmCancelButton = document.getElementById("confirm-cancel");
        const timerMessage = document.getElementById("timer-message");
        const timerCountdown = document.getElementById("timer-countdown");

        let proceedTimer;

        // Function to show confirmation modal
        function showConfirmationModal() {
            confirmationModal.style.display = "flex";
            confirmProceedButton.disabled = true;

            // Start the countdown timer
            let secondsRemaining = 5;
            timerCountdown.textContent = secondsRemaining;

            proceedTimer = setInterval(() => {
                secondsRemaining--;
                timerCountdown.textContent = secondsRemaining;

                if (secondsRemaining <= 0) {
                    clearInterval(proceedTimer);
                    confirmProceedButton.disabled = false;
                    timerMessage.textContent = "You can now proceed.";
                }
            }, 1000);
        }

        // Function to close confirmation modal
        function closeConfirmationModal() {
            confirmationModal.style.display = "none";
            clearInterval(proceedTimer);
            timerMessage.textContent = "You can proceed in 5 seconds...";
        }

        // Event listener for Place Order button
        placeOrderButton.addEventListener("click", function () {
            showConfirmationModal();
        });

        // Event listener for Cancel button
        confirmCancelButton.addEventListener("click", function () {
            closeConfirmationModal();
        });

        // Event listener for Proceed button
        confirmProceedButton.addEventListener("click", function () {
            closeConfirmationModal();

            // Proceed with order submission (your existing fetch code)
            const selectedItems = [];
            const checkboxes = document.querySelectorAll(".item-checkbox");
            const paymentMethodInputs = document.getElementsByName("payment_method");
            const gcashRefInput = document.getElementById("gcash-ref");

            let paymentMethod = "";
            let referenceNumber = null;

            // Gather payment details
            paymentMethodInputs.forEach(input => {
                if (input.checked) {
                    paymentMethod = input.value;
                }
            });
            if (paymentMethod === "gcash") {
                referenceNumber = gcashRefInput.value.trim();
            }

            // Gather selected items
            checkboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    selectedItems.push({
                        name: checkbox.getAttribute("data-name"),
                        size: checkbox.getAttribute("data-size"),
                        price: parseFloat(checkbox.getAttribute("data-price")),
                        org: checkbox.getAttribute("data-org"),
                        quantity: parseInt(checkbox.getAttribute("data-quantity"), 10),
                    });
                }
            });

            if (selectedItems.length === 0) {
                alert("No items selected. Please select at least one item.");
                return;
            }

            const payload = {
                items: selectedItems,
                student_id: "{{ $student_id }}",
                firstname: "{{ $firstname }}",
                lastname: "{{ $lastname }}",
                middlename: "{{ $middlename }}",
                course: "{{ $course->name }}",
                payment_method: paymentMethod,
                reference_number: referenceNumber,
            };

            fetch("{{ route('place.order') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                },
                body: JSON.stringify(payload),
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(`Order placed successfully! Order Number: ${data.order_number}`);
                        location.reload();
                    } else {
                        alert(`Failed to place order: ${data.error || "Unknown error"}`);
                    }
                })
                .catch(error => {
                    console.error("Error placing order:", error);
                    alert("An unexpected error occurred. Please check the console for more details.");
                });
        });
    });
</script>

@endsection
