@extends('layouts.auth') <!-- Extend the main layout -->

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0">View Order - #{{ $order->order_number }}</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="mb-4">
                <h4>Order Details</h4>
                <p><strong>Name:</strong> {{ $order->user->name ?? 'N/A' }}</p>
                <p><strong>Status:</strong> 
                    <span class="badge 
                        @if($order->status == 'Pending') bg-warning
                        @elseif($order->status == 'Completed') bg-success
                        @elseif($order->status == 'Cancelled') bg-danger
                        @endif">
                        {{ $order->status }}
                    </span>
                </p>
                <p><strong>Total Amount:</strong> ₱{{ number_format($order->total_amount, 2) }}</p>
                <p><strong>Order Date:</strong> {{ $order->created_at->format('Y-m-d H:i A') }}</p>
            </div>

            <h4>Order Items</h4>
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Product Image</th>
                            <th>Product Name</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->items as $key => $item)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>
                                    <img src="{{ asset($item->product->product_image) }}" alt="Product Image" class="img-fluid" width="70">
                                </td>
                                <td>{{ $item->product->product_name }}</td>
                                <td>₱{{ number_format($item->price, 2) }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>₱{{ number_format($item->subtotal, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
