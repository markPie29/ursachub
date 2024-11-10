@extends('layouts.layout')

@section('content')
<div class="container">
    <h1>Your Cart</h1>

    @if($cartItems->isEmpty())
        <p>Your cart is currently empty.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Organization</th>
                    <th>Size</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Photos</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cartItems as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->org }}</td>
                        <td>{{ ucfirst($item->size) }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>${{ number_format($item->price, 2) }}</td>
                        <td>
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
                        </td>
                        <td>
                            <!-- Form to remove the item -->
                            <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                @csrf
                                @method('POST') <!-- POST method is used for form submission -->
                                <button type="submit" class="btn btn-danger">Remove</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <a href="" class="btn btn-primary">Proceed to Checkout</a>
    @endif
</div>
@endsection
