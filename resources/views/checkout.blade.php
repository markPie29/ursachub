@extends('layouts.layout')

@section('content')

<section class='filler'></section>

<div class="checkout-container">
    <h1>Checkout</h1>

    <!-- Selected Items Summary -->
    <div class="checkout-summary">
        <h4>Selected Items</h4>
        @foreach($cartItems as $item)
            <div class="checkout-item">
                <p><strong>Product Name:</strong> {{ $item->name }}</p>
                <p><strong>Quantity:</strong> {{ $item->quantity }}</p>
                <p><strong>Organization:</strong> {{ $item->org }}</p>
                <p><strong>Price:</strong> ${{ number_format($item->price, 2) }}</p>
                <div><strong>Photos:</strong></div>
                <div class="product-images">
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
                <hr>
            </div>
        @endforeach
        <h5>Total: ${{ number_format($cartItems->sum('price'), 2) }}</h5>
    </div>

    <!-- Checkout Details -->
    <div class="checkout-details">
        <h4>Student Details</h4>
        <p><strong>Name:</strong> {{ $firstname }} {{ $middlename }} {{ $lastname }}</p>
        <p><strong>Student ID:</strong> {{ $student_id }}</p>
        <p><strong>Course:</strong> {{ $course->name }}</p>

        <h4>Payment Method</h4>
        <form action="{{ route('cart.placeorder') }}" method="POST">
            @csrf
            <input type="hidden" name="selected_items" value="{{ json_encode($cartItems->pluck('id')) }}">
            <div>
                <input type="radio" id="cash-payment" name="payment_method" value="cash" required> Cash Payment
            </div>
            <div>
                <input type="radio" id="gcash-payment" name="payment_method" value="gcash" required> GCash Payment
            </div>
            <div id="gcash-reference-div" style="display: none;">
                <label for="gcash-reference">GCash Reference Number:</label>
                <input type="text" id="gcash-reference" name="gcash_reference" class="form-control">
            </div>
            <button type="submit" class="btn btn-success mt-3">Place Order</button>
        </form>
    </div>

    <!-- Grand Total Section -->
    <div class="grand-total">
        <h4>Grand Total: ${{ number_format($cartItems->sum('price'), 2) }}</h4>
    </div>

    <!-- Cancel Button -->
    <div class="cancel-button mt-3">
        <a href="{{ route('student.cart') }}" class="btn btn-secondary">Cancel</a>
    </div>
</div>

<!-- Script for GCash Toggle -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const gcashPaymentRadio = document.getElementById('gcash-payment');
        const cashPaymentRadio = document.getElementById('cash-payment');
        const gcashReferenceDiv = document.getElementById('gcash-reference-div');

        gcashPaymentRadio.addEventListener('change', function () {
            gcashReferenceDiv.style.display = 'block';
        });

        cashPaymentRadio.addEventListener('change', function () {
            gcashReferenceDiv.style.display = 'none';
        });
    });
</script>

@endsection
