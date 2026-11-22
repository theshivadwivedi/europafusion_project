@php($H = \App\Support\Html::class)
@php($accId = 'faq-'.\Illuminate\Support\Str::random(6))
<div class="faq-grid">
    <div class="faq-head">
        @include('blocks.partials.head', ['block' => $block])
        <div class="ef-card mt-2" style="height:auto">
            <h3 class="mb-2">Still have a question?</h3>
            <p class="mb-3">Ask a counsellor — we reply within one working day.</p>
            <x-cta :cta="['label' => 'Ask a counsellor', 'action' => 'counselling']" :country="$country" class="btn btn-primary btn-sm"> <i class="bi bi-arrow-right" aria-hidden="true"></i></x-cta>
        </div>
    </div>
    <div class="accordion faq-acc" id="{{ $accId }}">
        @foreach ($block['items'] ?? [] as $i => $item)
            <div class="accordion-item">
                <h3 class="accordion-header m-0">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $accId }}-{{ $i }}" aria-expanded="false" aria-controls="{{ $accId }}-{{ $i }}">
                        <span class="q-num">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span><span>{{ $H::inline(preg_replace('/^(Q\.\s*|\d+\.\s+)/', '', $item['q'])) }}</span>
                    </button>
                </h3>
                <div id="{{ $accId }}-{{ $i }}" class="accordion-collapse collapse" data-bs-parent="#{{ $accId }}">
                    <div class="accordion-body">
                        @if (str_contains($item['a'], '<p>') || str_contains($item['a'], '<ul>'))
                            {{ $H::rich($item['a']) }}
                        @else
                            <p>{{ $H::inline($item['a']) }}</p>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
