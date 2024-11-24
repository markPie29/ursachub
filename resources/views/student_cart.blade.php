@extends('layouts.layout')

@section('content')

<section class='filler-div'></section>

<div class="cart-container">
    <div class="cart-items-ctn">
        <h1>Cart</h1>
        @if($cartItems->isEmpty())
            <p>Your cart is currently empty.</p>
        @else
            @foreach($cartItems as $item)
                <div class="cart-item">
                                
                    <div class="cart-item-details">
                        <input type="checkbox" 
                                class="item-checkbox" 
                                data-item-id="{{ $item->id }}" 
                                data-name="{{ $item->name }}" 
                                data-size="{{ $item->size }}" 
                                data-quantity="{{ $item->quantity }}" 
                                data-price="{{ $item->price }}" 
                                data-org="{{ $item->org }}">
                                
                        <div class="cart-item-details-only">
                            <h3>{{ $item->name }}</h3>
                            <p>{{ $item->org }}</p>
                            <p>Size: <strong>{{ ucfirst($item->size) }}</strong></p>
                            <p>Quantity: <strong>{{ $item->quantity }}</strong></p>
                            <p>₱{{ number_format($item->price, 2) }}</p>
                        </div>
                    </div>

                    <div class="cart-item-images">
                        @php
                            $photos = json_decode($item->photos, true);
                        @endphp
                        @if(is_array($photos) && count($photos) > 0)
                            <img src="{{ asset('storage/' . $photos[0]) }}" alt="Product Image" width="50">
                        @else
                            <span>No images available</span>
                        @endif
                    </div>

                    <div class="cart-item-remove">
                        <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="cart-btn">Remove</button>
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

            <!-- GCash reference input, hidden by default -->
            <div id="gcash-ref-container" style="display: none; margin-top: 10px;">
                <label for="gcash-ref">Reference Number:</label>
                <input type="text" id="gcash-ref" name="gcash_ref" class="form-control" placeholder="Enter reference number">
                
                <!-- GCash details for the organization -->
                <div id="gcash-org-details" style="margin-top: 10px; display: none;">
                    <p><strong>GCash Name:</strong> <span id="gcash-name"></span></p>
                    <p><strong>GCash Number:</strong> <span id="gcash-number"></span></p>
                </div>

                <!-- New: GCash photo upload section -->
                <div id="gcash-photo-upload" style="display: none; margin-top: 10px;">
                    <label for="gcash-photo">Upload Proof of Payment:</label>
                    <input type="file" id="gcash-photo" name="gcash_photo" accept="image/*" class="form-control">
                    <img id="gcash-photo-preview" src="#" alt="Preview" style="display: none; max-width: 200px; margin-top: 10px;">
                </div>
            </div>
        </div>

        <!-- Hidden input to store selected payment method -->
        <input type="hidden" id="selected-payment-method" name="payment_method">

        <button type="button" class="cart-btn" id="placeorder-button">Place Order</button>
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
        <button id="confirm-proceed" class="cart-btn" disabled>Proceed</button>
        <button id="confirm-cancel" class="cart-btn">Cancel</button>
        <p id="timer-message" style="margin-top: 10px; color: gray;">You can proceed in <span id="timer-countdown">5</span> seconds...</p>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const checkboxes = document.querySelectorAll('.item-checkbox');
        const totalPriceElement = document.getElementById('total-price');
        const gcashRefContainer = document.getElementById("gcash-ref-container");
        const gcashOrgDetails = document.getElementById("gcash-org-details");
        const gcashNameElement = document.getElementById("gcash-name");
        const gcashNumberElement = document.getElementById("gcash-number");
        const admins = @json($admins); // Pass admins from the controller to JavaScript
        const gcashPhotoUpload = document.getElementById("gcash-photo-upload");
        const gcashPhotoInput = document.getElementById("gcash-photo");
        const gcashPhotoPreview = document.getElementById("gcash-photo-preview");

        let selectedOrg = null;

        // Function to update total price
        function updateTotalPrice() {
            let totalPrice = 0;
            checkboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    totalPrice += parseFloat(checkbox.getAttribute('data-price'));
                }
            });
            totalPriceElement.textContent = totalPrice.toFixed(2);
        }

        // Function to set GCash details for the selected organization
        function updateGcashDetails(org) {
            const admin = admins.find(admin => admin.org === org);
            if (admin) {
                gcashNameElement.textContent = admin.gcash_name || "Not provided";
                gcashNumberElement.textContent = admin.gcash_number || "Not provided";
                gcashOrgDetails.style.display = "block";
            } else {
                gcashOrgDetails.style.display = "none";
            }
        }

        // Event listener for checkbox changes
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                const itemOrg = checkbox.getAttribute('data-org');

                if (checkbox.checked) {
                    // If no organization is selected, set the current one
                    if (!selectedOrg) {
                        selectedOrg = itemOrg;
                        if (document.getElementById("payment-gcash").checked) {
                            updateGcashDetails(selectedOrg);
                        }
                    }

                    // Prevent selection of items from a different organization
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
                        gcashOrgDetails.style.display = "none"; // Hide GCash details
                    }
                }

                updateTotalPrice();
            });
        });

        // Event listener for payment method change
        const paymentMethodInputs = document.getElementsByName("payment_method");
        paymentMethodInputs.forEach(input => {
            input.addEventListener('change', function () {
                if (document.getElementById("payment-gcash").checked && selectedOrg) {
                    gcashRefContainer.style.display = "block";
                    gcashPhotoUpload.style.display = "block";
                    updateGcashDetails(selectedOrg);
                } else {
                    gcashRefContainer.style.display = "none";
                    gcashPhotoUpload.style.display = "none";
                    gcashOrgDetails.style.display = "none";
                }
            });
        });

        // Preview uploaded photo
        gcashPhotoInput.addEventListener('change', function (event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    gcashPhotoPreview.src = e.target.result;
                    gcashPhotoPreview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            }
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

            // Proceed with order submission
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

            const formData = new FormData();
            formData.append('items', JSON.stringify(selectedItems));
            formData.append('student_id', "{{ $student_id }}");
            formData.append('firstname', "{{ $firstname }}");
            formData.append('lastname', "{{ $lastname }}");
            formData.append('middlename', "{{ $middlename }}");
            formData.append('course', "{{ $course->name }}");
            formData.append('payment_method', paymentMethod);
            formData.append('reference_number', referenceNumber);

            if (paymentMethod === "gcash") {
                const referenceNumber = gcashRefInput.value.trim();
                if (!referenceNumber) {
                    alert("Please enter the GCash reference number.");
                    return;
                }
                formData.append('reference_number', referenceNumber);

                if (!gcashPhotoInput.files[0]) {
                    alert("Please upload the proof of payment for GCash.");
                    return;
                }
                formData.append('gcash_photo', gcashPhotoInput.files[0]);
            }

            fetch("{{ route('place.order') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                },
                body: formData,
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