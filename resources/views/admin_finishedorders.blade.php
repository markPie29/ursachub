@extends('layouts.admin_layout')

@section('content')
<section class="filler"></section>

<div class="orders-container">
    <h1>Finished Orders</h1>
    <h2>{{ $org_name }}</h2>

    <!-- Button to Track Orders -->
    <div class="mb-4">
        <a href="{{ route('admin.trackOrders') }}" class="btn btn-secondary">Back to Track Orders</a>
    </div>

    <div class="orders-table-container">
        @forelse ($orders as $order_number => $orderGroup)
            @if($orderGroup->first()->status !== 'claimed')
                @continue
            @endif
            <h3>Order Number: {{ $order_number }}</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Size</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Payment Method</th>
                        <th>Reference Number</th>
                        <th>Proof of Payment</th>
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
                            <td>${{ number_format($order->price, 2) }}</td>
                            <td>{{ $order->payment_method }}</td>
                            <td>
                                @if($order->payment_method == 'gcash')
                                    {{ $order->reference_number }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                @if($order->payment_method == 'gcash')
                                    @if($order->gcash_proof)
                                        <a href="{{ asset('storage/' . $order->gcash_proof) }}" target="_blank">View Proof</a>
                                    @endif
                                @elseif($order->payment_method == 'cash' && $order->status == 'claimed')
                                    Paid
                                @else
                                    Pending
                                @endif
                            </td>
                            <td>{{ $order->created_at->format('d-m-Y H:i') }}</td>
                            <td>{{ ucfirst($order->status) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @empty
            <p>No finished orders found for {{ $org_name }}</p>
        @endforelse
    </div>
</div>

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

    .btn-group {
        display: flex;
        justify-content: center;
    }

    .btn-group .btn {
        margin: 0;
        border-radius: 0;
    }

    .btn-group .btn:first-child {
        border-top-left-radius: 0.25rem;
        border-bottom-left-radius: 0.25rem;
    }

    .btn-group .btn:last-child {
        border-top-right-radius: 0.25rem;
        border-bottom-right-radius: 0.25rem;
    }

    .alert {
        margin-top: 20px;
        padding: 15px;
        border-radius: 4px;
    }

    .alert-danger {
        background-color: #f8d7da;
        border-color: #f5c6cb;
        color: #721c24;
    }

    .alert-success {
        background-color: #d4edda;
        border-color: #c3e6cb;
        color: #155724;
    }

    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0,0,0,0.4);
    }

    .modal-content {
        background-color: #fefefe;
        margin: 15% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
        max-width: 500px;
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-control {
        width: 100%;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }
</style>

@endsection
