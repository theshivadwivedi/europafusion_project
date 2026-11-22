@php($H = \App\Support\Html::class)
@include('blocks.partials.head', ['block' => $block])
<div class="row g-3">
    @foreach ($block['items'] ?? [] as $u)
        @php($mono = collect(preg_split('/[\s\-]+/', preg_replace('/\(.*?\)|\b(of|the|di|de|and|for|University|Universität)\b/i', '', $u['name'])))->filter()->map(fn ($w) => mb_substr($w, 0, 1))->take(2)->implode(''))
        <div class="col-md-6 col-lg-4 col-xl-3" data-reveal data-reveal-delay="{{ $loop->index % 4 }}">
            <article class="uni-card">
                <div class="uni-top">
                    <span class="uni-mono" aria-hidden="true">{{ mb_strtoupper($mono ?: mb_substr($u['name'], 0, 2)) }}</span>
                    @if (! empty($u['rank']))<span class="rank">{{ $u['rank'] }}</span>@elseif (! empty($u['type']))<span class="badge-soft">{{ $u['type'] }}</span>@endif
                </div>
                <h3>{{ $u['name'] }}</h3>
                <div class="uni-city">
                    @if (! empty($u['city']))<i class="bi bi-geo-alt" aria-hidden="true"></i> {{ $u['city'] }}@endif
                    @if (! empty($u['rank']) && ! empty($u['type'])) · {{ $u['type'] }}@endif
                </div>
                @if (! empty($u['desc']))<p>{{ $H::inline($u['desc']) }}</p>@endif
                <div class="uni-foot">
                    <x-cta :cta="['label' => $block['cta_label'] ?? 'Explore courses', 'action' => 'counselling']" :country="$country" class="link-arrow"> <i class="bi bi-arrow-right" aria-hidden="true"></i></x-cta>
                </div>
            </article>
        </div>
    @endforeach
</div>
