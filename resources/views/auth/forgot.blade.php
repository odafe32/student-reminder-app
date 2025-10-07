@extends('layouts.auth')

@section('content')

<div class="account-page">
    <div class="container-fluid p-0">
        <div class="row align-items-center g-0 px-3 py-3 vh-100">

            <!-- Left side: Forgot Password Form -->
            <div class="col-xl-5">
                <div class="row">
                    <div class="col-md-9 mx-auto">
                        <div class="card shadow border-0 rounded-4">
                            <div class="card-body p-4">

                                <div class="text-center mb-4">
                                    <div class="auth-brand">
                                        <a class='logo logo-light' href='index.html'>
                                            <span class="logo-lg">
                                                <img src="{{ asset('assets/images/logo-light-3.png') }}" alt="Hando Logo" height="40">
                                            </span>
                                        </a>
                                        <a class='logo logo-dark' href='index.html'>
                                            <span class="logo-lg">
                                                <img src="{{ asset('assets/images/logo-dark-3.png') }}" alt="Hando Logo" height="40">
                                            </span>
                                        </a>
                                    </div>
                                    <h3 class="text-dark fw-semibold mb-1 mt-3">Forgot Your Password?</h3>
                                    <p class="text-muted fs-14 mb-0">Enter your email address and we’ll send you a link to reset your password.</p>
                                </div>

                                @if (session('status'))
                                    <div class="alert alert-success mb-4 text-sm text-green-700" role="alert">
                                        {{ session('status') }}
                                    </div>
                                @endif

                                <form method="POST" action="" class="mt-4">
                                    @csrf

                                    <div class="form-group mb-3">
                                        <label for="email" class="form-label fw-medium">Email Address</label>
                                        <input type="email" name="email" id="email" class="form-control" required placeholder="Enter your email" value="{{ old('email') }}">
                                        @error('email')
                                            <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-0">
                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-primary">
                                                Send Password Reset Link
                                            </button>
                                        </div>
                                    </div>
                                </form>

                                <div class="text-center text-muted mt-4">
                                    <p class="mb-0">Remembered your password?
                                        <a href="{{ route('login') }}" class="text-primary fw-medium">Login here</a>
                                    </p>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right side splash section -->
            <div class="col-xl-7 d-none d-xl-inline-block">
                <div class="account-page-bg rounded-4 position-relative overflow-hidden">
                    <div class="absolute inset-0 bg-cover bg-center"
                         style="background-image: url('{{ asset('assets/images/bg-auth.jpg') }}'); filter: brightness(70%);">
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

@endsection
