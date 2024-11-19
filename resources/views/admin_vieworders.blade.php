@extends('layouts.admin_layout')

@section('content')
<section class="filler"></section>

<div class="orders-container">
    <h1>Track Orders</h1>
    <h2>{{ $org_name }}</h2>

    <div class="orders-table-container">
        @forelse ($orders as $order_number => $orderGroup)
            <h3>Order Number: {{ $order_number }}</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Size</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Order Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orderGroup as $order)
                        <tr>
                            <td>{{ $order->name }}</td>
                            <td>{{ $order->size }}</td>
                            <td>{{ $order->quantity }}</td>
                            <td>${{ number_format($order->price, 2) }}</td>
                            <td>{{ $order->created_at->format('d-m-Y H:i') }}</td>
                            <td>{{ ucfirst($order->status) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Consolidated action for the entire order group -->
            <form action="{{ route('admin.updateOrderStatus', $order_number) }}" method="POST" class="mt-3">
                @csrf
                @method('PUT')
                <button type="submit" 
                        name="status" 
                        value="{{ $orderGroup->first()->status === 'pending' ? 'to be claimed' : 'pending' }}" 
                        class="btn btn-lg {{ $orderGroup->first()->status === 'pending' ? 'btn-primary' : 'btn-warning' }}">
                    Current Status: {{ ucfirst($orderGroup->first()->status) }} - Click to Change
                </button>
            </form>
        @empty
            <p>No orders found for {{ $org_name }}</p>
        @endforelse
    </div>
</div>

<style>
    .orders-container {
        padding: 20px;
    }

    .orders-table-container {
        margin-bottom: 30px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    table th, table td {
        padding: 10px;
        text-align: center;
    }

    h3 {
        margin-top: 20px;
        font-size: 18px;
    }

    form {
        text-align: center;
    }

    .btn {
        margin-top: 10px;
    }
</style>
@endsection
