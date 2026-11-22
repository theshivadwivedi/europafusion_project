@php
    $scale = $block['scale'] ?? [1000, 2000];
    $min = min($scale); $max = max($scale);
    $items = collect($block['items'] ?? [])->sortBy('year')->values();
@endphp
<div class="chrono" data-reveal>
    @if (! empty($block['title']))<h3 class="h4 mb-1">{{ $block['title'] }}</h3>@endif
    @if (! empty($block['intro']))<p class="text-muted-ef small mb-4">{{ $block['intro'] }}</p>@endif
    <ol class="chrono-axis list-unstyled">
        @foreach ($items as $i => $item)
            @php($pos = ($item['year'] - $min) / max(1, $max - $min) * 100)
            <li class="chrono-item" style="left: {{ round($pos, 2) }}%">
                <span class="lbl"><span>{{ $item['year'] }}</span>{{ $item['name'] }}</span>
                <span class="stem" style="height: {{ 18 + ($i % 4) * 38 }}px"></span>
                <span class="dot"></span>
            </li>
        @endforeach
    </ol>
    <div class="chrono-scale" aria-hidden="true">@foreach ($scale as $y)<span>{{ $y }}</span>@endforeach</div>
</div>
