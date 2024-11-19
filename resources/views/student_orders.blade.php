@extends('layouts.layout')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">My Orders</h1>

    @forelse ($orders as $orderNumber => $orderItems)
        <div class="card mb-4">
            <div class="card-header">
                <h5>Order #{{ $orderNumber }}</h5>
                <small>Placed on: {{ $orderItems->first()->created_at }}</small>
                <span class="badge bg-info text-dark float-end">
                    Status: {{ $orderItems->first()->status }}
                </span>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Size</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Organization</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orderItems as $item)
                            <tr>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->size }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>₱{{ number_format($item->price, 2) }}</td>
                                <td>{{ $item->org }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @empty
        <div class="alert alert-info">You have no orders yet.</div>
    @endforelse
</div>
@endsection
