@php
    $H = \App\Support\Html::class;
    $style = $block['style'] ?? 'icon';
    $items = $block['items'] ?? [];
    $cols = $block['columns'] ?? (count($items) % 3 === 0 || count($items) > 4 ? 3 : (count($items) === 4 ? 4 : 2));
    $dark = ($block['tone'] ?? null) === 'dark';
@endphp
@include('blocks.partials.head', ['block' => $block])
<ul class="cards-grid" style="--cols: {{ $cols }}">
    @foreach ($items as $item)
        <li data-reveal data-reveal-delay="{{ $loop->index % 3 }}">
            <article @class(['ef-card is-hoverable', 'style-check' => $style === 'check'])>
                @if ($style === 'check')
                    <span class="check-ico"><i class="bi bi-check-lg" aria-hidden="true"></i></span>
                    <div>
                @elseif ($style === 'numbered')
                    <div class="num-tile">{{ $item['badge'] ?? str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</div>
                @elseif ($style === 'icon' && ! empty($item['icon']))
                    <div @class(['icon-tile', 'is-dark' => $dark])><i class="bi bi-{{ $item['icon'] }}" aria-hidden="true"></i></div>
                @endif

                @if (! empty($item['badge']) && $style !== 'numbered')
                    @if (preg_match('/\d/', $item['badge']) && mb_strlen($item['badge']) < 16)
                        <div class="big-badge">{{ $item['badge'] }}</div>
                    @else
                        <span class="badge-soft mb-2 d-inline-block">{{ $item['badge'] }}</span>
                    @endif
                @endif
                @if (! empty($item['title']))<h3>{{ $H::inline($item['title']) }}</h3>@endif
                @if (! empty($item['desc']))<p>{{ $H::inline($item['desc']) }}</p>@endif
                @if (! empty($item['points']))
                    <ul class="card-points">@foreach ($item['points'] as $pt)<li><span>{{ $H::inline($pt) }}</span></li>@endforeach</ul>
                @endif
                @if (! empty($item['tags']))
                    <ul class="chip-list card-tags">@foreach ($item['tags'] as $tag)<li @class(['chip', 'is-dark' => $dark])>{{ $tag }}</li>@endforeach</ul>
                @endif
                @if (! empty($item['meta']))<div class="card-meta">{{ $H::inline($item['meta']) }}</div>@endif
                @if (! empty($item['cta']))
                    <div class="card-cta"><x-cta :cta="$item['cta']" :country="$country" :class="$dark ? 'link-arrow on-dark' : 'link-arrow'"> <i class="bi bi-arrow-right" aria-hidden="true"></i></x-cta></div>
                @endif

                @if ($style === 'check')</div>@endif
            </article>
        </li>
    @endforeach
</ul>
