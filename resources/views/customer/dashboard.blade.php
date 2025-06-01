@extends('layouts.auth') <!-- Extend the main layout -->

@section('content')
    <!-- Start the content section -->
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0">Dashboard</h1>
    </div>

    <!-- Content Row -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-2">
                <div class="card-body">
                    <div class="d-flex h-100 flex-column justify-content-between">
                        <div class="row align-items-center justify-content-between">
                            <div class="col mr-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">
                                        Orders | Today</div>
                                    <div class="col-auto">
                                        <i class="fa fa-shopping-cart fa-2x text-dark"></i>
                                    </div>
                                </div>
                                <div class="mb-0 font-weight-bold">
                                    <p style="font-size: 70px">{{ $ordersToday }}</p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-2">
                <div class="card-body">
                    <div class="d-flex h-100 flex-column justify-content-between">
                        <div class="row align-items-center justify-content-between">
                            <div class="col mr-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">
                                        Orders | This Week</div>
                                    <div class="col-auto">
                                        <i class="fa fa-shopping-cart fa-2x text-dark"></i>
                                    </div>
                                </div>
                                <div class="mb-0 font-weight-bold">
                                    <p style="font-size: 70px">{{ $ordersThisWeek }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-2">
                <div class="card-body">
                    <div class="d-flex h-100 flex-column justify-content-between">
                        <div class="row align-items-center justify-content-between">
                            <div class="col mr-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">
                                        Orders | This Month</div>
                                    <div class="col-auto">
                                        <i class="fa fa-shopping-cart fa-2x text-dark"></i>
                                    </div>
                                </div>
                                <div class="mb-0 font-weight-bold">
                                    <p style="font-size: 70px">{{ $ordersThisMonth }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-2">
                <div class="card-body">
                    <div class="d-flex h-100 flex-column justify-content-between">
                        <div class="row align-items-center justify-content-between">
                            <div class="col mr-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">
                                        Orders | This Year</div>
                                    <div class="col-auto">
                                        <i class="fa fa-shopping-cart fa-2x text-dark"></i>
                                    </div>
                                </div>
                                <div class="mb-0 font-weight-bold">
                                    <p style="font-size: 70px">{{ $ordersThisYear }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Content Row -->
    @endsection <!-- End the content section -->
