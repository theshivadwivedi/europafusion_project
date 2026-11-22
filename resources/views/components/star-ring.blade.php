{{-- Decorative ring of twelve five-pointed stars (the European flag motif) --}}
@props(['color' => '#e2a72e'])
<svg {{ $attributes }} viewBox="0 0 200 200" aria-hidden="true" focusable="false">
    <g fill="{{ $color }}">
        @foreach (range(0, 11) as $i)
            @php($a = deg2rad($i * 30))
            <polygon points="0,-9 2.23,-3.07 8.56,-2.78 3.61,1.17 5.29,7.28 0,3.8 -5.29,7.28 -3.61,1.17 -8.56,-2.78 -2.23,-3.07" transform="translate({{ round(100 + 78 * sin($a), 1) }} {{ round(100 - 78 * cos($a), 1) }})"/>
        @endforeach
    </g>
</svg>
