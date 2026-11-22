@php
    $d = $page->destination;
    $skyline = $d ? 'images/skylines/'.$d->slug.'.svg' : null;
    $crumbs = [['Home', route('home')], ['Destinations', route('destinations')]];
    if ($page->kind === 'pathway') { $crumbs[] = ['Germany', route('germany')]; }
@endphp
<section class="country-hero">
    @if ($d)
        <div class="flag-band" aria-hidden="true">@foreach ($d->flagColors() as $c)<span style="background: {{ $c }}"></span>@endforeach</div>
    @endif
    @if ($skyline && file_exists(public_path($skyline)))
        <img class="skyline" src="{{ asset($skyline) }}" alt="" width="700" height="260">
    @endif
    <div class="container">
        <ol class="breadcrumb-ef" aria-label="Breadcrumb">
            @foreach ($crumbs as [$label, $href])<li><a href="{{ $href }}">{{ $label }}</a></li>@endforeach
            <li aria-current="page">{{ $page->nav_label ?? $page->title }}</li>
        </ol>
        <div class="hero-flag-chip">
            @if ($d)<x-flag :code="$d->code" />@endif
            {{ $block['eyebrow'] ?? $page->title }}
        </div>
        @if (! empty($block['tagline']))<p class="hero-tagline">{{ $block['tagline'] }}</p>@endif
        <h1>{{ \App\Support\Html::highlight($block['title'], $block['highlight'] ?? null) }}</h1>
        @if (! empty($block['lead']))<p class="lead-text">{{ \App\Support\Html::inline($block['lead']) }}</p>@endif
        @if (! empty($block['chips']))
            <ul class="chip-list mt-3">
                @foreach ($block['chips'] as $chip)<li class="chip is-dark">{{ $chip }}</li>@endforeach
            </ul>
        @endif
        <div class="d-flex flex-wrap gap-2 mt-4">
            @forelse ($block['ctas'] ?? [] as $cta)
                <x-cta :cta="$cta" :country="$d?->name" :class="$loop->first ? 'btn btn-gold' : 'btn btn-ghost-light'">@if ($loop->first) <i class="bi bi-arrow-right" aria-hidden="true"></i>@endif</x-cta>
            @empty
                <x-cta :cta="['label' => 'Free Profile Evaluation', 'action' => 'evaluation']" :country="$d?->name" class="btn btn-gold"> <i class="bi bi-arrow-right" aria-hidden="true"></i></x-cta>
                <x-cta :cta="['label' => 'Book Consultation', 'action' => 'counselling']" :country="$d?->name" class="btn btn-ghost-light" />
            @endforelse
        </div>
        @if (! empty($block['facts']))
            <div class="hero-facts">
                @foreach ($block['facts'] as $fact)<div><strong>{{ $fact['value'] }}</strong><span>{{ $fact['label'] }}</span></div>@endforeach
            </div>
        @endif
    </div>
</section>
