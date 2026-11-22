@extends('layouts.app')

@section('title', 'Contact Us')
@section('description', 'Talk to EUROPA FUSION about studying in Germany and Europe. Call or WhatsApp +91 99747 98803, email contact@europafusion.io, or book a free counselling session.')

@section('content')
    @php
        $tel = preg_replace('/[^+0-9]/', '', config('site.phone'));
        $type = in_array(request('type'), ['counselling', 'evaluation'], true) ? request('type') : old('type', 'contact');
        $success = session('enquiry_success');
    @endphp
    <section class="page-hero">
        <x-star-ring class="hero-orbit" />
        <div class="container">
            <ol class="breadcrumb-ef" aria-label="Breadcrumb"><li><a href="{{ route('home') }}">Home</a></li><li aria-current="page">Contact</li></ol>
            <p class="eyebrow">Get in touch</p>
            <h1>Contact <span class="hl">EUROPA FUSION</span></h1>
            <p class="lead-text"><strong class="text-white">Let's start your international education journey.</strong> Have questions about studying abroad? Whether you're still exploring your options or already know your target university, our team can help you understand the next steps.</p>
            <div class="contact-cards">
                <a href="tel:{{ $tel }}"><i class="bi bi-telephone" aria-hidden="true"></i><span><small>Call directly</small><strong>{{ config('site.phone') }}</strong></span></a>
                <a href="https://wa.me/{{ config('site.whatsapp') }}" target="_blank" rel="noopener"><i class="bi bi-whatsapp" aria-hidden="true"></i><span><small>WhatsApp us</small><strong>{{ config('site.phone') }}</strong></span></a>
                <a href="mailto:{{ config('site.email') }}"><i class="bi bi-envelope" aria-hidden="true"></i><span><small>Official email</small><strong>{{ config('site.email') }}</strong></span></a>
            </div>
        </div>
    </section>

    <section class="section" id="enquiry">
        <div class="container">
            <div class="row g-4 g-xl-5">
                <div class="col-lg-7">
                    <div class="form-panel">
                        <div id="contact-success" class="success-panel" @unless ($success) hidden @endunless tabindex="-1" aria-live="polite">
                            <div class="success-icon"><i class="bi bi-check-lg" aria-hidden="true"></i></div>
                            <h2 class="h3">Thank you, <span data-fill="name">{{ $success ?: 'there' }}</span>!</h2>
                            <span class="ref" data-fill="reference" data-hide-empty hidden></span>
                            <p class="text-muted-ef">Your enquiry is with our counselling team. A counsellor will review your profile and contact you — usually within one working day.</p>
                            <div class="d-flex flex-wrap justify-content-center gap-2">
                                <a href="https://wa.me/{{ config('site.whatsapp') }}" class="btn btn-whatsapp" target="_blank" rel="noopener"><i class="bi bi-whatsapp" aria-hidden="true"></i> Continue on WhatsApp</a>
                                <a href="{{ route('destinations') }}" class="btn btn-outline-primary">Explore destinations</a>
                            </div>
                        </div>

                        <form action="{{ route('enquiries.store') }}" method="POST" novalidate data-ajax-form="contact-success" @if ($success) hidden @endif>
                            @csrf
                            <input type="hidden" name="source_url" value="{{ url()->current() }}">
                            <p class="chip mb-3"><i class="bi bi-person-vcard" aria-hidden="true"></i> Profile intake form</p>
                            <h2 class="h3 mb-1">Book a counselling session</h2>
                            <p class="text-muted-ef mb-4">Tell us a little about yourself and your academic aspirations:</p>
                            <fieldset class="mb-3">
                                <legend class="form-label">I would like</legend>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach (['contact' => 'General enquiry', 'counselling' => 'Counselling session', 'evaluation' => 'Profile evaluation'] as $value => $label)
                                        <label class="check-tile" style="height:auto"><input class="form-check-input" type="radio" name="type" value="{{ $value }}" @checked($type === $value)> {{ $label }}</label>
                                    @endforeach
                                </div>
                            </fieldset>
                            <div data-form-alert>
                                @if ($errors->any())
                                    <div class="alert-ef is-error mb-3" role="alert"><i class="bi bi-exclamation-circle" aria-hidden="true"></i><span>Please check the highlighted fields and try again.</span></div>
                                @endif
                            </div>
                            @include('partials.enquiry-fields', ['prefix' => 'c', 'withMessage' => true])
                            <button type="submit" class="btn btn-primary btn-lg w-100 mt-4 btn-submit">
                                <span class="spinner-border spinner-border-sm d-none" aria-hidden="true"></span> Submit enquiry <i class="bi bi-send" aria-hidden="true"></i>
                            </button>
                            <p class="form-hint mt-3 mb-0"><i class="bi bi-shield-lock" aria-hidden="true"></i> We only use your details to respond to this enquiry. See our <a href="{{ route('legal', 'privacy-policy') }}">privacy policy</a>.</p>
                        </form>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="process-card">
                        <p class="eyebrow">Transparent process</p>
                        <h3>What happens next?</h3>
                        <ol>
                            <li><div><h4>Our counsellor reviews your enquiry</h4><p>We examine your academic background, degree, marks/GPA, and target study preferences.</p></div></li>
                            <li><div><h4>We contact you to understand your profile</h4><p>A dedicated advisor reaches out to discuss your specific interests, budgets, and career objectives.</p></div></li>
                            <li><div><h4>We discuss suitable study pathways</h4><p>We evaluate public vs private options, language criteria, dMAT requirements, and deadlines.</p></div></li>
                            <li><div><h4>You receive guidance on the next steps</h4><p>Get structured checklists, documentation frameworks, and an actionable application roadmap.</p></div></li>
                        </ol>
                    </div>
                    <div class="brand-card">
                        <x-brand light />
                        <p class="tag">{{ config('site.tagline') }}</p>
                        <dl>
                            <dt>Phone</dt><dd><a href="tel:{{ $tel }}">{{ config('site.phone') }}</a></dd>
                            <dt>WhatsApp</dt><dd><a href="https://wa.me/{{ config('site.whatsapp') }}" target="_blank" rel="noopener">{{ config('site.phone') }}</a></dd>
                            <dt>Email</dt><dd><a href="mailto:{{ config('site.email') }}">{{ config('site.email') }}</a></dd>
                            <dt>Website</dt><dd>{{ config('site.website') }}</dd>
                            @foreach (config('site.offices') as $office)
                                <dt>Office</dt><dd>{{ $office['name'] }}<br>{{ implode(', ', $office['lines']) }}</dd>
                            @endforeach
                        </dl>
                        <div class="social-links">
                            @foreach (['instagram', 'facebook', 'linkedin', 'youtube'] as $key)
                                @if (config("site.social.$key"))<a href="{{ config("site.social.$key") }}" target="_blank" rel="noopener" aria-label="{{ ucfirst($key) }}"><i class="bi bi-{{ $key }}" aria-hidden="true"></i></a>@endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <p class="block-note mx-auto justify-content-center text-center mt-5"><i class="bi bi-info-circle" aria-hidden="true"></i><span>{{ config('site.disclaimer') }}</span></p>
        </div>
    </section>
@endsection
