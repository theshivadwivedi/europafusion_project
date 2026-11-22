@props(['eyebrow' => null, 'title' => null, 'intro' => null, 'center' => false, 'level' => 'h2'])
<div {{ $attributes->class(['section-head', 'text-center' => $center]) }}>
    @if ($eyebrow)<p class="eyebrow {{ $center ? 'is-centered' : '' }}">{{ $eyebrow }}</p>@endif
    @if ($title)<{{ $level }}>{{ $title }}</{{ $level }}>@endif
    @if ($intro)<p class="lead-text">{{ $intro }}</p>@endif
    {{ $slot }}
</div>
