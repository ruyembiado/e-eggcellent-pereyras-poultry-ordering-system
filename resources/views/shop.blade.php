@extends('layouts.public') <!-- Extend the main layout -->

@section('content')
    <div class="inner-page-banner">
        <h1>Shop</h1>
    </div>

    <main class="content px-3 py-4 col-12" id="page-top">
        <div class="container-fluid">
            <div class="row justify-content-start align-items-center py-5 g-4">
                @forelse ($products as $product)
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2 d-flex justify-content-center">
                        <div class="card products-container shadow h-100 w-100">
                            <img src="{{ asset($product->product_image) }}" class="card-img-top"
                                alt="{{ $product->product_name }}" style="object-fit: cover; height: 200px;">
                            <div class="card-body d-flex flex-column justify-content-between">
                                <h6 class="card-title">{{ $product->product_name }}</h6>
                                <div class="d-flex justify-content-between align-items-center">
                                    <p class="card-text m-0">₱{{ number_format($product->product_price, 2) }}</p>
                                    <span class="badge {{ $product->status == 'Available' ? 'bg-success' : 'bg-danger' }}">
                                        {{ ucfirst($product->status) }}
                                    </span>
                                </div>
                                @if (auth()->check())
                                    <a href="#" class="text-dark btn btn-warning border-light bg-theme-secondary p-1 mt-2"><i class="fa-solid fa-cart-shopping"></i> Cart</a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-center">No products available.</p>
                @endforelse
            </div>
        </div>
    </main>
@endsection
