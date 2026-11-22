@extends('layouts.app')

@section('title', 'B2B Partnerships')
@section('description', 'Partner with EUROPA FUSION: schools, colleges, corporates, coaching centres, agencies and NGOs can offer European study-abroad guidance to their students and employees.')

@section('content')
    @php
        $tel = preg_replace('/[^+0-9]/', '', config('site.phone'));
        $success = session('partner_success');
        $audiences = [
            ['Schools & Junior Colleges', 'Offer European study abroad options to your Class 11 & 12 students as a premium add-on to your institution.', 'backpack2'],
            ['Degree Colleges & Universities', "Help graduating students and alumni unlock EU master's, bachelor's and research opportunities.", 'mortarboard'],
            ['HR & Corporate Teams', 'Enable employees & sponsored talents with structured international upskilling and MBA pathways.', 'briefcase'],
            ['Coaching & Training Centers', 'Add a Europe study-abroad vertical to your student services — we handle end-to-end guidance.', 'people'],
            ['Education Consultants & Agencies', 'Collaborate under a referral or co-counselling model and expand your Europe offering instantly.', 'globe-europe-africa'],
            ['NGOs & Skill Development Bodies', 'Help your beneficiaries access tuition-free European university education and vocational programs.', 'building'],
        ];
        $benefits = [
            ['Dedicated B2B Relationship Manager', 'A single point of contact who works closely with your team to onboard and support students.', 'person-badge'],
            ['Revenue Sharing / Referral Model', 'Earn on every successful admission through a transparent and structured referral agreement.', 'cash-coin'],
            ['Co-branded Student Materials', "Workshops, brochures, webinars and info sessions with your institution's branding.", 'easel'],
            ['Expert Europe Counselling', 'We cover Germany (dMAT, APS), Finland, Ireland, Netherlands, France, Poland & 6 more destinations.', 'compass'],
            ['Priority Processing for Partner Students', 'Students referred by partner institutions get priority slots and dedicated advisor attention.', 'lightning-charge'],
            ['Flexible Partnership Models', 'Choose from referral, co-counselling, campus integration, or white-label model — whatever fits you best.', 'sliders'],
        ];
    @endphp

    <section class="page-hero">
        <div class="container">
            <div class="row align-items-center g-4 g-lg-5">
                <div class="col-lg-7">
                    <ol class="breadcrumb-ef" aria-label="Breadcrumb"><li><a href="{{ route('home') }}">Home</a></li><li aria-current="page">B2B Partner</li></ol>
                    <p class="eyebrow">Institutional &amp; corporate partnerships</p>
                    <h1 style="max-width: 20ch">Partner with EUROPA FUSION. Grow together in <span class="hl">European education</span>.</h1>
                    <p class="lead-text">Join our B2B network — schools, colleges, corporates, coaching centres and education agencies — and help your students or employees unlock world-class European university education.</p>
                    <div class="d-flex flex-wrap gap-2 mt-4">
                        <a href="#b2b-form" class="btn btn-gold"><i class="bi bi-stars" aria-hidden="true"></i> Register as Partner <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
                        <a href="tel:{{ $tel }}" class="btn btn-ghost-light"><i class="bi bi-telephone" aria-hidden="true"></i> Talk to B2B Team</a>
                    </div>
                    <div class="b2b-hero-stats">
                        <div><strong>11+</strong><span>European destinations</span></div>
                        <div><strong>100+</strong><span>Profiles guided</span></div>
                        <div><strong>3</strong><span>Office locations</span></div>
                        <div><strong>100%</strong><span>Free counselling</span></div>
                    </div>
                </div>
                <div class="col-lg-5 d-none d-lg-block">
                    <img src="{{ asset('images/illustrations/b2b-network.svg') }}" alt="Illustration: EUROPA FUSION at the centre of a network of schools, universities, companies, agencies and coaching centres" width="520" height="440">
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <x-section-head center eyebrow="Who can partner" title="Built for organisations like yours" intro="Whether you're an educational institution, a company or a consultancy — there's a partnership model designed for you." />
            <ul class="cards-grid" style="--cols: 3">
                @foreach ($audiences as [$title, $desc, $icon])
                    <li data-reveal data-reveal-delay="{{ $loop->index % 3 }}"><article class="ef-card is-hoverable"><div class="icon-tile"><i class="bi bi-{{ $icon }}" aria-hidden="true"></i></div><h3>{{ $title }}</h3><p>{{ $desc }}</p></article></li>
                @endforeach
            </ul>
        </div>
    </section>

    <section class="section bg-paper">
        <div class="container">
            <x-section-head center eyebrow="Partnership benefits" title="What you get as a partner" intro="We make the partnership rewarding for you and transformative for your students or employees." />
            <ul class="cards-grid" style="--cols: 3">
                @foreach ($benefits as [$title, $desc, $icon])
                    <li data-reveal data-reveal-delay="{{ $loop->index % 3 }}"><article class="ef-card is-hoverable"><div class="icon-tile is-gold"><i class="bi bi-{{ $icon }}" aria-hidden="true"></i></div><h3>{{ $title }}</h3><p>{{ $desc }}</p></article></li>
                @endforeach
            </ul>
        </div>
    </section>

    <section class="section" id="b2b-form">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-9">
                    <x-section-head center eyebrow="Partner registration" title="Register your organisation" intro="Fill in the form below and our B2B team will contact you within 1 business day to discuss partnership options." />
                    <div class="form-panel">
                        <div id="partner-success" class="success-panel" @unless ($success) hidden @endunless tabindex="-1" aria-live="polite">
                            <div class="success-icon"><i class="bi bi-check-lg" aria-hidden="true"></i></div>
                            <h3>Partnership request received!</h3>
                            <p class="text-muted-ef">Thank you — the request for <strong data-fill="organisation">{{ $success ?: 'your organisation' }}</strong> is with our B2B team. We will contact you within 1 business day.</p>
                            <a href="tel:{{ $tel }}" class="btn btn-primary"><i class="bi bi-telephone" aria-hidden="true"></i> Prefer to talk now? {{ config('site.phone') }}</a>
                        </div>

                        <form action="{{ route('b2b.store') }}" method="POST" novalidate data-ajax-form="partner-success" @if ($success) hidden @endif>
                            @csrf
                            <div class="hp-field" aria-hidden="true"><label for="b-website">Website</label><input type="text" id="b-website" name="website" tabindex="-1" autocomplete="off"></div>
                            <div data-form-alert>
                                @if ($errors->any())<div class="alert-ef is-error mb-3" role="alert"><i class="bi bi-exclamation-circle" aria-hidden="true"></i><span>Please check the highlighted fields and try again.</span></div>@endif
                            </div>

                            <p class="form-section-title">Organisation details</p>
                            <div class="row g-3">
                                <div class="col-md-6" data-field>
                                    <label class="form-label" for="b-org">Organisation name <span class="req">*</span></label>
                                    <input class="form-control @error('org_name') is-invalid @enderror" id="b-org" name="org_name" value="{{ old('org_name') }}" placeholder="e.g. Sunrise International School" required maxlength="160">
                                    @error('org_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6" data-field>
                                    <label class="form-label" for="b-type">Organisation type <span class="req">*</span></label>
                                    <select class="form-select @error('org_type') is-invalid @enderror" id="b-type" name="org_type" required>
                                        <option value="">Select type</option>
                                        @foreach ($orgTypes as $t)<option @selected(old('org_type') === $t)>{{ $t }}</option>@endforeach
                                    </select>
                                    @error('org_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6" data-field>
                                    <label class="form-label" for="b-city">City <span class="req">*</span></label>
                                    <input class="form-control @error('city') is-invalid @enderror" id="b-city" name="city" value="{{ old('city') }}" placeholder="e.g. Mumbai" required maxlength="80" autocomplete="address-level2">
                                    @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6" data-field>
                                    <label class="form-label" for="b-state">State <span class="req">*</span></label>
                                    <input class="form-control @error('state') is-invalid @enderror" id="b-state" name="state" value="{{ old('state') }}" placeholder="e.g. Maharashtra" required maxlength="80" autocomplete="address-level1">
                                    @error('state')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <p class="form-section-title">Contact person</p>
                            <div class="row g-3">
                                <div class="col-md-6" data-field>
                                    <label class="form-label" for="b-person">Full name <span class="req">*</span></label>
                                    <input class="form-control @error('contact_person') is-invalid @enderror" id="b-person" name="contact_person" value="{{ old('contact_person') }}" placeholder="Your full name" required maxlength="120" autocomplete="name">
                                    @error('contact_person')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6" data-field>
                                    <label class="form-label" for="b-desig">Designation <span class="req">*</span></label>
                                    <input class="form-control @error('designation') is-invalid @enderror" id="b-desig" name="designation" value="{{ old('designation') }}" placeholder="e.g. Principal, HR Manager, Director" required maxlength="120" autocomplete="organization-title">
                                    @error('designation')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6" data-field>
                                    <label class="form-label" for="b-email">Work email <span class="req">*</span></label>
                                    <input class="form-control @error('email') is-invalid @enderror" id="b-email" name="email" type="email" value="{{ old('email') }}" placeholder="you@organisation.com" required maxlength="190" autocomplete="email">
                                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6" data-field>
                                    <label class="form-label" for="b-phone">Phone / WhatsApp <span class="req">*</span></label>
                                    <input class="form-control @error('phone') is-invalid @enderror" id="b-phone" name="phone" type="tel" value="{{ old('phone') }}" placeholder="+91 XXXXX XXXXX" required maxlength="30" autocomplete="tel">
                                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <p class="form-section-title">Partnership scope</p>
                            <div class="row g-3">
                                <div class="col-md-6" data-field>
                                    <label class="form-label" for="b-volume">Estimated students / referrals per year</label>
                                    <select class="form-select @error('students_per_year') is-invalid @enderror" id="b-volume" name="students_per_year">
                                        @foreach ($volumes as $v)<option @selected(old('students_per_year') === $v)>{{ $v }}</option>@endforeach
                                    </select>
                                    @error('students_per_year')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-12" data-field>
                                    <fieldset>
                                        <legend class="form-label">Areas of interest <span class="text-muted-ef fw-normal">(select all that apply)</span></legend>
                                        <div class="row g-2">
                                            @foreach ($interests as $i => $interest)
                                                <div class="col-sm-6 col-lg-4"><label class="check-tile"><input class="form-check-input" type="checkbox" name="interests[]" value="{{ $interest }}" @checked(in_array($interest, old('interests', []), true))> {{ $interest }}</label></div>
                                            @endforeach
                                        </div>
                                    </fieldset>
                                    @error('interests')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-12" data-field>
                                    <label class="form-label" for="b-msg">Tell us more about your requirements</label>
                                    <textarea class="form-control @error('message') is-invalid @enderror" id="b-msg" name="message" rows="4" maxlength="3000" placeholder="Any specific requests, preferred partnership model, campus visit availability, etc.">{{ old('message') }}</textarea>
                                    @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <p class="form-hint mt-4">By submitting, you agree to be contacted by our B2B team. We never share your data with third parties.</p>
                            <button type="submit" class="btn btn-primary btn-lg w-100 btn-submit"><span class="spinner-border spinner-border-sm d-none" aria-hidden="true"></span> Submit partnership request <i class="bi bi-arrow-right" aria-hidden="true"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section pt-0">
        <div class="container">
            <div class="talk-band" data-reveal>
                <div>
                    <h2>Prefer to talk first?</h2>
                    <p>Our B2B team is available {{ config('site.b2b_hours') }}. Reach us at any of our offices.</p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="tel:{{ $tel }}" class="btn btn-primary"><i class="bi bi-telephone" aria-hidden="true"></i> {{ config('site.phone') }}</a>
                    <a href="mailto:{{ config('site.email') }}" class="btn btn-outline-primary"><i class="bi bi-envelope" aria-hidden="true"></i> {{ config('site.email') }}</a>
                </div>
            </div>
        </div>
    </section>
@endsection
