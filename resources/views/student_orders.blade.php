@extends('layouts.layout')

@section('content')
<section class="filler-div">

</section>

<section class="page-header">
    <h1>My Orders</h1>
</section>

<div class="orders-container">
    @forelse ($orders as $orderNumber => $orderItems)
        <div class="order-card">
            <div class="order-header">
                <h2>Order #{{ $orderNumber }}</h2>
                <p class="order-date">Placed on: {{ $orderItems->first()->created_at->format('F d, Y') }}</p>
                <span class="order-status">{{ $orderItems->first()->status }}</span>
            </div>
            <div class="order-body">
                <table class="order-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Size</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Organization</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $orderTotal = 0; @endphp
                        @foreach ($orderItems as $item)
                            <tr>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->size }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>₱{{ number_format($item->price, 2) }}</td>
                                <td>{{ $item->org }}</td>
                            </tr>
                            @php $orderTotal += $item->price; @endphp
                        @endforeach
                    </tbody>
                </table>
                <div class="order-total">
                    <strong>Total Price:</strong> ₱{{ number_format($orderTotal, 2) }}
                </div>
            </div>
        </div>
    @empty
        <div class="no-orders">You have no orders yet.</div>
    @endforelse
</div>
@endsection
