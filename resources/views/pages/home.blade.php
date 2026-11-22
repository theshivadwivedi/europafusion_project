@extends('layouts.app')

@section('content')
    {{-- Hero ------------------------------------------------------------------------------------------- --}}
    <section class="home-hero">
        <div class="container">
            <div class="row align-items-center g-4 g-lg-5">
                <div class="col-lg-6">
                    <span class="hero-kicker"><span class="dot" aria-hidden="true">★</span>Trusted European study abroad experts</span>
                    <h1>Where academic ambition meets European <span class="hl">opportunity</span></h1>
                    <p class="lead-text">Personalised guidance for ambitious students seeking the right university and programme across <strong class="text-ink">Germany and Europe</strong>.</p>
                    <div class="d-flex flex-wrap gap-2 mt-4">
                        <a href="{{ route('contact', ['type' => 'counselling']) }}#enquiry" class="btn btn-primary btn-lg" data-enquiry="counselling">Book Free Counselling <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
                        <a href="{{ route('contact', ['type' => 'evaluation']) }}#enquiry" class="btn btn-outline-primary btn-lg" data-enquiry="evaluation"><i class="bi bi-person-check" aria-hidden="true"></i> Get Profile Evaluation</a>
                    </div>
                    <div class="hero-trust">
                        <span class="flags" aria-hidden="true">
                            @foreach ($navDestinations->take(6) as $d)<x-flag :code="$d->code" />@endforeach
                        </span>
                        <span>{{ $navDestinations->count() }} European destinations · Germany specialists · Free first consultation</span>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="hero-visual">
                        <img src="{{ asset('images/illustrations/hero-home.svg') }}" alt="Illustration: an arched university portal framing the circle of European stars, with books, a diploma and a globe" width="560" height="520" fetchpriority="high">
                        <div class="float-badge b1"><span class="ico"><i class="bi bi-bank" aria-hidden="true"></i></span><span><strong>1,200+ Partners</strong>Top global universities</span></div>
                        <div class="float-badge b2"><span class="ico"><i class="bi bi-patch-check" aria-hidden="true"></i></span><span><strong>98.4% Visa Rate</strong>Consulate-ready files</span></div>
                        <div class="float-badge b3"><span class="ico"><i class="bi bi-chat-heart" aria-hidden="true"></i></span><span><strong>100% Free</strong>Expert counselling</span></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Universities --------------------------------------------------------------------------------- --}}
    <section class="uni-strip" aria-labelledby="uni-title">
        <div class="container">
            <h2 id="uni-title">Explore leading universities across Europe</h2>
            <p class="sub">Public research universities, technical institutes and historic seats of learning our students apply to.</p>
        </div>
        @php
            $palette = ['#15294d', '#1e3a68', '#8e1f2f', '#2b59d1', '#0f5a4a', '#5b3a8c'];
            $rows = $universities->chunk((int) ceil($universities->count() / 2));
        @endphp
        @foreach ($rows as $r => $row)
            <div @class(['marquee', 'is-reverse' => $r === 1])>
                @foreach ([0, 1] as $copy)
                    <ul class="marquee-track" @if ($copy) aria-hidden="true" @endif>
                        @foreach ($row as $u)
                            <li class="uni-tile">
                                <span class="crest" style="background: {{ $palette[$loop->index % count($palette)] }}">
                                    {{ \Illuminate\Support\Str::of($u->short_name)->replaceMatches('/[^A-Za-z]/', '')->substr(0, 2)->upper() }}
                                    <x-flag :code="$u->country_code" />
                                </span>
                                <span><strong>{{ $u->name }}</strong><span>{{ $u->city }}</span></span>
                            </li>
                        @endforeach
                    </ul>
                @endforeach
            </div>
        @endforeach
    </section>

    {{-- About ---------------------------------------------------------------------------------------- --}}
    <section class="section about-block" id="about">
        <div class="container">
            <div class="row align-items-center g-4 g-lg-5">
                <div class="col-lg-6" data-reveal>
                    <p class="eyebrow">About EUROPA FUSION</p>
                    <h2 class="display-title" style="font-size: clamp(2rem, 3.6vw, 2.9rem)">Education deserves a considered choice.</h2>
                    <p class="lead-text mt-3"><strong>EUROPA FUSION</strong> is a European higher-education advisory built around a simple belief: where and what you study deserves careful consideration.</p>
                    <p>We help students make sense of the European academic landscape — its universities, programmes, admission pathways and possibilities — and approach it with greater knowledge and direction.</p>
                    <p>Our counsel is individual, informed and academically grounded, shaped by the understanding that no two students arrive with the same background or ambition.</p>
                    <p class="pull-quote">We bring clarity to the possibilities; students can choose their path with purpose.</p>
                </div>
                <div class="col-lg-6" data-reveal data-reveal-delay="1">
                    <img src="{{ asset('images/illustrations/advisory.svg') }}" alt="Diagram: EUROPA FUSION connects European universities, expert guidance and the student, with the student first" width="480" height="420" loading="lazy" class="d-block mx-auto">
                </div>
            </div>
        </div>
    </section>

    {{-- Mission & vision ------------------------------------------------------------------------------ --}}
    <section class="section pt-0" aria-label="Mission and vision">
        <div class="container">
            <div class="mv-grid" data-reveal>
                <article class="mv-item">
                    <h2 class="mv-label"><span class="mv-icon"><i class="bi bi-compass" aria-hidden="true"></i></span>Our <span>mission</span></h2>
                    <h3>To cultivate scholars, not merely applicants.</h3>
                    <p>EUROPA FUSION exists to uphold the seriousness of the academic journey. We believe a university education is among the most consequential commitments a young person will make, and it deserves to be entered with rigor, reflection, and intellectual honesty.</p>
                    <p class="mb-0">We seek to nurture in every student a clear sense of scholarly purpose: the discipline to question, the humility to learn, and the resolve to pursue excellence for its own sake. In doing so, we hope to restore to the pursuit of higher education its proper dignity, where ambition is governed by understanding, and achievement is measured by substance rather than status.</p>
                </article>
                <article class="mv-item is-vision">
                    <h2 class="mv-label"><span class="mv-icon"><i class="bi bi-eye" aria-hidden="true"></i></span>Our <span>vision</span></h2>
                    <h3>To advance the enduring ideals of scholarship.</h3>
                    <p>We envision a generation of students who approach the world's great institutions not as destinations to be won, but as communities of thought to join and enrich. We aspire to become a trusted name in international education, recognized for our principles as much as our outcomes.</p>
                    <p class="mb-0">Guided by integrity, intellectual independence and a long view of what learning can achieve, we aim to help shape a culture in which education serves both the individual and the wider good. In every student we support, we hope to see the beginnings of a life devoted to knowledge, service and distinction.</p>
                </article>
            </div>
        </div>
    </section>

    {{-- What we do ------------------------------------------------------------------------------------ --}}
    <section class="section bg-paper">
        <div class="container">
            <div class="wwd">
                <div data-reveal>
                    <p class="eyebrow">What we do</p>
                    <h2>From ambition to academic direction.</h2>
                    <div class="wwd-maxims">
                        <div><i>01</i>Behind every recommendation is research.</div>
                        <div><i>02</i>Behind every application is preparation.</div>
                        <div><i>03</i>Behind every pathway is a reason for choosing it.</div>
                    </div>
                </div>
                <div class="wwd-body" data-reveal data-reveal-delay="1">
                    <p class="lead-text">European higher education is not a single system. Each country, university, and programme brings its own academic structures, expectations, and routes to admission.</p>
                    <p><strong class="text-ink">EUROPA FUSION</strong> brings these elements into perspective.</p>
                    <p>We examine the opportunities available to each student, distinguish what is relevant from what is merely possible, and shape a coherent pathway from initial exploration through to admission.</p>
                    <p>Behind every recommendation is research. Behind every application is preparation. And behind every pathway is a reason for choosing it.</p>
                    <a href="{{ route('services') }}" class="link-arrow mt-2">See how we guide each stage <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
                </div>
            </div>
        </div>
    </section>

    {{-- Approach -------------------------------------------------------------------------------------- --}}
    <section class="section bg-paper pt-0" aria-labelledby="approach-title">
        <div class="container">
            <x-section-head center eyebrow="Our approach" title="A five-stage framework" intro="A 5-stage framework designed to give clarity, avoid common application errors, and keep you confident." id="approach-title" />
            <ol class="approach">
                @foreach ([
                    ['understand', 'Understand', 'We begin by understanding your academic background, interests, career goals and preferred destination.'],
                    ['explore', 'Explore', 'We identify relevant countries, courses and universities based on your profile.'],
                    ['plan', 'Plan', 'We create a practical application strategy and explain the requirements involved.'],
                    ['apply', 'Apply', 'We assist you through the application and documentation process.'],
                    ['prepare', 'Prepare', 'After admission, we guide you through the next stages, including visa preparation and pre-departure planning.'],
                ] as [$icon, $title, $text])
                    <li class="stage" data-reveal data-reveal-delay="{{ min($loop->index, 3) }}">
                        <div class="stage-art"><img src="{{ asset("images/illustrations/step-{$icon}.svg") }}" alt="" width="78" height="78" loading="lazy"></div>
                        <div>
                            <h3>{{ $title }}</h3>
                            <p>{{ $text }}</p>
                        </div>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    {{-- Destinations teaser -------------------------------------------------------------------------- --}}
    <section class="section" aria-labelledby="dest-title">
        <div class="container">
            <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
                <div class="section-head mb-0">
                    <p class="eyebrow">Study destinations</p>
                    <h2 id="dest-title">Where would you like to study?</h2>
                </div>
                <a href="{{ route('destinations') }}" class="link-arrow">Compare all destinations <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
            </div>
            <div class="dest-teaser">
                @foreach ($navDestinations as $d)
                    <a class="dest-pill" href="{{ $d->slug === 'germany' ? route('germany') : $d->url() }}" data-reveal data-reveal-delay="{{ $loop->index % 4 }}">
                        <x-flag :code="$d->code" />
                        <span><strong>{{ $d->name }}</strong><span>{{ $d->shortLivingCost() }}</span></span>
                        <i class="bi bi-arrow-right" aria-hidden="true"></i>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endsection
