@extends('layouts.auth')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0">Order #{{ $order->order_number }}</h1>
        @if ($order->status == 'Pending')
            @if ($order->type_of_service == 'Delivery')
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#confirmRequestModal">
                    Accept Order Request
                </button>
            @else
                <button type="button" class="btn btn-success" data-bs-toggle="modal"
                    data-bs-target="#confirmPickUpRequestModal">
                    Accept Order Request
                </button>
            @endif
        @endif
    </div>

    <!-- Order Progress -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-3"><i class="fa fa-list-alt text-dark me-2"></i></i> Order Status</h5>
                    <div class="d-flex justify-content-between align-items-center my-4 position-relative">
                        @php
                            $steps = [
                                'Pending' => 'clock',
                                'Accepted' => 'box',
                                'Delivered' => 'check-circle',
                            ];
                            $currentStatus = $order->status;
                            $statuses = array_keys($steps);
                            $currentIndex = array_search($currentStatus, $statuses);
                        @endphp

                        @foreach ($steps as $label => $icon)
                            <div class="text-center flex-fill position-relative">
                                <!-- Circle -->
                                <div
                                    class="rounded-circle step-icon 
                        @if ($loop->index <= $currentIndex) bg-success text-white 
                        @else bg-light text-dark border @endif
                        d-flex align-items-center justify-content-center mx-auto">
                                    <i class="fa fa-{{ $icon }}"></i>
                                </div>

                                <!-- Label -->
                                <small
                                    class="d-block mt-2 {{ $loop->index <= $currentIndex ? 'fw-bold text-success' : 'text-muted' }}">
                                    {{ $label }}
                                </small>

                                <!-- Connector -->
                                @if (!$loop->last)
                                    <div
                                        class="step-connector 
                            @if ($loop->index < $currentIndex) bg-success 
                            @else bg-light @endif">
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Styling -->
        <style>
            .step-icon {
                width: 50px;
                height: 50px;
                font-size: 20px;
                z-index: 2 !important;
            }

            .step-connector {
                position: absolute;
                top: 25px;
                /* center align with icon */
                left: 56.4%;
                width: 88.5%;
                height: 4px;
                z-index: 1;
            }
        </style>
    </div>

    <!-- Customer & Delivery Info -->
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-3"><i class="fa fa-user me-2"></i> Customer Details</h5>
                    <p class="m-0"><strong>Name:</strong>
                        {{ $order->user->user_type == 'admin' ? 'Walk-in' : $order->user->name }}</p>
                    @if ($order->user->user_type == 'admin')
                    @else
                        <p class="m-0"><strong>Phone:</strong> {{ $order->user->phone_number ?? 'N/A' }}</p>
                        <p class="m-0"><strong>Email:</strong> {{ $order->user->email ?? 'N/A' }}</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-3"><i class="fa fa-truck me-2"></i> Delivery Info</h5>
                    <p class="m-0"><strong>Type of Service:</strong> {{ $order->type_of_service }}</p>
                    @if ($order->type_of_service == 'Pick-up')
                        <p class="m-0"><strong>Pick-up Date:</strong>
                            {{ $order->pick_up_datetime ? \Carbon\Carbon::parse($order->pick_up_datetime)->format('F d, Y h:i A') : '' }}
                        </p>
                        <p class="m-0"><strong>Comment:</strong> {{ $order->comment ?? '' }}</p>
                    @elseif ($order->type_of_service == 'Walk-in')
                    @else
                        <p class="m-0"><strong>Address:</strong> {{ $order->shipping_address }}</p>
                        <p class="m-0"><strong>Delivery Date:</strong>
                            {{ \Carbon\Carbon::parse($order->delivery_date)->format('F d, Y') ?? 'Not set' }}</p>
                        <p class="m-0"><strong>Notes:</strong> {{ $order->delivery_notes ?? '' }}</p>
                        <p class="m-0"><strong>Comment:</strong> {{ $order->comment ?? '' }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @php
        if ($order->user->user_type == 'admin') {
            $num = 0;
        } else {
            $num = 4;
        }
    @endphp

    <!-- Order Items -->
    <div class="card shadow-sm mb-{{ $num }}">
        <div class="card-body">
            <h5 class="fw-bold mb-3"><i class="fa fa-shopping-cart me-2"></i> Order Items</h5>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->items as $key => $item)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td class="d-flex align-items-center">
                                    <img src="{{ asset($item->product->product_image ?? 'img/default-egg.jpg') }}"
                                        class="rounded me-2 img-fluid" width="100" height="100" alt="product">
                                    {{ $item->product->product_name }}
                                </td>
                                <td>₱{{ number_format($item->price, 2) }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td class="text-end">₱{{ number_format($item->price * $item->quantity, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="text-end fw-bold fs-5 mt-3">
                    Total: ₱{{ number_format($order->total_amount, 2) }}
                </div>
            </div>
        </div>
    </div>

    @if ($order->user->user_type == 'admin')
    @else
        <!-- Rating Section -->
        <div class="card shadow-sm mb-0">
            <div class="card-body">
                <h5 class="fw-bold"><i class="fa fa-star text-warning me-2"></i> Rating</h5>
                @if ($order->rating)
                    <p class="m-0">
                        <strong>Service Speed:</strong>
                        @for ($i = 1; $i <= 5; $i++)
                            <i class="fa{{ $i <= $order->rating->service_speed ? 's' : 'r' }} fa-star text-warning"></i>
                        @endfor
                    </p>
                    <p class="m-0">
                        <strong>Egg Quality:</strong>
                        @for ($i = 1; $i <= 5; $i++)
                            <i class="fa{{ $i <= $order->rating->egg_quality ? 's' : 'r' }} fa-star text-warning"></i>
                        @endfor
                    </p>
                    <p class="m-0">
                        <strong>Egg Size Accuracy:</strong>
                        @for ($i = 1; $i <= 5; $i++)
                            <i
                                class="fa{{ $i <= $order->rating->egg_size_accuracy ? 's' : 'r' }} fa-star text-warning"></i>
                        @endfor
                    </p>
                    <p class="mt-2"><strong>Comment:</strong> {{ $order->rating->comment ?? '' }}</p>
                @else
                    <p class="text-muted">No rating yet.</p>
                @endif
            </div>
        </div>
    @endif

    <!-- Delivery Modal -->
    <div class="modal fade" id="confirmRequestModal" tabindex="-1" aria-labelledby="confirmRequestModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form action="{{ route('order.accept') }}" method="POST" class="modal-content">
                @csrf
                <input type="hidden" name="order_id" value="{{ $order->id }}">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmRequestModalLabel">Schedule of Delivery</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-3 mb-3">
                            <label for="delivery_date" class="form-label">Date</label>
                            <input type="date" class="form-control" id="delivery_date" name="delivery_date" required>
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

    <!-- Pick-up Modal -->
    <div class="modal fade" id="confirmPickUpRequestModal" tabindex="-1"
        aria-labelledby="confirmPickUpRequestModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form action="{{ route('order.accept') }}" method="POST" class="modal-content">
                @csrf
                <input type="hidden" name="order_id" value="{{ $order->id }}">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmPickUpRequestModalLabel">Pick-up Confirmation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="comment" class="form-label">Comment <i>(optional)</i></label>
                        <textarea name="comment" id="comment" class="form-control" rows="3"></textarea>
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
