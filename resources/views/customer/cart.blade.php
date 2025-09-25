@extends('layouts.auth') <!-- Extend the main layout -->

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0">Cart</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Product Image</th>
                            <th>Product Name</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($cartItems->isEmpty())
                            <tr>
                                <td colspan="7" class="text-center">Your cart is empty.</td>
                            </tr>
                        @endif
                        @foreach ($cartItems as $key => $item)
                            <tr data-product-id="{{ $item->id }}">
                                <td>{{ $key + 1 }}</td>
                                <td>
                                    <img src="{{ asset($item->product->product_image ?? 'img/default-egg.jpg') }}"
                                        alt="Product Image" class="img-fluid" width="70">
                                </td>
                                <td>{{ $item->product->product_name }}</td>
                                <td>₱<span
                                        class="price">{{ number_format($item->product->product_price, 2, '.', '') }}</span>
                                </td>
                                <td width="10%">
                                    <form action="{{ route('cart.update', $item->id) }}" method="POST" class="update-form">
                                        @csrf
                                        <input type="number" name="quantity" min="1"
                                            class="form-control quantity-input" value="{{ $item->quantity }}"
                                            data-price="{{ $item->product->product_price }}">
                                    </form>
                                </td>
                                <td>₱<span class="subtotal">
                                        {{ number_format($item->product->product_price * $item->quantity, 2) }}
                                    </span></td>
                                <td>
                                    <form action="{{ route('cart.remove', $item->id) }}" method="POST"
                                        onsubmit="return confirmDelete(event)" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="text-end">
                    @if (!$cartItems->isEmpty())
                        <h4>Total: ₱{{ number_format($cartTotal, 2) }}</h4>
                    @endif
                    @if ($cartItems->isEmpty())
                        <a class="btn btn-primary" href="/shop">Order Products</a>
                    @endif
                </div>

            </div>
        </div>
    </div>
    @if (auth()->check() && $cartItems->isNotEmpty())
        <div class="d-flex justify-content-end mt-3 gap-2">
            <a class="btn btn-primary" href="/shop">Add more products</a>
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#checkoutModal">
                Proceed to Checkout
            </button>
        </div>
    @endif

    <!-- Checkout Modal -->
    <div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form action="{{ route('cart.checkout') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="checkoutModalLabel">Enter Delivery Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="useHomeAddress" name="home_address"
                                    value="1">
                                <label class="form-check-label" for="useHomeAddress">
                                    Use my home address
                                </label>
                            </div>
                            <div class="form-check ps-0 mb-3">
                                <label for="shipping_address" class="form-label">Delivery Address</label>
                                <textarea class="form-control" id="shipping_address" name="shipping_address" rows="2" required></textarea>
                            </div>

                            <div class="form-check ps-0 mb-3 col-3">
                                <label for="type_of_service" class="form-label">Type of Service</label>
                                <select name="type_of_service" class="form-control" id="type_of_service" required>
                                    <option value="" disabled selected>Select Service</option>
                                    <option value="Delivery">Delivery</option>
                                    <option value="Pick-up">Pick-up</option>
                                </select>
                            </div>

                            {{-- Landmark or Note --}}
                            <div class="form-check ps-0 mb-3 col-16" id="delivery_notes_group" style="display: none;">
                                <label for="delivery_notes" class="form-label">Additional Delivery Instructions / Landmark</label>
                                <textarea class="form-control" id="delivery_notes" name="delivery_notes" rows="3"
                                    placeholder="E.g., Near the big mall, call upon arrival, etc."></textarea>
                            </div>

                            {{-- Pick up date and time --}}
                            <div class="form-check ps-0 mb-3 col-4" id="pickup_date_group" style="display: none;">
                                <label for="pick_up_datetime" class="form-label">Pick-up Date & Time</label>
                                <input type="datetime-local" class="form-control" id="pick_up_datetime"
                                    name="pick_up_datetime" min="{{ \Carbon\Carbon::tomorrow()->format('Y-m-d\TH:i') }}">
                            </div>

                            <!-- Order Summary -->
                            <h6 class="mt-4">Order Summary</h6>
                            <table class="table table-sm table-bordered mt-2">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Product Name</th>
                                        <th>Quantity</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cartItems as $key => $item)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $item->product->product_name }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>₱{{ number_format($item->product->product_price * $item->quantity, 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="text-end mt-3">
                                <strong>Total: ₱{{ number_format($cartTotal, 2) }}</strong>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Confirm & Checkout</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

<script>
    function confirmDelete(event) {
        if (!confirm('Are you sure you want to remove this item from the cart? This action cannot be undone.')) {
            event.preventDefault();
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const quantityInputs = document.querySelectorAll('.quantity-input');

        quantityInputs.forEach(input => {
            input.addEventListener('change', function() {
                this.closest('form').submit();
            });
        });

        const useHomeCheckbox = document.getElementById('useHomeAddress');
        const addressField = document.getElementById('shipping_address');

        const homeAddress = @json(Auth::user()->home_address);
        useHomeCheckbox.addEventListener('change', function() {
            if (this.checked) {
                addressField.value = homeAddress;
                addressField.setAttribute('readonly', true);
                addressField.style.backgroundColor = '#f8f9fa';
                addressField.removeAttribute('required');
            } else {
                addressField.value = '';
                addressField.removeAttribute('readonly');
                addressField.setAttribute('required', true);
            }
        });

        const serviceType = document.getElementById("type_of_service");
        const pickupGroup = document.getElementById("pickup_date_group");
        const pickupDate = document.getElementById("pick_up_datetime");
        const deliveryGroup = document.getElementById("delivery_notes_group");
        const deliveryNotes = document.getElementById("delivery_notes");

        serviceType.addEventListener("change", function() {
            if (this.value === "Pick-up") {
                // Show Pick-up, require date
                pickupGroup.style.display = "block";
                pickupDate.setAttribute("required", "required");

                // Hide Delivery notes
                deliveryGroup.style.display = "none";
                deliveryNotes.removeAttribute("required");
                deliveryNotes.value = "";
            } else if (this.value === "Delivery") {
                // Show Delivery notes, require it
                deliveryGroup.style.display = "block";
                deliveryNotes.setAttribute("required", "required");

                // Hide Pick-up date
                pickupGroup.style.display = "none";
                pickupDate.removeAttribute("required");
                pickupDate.value = "";
            } else {
                // Default (reset both)
                pickupGroup.style.display = "none";
                pickupDate.removeAttribute("required");
                pickupDate.value = "";

                deliveryGroup.style.display = "none";
                deliveryNotes.removeAttribute("required");
                deliveryNotes.value = "";
            }
        });

    });
</script>
