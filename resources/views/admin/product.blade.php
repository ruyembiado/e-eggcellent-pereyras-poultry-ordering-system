@extends('layouts.auth') <!-- Extend the main layout -->

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0">Products</h1>
        <a href="{{ route('product.create') }}" class="btn btn-primary">Add Product</a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable1" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Product Image</th>
                            <th>Product Name & Size</th>
                            <th>Stock Qty.</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Date Created</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $key => $product)
                            <tr>
                                <td class="text-center">{{ $key + 1 }}</td>
                                <td>
                                    <img src="{{ asset($product->product_image ?? 'img/default-egg.jpg') }}"
                                        alt="Product Image" class="img-fluid" width="70">
                                </td>
                                <td>{{ $product->product_name }}</td>
                                <td class="text-center">{{ $product->quantity }}</td>
                                <td>₱{{ number_format($product->product_price, 2) }}</td>
                                <td>
                                    <span class="badge {{ $product->status == 'Available' ? 'bg-success' : 'bg-danger' }}">
                                        {{ $product->status }}
                                    </span>
                                </td>
                                <td>{{ $product->created_at->format('Y-m-d h:i a') }}</td>
                                <td>
                                	<div class="d-flex gap-1">
                                    <a href="{{ route('product.edit', $product->id) }}"
                                        class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('product.destroy', $product->id) }}" method="POST"
                                        onsubmit="return confirmDelete(event)" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </td>
        </div>
    </div>
@endsection

<script>
    function confirmDelete(event) {
        if (!confirm('Are you sure you want to delete this product? This action cannot be undone.')) {
            event.preventDefault();
        }
    }
</script>
