{{-- Brand emblem: classical portico inside the ring of twelve European stars --}}
<svg {{ $attributes->merge(['class' => 'brand-emblem']) }} viewBox="0 0 48 48" aria-hidden="true" focusable="false">
    <circle cx="24" cy="24" r="24" fill="#15294d"/>
    <g fill="#e2a72e">
        @foreach (range(0, 11) as $i)
            @php($a = deg2rad($i * 30)) 
            <circle cx="{{ round(24 + 17.2 * sin($a), 2) }}" cy="{{ round(24 - 17.2 * cos($a), 2) }}" r="1.55"/>
        @endforeach
    </g>
    <path d="M15.5 19.6 24 14.4l8.5 5.2z" fill="#fff"/>
    <rect x="16.2" y="20.6" width="15.6" height="1.6" rx=".4" fill="#fff"/>
    <g fill="#fff"><rect x="17.4" y="23" width="2" height="7.2" rx=".5"/><rect x="21.1" y="23" width="2" height="7.2" rx=".5"/><rect x="24.9" y="23" width="2" height="7.2" rx=".5"/><rect x="28.6" y="23" width="2" height="7.2" rx=".5"/></g>
    <rect x="15.4" y="31" width="17.2" height="2" rx=".5" fill="#fff"/>
</svg>
