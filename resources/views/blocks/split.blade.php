@php($H = \App\Support\Html::class)
@include('blocks.partials.head', ['block' => $block])
<div class="split-grid" style="--cols: {{ min(count($block['columns'] ?? []), 3) }}">
    @foreach ($block['columns'] ?? [] as $col)
        <article class="split-col" data-reveal data-reveal-delay="{{ $loop->index }}">
            @if (! empty($col['badge']))<span class="badge-soft mb-3 d-inline-block">{{ $col['badge'] }}</span>@endif
            @if (! empty($col['title']))<h3>{{ $H::inline($col['title']) }}</h3>@endif
            @if (! empty($col['subtitle']))<p class="split-sub">{{ $H::inline($col['subtitle']) }}</p>@endif
            @foreach ($col['paragraphs'] ?? [] as $p)<p>{{ $H::inline($p) }}</p>@endforeach
            @if (! empty($col['points']))
                @if ($block['ordered'] ?? false)
                    <ol>@foreach ($col['points'] as $pt)<li><span>{{ $H::inline($pt) }}</span></li>@endforeach</ol>
                @else
                    <ul>@foreach ($col['points'] as $pt)<li><span>{{ $H::inline($pt) }}</span></li>@endforeach</ul>
                @endif
            @endif
        </article>
    @endforeach
</div>
