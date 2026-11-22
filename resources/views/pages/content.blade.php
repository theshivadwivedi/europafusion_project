{{-- Country & pathway pages: rendered from the JSON content blocks in pages.sections --}}
@extends('layouts.app', ['bodyCountry' => $page->destination?->name, 'hideFooterCta' => true])

@section('title', $page->title)
@section('description', $page->meta_description)

@section('content')
    @php
        $sections = collect($page->sections);
        $hero = $sections->firstWhere('type', 'hero');
        $rest = $sections->reject(fn ($s) => $s['type'] === 'hero')->values();
        // Stats directly after the hero overlap it as a card row.
        $overlapStats = ($rest->first()['type'] ?? null) === 'stats' && empty($rest->first()['title']) ? $rest->shift() : null;
        $anchors = $page->anchors();
        $country = $page->destination?->name;
        $tone = 0;
    @endphp

    @include('blocks.hero', ['block' => $hero, 'page' => $page])

    @if ($overlapStats)
        <div class="stats-overlap">
            <div class="container">
                @include('blocks.partials.stat-cards', ['items' => $overlapStats['items']])
            </div>
        </div>
    @endif

    @if (count($anchors) > 2)
        <nav class="page-subnav" id="pageSubnav" aria-label="On this page" data-spy-nav>
            <div class="container">
                <div class="nav">
                    @foreach ($anchors as $a)
                        <a class="nav-link" href="#{{ $a['id'] }}">{{ $a['label'] }}</a>
                    @endforeach
                </div>
            </div>
        </nav>
    @endif

    @foreach ($rest as $i => $block)
        @php
            $type = $block['type'];
            $id = $block['id'] ?? $type.'-'.$i;
            $isDark = ($block['tone'] ?? null) === 'dark';
            if (! $isDark && ! in_array($type, ['cta', 'notice'], true)) { $tone++; }
            $bg = $isDark ? 'bg-ink texture-stars' : (($block['tone'] ?? null) === 'tint' || ($tone % 2 === 0 && ! in_array($type, ['cta', 'notice'], true)) ? 'bg-paper' : '');
        @endphp
        @if (view()->exists("blocks.$type"))
            <section id="{{ $id }}" @class(['block', "block-$type", $bg])>
                <div class="container">
                    @include("blocks.$type", ['block' => $block, 'country' => $country])
                    @if (! empty($block['note']) && ! in_array($type, ['cta'], true))
                        <p class="block-note"><i class="bi bi-info-circle" aria-hidden="true"></i><span>{{ \App\Support\Html::inline($block['note']) }}</span></p>
                    @endif
                </div>
            </section>
        @endif
    @endforeach

    @if ($related->isNotEmpty())
        <section class="section-sm border-top">
            <div class="container">
                <p class="small-caps text-muted-ef mb-3">{{ $page->kind === 'pathway' ? 'More Germany pathways' : 'Explore other destinations' }}</p>
                <div class="related-strip">
                    @foreach ($related as $r)
                        <a class="chip" href="{{ url($r->slug) }}">{{ $r->nav_label ?? $r->title }} <i class="bi bi-arrow-right-short" aria-hidden="true"></i></a>
                    @endforeach
                    <a class="chip is-gold" href="{{ route('destinations') }}">Compare all destinations</a>
                </div>
            </div>
        </section>
    @endif
@endsection
