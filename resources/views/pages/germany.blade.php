@extends('layouts.app', ['bodyCountry' => 'Germany', 'hideFooterCta' => true])

@section('title', 'Study in Germany')
@section('description', $destination->overview)

@section('content')
    @php
        $pathwayMeta = [
            'germany/studienkolleg' => ['Prep year & FSP exam', 'Bridge a 12-year school record to German university entry with a one-year foundation course and the Feststellungsprüfung.'],
            'germany/bachelor' => ['Undergraduate degrees', 'Admission requirements, the thirteen-year rule, language proof and the two application routes to German public universities.'],
            'germany/master' => ['English & German M.Sc.', 'dMAT, APS, shortlisting, applications and visa — your Master’s in Germany mapped step by step.'],
            'germany/medicine' => ['Staatsexamen & clinicals', 'Eligibility, entry routes, programme structure, costs and the long road to the Approbation.'],
        ];
    @endphp
    <section class="country-hero">
        <div class="flag-band" aria-hidden="true">@foreach ($destination->flagColors() as $c)<span style="background: {{ $c }}"></span>@endforeach</div>
        <img class="skyline" src="{{ asset('images/skylines/germany.svg') }}" alt="" width="700" height="260">
        <div class="container">
            <ol class="breadcrumb-ef" aria-label="Breadcrumb"><li><a href="{{ route('home') }}">Home</a></li><li><a href="{{ route('destinations') }}">Destinations</a></li><li aria-current="page">Germany</li></ol>
            <div class="hero-flag-chip"><x-flag code="de" /> Study in Germany</div>
            <h1>{{ \App\Support\Html::highlight($destination->headline, 'World-Class Public Education') }}</h1>
            <p class="lead-text">{{ $destination->tagline }}</p>
            <div class="d-flex flex-wrap gap-2 mt-4">
                <a href="{{ route('contact', ['type' => 'evaluation']) }}#enquiry" class="btn btn-gold" data-enquiry="evaluation" data-country="Germany">Free Profile Evaluation <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
                <a href="{{ route('contact', ['type' => 'counselling']) }}#enquiry" class="btn btn-ghost-light" data-enquiry="counselling" data-country="Germany">Book Consultation</a>
            </div>
        </div>
    </section>

    <div class="stats-overlap">
        <div class="container">
            @include('blocks.partials.stat-cards', ['items' => [
                ['value' => '€0 – €3,000', 'label' => 'Tuition per semester', 'sub' => 'Public universities'],
                ['value' => '€11,208', 'label' => 'Blocked account / year', 'sub' => 'Living-cost requirement'],
                ['value' => '140 / 280', 'label' => 'Full / half work days', 'sub' => 'Allowed per year'],
                ['value' => '18 months', 'label' => 'Job-seeker permit', 'sub' => 'After graduation'],
            ]])
        </div>
    </div>

    <section class="block pt-4" id="pathways">
        <div class="container">
            <x-section-head eyebrow="Germany degree pathways" title="Choose the route that fits your profile" intro="{{ $destination->overview }}" />
            <div class="row g-4">
                @foreach ($pathways as $p)
                    <div class="col-md-6 col-xl-3" data-reveal data-reveal-delay="{{ $loop->index }}">
                        <a class="pathway-tile" href="{{ url($p->slug) }}">
                            <span class="pt-num">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                            <h3>{{ $p->nav_label }}</h3>
                            <span class="pt-desc">{{ $pathwayMeta[$p->slug][0] ?? '' }}</span>
                            <p>{{ $pathwayMeta[$p->slug][1] ?? $p->meta_description }}</p>
                            <span class="link-arrow">Read the guide <i class="bi bi-arrow-right" aria-hidden="true"></i></span>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="block bg-paper" id="highlights">
        <div class="container">
            <div class="row g-4 g-lg-5">
                <div class="col-lg-6" data-reveal>
                    <p class="eyebrow">Why Germany</p>
                    <h2 class="mb-4">Key highlights</h2>
                    <ul class="checklist-cols is-single">
                        @foreach ($destination->highlights as $h)<li><i class="bi bi-check2-circle" aria-hidden="true"></i><span>{{ $h }}</span></li>@endforeach
                    </ul>
                    <div class="chips-panel">
                        <h3>Popular study areas</h3>
                        <ul class="chip-list">@foreach ($destination->popular_study_areas as $a)<li class="chip is-gold">{{ $a }}</li>@endforeach</ul>
                    </div>
                </div>
                <div class="col-lg-6" data-reveal data-reveal-delay="1">
                    <p class="eyebrow">Top ranked universities</p>
                    <h2 class="mb-4">Where our students apply</h2>
                    <div class="row g-3">
                        @foreach ($destination->top_universities as $u)
                            <div class="col-sm-6">
                                <div class="uni-card">
                                    <div class="uni-top"><span class="uni-mono">{{ mb_strtoupper(mb_substr(preg_replace('/^(Technical University of|University of|TU)\s+/i', '', $u['name']), 0, 2)) }}</span><span class="rank">{{ $u['rank'] }}</span></div>
                                    <h3>{{ $u['name'] }}</h3>
                                    <div class="uni-city mb-0"><i class="bi bi-geo-alt" aria-hidden="true"></i> {{ $u['city'] }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="block">
        <div class="container">
            @include('blocks.cta', ['block' => [
                'eyebrow' => 'Germany specialists',
                'title' => 'Plan your German admission with us',
                'text' => 'From dMAT and APS to blocked accounts and visas — speak with a counsellor who works on German admissions every day.',
                'ctas' => [['label' => 'Book Free Counselling', 'action' => 'counselling'], ['label' => 'Free Profile Evaluation', 'action' => 'evaluation']],
            ], 'country' => 'Germany'])
        </div>
    </section>
@endsection
