<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-EGGCELLENT</title>
    <!-- Bootstrap Style -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Fontawesone Style -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Datatables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.6/css/dataTables.dataTables.css" />

    <!-- Select2 Style -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Custom Styles -->
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
</head>

<body>
    <div class="wrapper">
        <div class="main">
            <nav class="navbar navbar-expand-lg px-0 px-sm-4 bg-dark shadow-sm">
                <div class="container">
                    <a class="navbar-brand p-0 d-flex align-items-center text-light" href="/">
                        <img src="{{ asset('img/eggcellent-logo.webp') }}" width="70" alt="pereyras-logo">
                        <h1 class="eggcellent-title text-light ms-2 mb-0 d-none d-sm-block">E-EGGCELLENT PORTAL</h1>
                    </a>

                    <button class="navbar-toggler text-dark bg-light" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarContent">
                        <span class="navbar-toggler-icon text-ligh"></span>
                    </button>

                    <div class="collapse navbar-collapse justify-content-end" id="navbarContent">
                        <ul class="navbar-nav gap-3 align-items-center">
                            <li class="nav-item">
                                <a class="nav-link text-light" href="{{ url('/') }}"><i class="fa fa-home"></i>
                                    Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-light" href="{{ url('/contact-us') }}"><i
                                        class="fa fa-phone"></i> Contact
                                    Us</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-light" href="{{ url('/testimonials') }}"><i class="fa fa-comments"></i>
                                    Testimonials</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-light" href="{{ url('/shop') }}"><i class="fa fa-shop"></i>
                                    Shop</a>
                            </li>

                            @if (!auth()->check())
                                <button class="btn btn-light py-1 px-2" type="button" data-bs-toggle="offcanvas"
                                    data-bs-target="#loginOffcanvas" aria-controls="loginOffcanvas">
                                    <i class="fa fa-user-circle"></i> Admin
                                </button>
                            @endif

                            @if (auth()->check() && auth()->user()->user_type == 'customer')
                                <li class="nav-item">
                                    <a class="nav-link text-light" href="{{ url('/cart') }}">
                                        <i class="fa-solid fa-cart-shopping"></i> Cart
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-light" href="{{ url('/dashboard') }}">
                                        <i class="fa-solid fa-dashboard"></i> Dashboard</a>
                                </li>

                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle text-light" href="#" id="userDropdown"
                                        role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-user-circle"></i> {{ Str::ucfirst(auth()->user()->username) }}
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('logout') }}">
                                                <i class="text-primary fas fa-sign-out-alt fa-sm fa-fw me-2"></i> Logout
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </nav>

            @yield('content')

            <!-- Offcanvas for the Login Form -->
            <div class="offcanvas offcanvas-end bg-dark bg-gradient" tabindex="-1" id="loginOffcanvas"
                aria-labelledby="loginOffcanvasLabel">
                <div class="offcanvas-body">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title text-light" id="loginOffcanvasLabel">Admin Login</h5>
                        <button type="button" class="btn-close text-reset bg-light " data-bs-dismiss="offcanvas"
                            aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-logo text-center">
                        <img src="{{ asset('img/eggcellent-logo.webp') }}" alt="eggcellent-logo" class="img-fluid"
                            width="200">
                    </div>
                    @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif
                    <form action="{{ route('login') }}" method="post">
                        @csrf
                        <input type="hidden" name="admin_login" value="admin_login">
                        <div class="mb-2">
                            <label for="email" class="form-label text-light">Email</label>
                            <div class="input-group w-100">
                                <div class="input-group-prepend bg-light rounded-start d-flex align-items-center">
                                    <span class="input-group-text border-0" id="basic-addon1"><i
                                            class="fa fa-envelope bg-transparent"></i></span>
                                </div>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    aria-describedby="basic-addon1" id="email" name="email"
                                    value="{{ old('email') }}" required>
                            </div>
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-2">
                            <label for="password" class="form-label text-light">Password</label>
                            <div class="input-group d-flex w-100">
                                <div class="input-group-prepend bg-light rounded d-flex align-items-center w-100">
                                    <span class="input-group-text border-0" id="basic-addon2"><i
                                            class="fa fa-lock bg-transparent"></i></span>
                                    <input type="password" style="border-radius: 0 6px 6px 0;"
                                        class="form-control @error('password') is-invalid @enderror" id="password"
                                        aria-describedby="basic-addon2" name="password" required>
                                </div>
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-2">
                            <a href="#" class="text-light"><i>Forgot Password?</i></a>
                        </div>

                        <div class="mt-2">
                            <button type="submit" class="btn btn-warning">Login</button>
                        </div>
                    </form>
                </div>
            </div>

            <footer class="footer py-2 shadow text-center bg-dark text-light">
                <div class="m-auto">
                    <div class="">© 2025 e-Eggcellent. All Rights Reserved.</div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Bootstrap Script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>

    <!-- Fontawesome Script -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/js/all.min.js"
        integrity="sha512-6sSYJqDreZRZGkJ3b+YfdhB3MzmuP9R7X1QZ6g5aIXhRvR1Y/N/P47jmnkENm7YL3oqsmI6AK+V6AD99uWDnIw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- jQuery Script -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- Datatables -->
    <script src="https://cdn.datatables.net/2.1.6/js/dataTables.js"></script>

    <!-- Select2 Script -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!--Custom Script -->
    <script src="{{ asset('js/script.js') }}"></script>
    <script>
        function hideAlerts(delay = 3000) {
            if ($('.alert-success, .alert-danger').length) {
                setTimeout(function() {
                    $('.alert-success, .alert-danger').fadeOut('slow');
                }, delay);
            }
        }
        hideAlerts();

        window.addEventListener('DOMContentLoaded', (event) => {
            @if ($errors->any())
                @if (old('admin_login'))
                    // Open login offcanvas if email exists but not name (login form)
                    var loginOffcanvas = new bootstrap.Offcanvas(document.getElementById('loginOffcanvas'));
                    loginOffcanvas.show();
                @endif
            @endif
        });
    </script>
</body>

</html>
