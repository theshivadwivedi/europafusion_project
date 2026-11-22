@php($H = \App\Support\Html::class)
@include('blocks.partials.head', ['block' => $block])
@if (! empty($block['groups']))
    <div class="row g-4">
        @foreach ($block['groups'] as $group)
            <div class="col-lg-{{ count($block['groups']) >= 3 ? 4 : 6 }}" data-reveal>
                <div class="check-group">
                    @if (! empty($group['title']))<h3>{{ $group['title'] }}</h3>@endif
                    @if (! empty($group['intro']))<p class="small">{{ $H::inline($group['intro']) }}</p>@endif
                    <ul class="checklist-cols">
                        @foreach ($group['items'] ?? [] as $item)<li><i class="bi bi-check2-circle" aria-hidden="true"></i><span>{{ $H::inline($item) }}</span></li>@endforeach
                    </ul>
                </div>
            </div>
        @endforeach
    </div>
@else
    <ul @class(['checklist-cols', 'is-single' => ($block['columns'] ?? 2) == 1 || count($block['items'] ?? []) < 4])>
        @foreach ($block['items'] ?? [] as $item)<li><i class="bi bi-check2-circle" aria-hidden="true"></i><span>{{ $H::inline(is_array($item) ? ($item['title'] ?? '').(isset($item['desc']) ? ' — '.$item['desc'] : '') : $item) }}</span></li>@endforeach
    </ul>
@endif
