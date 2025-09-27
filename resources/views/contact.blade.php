@extends('layouts.public')

@section('content')
    <div class="inner-page-banner text-center py-4 bg-warning">
        <h1>Contact Us</h1>
    </div>

    <main class="content px-3 py-4 col-12" id="page-top">
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

        <div class="container py-5 rounded">
            <div class="d-flex m-auto col-8 bg-light p-4 rounded shadow-sm">
                <div class="col-md-6 text-center mb-4 mb-md-0">
                    <h3 class="mb-3">Let's Get in Touch!</h3>
                    <img src="{{ asset('img/eggcellent-logo.webp') }}" alt="Pereyra's Egg Poultry" class="img-fluid mb-3"
                        style="max-height: 150px;">
                    <p>Connect With Us:</p>
                    <a href="https://facebook.com" target="_blank">
                        <i style="font-size:24px;" class="fa-brands fa-facebook-f border border-primary rounded p-2 bg-primary text-light"></i>
                    </a>
                </div>

                <div class="col-md-6">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="mb-0">Contact Us</h4>
                    </div>

                    <form action="{{ route('contact.send') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <input type="text" name="name" class="form-control" placeholder="Name" required>
                        </div>
                        <div class="mb-3">
                            <input type="email" name="email" class="form-control" placeholder="Email" required>
                        </div>
                        <div class="mb-3">
                            <input type="text" name="phone" class="form-control" placeholder="Phone" required>
                        </div>
                        <div class="mb-3">
                            <textarea name="message" class="form-control" rows="4" placeholder="Message" required></textarea>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary text-white">Send</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection
