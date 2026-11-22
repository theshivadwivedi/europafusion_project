@include('blocks.partials.head', ['block' => $block])
<div class="stats-plain">@include('blocks.partials.stat-cards', ['items' => $block['items'] ?? []])</div>
