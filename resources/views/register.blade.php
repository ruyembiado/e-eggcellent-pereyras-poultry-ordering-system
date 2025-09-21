@extends('layouts.public')

@section('content')
    <main class="content px-3 py-5 col-12 bg-dark bg-gradient" id="page-top">
        <div class="bg-light p-4 rounded mx-auto" style="max-width: 400px;">
            <div class="text-center mb-2">
                <h5 class="text-dark fs-3">Register</h5>
                <img src="{{ asset('img/eggcellent-logo.webp') }}" alt="eggcellent-logo" class="img-fluid my-2" width="200">
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
                    <label for="name" class="form-label text-dark">Name</label>
                    <div class="input-group">
                        <span class="input-group-text border bg-light">
                            <i class="fa fa-user"></i>
                        </span>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                            id="name" name="name" value="{{ old('name') }}" required>
                    </div>
                    @error('name')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-2">
                    <label for="gender" class="form-label text-dark">Gender</label>
                    <div class="input-group">
                        <span class="input-group-text border bg-light">
                            <i class="fa fa-venus-mars"></i>
                        </span>
                        <select name="gender" id="gender"
                            class="form-control @error('gender') is-invalid @enderror" required>
                            <option value="">-- Select Gender --</option>
                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>
                    @error('gender')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-2">
                    <label for="phone_number" class="form-label text-dark">Phone Number</label>
                    <div class="input-group">
                        <span class="input-group-text border bg-light">
                            <i class="fa fa-phone"></i>
                        </span>
                        <input type="text" class="form-control @error('phone_number') is-invalid @enderror"
                            id="phone_number" name="phone_number" value="{{ old('phone_number') }}" required>
                    </div>
                    @error('phone_number')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-2">
                    <label for="home_address" class="form-label text-dark">Home Address</label>
                    <div class="input-group">
                        <span class="input-group-text border bg-light">
                            <i class="fa fa-map-marker-alt"></i>
                        </span>
                        <input type="text" class="form-control @error('home_address') is-invalid @enderror"
                            id="home_address" name="home_address" value="{{ old('home_address') }}" required>
                    </div>
                    @error('home_address')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-2">
                    <label for="username" class="form-label text-dark">Username</label>
                    <div class="input-group">
                        <span class="input-group-text border bg-light">
                            <i class="fa fa-user-circle"></i>
                        </span>
                        <input type="text" class="form-control @error('username') is-invalid @enderror"
                            id="username" name="username" value="{{ old('username') }}" required>
                    </div>
                    @error('username')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-2">
                    <label for="email" class="form-label text-dark">Email</label>
                    <div class="input-group">
                        <span class="input-group-text border bg-light">
                            <i class="fa fa-envelope"></i>
                        </span>
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                            id="email" name="email" value="{{ old('email') }}" required>
                    </div>
                    @error('email')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-2">
                    <label for="password" class="form-label text-dark">Password</label>
                    <div class="input-group">
                        <span class="input-group-text border bg-light">
                            <i class="fa fa-lock"></i>
                        </span>
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                            id="password" name="password" required>
                    </div>
                    @error('password')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-2">
                    <label for="confirm_password" class="form-label text-dark">Confirm Password</label>
                    <div class="input-group">
                        <span class="input-group-text border bg-light">
                            <i class="fa fa-lock"></i>
                        </span>
                        <input type="password" class="form-control @error('confirm_password') is-invalid @enderror"
                            id="confirm_password" name="confirm_password" required>
                    </div>
                    @error('confirm_password')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-warning">Register</button>
                </div>
            </form>
        </div>
    </main>
@endsection