@php($tel = preg_replace('/[^+0-9]/', '', config('site.phone')))
<footer class="site-footer">
    @unless ($hideFooterCta ?? false)
        <div class="footer-cta">
            <div class="container d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-4">
                <div>
                    <h2 class="cta-title">Ready to explore your options?</h2>
                    <p>Start with profile-based guidance. Talk to our counsellors today — the first conversation is free.</p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('contact', ['type' => 'counselling']) }}#enquiry" class="btn btn-gold" data-enquiry="counselling">Book Free Counselling <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
                    <a href="https://wa.me/{{ config('site.whatsapp') }}" class="btn btn-ghost-light" target="_blank" rel="noopener"><i class="bi bi-whatsapp" aria-hidden="true"></i> WhatsApp us</a>
                </div>
            </div>
        </div>
    @endunless

    <div class="container footer-main">
        <div class="row g-4 g-lg-5">
            <div class="col-lg-4 footer-about">
                <x-brand light />
                <p class="footer-tagline">{{ config('site.tagline') }}</p>
                <p><strong class="text-white">{{ config('site.legal_name') }}</strong> {{ config('site.description') }}</p>
                <ul class="footer-contact mt-4">
                    <li><i class="bi bi-telephone" aria-hidden="true"></i><a href="tel:{{ $tel }}">{{ config('site.phone') }}</a></li>
                    <li><i class="bi bi-envelope" aria-hidden="true"></i><a href="mailto:{{ config('site.email') }}">{{ config('site.email') }}</a></li>
                    <li><i class="bi bi-globe2" aria-hidden="true"></i><a href="{{ url('/') }}">{{ config('site.website') }}</a></li>
                </ul>
                <div class="social-links" aria-label="Follow us">
                    @foreach (['instagram' => 'Instagram', 'facebook' => 'Facebook', 'linkedin' => 'LinkedIn', 'youtube' => 'YouTube'] as $key => $label)
                        @if (config("site.social.$key"))
                            <a href="{{ config("site.social.$key") }}" target="_blank" rel="noopener" aria-label="{{ config('site.name') }} on {{ $label }}"><i class="bi bi-{{ $key }}" aria-hidden="true"></i></a>
                        @endif
                    @endforeach
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <h3>Quick links</h3>
                <ul class="footer-links">
                    <li><a href="{{ route('home') }}">About Us</a></li>
                    <li><a href="{{ route('destinations') }}">Destinations</a></li>
                    <li><a href="{{ route('services') }}">Services</a></li>
                    <li><a href="{{ route('exam-prep') }}">Exam Prep</a></li>
                    <li><a href="{{ route('b2b') }}">B2B Partner</a></li>
                    <li><a href="{{ route('contact') }}">Contact Us</a></li>
                </ul>
            </div>
            <div class="col-6 col-md-4 col-lg-3">
                <h3>Study destinations</h3>
                <ul class="footer-flags">
                    @foreach ($navDestinations as $d)
                        <li><a href="{{ $d->slug === 'germany' ? route('germany') : $d->url() }}"><x-flag :code="$d->code" /> {{ $d->name }}</a></li>
                    @endforeach
                </ul>
                <h3 class="mt-4">Germany pathways</h3>
                <ul class="footer-links">
                    <li><a href="{{ url('germany/studienkolleg') }}">Studienkolleg</a></li>
                    <li><a href="{{ url('germany/bachelor') }}">Bachelor’s</a></li>
                    <li><a href="{{ url('germany/master') }}">Master’s &amp; dMAT</a></li>
                    <li><a href="{{ url('germany/medicine') }}">Medicine</a></li>
                </ul>
            </div>
            <div class="col-md-4 col-lg-3">
                <h3>Our services</h3>
                <ul class="footer-links mb-4">
                    @foreach ($navServices->flatten()->take(8) as $service)
                        <li><a href="{{ $service->url() }}">{{ $service->title }}</a></li>
                    @endforeach
                </ul>
                <h3>Our office</h3>
                @foreach (config('site.offices') as $office)
                    <div class="footer-office">
                        <strong><i class="bi bi-geo-alt me-1 text-gold" aria-hidden="true"></i>{{ $office['name'] }}</strong>
                        @foreach ($office['lines'] as $line){{ $line }}<br>@endforeach
                        <span class="d-inline-block mt-2">
                            @foreach ($office['phones'] as $phone)
                                <a href="tel:{{ preg_replace('/[^+0-9]/', '', $phone) }}">{{ $phone }}</a>@if (! $loop->last) <span class="opacity-50 mx-1">/</span> @endif
                            @endforeach
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="container d-flex flex-column flex-lg-row justify-content-between gap-3">
            <div>
                <div class="legal-entity"><strong>{{ config('site.legal_name') }}</strong> <span class="mx-2 opacity-50">|</span> CIN: {{ config('site.cin') }}</div>
                <div class="mt-1">Registered office: {{ config('site.registered_office') }}</div>
                <div class="mt-1">© {{ date('Y') }} {{ config('site.name') }}. All rights reserved.</div>
            </div>
            <ul class="legal-links align-self-lg-end">
                <li><a href="{{ route('legal', 'privacy-policy') }}">Privacy Policy</a></li>
                <li><a href="{{ route('legal', 'terms-conditions') }}">Terms &amp; Conditions</a></li>
                <li><a href="{{ route('legal', 'disclaimer') }}">Disclaimer</a></li>
                <li><a href="{{ route('legal', 'refund-policy') }}">Refund Policy</a></li>
                <li><a href="{{ route('sitemap') }}">Sitemap</a></li>
            </ul>
        </div>
    </div>
</footer>
