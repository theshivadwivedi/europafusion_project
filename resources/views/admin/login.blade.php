@extends('admin.layout')
@section('title', 'Sign in')
@section('content')
    <div class="login-wrap">
        <div class="login-card">
            <div class="d-flex align-items-center gap-2 mb-4"><x-emblem style="width:44px;height:44px" /><div><h1 class="h6 fw-bold m-0" style="letter-spacing:.06em">EUROPA FUSION</h1><span class="text-secondary small">Lead desk · admin sign in</span></div></div>
            @if (session('status'))<div class="alert alert-info small">{{ session('status') }}</div>@endif
            <form method="POST" action="{{ route('admin.login.store') }}" novalidate>
                @csrf
                <div class="mb-3">
                    <label class="form-label" for="email">Email</label>
                    <input class="form-control @error('email') is-invalid @enderror" id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username">
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" for="password">Password</label>
                    <input class="form-control @error('password') is-invalid @enderror" id="password" name="password" type="password" required autocomplete="current-password">
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-check mb-4"><input class="form-check-input" type="checkbox" name="remember" id="remember"><label class="form-check-label" for="remember">Keep me signed in</label></div>
                <button class="btn btn-primary w-100">Sign in</button>
            </form>
            <p class="small text-secondary mt-4 mb-0"><a href="{{ route('home') }}">← Back to website</a></p>
        </div>
    </div>
@endsection
