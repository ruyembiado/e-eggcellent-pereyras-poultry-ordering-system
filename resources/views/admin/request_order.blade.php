@extends('layouts.auth') <!-- Extend the main layout -->

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0">Request Orders</h1>
        <a href="{{ route('order.walkin') }}" class="btn btn-primary">Add Walk-in Order</a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('order.index') }}" class="d-print-none">
                <div class="d-flex justify-content-start align-items-end gap-2 mb-3">
                    <div class="status col-12 col-lg-2 col-xl-1 ">
                        <label for="status" class="form-label mb-0">Status:</label>
                        <div class="p-0">
                            <select name="status" id="status" class="form-control form-control-sm"
                                onchange="this.form.submit()">
                                <option value="All" {{ request('status', 'All') == 'All' ? 'selected' : '' }}>All
                                </option>
                                <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="Accepted" {{ request('status') == 'Accepted' ? 'selected' : '' }}>Accepted
                                </option>
                                <option value="Delivered" {{ request('status') == 'Delivered' ? 'selected' : '' }}>Delivered
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="clear-status">
                        <a class="btn btn-danger btn-sm" href="{{ route('order.index') }}">Clear</a>
                    </div>
                </div>
            </form>
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
                                <td>
                                    {{ $order->user->user_type == 'admin' ? 'Walk-in' : $order->user->name }}</td>
                                <td>
                                    <span
                                        class="badge 
                                        @if ($order->status == 'Pending') bg-warning
                                        @elseif($order->status == 'Done') bg-success
                                        @elseif($order->status == 'Delivered') bg-success
                                        @elseif($order->status == 'Accepted') bg-success
                                        @elseif($order->status == 'Cancelled') bg-danger @endif">
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td>₱{{ number_format($order->total_amount, 2) }}</td>
                                <td>{{ $order->created_at->format('Y-m-d h:i A') }}</td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('order.view', $order->id) }}"
                                            class="btn btn-primary btn-sm">View</a>
                                        {{-- @if ($order->status == 'Pending')
                                            <form action="{{ route('order.decline') }}" onsubmit="confirmCancel(event)" method="POST">
                                                @csrf
                                                <input type="hidden" name="order_id" value="{{ $order->id }}">
                                                <button type="submit" class="btn btn-danger btn-sm">Decline</button>
                                            </form>
                                        @endif --}}
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

<script>
    function confirmCancel(event) {
        if (!confirm('Are you sure you want to decline this request order? This action cannot be undone.')) {
            event.preventDefault();
        }
    }
</script>
