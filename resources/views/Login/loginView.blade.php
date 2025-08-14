@extends('Layouts.auth')

@section('title', 'Login')

@section('content')
    <!-- Left Side Image -->
    <div class="d-none d-lg-flex col-lg-7 p-0">
        <div class="auth-cover-bg auth-cover-bg-color d-flex justify-content-center align-items-center">
            <img src="{{ asset('assets/img/illustrations/auth-login-illustration-light.png') }}"
                 alt="auth-login-cover" class="img-fluid my-5 auth-illustration"
                 data-app-light-img="illustrations/auth-login-illustration-light.png"
                 data-app-dark-img="illustrations/auth-login-illustration-dark.png" />

            <img src="{{ asset('assets/img/illustrations/bg-shape-image-light.png') }}"
                 alt="auth-login-cover" class="platform-bg"
                 data-app-light-img="illustrations/bg-shape-image-light.png"
                 data-app-dark-img="illustrations/bg-shape-image-dark.png" />
        </div>
    </div>

    <!-- Login Form -->
    <div class="d-flex col-12 col-lg-5 align-items-center p-sm-5 p-4">
        <div class="w-px-400 mx-auto">
            <!-- Logo -->
            <div class="app-brand mb-4">
                <a href="{{ url('/') }}" class="app-brand-link gap-2">
                    <span class="app-brand-logo demo"></span>
                </a>
            </div>

            <h4 class="mb-1">Welcome to Ichiban Japan Shop 👋</h4>
            <p class="mb-4">Please sign-in to your account</p>

            {{-- ✅ Success Notification --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="ti ti-checks me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- You can also show login errors here --}}
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="ti ti-alert-triangle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('login.store') }}">
                @csrf
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text"
                           class="form-control @error('username') is-invalid @enderror"
                           id="username"
                           name="username"
                           placeholder="Enter your username"
                           autofocus required />
                    @error('username')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3 form-password-toggle">
                    <div class="d-flex justify-content-between">
                        <label class="form-label" for="password">Password</label>
                        <a href="#">
                            <small>Forgot Password?</small>
                        </a>
                    </div>
                    <div class="input-group input-group-merge">
                        <input type="password"
                               id="password"
                               class="form-control @error('password') is-invalid @enderror"
                               name="password"
                               placeholder="••••••••••••"
                               required />
                        <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                    </div>
                    @error('password')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="remember-me" name="remember" />
                        <label class="form-check-label" for="remember-me">Remember Me</label>
                    </div>
                </div>

                <button class="btn btn-primary d-grid w-100">Sign in</button>
            </form>

            <p class="text-center mt-3">
                <span>New on our platform?</span>
                <a href="{{ route('register') }}">
                    <span>Create an account</span>
                </a>
            </p>
        </div>
    </div>
@endsection
