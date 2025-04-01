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
            <nav class="navbar-expand px-4 bg-dark shadow-sm">
                <div class="d-flex justify-content-between flex-wrap align-items-center">
                    <div class="d-flex flex-wrap align-items-center">
                        <img src="{{ asset('img/eggcellent-logo.webp') }}" width="70" alt="pereyras-logo">
                        <h1 class="eggcellent-title text-light">E-EGGCELLENT PORTAL</h1>
                    </div>
                    <div class="d-flex align-items-center gap-5">
                        <a href="/" class="text-light">Home</a>
                        {{-- <a href="#" class="text-light">Contact Us</a> --}}
                        <a href="/shop" class="text-light">Shop</a>
                        @if (auth()->check())
                            <a href="/cart" class="text-light"><i class="fa-solid fa-cart-shopping"></i> Cart</a>
                            <a href="/dashboard" class="text-light">Dashboard</a>
                            <ul class="navbar-nav ms-auto">
                                <span class="m-auto me-1 text-light">{{ Str::ucfirst(auth()->user()->username) }}</span>
                                <li class="nav-item dropdown">
                                    <a href="#" data-bs-toggle="dropdown" class="nav-stat-icon pe-md-0">
                                        <a data-bs-toggle="dropdown" class="nav-stat-icon pe-md-0"
                                            title="Google, Chromium project, BSD &lt;http://opensource.org/licenses/bsd-license.php&gt;, via Wikimedia Commons"
                                            href="https://commons.wikimedia.org/wiki/File:Profile_avatar_placeholder_large.png">
                                            <i class="text-light fas fa-user-circle avatar"></i>
                                        </a>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end rounded animated--fade-in">
                                        {{-- <a class="dropdown-item" href="#">
                                            <i class="text-primary fas fa-user fa-sm fa-fw mr-2"></i>
                                            Profile
                                        </a>
                                        <a class="dropdown-item" href="#">
                                            <i class="text-primary fas fa-cogs fa-sm fa-fw mr-2"></i>
                                            Settings
                                        </a>
                                        <div class="dropdown-divider"></div> --}}
                                        <a class="dropdown-item" href="{{ route('logout') }}" data-toggle="modal"
                                            data-target="#logoutModal">
                                            <i class="text-primary fas fa-sign-out-alt fa-sm fa-fw mr-2"></i>
                                            Logout
                                        </a>
                                    </div>
                                </li>
                            </ul>
                        @endif
                    </div>
                </div>
            </nav>

            @yield('content')

            <footer class="footer py-2 shadow text-center bg-dark text-light">
                <div class="m-auto">
                    <div class="">© 2025 e-Eggcellent. All rights reserved.</div>
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
            console.log('Hiding alerts');
            if ($('.alert-success, .alert-danger').length) {
                setTimeout(function() {
                    $('.alert-success, .alert-danger').fadeOut('slow');
                }, delay);
            }
        }
        hideAlerts();
    </script>
</body>

</html>
