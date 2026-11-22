@props(['code', 'name' => null])
<img src="{{ asset('images/flags/'.strtolower($code).'.svg') }}" alt="{{ $name ? 'Flag of '.$name : '' }}" width="30" height="20" loading="lazy" decoding="async" {{ $attributes }}>
