@php($H = \App\Support\Html::class)
@include('blocks.partials.head', ['block' => $block])
<ol class="timeline-track">
    @foreach ($block['items'] ?? [] as $item)
        <li class="tl-item" data-reveal data-reveal-delay="{{ $loop->index % 3 }}">
            @if (! empty($item['label']))<span class="tl-label">{{ $item['label'] }}</span>@endif
            @if (! empty($item['title']))<h3>{{ $H::inline($item['title']) }}</h3>@endif
            @if (! empty($item['desc']))<p>{{ $H::inline($item['desc']) }}</p>@endif
        </li>
    @endforeach
</ol>
