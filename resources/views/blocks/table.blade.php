@php($H = \App\Support\Html::class)
@include('blocks.partials.head', ['block' => $block])
@if (! empty($block['rows']))
    <div class="table-responsive-md">
        <table class="table table-ef">
            @if (! empty($block['columns']))
                <thead><tr>@foreach ($block['columns'] as $col)<th scope="col">{{ $col }}</th>@endforeach</tr></thead>
            @endif
            <tbody>
                @foreach ($block['rows'] as $row)
                    <tr>@foreach ($row as $j => $cell)<td data-label="{{ $block['columns'][$j] ?? '' }}">{{ $H::inline($cell) }}</td>@endforeach</tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
@if (! empty($block['chips']))
    <div class="chips-panel">
        @if (! empty($block['chips_title']))<h3>{{ $block['chips_title'] }}</h3>@endif
        <ul class="chip-list">@foreach ($block['chips'] as $chip)<li class="chip is-gold">{{ $chip }}</li>@endforeach</ul>
    </div>
@endif
