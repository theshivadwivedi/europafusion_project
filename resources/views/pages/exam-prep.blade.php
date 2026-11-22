@extends('layouts.app')

@section('title', 'Exam Preparation – IELTS, TOEFL, PTE, GRE, GMAT, dMAT')
@section('description', 'Structured coaching, diagnostic-led strategies and expert faculty for IELTS, TOEFL, PTE, Duolingo, GRE, GMAT, dMAT, SAT and ACT.')

@section('content')
    @php
        $benefits = [
            ['Expert Guidance', 'Receive personalised guidance and exceptional training by our experienced faculty to ensure you achieve the best scores possible.', 'person-workspace'],
            ['Exclusive Study Materials', 'Access meticulously curated study materials, designed to cover every aspect of the test for admission in international universities.', 'journal-richtext'],
            ['Performance Tracking', 'Track and analyse your progress with numerous mock tests, tutorials and make-up classes to attain your target score.', 'graph-up-arrow'],
            ['Personalised Strategies', 'Our coaching uses a unique mentoring model with a personal trainer ensuring individualised attention through innovative teaching methodology.', 'bullseye'],
            ['Timed to Your Application', 'Test dates are planned backwards from deadlines, leaving room for a considered retake if necessary.', 'calendar-check'],
        ];
    @endphp

    <section class="page-hero is-light">
        <div class="container">
            <div class="row align-items-center g-4 g-lg-5">
                <div class="col-lg-6">
                    <ol class="breadcrumb-ef" aria-label="Breadcrumb"><li><a href="{{ route('home') }}">Home</a></li><li aria-current="page">Exam Prep</li></ol>
                    <p class="eyebrow">Examination preparation</p>
                    <h1>Where academic preparation meets the <span class="hl">standard</span></h1>
                    <p class="lead-text">A score is often the first line of an application that an admissions committee reads. We prepare students to make it a strong one — with structured coaching, diagnostic-led strategies, and expert faculty.</p>
                    <ul class="chip-list mt-4">
                        <li class="chip"><i class="bi bi-graph-up-arrow" aria-hidden="true"></i> {{ $languageTests->count() + $standardizedTests->count() }} exams covered</li>
                        <li class="chip"><i class="bi bi-mortarboard" aria-hidden="true"></i> Expert faculty</li>
                        <li class="chip"><i class="bi bi-clipboard2-pulse" aria-hidden="true"></i> Diagnostic led</li>
                    </ul>
                    <div class="d-flex flex-wrap gap-2 mt-4">
                        <a href="{{ route('contact', ['type' => 'counselling']) }}#enquiry" class="btn btn-primary" data-enquiry="counselling">Book a free diagnostic call <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
                        <a href="#language-tests" class="btn btn-outline-primary">Browse the exams</a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <img src="{{ asset('images/illustrations/exam-hero.svg') }}" alt="Illustration: exam preparation desk with books, laptop, score chart and stopwatch" width="520" height="420" class="d-block mx-auto">
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <x-section-head center eyebrow="Why EUROPA FUSION?" title="Unlock the unparalleled benefits of test prep" intro="Our test preparation coaching is comprehensive and provides complete coaching for study abroad tests like TOEFL, PTE, IELTS, GMAT, GRE, ACT, SAT and more." />
            <div class="row g-4 align-items-stretch">
                <div class="col-lg-5" data-reveal>
                    <div class="art-frame"><img src="{{ asset('images/illustrations/exam-benefits.svg') }}" alt="Illustration: a rising score chart reaching its target" width="460" height="400" loading="lazy"></div>
                </div>
                <div class="col-lg-7">
                    <div class="d-grid gap-3">
                        @foreach ($benefits as [$title, $desc, $icon])
                            <div class="benefit-row" data-reveal data-reveal-delay="{{ $loop->index % 3 }}">
                                <span class="num">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                <div><h3><i class="bi bi-{{ $icon }} me-2 text-royal" aria-hidden="true"></i>{{ $title }}</h3><p>{{ $desc }}</p></div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section bg-paper" id="language-tests">
        <div class="container">
            <x-section-head center eyebrow="Language proficiency" title="English language proficiency tests" intro="Demonstrating linguistic competence is a strict requirement for almost all international applications. We provide targeted preparation for the full spectrum of accepted assessments." />
            <div class="row g-4 align-items-center mb-5">
                <div class="col-lg-5" data-reveal><div class="art-frame"><img src="{{ asset('images/illustrations/exam-language.svg') }}" alt="Illustration: speech bubbles in several languages around a globe" width="460" height="400" loading="lazy"></div></div>
                <div class="col-lg-7" data-reveal data-reveal-delay="1">
                    <p class="lead-text">For students whose degree will be taught in English, achieving the required score in an accepted English proficiency test is non-negotiable. We help you identify the right test, build a preparation plan, and practise under exam conditions until your score reflects your true ability.</p>
                    <ul class="chip-list">@foreach ($languageTests as $t)<li class="chip is-gold">{{ \Illuminate\Support\Str::before($t->name, ' ') }}</li>@endforeach</ul>
                </div>
            </div>
            <div class="row g-3">
                @foreach ($languageTests as $t)
                    <div class="col-md-6 col-lg-4" data-reveal data-reveal-delay="{{ $loop->index % 3 }}">
                        <article class="exam-card">
                            <div class="exam-head"><div class="icon-tile"><i class="bi bi-{{ $t->icon }}" aria-hidden="true"></i></div><div><h3>{{ $t->name }}</h3>@if ($t->full_name)<div class="exam-full">{{ $t->full_name }}</div>@endif</div></div>
                            <p>{{ $t->description }}</p>
                        </article>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section bg-ink texture-stars" id="standardized-tests">
        <div class="container">
            <div class="row g-4 g-lg-5 align-items-center mb-5">
                <div class="col-lg-7">
                    <x-section-head eyebrow="Standardized tests" title="Standardized admissions tests" intro="Beyond language, many top-tier institutions require proof of academic readiness through standardized testing. Our curriculum is tailored to master the specific mechanics of each exam." class="mb-3" />
                    <p>For undergraduate and graduate programme entry, standardized tests remain a key differentiator. Our approach is diagnostic-first — we assess where you stand before designing your study plan.</p>
                    <div class="hero-facts border-0 pt-0 mt-3">
                        <div><strong>{{ $standardizedTests->count() }}+</strong><span>Exams covered</span></div>
                        <div><strong>100%</strong><span>Score-focused</span></div>
                    </div>
                </div>
                <div class="col-lg-5"><div class="art-frame"><img src="{{ asset('images/illustrations/exam-standard.svg') }}" alt="Illustration: an answer sheet with a pencil and a stopwatch" width="460" height="400" loading="lazy"></div></div>
            </div>
            <div class="row g-3">
                @foreach ($standardizedTests as $t)
                    <div class="col-md-6 col-lg-4" data-reveal data-reveal-delay="{{ $loop->index % 3 }}">
                        <article class="exam-card">
                            <div class="exam-head"><div class="icon-tile is-dark"><i class="bi bi-{{ $t->icon }}" aria-hidden="true"></i></div><div><h3>{{ $t->name }}</h3>@if ($t->full_name)<div class="exam-full">{{ $t->full_name }}</div>@endif</div></div>
                            <p>{{ $t->description }}</p>
                            @if ($t->tags)<ul class="chip-list mt-3">@foreach ($t->tags as $tag)<li class="chip is-dark">{{ $tag }}</li>@endforeach</ul>@endif
                        </article>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="block" id="faq">
        <div class="container">
            @include('blocks.faq', ['block' => [
                'eyebrow' => 'Got questions?',
                'title' => 'Frequently asked questions',
                'items' => $faqs->map(fn ($f) => ['q' => $f->question, 'a' => $f->answer])->all(),
            ], 'country' => null])
        </div>
    </section>
@endsection
