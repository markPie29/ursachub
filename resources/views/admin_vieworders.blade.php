@extends('layouts.admin_layout')

@section('content')

<div class="admin-main">
    <div class="orders-container">
        <div class="header-actions">
            <h1 class="main-title">Track Orders</h1>
            <h2 class="sub-title">{{ $org_name }}</h2>

            <div class="controls-container">
                <!-- View Finished Orders Button -->
                <div class="top-right-button">
                    <a href="{{ route('admin.finishedOrders') }}" class="btn btn-success">
                        View Finished Orders
                    </a>
                </div>

                <!-- Search Form -->
                <form action="{{ route('admin.trackOrders') }}" method="GET" class="search-form">
                    <div class="search-input-container">
                        <!-- Search Textbox -->
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}" 
                            class="form-control search-input" 
                            placeholder="Search"
                        >
                        <!-- Refresh Button -->
                        <a href="{{ route('admin.trackOrders') }}" class="refresh-icon">
                            <img src="/path/to/refresh-icon.svg" alt="Refresh" class="refresh-icon-img">
                        </a>
                    </div>
                    <!-- Search Button -->
                    <button type="submit" class="btn btn-primary search-btn">Search</button>
                </form>
            </div>

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
        color: #FFFF;
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

    .badge-gcash {
        background-color: #007bff;
        color: #ffffff;
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
    max-width: 800px; /* Limit the width of the button row */
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

.controls-container {
    position: relative;
    margin-bottom: 20px;
}

/* View Finished Orders Button */
.top-right-button {
    position: absolute;
    top: 0;
    right: 0;
}

.top-right-button .btn {
    padding: 10px 15px;
    font-size: 16px;
    border-radius: 8px;
    text-align: center;
}

/* Search Form */
.search-form {
    display: flex;
    flex-direction: column;
    gap: 10px;
    align-items: flex-start;
    position: relative;
    max-width: 600px;
    margin: 0 auto;
}

/* Search Input and Refresh */
.search-input-container {
    display: flex;
    align-items: center;
    width: 50%;
    position: relative;
}

.search-input {
    flex: 1;
    padding: 10px 15px;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 8px;
    margin-right: 10px;
}

.refresh-icon {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
}

.refresh-icon-img {
    width: 24px;
    height: 24px;
    cursor: pointer;
    transition: transform 0.2s ease;
}

.refresh-icon-img:hover {
    transform: scale(1.1);
}

/* Search Button */
.search-button-container {
    text-align: left;
    width: 50%;
}

.search-button-container .btn {
    padding: 10px 15px;
    font-size: 16px;
    border-radius: 8px;
    width: 50%;
    text-align: center;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

.search-button-container .btn:hover {
    transform: scale(1.05);
}

.controls-container {
    position: relative;
    margin-bottom: 20px;
    display: flex;
    justify-content: space-between; /* Space out search form and other elements */
    align-items: center;
}

/* View Finished Orders Button */
.top-right-button {
    position: absolute;
    top: 0;
    right: 0;
}

/* Search Form */
.search-form {
    display: flex;
    align-items: center;
    gap: 10px; /* Add spacing between search elements */
    flex: 1; /* Allow the search form to occupy the left side */
    max-width: 600px;
}

.search-input-container {
    display: flex;
    align-items: center;
    gap: 10px;
}

.search-input {
    padding: 10px 15px;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 8px;
    width: 300px; /* Fixed width for the search bar */
}

.refresh-icon-img {
    width: 24px;
    height: 24px;
    cursor: pointer;
    transition: transform 0.2s ease;
}

.refresh-icon-img:hover {
    transform: scale(1.1);
}

.search-btn {
    width: 50%;
    padding: 10px 15px;
    font-size: 16px;
    border-radius: 8px;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

.search-btn:hover {
    transform: scale(1.05);
}

/* Keep the middle section blank */
.controls-container::after {
    content: '';
    flex: 1;
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
