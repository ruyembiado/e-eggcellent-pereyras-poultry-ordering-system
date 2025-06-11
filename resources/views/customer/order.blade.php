@extends('layouts.auth') <!-- Extend the main layout -->

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0">Orders</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <ul class="nav nav-tabs" id="notificationTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="pending-orders-tab" data-bs-toggle="tab" href="#pending-orders"
                        role="tab" aria-controls="pending-orders" aria-selected="true">Pending Orders</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="to-receive-tab" data-bs-toggle="tab" href="#to-receive" role="tab"
                        aria-controls="to-receive" aria-selected="false">To Receive</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="to-rate-tab" data-bs-toggle="tab" href="#to-rate" role="tab"
                        aria-controls="to-rate" aria-selected="false">To Rate</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="order-history-tab" data-bs-toggle="tab" href="#order-history" role="tab"
                        aria-controls="order-history" aria-selected="false">Order History</a>
                </li>
            </ul>
            <div class="tab-content" id="notificationTabsContent">
                <!-- Pending Orders Tab -->
                <div class="tab-pane fade show active" id="pending-orders" role="tabpanel"
                    aria-labelledby="pending-orders-tab">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable1" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Order Number</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Total Amount</th>
                                    <th>Date of Request</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders->where('status', 'Pending') as $key => $order)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $order->order_number }}</td>
                                        <td>{{ $order->user->name ?? 'N/A' }}</td> <!-- Display user name -->
                                        <td>
                                            <span
                                                class="badge 
                                        @if ($order->status == 'Pending') bg-warning
                                        @elseif($order->status == 'Done') bg-success
                                        @elseif($order->status == 'Accepted') bg-success
                                        @elseif($order->status == 'Cancelled') bg-danger @endif">
                                                {{ $order->status }}
                                            </span>
                                        </td>
                                        <td>₱{{ number_format($order->total_amount, 2) }}</td>
                                        <td>{{ $order->created_at->format('Y-m-d H:i A') }}</td>
                                        <td>
                                            <a href="{{ route('order.view', $order->id) }}"
                                                class="btn btn-primary btn-sm">View</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- To Deliver Tab -->
                <div class="tab-pane fade" id="to-receive" role="tabpanel" aria-labelledby="to-receive-tab">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable2" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Order Number</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Total Amount</th>
                                    <th>Date of Request</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders->where('status', 'Accepted') as $key => $order)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $order->order_number }}</td>
                                        <td>{{ $order->user->name ?? 'N/A' }}</td> <!-- Display user name -->
                                        <td>
                                            <span
                                                class="badge 
                                    @if ($order->status == 'Pending') bg-warning
                                    @elseif($order->status == 'Done') bg-success
                                    @elseif($order->status == 'Accepted') bg-success
                                    @elseif($order->status == 'Cancelled') bg-danger @endif">
                                                {{ $order->status }}
                                            </span>
                                        </td>
                                        <td>₱{{ number_format($order->total_amount, 2) }}</td>
                                        <td>{{ $order->created_at->format('Y-m-d H:i A') }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-1">
                                                <a href="{{ route('order.view', $order->id) }}"
                                                    class="btn btn-primary btn-sm">View</a>
                                                <a href="{{ route('order.received', $order->id) }}"
                                                    onclick="return confirmReceived(event)" class="btn btn-success btn-sm">Received Order</a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- To Rate Tab -->
                <div class="tab-pane fade" id="to-rate" role="tabpanel" aria-labelledby="to-rate-tab">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable3" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Order Number</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Total Amount</th>
                                    <th>Date of Request</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders->where('status', 'Delivered') as $key => $order)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $order->order_number }}</td>
                                        <td>{{ $order->user->name ?? 'N/A' }}</td> <!-- Display user name -->
                                        <td>
                                            <span
                                                class="badge 
                                @if ($order->status == 'Pending') bg-warning
                                @elseif($order->status == 'Done') bg-success
                                @elseif($order->status == 'Accepted') bg-success
                                @elseif($order->status == 'Delivered') bg-success
                                @elseif($order->status == 'Cancelled') bg-danger @endif">
                                                {{ $order->status }}
                                            </span>
                                        </td>
                                        <td>₱{{ number_format($order->total_amount, 2) }}</td>
                                        <td>{{ $order->created_at->format('Y-m-d H:i A') }}</td>
                                        <td>
                                            <a href="{{ route('order.view', $order->id) }}"
                                                class="btn btn-primary btn-sm">View</a>
                                            <a href="#" class="btn btn-warning btn-sm rate-button">Rate</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Order History Tab -->
                <div class="tab-pane fade" id="order-history" role="tabpanel" aria-labelledby="order-history-tab">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable4" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Order Number</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Total Amount</th>
                                    <th>Date of Request</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $key => $order)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $order->order_number }}</td>
                                        <td>{{ $order->user->name ?? 'N/A' }}</td> <!-- Display user name -->
                                        <td>
                                            <span
                                                class="badge 
                                @if ($order->status == 'Pending') bg-warning
                                @elseif($order->status == 'Done') bg-success
                                @elseif($order->status == 'Accepted') bg-success
                                @elseif($order->status == 'Delivered') bg-success
                                @elseif($order->status == 'Cancelled') bg-danger @endif">
                                                {{ $order->status }}
                                            </span>
                                        </td>
                                        <td>₱{{ number_format($order->total_amount, 2) }}</td>
                                        <td>{{ $order->created_at->format('Y-m-d H:i A') }}</td>
                                        <td>
                                            <a href="{{ route('order.view', $order->id) }}"
                                                class="btn btn-primary btn-sm">View</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
    function confirmReceived(event) {
        if (!confirm('Are you sure you have received this order?')) {
            event.preventDefault();
        }
    }
</script>