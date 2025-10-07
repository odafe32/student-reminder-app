@extends('layouts.auth')

@section('content')

<div class="account-page">
    <div class="container-fluid p-0">
        <div class="row align-items-center g-0 px-3 py-3 vh-100">

            <!-- Left side: login form -->
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
                                    <h3 class="text-dark fw-semibold mb-1 mt-3">Welcome Back!</h3>
                                    <p class="text-muted fs-14 mb-0">Sign in to continue to <strong>Hando</strong>.</p>
                                </div>

                                <!-- Display success messages -->
                                @if(session('success'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        {{ session('success') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                @endif

                                <!-- Display error messages -->
                                @if($errors->any())
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        @foreach($errors->all() as $error)
                                            <div>{{ $error }}</div>
                                        @endforeach
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                @endif

                                <form method="POST" action="{{ route('login') }}" class="mt-4">
                                    @csrf

                                    <div class="form-group mb-3">
                                        <label for="email" class="form-label fw-medium">Email Address</label>
                                        <input type="email" name="email" id="email"
                                               class="form-control @error('email') is-invalid @enderror"
                                                placeholder="Enter your email"
                                               value="{{ old('email') }}">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="password" class="form-label fw-medium">Password</label>
                                        <input type="password" name="password" id="password"
                                               class="form-control @error('password') is-invalid @enderror"
                                                placeholder="Enter your password">
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group d-flex justify-content-between align-items-center mb-4">
                                        <div class="form-check">
                                            <input type="checkbox" name="remember" id="remember" class="form-check-input">
                                            <label for="remember" class="form-check-label small text-muted">Remember me</label>
                                        </div>
                                        <a href="{{ route('password.request') }}" class="text-primary small fw-medium">
                                            Forgot Password?
                                        </a>
                                    </div>

                                    <div class="form-group mb-0">
                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-primary">
                                                Log In
                                            </button>
                                        </div>
                                    </div>
                                </form>

                                <div class="text-center text-muted mt-4">
                                    <p class="mb-0">Don't have an account?
                                        <a href="{{ route('register') }}" class="text-primary fw-medium">Register here</a>
                                    </p>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right side splash -->
            <div class="col-xl-7 d-none d-xl-inline-block">
                <div class="account-page-bg rounded-4 position-relative overflow-hidden">

                    <div class="position-absolute top-0 start-0 w-100 h-100 d-flex flex-column align-items-center justify-content-center text-white px-5">

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection
