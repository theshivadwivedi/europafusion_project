@extends('layouts.app')

@section('title', 'Study Destinations in Europe')
@section('description', 'Compare study destinations across Europe – Germany, Austria, Italy, Finland, Spain, the Netherlands, Poland, Latvia and Lithuania: tuition, living costs, work rights and popular courses.')

@section('content')
    <section class="page-hero">
        <x-star-ring class="hero-orbit" />
        <div class="container">
            <ol class="breadcrumb-ef" aria-label="Breadcrumb"><li><a href="{{ route('home') }}">Home</a></li><li aria-current="page">Destinations</li></ol>
            <p class="eyebrow">Continental Europe &amp; Nordics</p>
            <h1>Explore all <span class="hl">destinations</span></h1>
            <p class="lead-text">Explore quality higher education, affordable tuition, post-study work rights, and research universities across top study destinations.</p>
            <div class="hero-facts">
                <div><strong>{{ $destinations->count() }}</strong><span>Countries</span></div>
                <div><strong>€0</strong><span>Tuition at most German public universities</span></div>
                <div><strong>Up to 2 yrs</strong><span>Post-study stay-back</span></div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="row g-4">
                @foreach ($destinations as $d)
                    <div class="col-md-6 col-xl-4" data-reveal data-reveal-delay="{{ $loop->index % 3 }}">
                        <article class="dest-card">
                            <div class="dest-top">
                                <x-flag :code="$d->code" :name="$d->name" class="flag" width="56" height="38" />
                                <div>
                                    <h2 class="h3 mb-0">{{ $d->name }}</h2>
                                </div>
                                @if ($d->is_primary)<span class="badge-soft primary-tag">Specialist</span>@endif
                            </div>
                            <div class="dest-body">
                                <p class="dest-headline">{{ $d->headline }}</p>
                                <p class="dest-tagline">{{ $d->tagline }}</p>
                                <dl class="dest-facts">
                                    <div><dt>Work rights</dt><dd>{{ $d->shortWorkRights() }}</dd></div>
                                    <div><dt>Living cost</dt><dd>{{ $d->shortLivingCost() }}</dd></div>
                                </dl>
                                <div class="dest-areas">
                                    <p class="small-caps text-muted-ef mb-2">Popular study areas</p>
                                    <ul class="chip-list">
                                        @foreach (array_slice($d->popular_study_areas, 0, 4) as $area)<li class="chip">{{ $area }}</li>@endforeach
                                    </ul>
                                </div>
                                <div class="dest-actions">
                                    <button type="button" class="btn btn-outline-primary" data-destination="{{ $d->slug }}">View details</button>
                                    <a href="{{ $d->slug === 'germany' ? route('germany') : $d->url() }}" class="btn btn-primary">Explore {{ $d->name }} <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
                                </div>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>

            <div class="guidance-panel mt-5" data-reveal>
                <div class="row g-4 align-items-center">
                    <div class="col-lg-7">
                        <p class="eyebrow">Profile-based guidance</p>
                        <h2>Not sure which country is right for you?</h2>
                        <p>Every student’s profile is unique. Match your goals with the right European destination based on your:</p>
                        <div class="formula">
                            @foreach (['Academic qualification', 'GPA / percentage', 'Course preference', 'Budget', 'Career goals'] as $f)
                                <span class="chip">{{ $f }}</span>@unless ($loop->last)<span class="plus" aria-hidden="true">+</span>@endunless
                            @endforeach
                        </div>
                        <p class="mb-0">…and our counsellors will provide personalised country &amp; university recommendations.</p>
                    </div>
                    <div class="col-lg-5 text-lg-end">
                        <a href="{{ route('contact', ['type' => 'evaluation']) }}#enquiry" class="btn btn-gold btn-lg" data-enquiry="evaluation"><i class="bi bi-person-check" aria-hidden="true"></i> Get Free Profile Evaluation</a>
                        <p class="fine">100% free · Objective guidance · No visa guarantees</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('modals')
    <script type="application/json" id="destinations-data">{!! json_encode($destinations->mapWithKeys(fn ($d) => [$d->slug => [
        'name' => $d->name,
        'flag' => asset('images/flags/'.$d->code.'.svg'),
        'headline' => $d->headline,
        'overview' => $d->overview,
        'tuition_range' => $d->tuition_range,
        'living_cost' => $d->living_cost,
        'work_rights' => $d->work_rights,
        'highlights' => $d->highlights,
        'top_universities' => $d->top_universities,
        'url' => $d->slug === 'germany' ? route('germany') : $d->url(),
    ]]), JSON_HEX_TAG | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>

    <div class="modal fade dest-modal" id="destinationModal" tabindex="-1" aria-labelledby="destModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="dest-modal-head">
                    <img data-d="flag" src="" alt="" width="64" height="43">
                    <div>
                        <h2 id="destModalTitle" data-d="title">Study in Europe</h2>
                        <p data-d="headline"></p>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p data-d="overview"></p>
                    <dl class="facts">
                        <div><dt>Estimated tuition</dt><dd data-d="tuition"></dd></div>
                        <div><dt>Estimated living cost</dt><dd data-d="living"></dd></div>
                        <div><dt>Stay-back &amp; work rights</dt><dd data-d="work"></dd></div>
                    </dl>
                    <h3>Key highlights</h3>
                    <ul class="check-list" data-d="highlights"></ul>
                    <h3>Top ranked universities</h3>
                    <ul class="uni-list" data-d="universities"></ul>
                    <div class="d-flex flex-wrap gap-2 mt-4">
                        <a href="{{ route('contact', ['type' => 'counselling']) }}#enquiry" class="btn btn-gold" data-enquiry="counselling" data-d="book"><span>Book free counselling</span> <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
                        <a href="{{ route('destinations') }}" class="btn btn-outline-primary" data-d="page"><span>Explore</span></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endpush
