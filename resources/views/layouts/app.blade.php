<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        $pageTitle = trim($__env->yieldContent('title'));
        $fullTitle = $pageTitle ? $pageTitle.' | '.config('site.name') : config('site.name').' | Study Abroad with Clarity, Confidence & The Right Guidance';
        $metaDescription = trim($__env->yieldContent('description')) ?: 'EUROPA FUSION – Your Journey. Our Guidance. Your Global Future. Europe & Germany focused study abroad guidance, dMAT prep, Master’s, IT, Engineering & Ausbildung.';
    @endphp
    <title>{{ $fullTitle }}</title>
    <meta name="description" content="{{ $metaDescription }}">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta name="theme-color" content="#15294d">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ config('site.name') }}">
    <meta property="og:title" content="{{ $fullTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('images/og-image.png') }}">
    <meta name="twitter:card" content="summary_large_image">
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="32x32">
    <link rel="apple-touch-icon" href="{{ asset('images/apple-touch-icon.png') }}">
    <script>document.documentElement.classList.add('js');</script>
    @vite('resources/js/app.js')
    <script type="application/ld+json">{!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'EducationalOrganization',
        'name' => config('site.legal_name'),
        'alternateName' => config('site.name'),
        'url' => url('/'),
        'logo' => asset('images/logo-emblem.svg'),
        'email' => config('site.email'),
        'telephone' => config('site.phone'),
        'address' => ['@type' => 'PostalAddress', 'streetAddress' => 'Office No. 715A, No. 769, Spencer Plaza, Anna Salai', 'addressLocality' => 'Chennai', 'addressRegion' => 'Tamil Nadu', 'postalCode' => '600002', 'addressCountry' => 'IN'],
        'sameAs' => array_values(array_filter(config('site.social'))),
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @stack('head')
</head>
<body @isset($bodyCountry) data-country="{{ $bodyCountry }}" @endisset>
    <a href="#main" class="skip-link">Skip to content</a>

    @include('partials.header')

    <main id="main" tabindex="-1">
        @yield('content')
    </main>

    @include('partials.footer')
    @include('partials.enquiry-modal')

    <a class="wa-float" href="https://wa.me/{{ config('site.whatsapp') }}" target="_blank" rel="noopener" aria-label="Chat with us on WhatsApp">
        <i class="bi bi-whatsapp" aria-hidden="true"></i><span class="wa-label">Chat with us on WhatsApp</span>
    </a>
    <button type="button" class="back-to-top" aria-label="Back to top"><i class="bi bi-arrow-up" aria-hidden="true"></i></button>

    <nav class="quickbar" aria-label="Quick contact">
        <a href="tel:{{ preg_replace('/[^+0-9]/', '', config('site.phone')) }}"><i class="bi bi-telephone" aria-hidden="true"></i>Call</a>
        <a class="is-wa" href="https://wa.me/{{ config('site.whatsapp') }}" target="_blank" rel="noopener"><i class="bi bi-whatsapp" aria-hidden="true"></i>WhatsApp</a>
        <a class="is-primary" href="{{ route('contact') }}#enquiry" data-enquiry="counselling"><i class="bi bi-calendar2-check" aria-hidden="true"></i>Counselling</a>
    </nav>

    @stack('modals')
    @stack('scripts')
</body>
</html>
