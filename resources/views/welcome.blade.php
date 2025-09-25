@extends('layouts.public') <!-- Extend the main layout -->

@section('content')
    <main class="content px-0 px-sm-4 py-0 py-sm-4 col-12 home-bg" id="page-top">
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
        <div class="col-12 col-sm-10 my-5 m-sm-auto container-fluid">
            <div class="row justify-content-center align-items-center gap-3">
                <div class="title-container">
                    <h6 class="welcome-text m-0">Welcome to</h6>
                    <h1 class="home-title m-0">e-Eggcellent Portal</h1>
                </div>
                <div class="d-flex flex-column gap-5">
                    <div class="home-description">
                        <i>Crack into freshness - your trusted source for <br> quality eggs, delivered with care.</i>
                    </div>
                    @if (!auth()->check())
                        <div class="auth-buttons d-flex gap-3">
                            <!-- Button to toggle the Login Offcanvas -->
                            <a class="btn btn-dark text-light" href="{{ url('/login-auth') }}">
                                Login
                            </a>
                            <a class="btn btn-dark text-light" href="{{ url('/register-auth') }}">
                                Register
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>
@endsection