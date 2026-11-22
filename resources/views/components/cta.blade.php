{{-- Renders a CTA from block data: action "counselling" | "evaluation" opens the enquiry modal, anything else is a link. --}}
@props(['cta', 'country' => null])
@php
    $action = $cta['action'] ?? 'counselling';
    $isModal = in_array($action, ['counselling', 'evaluation'], true);
    $href = $isModal ? route('contact', ['type' => $action]).'#enquiry' : url($action);
@endphp
<a href="{{ $href }}" {{ $attributes }} @if ($isModal) data-enquiry="{{ $action }}" @if ($country) data-country="{{ $country }}" @endif @endif>
    <span>{{ \App\Support\Labels::button($cta['label']) }}</span>{{ $slot }}
</a>
