@if (! empty($block['eyebrow']) || ! empty($block['title']) || ! empty($block['intro']))
    <div @class(['section-head', 'text-center' => $center ?? false])>
        @if (! empty($block['eyebrow']))<p @class(['eyebrow', 'is-centered' => $center ?? false])>{{ $block['eyebrow'] }}</p>@endif
        @if (! empty($block['title']))<h2>{{ $block['title'] }}</h2>@endif
        @if (! empty($block['intro']))<p class="lead-text">{{ \App\Support\Html::inline($block['intro']) }}</p>@endif
    </div>
@endif
