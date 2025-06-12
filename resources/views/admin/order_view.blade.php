@extends('layouts.auth') <!-- Extend the main layout -->

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0">View Order - #{{ $order->order_number }}</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <h4>Order Details</h4>
                    @if ($order->status == 'Pending')
                        <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal"
                            data-bs-target="#confirmRequestModal">
                            Accept Order Request
                        </button>
                    @endif
                </div>
                <p class="m-0"><strong>Name:</strong> {{ $order->user->name ?? 'N/A' }}</p>
                <p class="m-0"><strong>Contact Number:</strong> {{ $order->user->phone_number ?? 'N/A' }}</p>
                <p class="m-0"><strong>Delivery Address:</strong> {{ $order->shipping_address }}</p>
                <p class="m-0"><strong>Status:</strong>
                    <span
                        class="badge 
                        @if ($order->status == 'Pending') bg-warning
                        @elseif($order->status == 'Done') bg-success
                        @elseif($order->status == 'Accepted') bg-success
                        @elseif($order->status == 'Delivered') bg-success
                        @elseif($order->status == 'Cancelled') bg-danger @endif">
                        {{ $order->status }}
                    </span>
                </p>
                <p class="m-0"><strong>Order Number:</strong> {{ $order->order_number }}</p>
                <p class="m-0"><strong>Order Date:</strong> {{ $order->created_at->format('Y-m-d H:i A') }}</p>
                <p class="m-0"><strong>Delivery Schedule:</strong> {{ $order->delivery_date ?? '' }}</p>
                <p class="m-0"><strong>Type of Service:</strong> {{ $order->type_of_service ?? '' }}</p>
                <p class="m-0"><strong>Comment:</strong> {{ $order->comment ?? '' }}</p>
            </div>

            @php
                function renderStars($rating)
                {
                    $stars = '';
                    for ($i = 1; $i <= 5; $i++) {
                        $stars .= $i <= $rating ? '⭐' : ''; 
                    }
                    return $stars;
                }
            @endphp

            <div class="mb-4">
                <h4>Order Rating</h4>
                @if ($order->rating)
                    <div class="mt-2">
                        <p class="m-0">
                            <strong>1. Service Speed Delivery:</strong>
                            {!! renderStars($order->rating->service_speed) !!}
                            ({{ $order->rating->service_speed }})
                        </p>
                        <p class="m-0">
                            <strong>2. Quality of Egg Items:</strong>
                            {!! renderStars($order->rating->egg_quality) !!}
                            ({{ $order->rating->egg_quality }})
                        </p>
                        <p class="m-0">
                            <strong>3. Accuration of Egg Size:</strong>
                            {!! renderStars($order->rating->egg_size_accuracy) !!}
                            ({{ $order->rating->egg_size_accuracy }})
                        </p>

                        @php
                            $average = round(
                                ($order->rating->service_speed +
                                    $order->rating->egg_quality +
                                    $order->rating->egg_size_accuracy) /
                                    3,
                                1,
                            );
                        @endphp

                        <p class="mt-1">
                            <strong>Average Rating:</strong>
                            {!! renderStars(round($average)) !!}
                            ({{ $average }})
                        </p>

                        @if ($order->rating->comment)
                            <p class="mt-2"><strong>Comment:</strong> {{ $order->rating->comment }}</p>
                        @endif
                    </div>
                @else
                    <p class="text-dark">No rating provided yet.</p>
                @endif
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
                            @php
                                $subtotal = $item->price * $item->quantity;
                            @endphp
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>
                                    <img src="{{ asset($item->product->product_image ?? 'img/default-egg.jpg') }}" alt="Product Image"
                                        class="img-fluid" width="70">
                                </td>
                                <td>{{ $item->product->product_name }}</td>
                                <td>₱{{ number_format($item->price, 2) }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>₱{{ number_format($subtotal, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="total-amount text-end">
                    <strong><p>Total Amount: ₱{{ number_format($order->total_amount, 2) }}</p></strong>
                </div>
            </div>
        </div>
    </div>

    <!-- Request Delivery Date Modal -->
    <div class="modal fade" id="confirmRequestModal" tabindex="-1" aria-labelledby="confirmRequestModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form action="{{ route('order.accept') }}" method="POST" class="modal-content">
                @csrf
                <input type="hidden" name="order_id" value="{{ $order->id }}">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmRequestModalLabel">Schedule of Delivery</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-3 mb-3">
                            <label for="delivery_date" class="form-label">Date</label>
                            <input type="date" class="form-control" id="delivery_date" name="delivery_date"
                                rows="2" required />
                        </div>
                        <div class="col-12 mb-3">
                            <label for="comment" class="form-label">Comment <i>(optional)</i></label>
                            <textarea name="comment" id="comment" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Confirm Order Request</button>
                </div>
            </form>
        </div>
    </div>
@endsection
