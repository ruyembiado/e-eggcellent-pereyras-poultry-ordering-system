@extends('layouts.auth') <!-- Extend the main layout -->

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0">Orders</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
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
                        @foreach ($orders as $key => $order)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $order->order_number }}</td>
                                <td>{{ $order->user->name ?? 'N/A' }}</td> <!-- Display user name -->
                                <td>
                                    <span class="badge 
                                        @if($order->status == 'Pending') bg-warning
                                        @elseif($order->status == 'Done') bg-success
                                        @elseif($order->status == 'Accepted') bg-info
                                        @elseif($order->status == 'Cancelled') bg-danger
                                        @endif">
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td>₱{{ number_format($order->total_amount, 2) }}</td>
                                <td>{{ $order->created_at->format('Y-m-d H:i A') }}</td>
                                <td>
                                    <a href="{{ route('order.view', $order->id) }}" class="btn btn-primary btn-sm">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection