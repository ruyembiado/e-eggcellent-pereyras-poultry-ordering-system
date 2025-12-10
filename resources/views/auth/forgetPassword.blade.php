@extends('layouts.public')

@section('content')
<main class="content px-3 py-5 col-12 bg-dark bg-gradient" id="page-top">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-dark text-white fw-bold">
                        Reset Password
                    </div>

                    <div class="card-body p-4">

                        @if (Session::has('message'))
                            <div class="alert alert-success">
                                {{ Session::get('message') }}
                            </div>
                        @endif

                        <form action="{{ route('forget.password.post') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="email_address" class="form-label fw-semibold">Email Address</label>
                                <input type="email" id="email_address" class="form-control" name="email" required autofocus>
                                @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                Send Password Reset Link
                            </button>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>

</main>
@endsection
