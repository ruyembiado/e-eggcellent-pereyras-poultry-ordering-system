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
        <div class="col-12 col-sm-10 m-auto container-fluid">
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
                            <button class="btn btn-light" type="button" data-bs-toggle="offcanvas"
                                data-bs-target="#loginOffcanvas" aria-controls="loginOffcanvas">
                                Login
                            </button>
                            <!-- Button to toggle the Register Offcanvas -->
                            <button class="btn btn-light" type="button" data-bs-toggle="offcanvas"
                                data-bs-target="#registerOffcanvas" aria-controls="registerOffcanvas">
                                Register
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>

    <!-- Offcanvas for the Register Form -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="registerOffcanvas" aria-labelledby="registerOffcanvasLabel">
        <div class="offcanvas-body">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="registerOffcanvasLabel">Register</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-logo text-center">
                <img src="{{ asset('img/eggcellent-logo.webp') }}" alt="eggcellent-logo" class="img-fluid" width="200">
            </div>
            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif
            <form action="{{ route('register') }}" method="post">
                @csrf
                <div class="mb-2">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                        name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-2">
                    <label for="gender" class="form-label">Gender</label>
                    <select name="gender" id="gender" required
                        class="form-control @error('gender') is-invalid @enderror">
                        <option value="">-- Select Gender --</option>
                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                    </select>
                    @error('gender')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-2">
                    <label for="phone_number" class="form-label">Phone Number</label>
                    <input type="text" class="form-control @error('phone_number') is-invalid @enderror"
                        id="phone_number" name="phone_number" value="{{ old('phone_number') }}" required>
                    @error('phone_number')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-2">
                    <label for="home_address" class="form-label">Home Address</label>
                    <input type="text" class="form-control @error('home_address') is-invalid @enderror"
                        id="home_address" name="home_address" value="{{ old('home_address') }}" required>
                    @error('home_address')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-2">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control @error('username') is-invalid @enderror" id="username"
                        name="username" value="{{ old('username') }}" required>
                    @error('username')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-2">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                        name="email" value="{{ old('email') }}" required>
                    @error('email')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-2">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                        name="password" required>
                    @error('password')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-2">
                    <label for="confirm_password" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control @error('confirm_password') is-invalid @enderror"
                        id="confirm_password" name="confirm_password" required>
                    @error('confirm_password')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-warning mt-1">Register</button>
            </form>
        </div>
    </div>
@endsection

<script>
    window.addEventListener('DOMContentLoaded', (event) => {
        @if ($errors->any())
            @if (old('email') && !old('name'))
                // Open login offcanvas if email exists but not name (login form)
                var loginOffcanvas = new bootstrap.Offcanvas(document.getElementById('loginOffcanvas'));
                loginOffcanvas.show();
            @else
                // Open register offcanvas if name exists (register form)
                var registerOffcanvas = new bootstrap.Offcanvas(document.getElementById('registerOffcanvas'));
                registerOffcanvas.show();
            @endif
        @endif
    });
</script>
