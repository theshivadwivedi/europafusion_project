@props(['light' => false])
<a href="{{ route('home') }}" {{ $attributes->class(['brand', 'is-light' => $light]) }} aria-label="{{ config('site.name') }} – home">
    <x-emblem />
    <span class="brand-text">
        <span class="brand-name">EUROPA <b>FUSION</b></span>
        <span class="brand-sub">Study Abroad · Ausbildung · Visa</span>
    </span>
</a>
