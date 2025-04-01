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
                        @foreach ($cartItems as $key => $item)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>
                                    <img src="{{ asset($item->product->product_image) }}" alt="Product Image"
                                        class="img-fluid" width="70">
                                </td>
                                <td>{{ $item->product->product_name }}</td>
                                <td>₱{{ number_format($item->product->product_price, 2) }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>₱{{ number_format($item->product->product_price * $item->quantity, 2) }}</td>
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
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end">
        <h4>Total: ₱{{ number_format($cartTotal, 2) }}</h4>
    </div>
    <div class="d-flex justify-content-end mt-3">
        <a href="{{ route('cart.checkout') }}" class="btn btn-success">Proceed to Checkout</a>
    </div>
@endsection

<script>
    function confirmDelete(event) {
        if (!confirm('Are you sure you want to remove this item from the cart? This action cannot be undone.')) {
            event.preventDefault();
        }
    }
</script>
