@extends('layouts.app', ['hideFooterCta' => true])

@section('title', $page->title)
@section('description', $page->meta_description)

@section('content')
    <section class="page-hero is-light">
        <div class="container">
            <ol class="breadcrumb-ef" aria-label="Breadcrumb"><li><a href="{{ route('home') }}">Home</a></li><li aria-current="page">{{ $page->title }}</li></ol>
            <p class="eyebrow">Legal</p>
            <h1>{{ $page->title }}</h1>
            <p class="text-muted-ef mb-0">Last updated: {{ $page->last_updated?->format('F Y') }} · {{ config('site.legal_name') }}</p>
        </div>
    </section>
    <section class="section">
        <div class="container">
            <div class="legal-layout">
                <nav class="legal-nav" aria-label="Legal pages">
                    @foreach ($legalPages as $lp)
                        <a href="{{ url($lp->slug) }}" @class(['active' => $lp->slug === $page->slug]) @if ($lp->slug === $page->slug) aria-current="page" @endif>{{ $lp->title }}</a>
                    @endforeach
                </nav>
                <article class="prose-ef">{{ \App\Support\Html::rich($page->body) }}</article>
            </div>
        </div>
    </section>
@endsection
