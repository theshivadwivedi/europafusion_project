@extends('layouts.app')

@section('title', 'Our Services')
@section('description', 'Counselling, course selection, applications, visa guidance, test preparation, scholarships, education finance, travel, accommodation and arrival support for studying in Europe.')

@section('content')
    <section class="page-hero">
        <x-star-ring class="hero-orbit" />
        <div class="container">
            <ol class="breadcrumb-ef" aria-label="Breadcrumb"><li><a href="{{ route('home') }}">Home</a></li><li aria-current="page">Services</li></ol>
            <p class="eyebrow">What we do for you</p>
            <h1>Our <span class="hl">services</span></h1>
            <p class="lead-text">Eleven services in two parts: academic guidance that gets you the right admission, and practical support that gets you settled once you have it.</p>
            <div class="d-flex flex-wrap gap-2 mt-4">
                <a href="#academic-guidance" class="btn btn-gold">I. Academic Guidance</a>
                <a href="#beyond-admission" class="btn btn-ghost-light">II. Beyond Admission</a>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="row g-4 g-lg-5">
                <aside class="col-lg-3 d-none d-lg-block">
                    <nav class="service-nav" id="serviceNav" aria-label="Services" data-spy-nav>
                        @foreach (\App\Models\Service::GROUPS as $key => $label)
                            <div class="group-label">{{ $loop->first ? 'I.' : 'II.' }} {{ $label }}</div>
                            @foreach ($groups->get($key, collect()) as $s)
                                <a class="nav-link" href="#{{ $s->slug }}"><i class="bi bi-{{ $s->icon }}" aria-hidden="true"></i>{{ $s->title }}</a>
                            @endforeach
                        @endforeach
                    </nav>
                </aside>
                <div class="col-lg-9">
                    @foreach (\App\Models\Service::GROUPS as $key => $label)
                        <div id="{{ $key === 'academic' ? 'academic-guidance' : 'beyond-admission' }}" @class(['mt-5 pt-3' => ! $loop->first])>
                            <div class="service-group-head">
                                <span class="roman">{{ $loop->first ? 'I.' : 'II.' }}</span>
                                <h2>{{ $label }}</h2>
                            </div>
                            @foreach ($groups->get($key, collect()) as $s)
                                <article class="service-article" id="{{ $s->slug }}" data-reveal>
                                    <div class="s-ico"><i class="bi bi-{{ $s->icon }}" aria-hidden="true"></i></div>
                                    <div>
                                        <h3>{{ $s->title }}</h3>
                                        <p class="s-tagline">{{ $s->tagline }}</p>
                                        @foreach ($s->body as $p)<p>{{ $p }}</p>@endforeach
                                        @if ($s->quote)<blockquote>{{ trim($s->quote, '"“”') }}</blockquote>@endif
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection
