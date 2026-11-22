@php
    $is = fn (...$patterns) => request()->is(...$patterns);
    $germany = $navDestinations->firstWhere('slug', 'germany');
    $others = $navDestinations->where('slug', '!=', 'germany');
    $pathways = [
        ['Studienkolleg', 'germany/studienkolleg', 'Prep year & FSP exam'],
        ['Bachelor', 'germany/bachelor', 'Undergraduate degrees'],
        ['Master', 'germany/master', 'English & German M.Sc.'],
        ['Medicine', 'germany/medicine', 'Staatsexamen & clinicals'],
    ];
    $tel = preg_replace('/[^+0-9]/', '', config('site.phone'));
@endphp
<header class="site-header">
    <div class="topbar">
        <div class="container">
            <span class="topbar-pill"><span class="star" aria-hidden="true">★</span>{{ config('site.specialism') }}</span>
            <div class="topbar-links">
                <a href="{{ route('contact', ['type' => 'evaluation']) }}#enquiry" class="topbar-cta" data-enquiry="evaluation">Free Profile Evaluation</a>
                <a href="tel:{{ $tel }}"><i class="bi bi-telephone me-1" aria-hidden="true"></i>{{ config('site.phone') }}</a>
                <a href="mailto:{{ config('site.email') }}" class="d-none d-md-inline"><i class="bi bi-envelope me-1" aria-hidden="true"></i>{{ config('site.email') }}</a>
            </div>
        </div>
    </div>

    <nav class="mainbar" aria-label="Main navigation">
        <div class="container mainbar-inner">
            <x-brand />

            <ul class="nav-primary d-none d-lg-flex">
                <li><a href="{{ route('home') }}" @class(['nav-link-ef', 'is-active' => $is('/')]) @if ($is('/')) aria-current="page" @endif>About Us</a></li>

                <li class="dropdown mega">
                    <button type="button" @class(['nav-link-ef', 'is-active' => $is('destinations', 'study-in-*', 'germany/*')]) data-href="{{ route('destinations') }}" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                        Destinations <i class="bi bi-chevron-down" aria-hidden="true"></i>
                    </button>
                    <div class="dropdown-menu mega-menu">
                        <div class="mega-grid">
                            <div class="mega-main">
                                <p class="mega-title">European study destinations</p>
                                <p class="mega-sub">Tuition-free public universities, top engineering hubs &amp; post-study work permits</p>
                                <div class="country-links">
                                    @foreach ($others as $d)
                                        <a class="country-link" href="{{ $d->url() }}">
                                            <x-flag :code="$d->code" />
                                            <span><strong>{{ $d->name }}</strong><span>{{ \Illuminate\Support\Str::limit($d->headline, 44) }}</span></span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                            <div class="mega-side">
                                @if ($germany)
                                    <div class="pathway-card">
                                        <a class="pathway-head p-0 mb-2" href="{{ route('germany') }}"><x-flag :code="$germany->code" /> Germany degree pathways</a>
                                        <ul>
                                            @foreach ($pathways as [$label, $path, $desc])
                                                <li><a href="{{ url($path) }}">{{ $label }} <small>{{ $desc }}</small></a></li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <div class="mega-cta">
                                    <p>Not sure which destination fits your GPA, budget &amp; career goals?</p>
                                    <a href="{{ route('contact', ['type' => 'evaluation']) }}#enquiry" class="btn btn-gold btn-sm w-100" data-enquiry="evaluation">Get free profile evaluation <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="mega-foot">
                            <span class="text-muted-ef">{{ $navDestinations->count() }} countries · public &amp; private universities</span>
                            <a class="link-arrow" href="{{ route('destinations') }}">Compare all destinations <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
                        </div>
                    </div>
                </li>

                <li class="dropdown mega">
                    <button type="button" @class(['nav-link-ef', 'is-active' => $is('services')]) data-href="{{ route('services') }}" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                        Services <i class="bi bi-chevron-down" aria-hidden="true"></i>
                    </button>
                    <div class="dropdown-menu mega-menu" style="width:min(820px, calc(100vw - 2rem))">
                        <div class="mega-main">
                            <p class="mega-title">Our end-to-end services</p>
                            <p class="mega-sub">Structured guidance from your first consultation to campus settlement</p>
                            <div class="service-cols">
                                @foreach (\App\Models\Service::GROUPS as $key => $label)
                                    <div>
                                        <p class="service-col-title"><em>{{ $loop->first ? 'I.' : 'II.' }}</em> {{ $label }}</p>
                                        @foreach ($navServices->get($key, collect()) as $service)
                                            <a class="service-link" href="{{ $service->url() }}">
                                                <span class="ico"><i class="bi bi-{{ $service->icon }}" aria-hidden="true"></i></span>
                                                <span><strong>{{ $service->title }}</strong><span>{{ $service->summary }}</span></span>
                                            </a>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="mega-foot">
                            <span class="text-muted-ef">2 categories · {{ $navServices->flatten()->count() }} services</span>
                            <a class="link-arrow" href="{{ route('services') }}">All services overview <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
                        </div>
                    </div>
                </li>

                <li><a href="{{ route('exam-prep') }}" @class(['nav-link-ef', 'is-active' => $is('exam-prep')])>Exam Prep</a></li>
                <li><a href="{{ route('contact') }}" @class(['nav-link-ef', 'is-active' => $is('contact')])>Contact</a></li>
            </ul>

            <div class="nav-actions ms-auto ms-lg-0">
                <a href="{{ route('b2b') }}" @class(['btn-b2b d-none d-sm-inline-flex', 'is-active' => $is('b2b')])><i class="bi bi-buildings" aria-hidden="true"></i>B2B Partner</a>
                <a href="{{ route('contact', ['type' => 'counselling']) }}#enquiry" class="btn btn-primary btn-sm d-none d-xl-inline-flex" data-enquiry="counselling">Book Free Counselling</a>
                <button class="nav-toggle d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileNav" aria-controls="mobileNav" aria-label="Open menu">
                    <i class="bi bi-list" aria-hidden="true"></i>
                </button>
            </div>
        </div>
    </nav>
</header>

<div class="offcanvas offcanvas-end mobile-nav" tabindex="-1" id="mobileNav" aria-label="Menu">
    <div class="offcanvas-header">
        <x-brand />
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close menu"></button>
    </div>
    <div class="offcanvas-body">
        <ul class="m-list">
            <li><a href="{{ route('home') }}">About Us</a></li>
            <li>
                <details @if ($is('destinations', 'study-in-*', 'germany/*')) open @endif>
                    <summary>Destinations <i class="bi bi-chevron-down" aria-hidden="true"></i></summary>
                    <ul class="m-sub">
                        <li class="m-sub-head">Germany pathways</li>
                        <li><a href="{{ route('germany') }}"><x-flag code="de" /> Study in Germany</a></li>
                        @foreach ($pathways as [$label, $path])
                            <li><a href="{{ url($path) }}" class="ps-5">{{ $label }}</a></li>
                        @endforeach
                        <li class="m-sub-head">More of Europe</li>
                        @foreach ($others as $d)
                            <li><a href="{{ $d->url() }}"><x-flag :code="$d->code" /> {{ $d->name }}</a></li>
                        @endforeach
                        <li><a href="{{ route('destinations') }}"><i class="bi bi-grid" aria-hidden="true"></i> Compare all destinations</a></li>
                    </ul>
                </details>
            </li>
            <li>
                <details @if ($is('services')) open @endif>
                    <summary>Services <i class="bi bi-chevron-down" aria-hidden="true"></i></summary>
                    <ul class="m-sub">
                        @foreach (\App\Models\Service::GROUPS as $key => $label)
                            <li class="m-sub-head">{{ $label }}</li>
                            @foreach ($navServices->get($key, collect()) as $service)
                                <li><a href="{{ $service->url() }}"><i class="bi bi-{{ $service->icon }}" aria-hidden="true"></i> {{ $service->title }}</a></li>
                            @endforeach
                        @endforeach
                    </ul>
                </details>
            </li>
            <li><a href="{{ route('exam-prep') }}">Exam Prep</a></li>
            <li><a href="{{ route('contact') }}">Contact</a></li>
            <li><a href="{{ route('b2b') }}">B2B Partner</a></li>
        </ul>
        <div class="d-grid gap-2">
            <a href="{{ route('contact', ['type' => 'counselling']) }}#enquiry" class="btn btn-primary" data-enquiry="counselling">Book Free Counselling</a>
            <a href="{{ route('contact', ['type' => 'evaluation']) }}#enquiry" class="btn btn-outline-primary" data-enquiry="evaluation">Free Profile Evaluation</a>
        </div>
        <div class="m-contact">
            <div class="small-caps text-muted-ef mb-2">Talk to us</div>
            <div><i class="bi bi-telephone me-2" aria-hidden="true"></i><a href="tel:{{ $tel }}">{{ config('site.phone') }}</a></div>
            <div class="mt-1"><i class="bi bi-envelope me-2" aria-hidden="true"></i><a href="mailto:{{ config('site.email') }}">{{ config('site.email') }}</a></div>
        </div>
    </div>
</div>
