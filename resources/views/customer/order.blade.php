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
                                                    onclick="return confirmReceived(event)"
                                                    class="btn btn-success btn-sm">Received Order</a>
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
                                            @if (@empty($order->rating))
                                                <a href="#" data-bs-toggle="modal"
                                                    data-bs-target="#RateModal{{ $order->id }}"
                                                    class="btn btn-warning btn-sm">Rate</a>
                                            @endif

                                            <!-- Rate Modal -->
                                            <div class="modal fade" id="RateModal{{ $order->id }}" tabindex="-1"
                                                aria-labelledby="RateModalLabel{{ $order->id }}" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <form action="{{ route('rate.store') }}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="order_id"
                                                                value="{{ $order->id }}">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title"
                                                                    id="RateModalLabel{{ $order->id }}">Rate for
                                                                    {{ $order->order_number }}</h5>
                                                            </div>
                                                            <div class="modal-body">
                                                                {{-- 1. Service Speed Delivery --}}
                                                                <div class="mb-3">
                                                                    <label class="form-label">1. Service Speed
                                                                        Delivery:</label>
                                                                    <select name="criteria[service_speed]"
                                                                        class="form-select" required>
                                                                        <option value="">Select rating</option>
                                                                        @for ($i = 5; $i >= 1; $i--)
                                                                            <option value="{{ $i }}">
                                                                                {{ $i }} -
                                                                                {{ str_repeat('⭐', $i) }}
                                                                            </option>
                                                                        @endfor
                                                                    </select>
                                                                </div>

                                                                {{-- 2. Quality of Egg Items --}}
                                                                <div class="mb-3">
                                                                    <label class="form-label">2. Quality of Egg
                                                                        Items:</label>
                                                                    <select name="criteria[egg_quality]"
                                                                        class="form-select" required>
                                                                        <option value="">Select rating</option>
                                                                        @for ($i = 5; $i >= 1; $i--)
                                                                            <option value="{{ $i }}">
                                                                                {{ $i }} -
                                                                                {{ str_repeat('⭐', $i) }}
                                                                            </option>
                                                                        @endfor
                                                                    </select>
                                                                </div>

                                                                {{-- 3. Accuration of Egg Size --}}
                                                                <div class="mb-3">
                                                                    <label class="form-label">3. Accuration of Egg
                                                                        Size:</label>
                                                                    <select name="criteria[egg_size_accuracy]"
                                                                        class="form-select" required>
                                                                        <option value="">Select rating</option>
                                                                        @for ($i = 5; $i >= 1; $i--)
                                                                            <option value="{{ $i }}">
                                                                                {{ $i }} -
                                                                                {{ str_repeat('⭐', $i) }}
                                                                            </option>
                                                                        @endfor
                                                                    </select>
                                                                </div>

                                                                {{-- Optional Comment --}}
                                                                <div class="mb-3">
                                                                    <label class="form-label">Comment (optional):</label>
                                                                    <textarea name="comment" class="form-control" rows="3" placeholder="Write your feedback..."></textarea>
                                                                </div>

                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-danger"
                                                                    data-bs-dismiss="modal">Cancel</button>
                                                                <button type="submit" class="btn btn-warning">Submit
                                                                    Rating</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
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
