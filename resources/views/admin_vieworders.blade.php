@extends('layouts.admin_layout')

@section('content')
<section class="spacer"></section>

<div class="orders-container">
    <div class="header-actions">
        <h1 class="main-title">Track Orders</h1>
        <h2 class="sub-title">{{ $org_name }}</h2>

        <!-- Button to Finished Orders -->
        <div class="action-button">
            <a href="{{ route('admin.finishedOrders') }}" class="btn btn-success">View Finished Orders</a>
        </div>
    </div>

    <!-- Search Form -->
    <form action="{{ route('admin.trackOrders') }}" method="GET" class="search-form">
        <div class="input-group">
            <input 
                type="text" 
                name="search" 
                value="{{ request('search') }}" 
                class="form-control" 
                placeholder="Search by Product Name or Order Number"
            >
            <div class="input-group-append">
                <button type="submit" class="btn btn-primary">Search</button>
                <a href="{{ route('admin.trackOrders') }}" class="btn btn-secondary">Refresh</a>
            </div>
        </div>
    </form>

    <div class="orders-table-container">
        @forelse ($orders as $order_number => $orderGroup)
            @if($orderGroup->first()->status === 'claimed')
                @continue
            @endif

            <!-- Order Details -->
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
                                    <span class="{{ $order->payment_method == 'cash' ? 'text-success' : 'text-primary' }}">
                                        {{ ucfirst($order->payment_method) }}
                                    </span>
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
                                <td>{{ $order->created_at->format('d-m-Y H:i') }}</td>
                                <td>
                                    <span class="badge badge-info">{{ ucfirst($order->status) }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Status Change Buttons -->
                <form action="{{ route('admin.updateOrderStatus', $order_number) }}" method="POST" class="status-form">
                    @csrf
                    @method('PUT')
                    <div class="button-container">
                        <button 
                            type="submit" 
                            name="status" 
                            value="pending" 
                            class="btn btn-secondary"
                            {{ $orderGroup->first()->status === 'pending' ? 'disabled' : '' }}>
                            Pending
                        </button>
                        <button 
                            type="submit" 
                            name="status" 
                            value="to be claimed" 
                            class="btn btn-primary"
                            {{ $orderGroup->first()->status === 'to be claimed' ? 'disabled' : '' }}>
                            To Be Claimed
                        </button>
                        <button 
                            type="button" 
                            class="btn btn-success" 
                            onclick="openClaimedModal('{{ $order_number }}')"
                            {{ $orderGroup->first()->status === 'claimed' ? 'disabled' : '' }}>
                            Claimed
                        </button>
                    </div>
                </form>
            </div>
        @empty
            <p class="no-orders">No orders found for {{ $org_name }}</p>
        @endforelse
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

<!-- Modal for Claimed Status -->
<div id="claimedModal" class="modal">
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
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        max-width: 1200px;
        margin: auto;
    }

    .header-actions {
        text-align: center;
        margin-bottom: 20px;
    }

    .main-title {
        font-size: 2rem;
        color: #343a40;
    }

    .sub-title {
        font-size: 1.5rem;
        color: #007bff;
    }

    .action-button {
        text-align: center;
        margin-top: 10px;
    }

    .search-form {
        margin-bottom: 20px;
    }

    .input-group {
        max-width: 600px;
        margin: auto;
    }

    .order-group {
        margin-bottom: 30px;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 8px;
        background-color: #f9f9f9;
    }

    .order-number {
        font-size: 1.25rem;
        margin-bottom: 15px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    table th, table td {
        text-align: center;
        padding: 10px;
        border: 1px solid #ddd;
    }

    table th {
        background-color: #f8f9fa;
        color: #333;
        font-weight: bold;
    }

    .btn-link {
        color: #007bff;
        text-decoration: none;
    }

    .btn-link:hover {
        text-decoration: underline;
    }

    .badge {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 5px;
    }

    .badge-success {
        background-color: #28a745;
        color: white;
    }

    .badge-warning {
        background-color: #ffc107;
        color: white;
    }

    .badge-info {
        background-color: #17a2b8;
        color: white;
    }

    .no-orders {
        text-align: center;
        color: #6c757d;
    }

    .modal {
        display: none;
        position: fixed;
        z-index: 1050;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
        align-items: center;
        justify-content: center;
    }

    .modal-content {
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        width: 400px;
        margin: auto;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-control {
        width: 100%;
        padding: 8px;
        border-radius: 5px;
    }

    .button-container {
    display: flex;
    justify-content: space-between; /* Distribute buttons evenly */
    align-items: center;
    max-width: 600px; /* Limit the width of the button row */
    margin: 20px auto; /* Center the container */
    gap: 10px; /* Add spacing between buttons */
}

.button-container .btn {
    flex: 1; /* Ensure buttons are of equal size */
    padding: 10px 15px;
    font-size: 16px;
    text-align: center;
    border-radius: 8px;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

.button-container .btn:hover {
    transform: scale(1.05); /* Slightly enlarge button on hover */
}

.button-container .btn:disabled {
    opacity: 0.6; /* Dim disabled buttons */
    cursor: not-allowed;
}

</style>

<script>
    function openClaimedModal(orderNumber) {
        const modal = document.getElementById("claimedModal");
        const span = document.querySelector(".modal .close");
        const form = document.getElementById("claimedForm");

        modal.style.display = "flex";
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
