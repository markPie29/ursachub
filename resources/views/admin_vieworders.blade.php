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
                        <th>Payment Method</th>
                        <th>Reference Number</th>
                        <th>Proof of Payment</th>
                        <th>Order Date</th>
                        <th>Status</th>
                        <th>Claimed At</th>
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
                                    <a href="{{ $order->proof_of_payment }}" target="_blank">View Proof</a>
                                @else
                                    Pending
                                @endif
                            </td>
                            <td>{{ $order->created_at->format('d-m-Y H:i') }}</td>
                            <td>{{ ucfirst($order->status) }}</td>
                            <td>{{ $order->claimed_at ? $order->claimed_at->format('d-m-Y H:i') : 'Not claimed' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Consolidated action for the entire order group -->
            <form action="{{ route('admin.updateOrderStatus', $order_number) }}" method="POST" class="mt-3">
                @csrf
                @method('PUT')
                @if($orderGroup->first()->status === 'pending')
                    <button type="submit" name="status" value="to be claimed" class="btn btn-lg btn-primary">
                        Change to: To Be Claimed
                    </button>
                @elseif($orderGroup->first()->status === 'to be claimed')
                    <button type="button" class="btn btn-lg btn-success" onclick="openClaimedModal('{{ $order_number }}')">
                        Change to: Claimed
                    </button>
                @endif
            </form>
        @empty
            <p>No orders found for {{ $org_name }}</p>
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

<!-- Modal for Claimed Status -->
<div id="claimedModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Change Order Status to Claimed</h2>
        <form id="claimedForm" action="" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="claimed_by">Claimed by:</label>
                <input type="text" id="claimed_by" name="claimed_by" required class="form-control">
            </div>
            <input type="hidden" name="status" value="claimed">
            <button type="submit" class="btn btn-primary">Confirm</button>
        </form>
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

<script>
    function openClaimedModal(orderNumber) {
        var modal = document.getElementById("claimedModal");
        var span = document.getElementsByClassName("close")[0];
        var form = document.getElementById("claimedForm");

        modal.style.display = "block";
        form.action = "{{ route('admin.updateOrderStatus', '') }}/" + orderNumber;

        span.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    }
</script>
@endsection

