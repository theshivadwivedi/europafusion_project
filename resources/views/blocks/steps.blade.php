@php($H = \App\Support\Html::class)
@include('blocks.partials.head', ['block' => $block])
<ol class="steps-list">
    @php($phase = null)
    @foreach ($block['items'] ?? [] as $item)
        @if (! empty($item['phase']) && $item['phase'] !== $phase)
            @php($phase = $item['phase'])
            <li class="phase-head" aria-hidden="true">{{ $phase }}</li>
        @endif
        <li class="step" data-reveal data-reveal-delay="{{ $loop->index % 2 }}">
            <div>
                @if (! empty($item['tag']))<span class="step-tag">{{ $item['tag'] }}</span>@endif
                <h3>{{ $H::inline($item['title']) }}</h3>
                @if (! empty($item['desc']))<p>{{ $H::inline($item['desc']) }}</p>@endif
            </div>
        </li>
    @endforeach
</ol>
