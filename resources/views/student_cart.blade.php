@extends('layouts.layout')

@section('content')

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<section class='filler'>
    
</section>

<div class="cart-container">
    <div class="cart-items-ctn">
        <h1>Your Cart</h1>
        @if($cartItems->isEmpty())
            <p>Your cart is currently empty.</p>
        @else
            @foreach($cartItems as $item)
                <div class="cart-item">
                    <div class="row align-items-center">
                        <div class="col-md-1">
                            <input type="checkbox" class="item-checkbox" data-price="{{ $item->price }}" data-item-id="{{ $item->id }}">
                        </div>
                        <div class="col-md-3">
                            <p><strong>Product:</strong> {{ $item->name }}</p>
                            <p><strong>Organization:</strong> {{ $item->org }}</p>
                        </div>
                        <div class="col-md-1">
                            <p><strong>Size:</strong> {{ ucfirst($item->size) }}</p>
                        </div>
                        <div class="col-md-1">
                            <p><strong>Quantity:</strong> {{ $item->quantity }}</p>
                        </div>
                        <div class="col-md-2">
                            <p><strong>Price:</strong> ${{ number_format($item->price, 2) }}</p>
                        </div>
                        <div class="col-md-2">
                            @php
                                $photos = json_decode($item->photos, true);
                            @endphp
                            @if(is_array($photos) && count($photos) > 0)
                                @foreach($photos as $photo)
                                    <img src="{{ asset('storage/' . $photo) }}" alt="Product Image" width="50">
                                @endforeach
                            @else
                                <span>No images</span>
                            @endif
                        </div>
                        <div class="col-md-1">
                            <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger">Remove</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <div class="checkout-ctn">
        <div class="mt-3">
            <h4>Total Price: $<span id="total-price">0.00</span></h4> <!-- Updated total price display -->
        </div>

        <!-- Checkout Section (Visible) -->
        <h5>Student Details</h5>
        <p><strong>Name:</strong> {{ $firstname }} {{ $middlename }} {{ $lastname }}</p>
        <p><strong>Student ID:</strong> {{ $student_id }}</p>
        <p><strong>Course:</strong> {{ $course->name }}</p>

        <!-- Payment Options -->
        <h5>Payment Method</h5>
        <div>
            <input type="radio" id="cash-payment" name="payment_method" value="cash"> Cash Payment
        </div>
        <div>
            <input type="radio" id="gcash-payment" name="payment_method" value="gcash"> GCash Payment
        </div>

        <!-- GCash Reference Number -->
        <div id="gcash-reference-div">
            <label for="gcash-reference">GCash Reference Number:</label>
            <input type="text" id="gcash-reference" name="gcash-reference" class="form-control">
        </div>

        <!-- Place Order Button -->
        <form action="" method="POST" id="checkout-form">
            @csrf
            <input type="hidden" name="selected_items" id="selected-items">
            <button type="submit" class="btn btn-success mt-3">Place Your Order</button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('.item-checkbox');
        const totalPriceDisplay = document.getElementById('total-price');
        const selectedItemsInput = document.getElementById('selected-items');

        function updateTotalPrice() {
            let total = 0;
            let selectedItems = [];

            checkboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    total += parseFloat(checkbox.dataset.price);
                    selectedItems.push(checkbox.dataset.itemId);
                }
            });

            // Update the total price display with the calculated value
            totalPriceDisplay.textContent = total.toFixed(2);
            selectedItemsInput.value = selectedItems.join(',');
        }

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateTotalPrice);
        });
    });
</script>

@endsection
