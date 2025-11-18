@extends('layouts.public')

@section('content')
    <main class="content px-3 py-5 col-12 bg-dark bg-gradient" id="page-top">
        <div class="bg-light p-4 rounded mx-auto" style="max-width: 400px;">
        		<div class="text-end">
        				<a href="{{ url('/') }}"><i class="fa fa-close bg-dark rounded p-2 text-light"></i></i></a>
        		</div>
            <div class="text-center mb-2">
                <h5 class="text-dark fs-3">Login</h5>
                <img src="{{ asset('img/eggcellent-logo.webp') }}" alt="eggcellent-logo" class="img-fluid my-2" width="200">
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
                <div class="mb-2">
                    <label for="email" class="form-label text-dark">Email</label>
                    <div class="input-group">
                        <span class="input-group-text border bg-light">
                            <i class="fa fa-envelope"></i>
                        </span>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                            name="email" value="{{ old('email') }}" required>
                    </div>
                    @error('email')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label text-dark">Password</label>
                    <div class="input-group">
                        <span class="input-group-text border bg-light">
                            <i class="fa fa-lock"></i>
                        </span>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                            name="password" required>
                    </div>
                    @error('password')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

               <!--  <div class="mb-2 text-start">
                   <a href="#" class="text-dark"><i>Forgot Password?</i></a>
               </div> -->

                <div class="d-grid">
                    <button type="submit" class="btn btn-warning">Login</button>
                </div>
            </form>
        </div>
    </main>
@endsection
