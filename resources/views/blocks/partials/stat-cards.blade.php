<div class="row g-3">
    @foreach ($items as $s)
        <div class="col-6 col-lg-{{ count($items) >= 4 ? 3 : 12 / max(count($items), 1) }}">
            <div class="stat-card">
                <div class="value">{{ $s['value'] }}</div>
                <div class="label">{{ $s['label'] }}</div>
                @if (! empty($s['sub']))<div class="sub">{{ $s['sub'] }}</div>@endif
            </div>
        </div>
    @endforeach
</div>
