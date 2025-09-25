@extends('layouts.auth') <!-- Extend the main layout -->

@section('content')
    <!-- Start the content section -->
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0">Profile</h1>
    </div>

    <!-- Content Row -->
    <div class="card col-12 mb-4 px-0">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-dark">Profile Information</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('update.profile') }}" method="post">
                @csrf
                <div class="d-flex justify-content-between align-items-center gap-1">
                    <div class="col-6">
                        <div class="mb-2">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" value="{{ $user->name }}" required>
                            @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-2">
                            <label for="gender" class="form-label">Gender</label>
                            <select name="gender" id="gender" required
                                class="form-control @error('gender') is-invalid @enderror">
                                <option value="male" {{ $user->gender == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ $user->gender == 'female' ? 'selected' : '' }}>Female</option>
                            </select>
                            @error('gender')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-2">
                            <label for="phone_number" class="form-label">Phone Number</label>
                            <input type="text" class="form-control @error('phone_number') is-invalid @enderror"
                                id="phone_number" name="phone_number" value="{{ $user->phone_number }}" required>
                            @error('phone_number')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-2">
                            <label for="home_address" class="form-label">Home Address</label>
                            <input type="text" class="form-control @error('home_address') is-invalid @enderror"
                                id="home_address" name="home_address" value="{{ $user->home_address }}" required>
                            @error('home_address')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-2">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control @error('username') is-invalid @enderror"
                                id="username" name="username" value="{{ $user->username }}" required>
                            @error('username')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-2">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                name="email" value="{{ $user->email }}" required>
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-2">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                id="password" name="password">
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-2">
                            <label for="confirm_password" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control @error('confirm_password') is-invalid @enderror"
                                id="confirm_password" name="confirm_password">
                            @error('confirm_password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="text-end">
                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                    <button type="submit" class="btn btn-primary mt-1">Update Profile</button>
                </div>
            </form>
        </div>
    </div>
    <!-- Content Row -->
@endsection <!-- End the content section -->
