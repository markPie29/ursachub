@extends('layouts.admin_layout')

@section('content')
<section class="filler"></section>

<div class="orders-container">
    <h1>Track Orders</h1>
    <h2>{{ $org_name }}</h2>

    <div class="orders-table-container">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Product Name</th>
                    <th>Organization</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->name }}</td>
                        <td>{{ $order->org }}</td>
                        <td>{{ $order->quantity }}</td>
                        <td>${{ number_format($order->total_price, 2) }}</td>
                        <td>
                            <form action="{{ route('admin.updateOrderStatus', $order->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <button type="submit" 
                                        name="status" 
                                        value="{{ $order->status === 'pending' ? 'to be claimed' : 'pending' }}" 
                                        class="btn btn-sm {{ $order->status === 'pending' ? 'btn-primary' : 'btn-warning' }}">
                                    {{ ucfirst($order->status) }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">No orders found for {{ $org_name }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
    .orders-container {
        padding: 20px;
    }

    .orders-table-container {
        max-height: 500px;
        overflow-y: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    table th, table td {
        padding: 10px;
        text-align: center;
    }
</style>
@endsection