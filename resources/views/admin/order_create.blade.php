@extends('layouts.auth') <!-- Extend the main layout -->

@section('content')
    <!-- Start the content section -->
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0">Add Walk-in Order</h1>
    </div>
    <div class="container-fluid p-0">
        <div class="row">
            <!-- Left Column: Products -->
            <div class="col-md-7">
                <div class="card shadow-sm">
                    <div class="card-header text-dark bg-light">
                        <span class="mb-0 fw-bold">Products</span>
                    </div>
                    <div class="card-body">
                        <input type="text" id="search" class="form-control mb-3" placeholder="Search product...">
                        <div id="product-list" class="row">
                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                                @forelse ($products as $product)
                                    <div class="card product-card-{{ $product->id }} mb-2 col-6 border text-start"
                                        style="width: 49%;">
                                        <div class="d-flex">
                                            <img src="{{ asset($product->product_image ?? 'img/default-egg.jpg') }}"
                                                style="width: 150px; height: auto; object-fit: cover;"
                                                class="img-fluid rounded-start" alt="{{ $product->product_name }}">
                                            <div class="p-2">
                                                <h6 class="m-0">{{ $product->product_name }}</h6>
                                                @if ($product->status == 'Available')
                                                    <p class="card-text m-0">Stock: {{ $product->quantity }}</p>
                                                @endif
                                                <span
                                                    class="badge {{ $product->status == 'Available' ? 'bg-success' : 'bg-danger' }}">
                                                    {{ ucfirst($product->status) }}
                                                </span>
                                                <p class="text-dark mb-1">₱{{ number_format($product->product_price, 2) }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-center">No products available.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Order & Payment -->
            <div class="col-md-5">
                <div class="card shadow-sm">
                    <div class="card-header bg-light text-dark">
                        <span class="mb-0 fw-bold">Order Summary</span>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('order.walkin.store') }}" method="POST">
                            @csrf
                            <table class="table table-sm borderless">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Qty</th>
                                        <th>Price</th>
                                        <th>Subtotal</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="order-summary">
                                    {{-- Show no items first --}}
                                    <tr>
                                        <td colspan="5" class="text-center">No items added yet.</td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="mb-3 col-3 mx-start">
                                <label class="form-label fw-bold">Total</label>
                                <div class="d-flex align-items-center">
                                    <span class="me-1">₱</span><input type="text" name="total_amount" id="total"
                                        class="form-control" value="0.00" readonly>
                                </div>
                            </div>
                            <button class="btn btn-success w-100">Complete Order</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const productCards = document.querySelectorAll('#product-list .card');
        const orderSummary = document.getElementById('order-summary');
        const totalField = document.getElementById('total');

        let order = [];

        // Click product to add to order
        productCards.forEach(card => {
            card.addEventListener('click', () => {
                const name = card.querySelector('h6').innerText;
                const priceText = card.querySelector('p.mb-1').innerText.replace('₱', '');
                const price = parseFloat(priceText.replace(',', '')) || 0;
                const id = card.className.match(/product-card-(\d+)/)[1];

                // Check if product already exists in order
                let existing = order.find(item => item.name === name);
                if (existing) {
                    existing.qty += 1;
                } else {
                    order.push({
                        id: id,
                        name: name,
                        price: price,
                        qty: 1
                    });
                }

                renderOrder();
            });
        });

        // Render Order Table
        function renderOrder() {
            orderSummary.innerHTML = '';
            let total = 0;

            order.forEach((item, index) => {
                const subtotal = item.price * item.qty;
                total += subtotal;

                orderSummary.innerHTML += `
                <tr>
                    <td>${item.name}</td>
                    <td style="width: 80px;">
                        <input type="number" min="1" name="quantity[]" value="${item.qty}" class="form-control form-control-sm qty-input" data-index="${index}">
                        <input type="hidden" value="${item.id}" name="product_id[]" class="form-control form-control-sm qty-input" data-index="${index}">
                    </td>
                    <td>₱${item.price.toFixed(2)}</td>
                    <td>₱${subtotal.toFixed(2)}</td>
                    <td><button class="btn btn-sm btn-danger remove-item" data-index="${index}">&times;</button></td>
                </tr>
            `;
            });

            totalField.value = `${total.toFixed(2)}`;

            // Add event listener to quantity inputs
            document.querySelectorAll('.qty-input').forEach(input => {
                input.addEventListener('input', e => {
                    const idx = e.target.dataset.index;
                    order[idx].qty = parseInt(e.target.value) || 1;
                    renderOrder();
                });
            });

            // Add event listener to remove buttons
            document.querySelectorAll('.remove-item').forEach(btn => {
                btn.addEventListener('click', e => {
                    const idx = e.target.dataset.index;
                    order.splice(idx, 1);
                    renderOrder();
                });
            });
        }
    });
</script>
