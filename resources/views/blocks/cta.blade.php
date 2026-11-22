@php($H = \App\Support\Html::class)
<div class="cta-panel" data-reveal>
    <x-star-ring class="cta-art" />
    @if (! empty($block['eyebrow']))<p class="eyebrow">{{ $block['eyebrow'] }}</p>@endif
    <h2>{{ $block['title'] ?? 'Speak with a counsellor' }}</h2>
    @if (! empty($block['text']))<p class="lead-text">{{ $H::inline($block['text']) }}</p>@endif
    <div class="d-flex flex-wrap gap-2 mt-4">
        @foreach ($block['ctas'] ?? [['label' => 'Book Free Consultation', 'action' => 'counselling']] as $cta)
            <x-cta :cta="['label' => \App\Support\Labels::button($cta['label']), 'action' => $cta['action'] ?? 'counselling']" :country="$country" :class="$loop->first ? 'btn btn-gold btn-lg' : 'btn btn-ghost-light btn-lg'">@if ($loop->first) <i class="bi bi-arrow-right" aria-hidden="true"></i>@endif</x-cta>
        @endforeach
    </div>
    @if (! empty($block['note']))<p class="cta-note">{{ $H::inline($block['note']) }}</p>@endif
</div>
