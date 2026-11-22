@extends('layouts.app')

@section('title', 'Page not found')

@section('content')
    <section class="error-page">
        <div class="container">
            <img src="{{ asset('images/illustrations/lost-compass.svg') }}" alt="" width="140" height="140">
            <div class="code">404</div>
            <h1 class="h2 mt-2">Page not found</h1>
            <p class="lead-text mx-auto" style="max-width: 32rem">The page you are looking for doesn't exist or has moved. Let us point you in the right direction.</p>
            <div class="d-flex flex-wrap justify-content-center gap-2 mt-4">
                <a href="{{ route('home') }}" class="btn btn-primary">Return to home</a>
                <a href="{{ route('destinations') }}" class="btn btn-outline-primary">Explore destinations</a>
                <a href="{{ route('contact') }}" class="btn btn-outline-primary">Contact us</a>
            </div>
        </div>
    </section>
@endsection
