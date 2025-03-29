@extends('layouts.auth') <!-- Extend the main layout -->

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0">Edit Product</h1>
    </div>

    <div class="card shadow col-6 mb-4">
        <div class="card-body">
            <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="col-12 mb-2">
                    <label for="product_name" class="form-label">Product Name</label>
                    <input type="text" name="product_name" placeholder="Enter product name"
                        class="form-control @error('product_name') is-invalid @enderror" id="product_name"
                        value="{{ old('product_name', $product->product_name) }}" required>
                    @error('product_name')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 mb-2">
                    <label for="product_image" class="form-label">Product Image</label>

                    @if ($product->product_image)
                        <div class="mb-2">
                            <img src="{{ asset($product->product_image) }}" alt="Current Image" class="img-fluid"
                                width="150">
                        </div>
                    @endif

                    <input type="file" name="product_image"
                        class="form-control @error('product_image') is-invalid @enderror" id="product_image">
                    @error('product_image')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 mb-2">
                    <label for="product_price" class="form-label">Product Price</label>
                    <input type="text" name="product_price" placeholder="Enter product price"
                        class="form-control @error('product_price') is-invalid @enderror" id="product_price"
                        value="{{ old('product_price', $product->product_price) }}" required>
                    @error('product_price')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 mb-2">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                        <option value="">-- Select Status --</option>
                        <option value="Available" {{ old('status', $product->status) == 'Available' ? 'selected' : '' }}>
                            Available</option>
                        <option value="not_available"
                            {{ old('status', $product->status) == 'not_available' ? 'selected' : '' }}>Not available
                        </option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mt-3 text-end">
                    <button class="btn btn-primary text-light" type="submit">Update Product</button>
                </div>
            </form>
        </div>
    </div>
@endsection
