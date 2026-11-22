@php($H = \App\Support\Html::class)
@include('blocks.partials.head', ['block' => $block])
<div class="row g-3">
    @foreach ($block['items'] ?? [] as $t)
        <div class="col-md-6 col-lg-4" data-reveal data-reveal-delay="{{ $loop->index % 3 }}">
            <article class="t-card">
                <i class="bi bi-mortarboard text-gold fs-4" aria-hidden="true"></i>
                <div class="t-uni mt-2">{{ $t['university'] ?? $t['name'] }}</div>
                @if (! empty($t['program']))<div class="t-prog">{{ $t['program'] }}</div>@endif
                @if (! empty($t['quote']))<p class="mt-2 mb-0 fst-italic">“{{ $t['quote'] }}”</p>@endif
                @if (! empty($t['meta']))<div class="t-meta"><span @class(['chip', 'is-dark' => ($block['tone'] ?? null) === 'dark'])>{{ $t['meta'] }}</span></div>@endif
            </article>
        </div>
    @endforeach
</div>
@if (! empty($block['cta']))
    <div class="mt-4"><x-cta :cta="$block['cta']" :country="$country" class="btn btn-gold"> <i class="bi bi-arrow-right" aria-hidden="true"></i></x-cta></div>
@endif
