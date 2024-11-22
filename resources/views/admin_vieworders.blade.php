@extends('layouts.admin_layout')

@section('content')
<section class="filler"></section>

<div class="orders-container">
    <h1>Track Orders</h1>
    <h2>{{ $org_name }}</h2>

    <!-- Search Form -->
    <form action="{{ route('admin.trackOrders') }}" method="GET" class="mb-4">
        <div class="input-group">
            <input 
                type="text" 
                name="search" 
                value="{{ request('search') }}" 
                class="form-control" 
                placeholder="Search by Product Name or Order Number"
            >
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>

    <div class="orders-table-container">
        @forelse ($orders as $order_number => $orderGroup)
            @if($orderGroup->first()->status === 'claimed')
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
                                    <a href="{{ $order->proof_of_payment }}" target="_blank">View Proof</a>
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

            <!-- Status change buttons -->
            <form action="{{ route('admin.updateOrderStatus', $order_number) }}" method="POST" class="mt-3 d-flex justify-content-center">
                @csrf
                @method('PUT')
                <div class="btn-group" role="group" aria-label="Change order status">
                    <button type="submit" name="status" value="pending" class="btn btn-lg btn-secondary" {{ $orderGroup->first()->status === 'pending' ? 'disabled' : '' }}>
                        Change to: Pending
                    </button>
                    <button type="submit" name="status" value="to be claimed" class="btn btn-lg btn-primary" {{ $orderGroup->first()->status === 'to be claimed' ? 'disabled' : '' }}>
                        Change to: To Be Claimed
                    </button>
                    <button type="button" class="btn btn-lg btn-success" onclick="openClaimedModal('{{ $order_number }}')" {{ $orderGroup->first()->status === 'claimed' ? 'disabled' : '' }}>
                        Change to: Claimed
                    </button>
                </div>
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

