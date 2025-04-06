@extends('layouts.public') <!-- Extend the main layout -->

@section('content')
    <div class="inner-page-banner">
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
    </main>
@endsection
