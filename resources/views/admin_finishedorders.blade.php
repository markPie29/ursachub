@extends('layouts.admin_layout')

@section('content')

<div class="admin-main">
    <div class="orders-container">
        <h1 class="main-title">Finished Orders</h1>
        <h2 class="sub-title">{{ $org_name }}</h2>

        <!-- Button to Track Orders -->
        <div class="action-button">
            <a href="{{ route('admin.trackOrders') }}" class="btn btn-secondary">Back to Track Orders</a>
        </div>

        <div class="orders-table-wrapper">
            @forelse ($orders as $order_number => $orderGroup)
                @if($orderGroup->first()->status !== 'claimed')
                    @continue
                @endif

                <div class="order-group">
                    <h3 class="order-number">Order Number: {{ $order_number }}</h3>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Size</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Payment Method</th>
                                <th>Reference Number</th>
                                <th>Proof of Payment</th>
                                <th>Claimed By</th> 
                                <th>Order Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orderGroup as $order)
                                <tr>
                                    <td>{{ $order->name }}</td>
                                    <td>{{ $order->size }}</td>
                                    <td>{{ $order->quantity }}</td>
                                    <td>₱{{ number_format($order->price, 2) }}</td>
                                    <td>
                                        @if ($order->payment_method == 'cash')
                                            <span class="badge badge-success">{{ ucfirst($order->payment_method) }}</span>
                                        @elseif ($order->payment_method == 'gcash')
                                            <span class="badge badge-gcash">{{ ucfirst($order->payment_method) }}</span>
                                        @else
                                            {{ ucfirst($order->payment_method) }}
                                        @endif
                                    </td>
                                    <td>{{ $order->payment_method == 'gcash' ? $order->reference_number : 'N/A' }}</td>
                                    <td>
                                        @if($order->payment_method == 'gcash' && $order->gcash_proof)
                                            <a href="{{ asset('storage/' . $order->gcash_proof) }}" target="_blank" class="btn btn-link">View Proof</a>
                                        @elseif($order->payment_method == 'cash' && $order->status == 'claimed')
                                            <span class="badge badge-success">Paid</span>
                                        @else
                                            <span class="badge badge-warning">Pending</span>
                                        @endif
                                    </td>
                                    <td>{{ $order->claimed_by ?? 'N/A' }}</td> 
                                    <td>{{ $order->created_at->format('d-m-Y H:i') }}</td>
                                    <td>
                                        <span class="badge badge-info">{{ ucfirst($order->status) }}</span>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @empty
                <p class="no-orders">No finished orders found for {{ $org_name }}</p>
            @endforelse
        </div>
    </div>
</div>



<!-- Notifications -->
@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<style>
    /* Retain existing styles */
    body {
        font-family: Arial, sans-serif;
        background-color: #f8f9fa;
    }

    .orders-container {
        padding: 20px;
        max-width: 1200px;
        margin: 0 auto;
        background-color: #ffffff;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .main-title {
        text-align: center;
        font-size: 2rem;
        color: #343a40;
        margin-bottom: 10px;
    }

    .sub-title {
        text-align: center;
        font-size: 1.5rem;
        color: #007bff;
        margin-bottom: 20px;
    }

    .action-button {
        text-align: right;
        margin-bottom: 20px;
    }

    .orders-table-wrapper {
        margin-bottom: 40px;
    }

    .order-group {
        margin-bottom: 30px;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 15px;
        background-color: #fdfdfe;
    }

    .order-number {
        font-size: 1.25rem;
        color: #495057;
        margin-bottom: 15px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    table th, table td {
        padding: 12px;
        text-align: center;
        border: 1px solid #dee2e6;
    }

    table th {
        background-color: #f8f9fa;
        font-weight: bold;
        color: #495057;
    }

    table tbody tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    .btn-link {
        color: #ffffff;
        text-decoration: none;
    }

    .btn-link:hover {
        text-decoration: underline;
    }

    .badge {
        display: inline-block;
        padding: 0.4em 0.8em;
        font-size: 0.9rem;
        border-radius: 0.25rem;
    }

    .badge-success {
        background-color: #28a745;
        color: #ffffff;
    }

    .badge-gcash {
        background-color: #007bff;
        color: #ffffff;
    }

    .badge-warning {
        background-color: #ffc107;
        color: #ffffff;
    }

    .badge-info {
        background-color: #17a2b8;
        color: #ffffff;
    }

    .alert {
        margin-top: 20px;
        padding: 15px;
        border-radius: 4px;
    }

    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
    }

    .alert-success {
        background-color: #d4edda;
        color: #155724;
    }

    .no-orders {
        text-align: center;
        color: #6c757d;
        font-size: 1.1rem;
    }
</style>
@endsection
