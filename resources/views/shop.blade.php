@extends('layouts.public') <!-- Extend the main layout -->

@section('content')
    <div class="inner-page-banner">
        <h1>Shop</h1>
    </div>

    <main class="content px-3 py-4 col-12" id="page-top">
        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
        @endif
        <div class="container-fluid col-10 mx-auto">
            <div class="row justify-content-start align-items-center py-5 g-4">
                @forelse ($products as $product)
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3 d-flex flex-wrap justify-content-center">
                        <div class="card products-container shadow h-100 w-100">
                            <img src="{{ asset($product->product_image ?? 'img/default-egg.jpg') }}" class="card-img-top"
                                alt="{{ $product->product_name }}" style="object-fit: cover; height: 200px;">
                            <div class="card-body d-flex flex-column justify-content-between">
                                <h6 class="card-title">{{ $product->product_name }}</h6>
                                @if ($product->status == 'Available')
                                    <p class="card-text m-0">Stock: {{ $product->quantity }}</p>
                                @endif
                                <div class="d-flex justify-content-between align-items-center">
                                    <p class="card-text m-0">₱{{ number_format($product->product_price, 2) }}</p>
                                    <span class="badge {{ $product->status == 'Available' ? 'bg-success' : 'bg-danger' }}">
                                        {{ ucfirst($product->status) }}
                                    </span>
                                </div>
                                @if (auth()->check())
                                    <button @if (auth()->user()->user_type != 'customer' || $product->status != 'Available') disabled @endif
                                        class="text-dark btn btn-warning border-light bg-theme-secondary p-1 mt-2"
                                        data-bs-toggle="modal" data-bs-target="#productModal{{ $product->id }}">
                                        <i class="fa-solid fa-cart-shopping"></i> Add to Cart
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Product Modal -->
                    <div class="modal fade" id="productModal{{ $product->id }}" tabindex="-1"
                        aria-labelledby="productModalLabel{{ $product->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <form action="{{ route('cart.add') }}" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="d-flex flex-row align-items-start justify-content-start gap-3">
                                            <div class="product-img">
                                                <img src="{{ asset($product->product_image ?? 'img/default-egg.jpg') }}"
                                                    style="width: 250px;height: 250px;object-fit: cover;"
                                                    class="img-fluid mb-3" alt="{{ $product->product_name }}">
                                            </div>
                                            <div class="product-meta">
                                                <h5 class="modal-title" id="productModalLabel{{ $product->id }}">
                                                    {{ $product->product_name }}</h5>
                                                <p class="mb-1 mt-2">Price:
                                                    ₱{{ number_format($product->product_price, 2) }}</p>
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                <label for="quantity{{ $product->id }}">Quantity:</label>
                                                <input type="number" name="quantity" id="quantity{{ $product->id }}"
                                                    class="form-control" value="1" min="1"
                                                    onchange="updateSubtotal({{ $product->id }}, {{ $product->product_price }})">
                                                <p class="mt-2"><strong>Subtotal:</strong> ₱<span
                                                        id="subtotal{{ $product->id }}">{{ number_format($product->product_price, 2) }}</span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger"
                                            data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-warning">Add to Cart</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-center">No products available.</p>
                @endforelse
            </div>
        </div>
    </main>

    <script>
        function updateSubtotal(productId, price) {
            let quantity = document.getElementById(`quantity${productId}`).value;
            let subtotal = quantity * price;
            document.getElementById(`subtotal${productId}`).innerText = subtotal.toFixed(2);
        }
    </script>
@endsection
