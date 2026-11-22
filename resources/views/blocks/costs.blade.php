@php
    $H = \App\Support\Html::class;
    $max = max(1, (float) ($block['scale_max'] ?? collect($block['rows'] ?? [])->max('max') ?? 1));
    $ticks = 5;
@endphp
@include('blocks.partials.head', ['block' => $block])
<div class="row g-4 align-items-start">
    <div @class(['col-lg-7' => ! empty($block['cards']), 'col-12' => empty($block['cards'])])>
        <div class="cost-chart">
            @if (! empty($block['chart_title']))<h3>{{ $block['chart_title'] }}</h3>@endif
            @foreach ($block['rows'] ?? [] as $row)
                @php
                    $lo = isset($row['min']) ? (float) $row['min'] : null;
                    $hi = isset($row['max']) ? (float) $row['max'] : $lo;
                    $left = $lo !== null && $hi !== $lo ? min(100, $lo / $max * 100) : 0;
                    $width = $hi !== null ? max(2, min(100, $hi / $max * 100) - ($hi !== $lo ? $left : 0)) : null;
                @endphp
                <div class="cost-row">
                    <span class="c-label">{{ $row['label'] }}</span>
                    <span class="c-value">{{ $row['value'] }}</span>
                    @if ($width !== null)
                        <span class="c-track" role="img" aria-label="{{ $row['label'] }}: {{ $row['value'] }}">
                            <span class="c-bar" style="left: {{ round($left, 2) }}%; --w: {{ round($width, 2) }}%"></span>
                        </span>
                    @endif
                </div>
            @endforeach
            @if (collect($block['rows'] ?? [])->whereNotNull('max')->isNotEmpty())
                <div class="cost-scale" aria-hidden="true">
                    @foreach (range(0, $ticks) as $t)<span>€{{ number_format($max / $ticks * $t) }}{{ $t === $ticks ? '+' : '' }}</span>@endforeach
                </div>
            @endif
        </div>
    </div>
    @if (! empty($block['cards']))
        <div class="col-lg-5">
            <div class="ef-card">
                <h3 class="mb-1">{{ $block['cards_title'] ?? 'Scholarships & funding' }}</h3>
                @foreach ($block['cards'] as $card)
                    <div class="funding-card">
                        <i class="bi bi-{{ $card['icon'] ?? 'award' }}" aria-hidden="true"></i>
                        <div><h4>{{ $card['title'] }}</h4><p>{{ $H::inline($card['desc'] ?? '') }}</p></div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
