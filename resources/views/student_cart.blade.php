@extends('layouts.layout')

@section('content')

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif


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
        <div class="mt-3">
            <h4>Total Price: $<span id="total-price">0.00</span></h4>
        </div>

        <form action="{{ route('cart.checkout') }}" method="GET">
            <input type="hidden" name="selected_items" id="checkout-selected-items">
            <button type="submit" class="btn btn-primary mt-3" id="checkout-button">Proceed to Checkout</button>
        </form>

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

    </div>
</div>




<script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkboxes = document.querySelectorAll('.item-checkbox');
        const totalPriceDisplay = document.getElementById('total-price');
        const checkoutButton = document.getElementById('checkout-button');
        const selectedItemsInput = document.getElementById('checkout-selected-items');

        let selectedOrg = null;

        function updateTotalPrice() {
            let total = 0;
            let selectedItems = [];

            checkboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    total += parseFloat(checkbox.dataset.price);
                    selectedItems.push(checkbox.dataset.itemId);
                }
            });

            totalPriceDisplay.textContent = total.toFixed(2);

            // Enable checkout button only if items are selected
            checkoutButton.disabled = selectedItems.length === 0;

            // Update hidden form input for checkout
            selectedItemsInput.value = JSON.stringify(selectedItems);
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
    });
</script>

@endsection
