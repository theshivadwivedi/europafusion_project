@include('blocks.partials.head', ['block' => $block])
<div class="prose-ef">{{ \App\Support\Html::rich($block['html'] ?? '') }}</div>
