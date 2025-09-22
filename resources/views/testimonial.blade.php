@extends('layouts.public') <!-- Extend the main layout -->

@section('content')
    <div class="inner-page-banner">
        <h1>Testimonials</h1>
    </div>

    <main class="content px-0 px-md-3 py-4 col-12" id="page-top">
        <div class="container-fluid col-12 col-xl-10 mx-auto">
            <div class="row justify-content-center align-items-stretch py-5 g-4">
                @forelse ($testimonials as $userId => $ratings)
                    <div class="col-md-6 col-xl-4">
                        <div class="card shadow-lg border-0 rounded-4">
                            <div class="card-body d-flex flex-column justify-content-start">
                                <!-- User Info -->
                                @php $user = $ratings->first()->order->user; @endphp
                                <div class="d-flex col-9 mx-auto align-items-center mb-3 justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle text-dark bg-theme-secondary d-flex justify-content-center align-items-center me-2"
                                            style="width: 45px; height: 45px; font-weight: bold;">
                                            {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                                        </div>
                                        <div>
                                            <h6 class="m-0 fw-bold">{{ $user->name ?? 'Anonymous' }}</h6>
                                        </div>
                                    </div>
                                    @if (count($ratings) > 1)
                                        <div class="badge bg-success">{{ count($ratings) }}</div>
                                    @endif
                                </div>

                                <!-- Multiple Ratings (Slider if > 1) -->
                                @if ($ratings->count() > 1)
                                    <div id="carousel-{{ $userId }}" class="carousel slide" data-bs-ride="carousel">
                                        <div class="carousel-inner">
                                            @foreach ($ratings as $index => $testimonial)
                                                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                                    @include('partials.testimonial-card', [
                                                        'testimonial' => $testimonial,
                                                    ])
                                                </div>
                                            @endforeach
                                        </div>

                                        <button class="carousel-control-prev" type="button"
                                            data-bs-target="#carousel-{{ $userId }}" data-bs-slide="prev">
                                            <i class="fa fa-arrow-left text-dark bg-white rounded-circle p-2 shadow"></i>
                                            <span class="visually-hidden">Previous</span>
                                        </button>

                                        <button class="carousel-control-next" type="button"
                                            data-bs-target="#carousel-{{ $userId }}" data-bs-slide="next">
                                            <i class="fa fa-arrow-right text-dark bg-white rounded-circle p-2 shadow"></i>
                                            <span class="visually-hidden">Next</span>
                                        </button>
                                    </div>
                                @else
                                    @include('partials.testimonial-card', [
                                        'testimonial' => $ratings->first(),
                                    ])
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-center">No testimonial available.</p>
                @endforelse
            </div>
        </div>
    </main>
@endsection
