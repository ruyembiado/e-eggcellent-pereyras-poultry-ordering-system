@extends('layouts.public')

@section('content')
<main class="content px-3 py-5 bg-light min-vh-100">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">

                <div class="card shadow-lg border-0">
                    <div class="card-header bg-dark text-white fw-bold">
                        Reset Password
                    </div>

                    <div class="card-body p-4">

                        <form action="{{ route('reset.password.post') }}" method="POST">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">

                            <div class="mb-3">
                                <label for="email_address" class="form-label fw-semibold">Email Address</label>
                                <input type="email" id="email_address" class="form-control" name="email" required autofocus>
                                @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label fw-semibold">New Password</label>
                                <input type="password" id="password" class="form-control" name="password" required>
                                @error('password')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password-confirm" class="form-label fw-semibold">Confirm Password</label>
                                <input type="password" id="password-confirm" class="form-control" name="password_confirmation" required>
                                @error('password_confirmation')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                Reset Password
                            </button>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>

</main>
@endsection
