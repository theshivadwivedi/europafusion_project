<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\View\View;

class PageController extends Controller
{
    public function country(string $country): View
    {
        return $this->render(Page::findOrFail("study-in-{$country}", 'country'));
    }

    public function pathway(string $pathway): View
    {
        return $this->render(Page::findOrFail("germany/{$pathway}", 'pathway'));
    }

    public function legal(string $slug): View
    {
        return view('pages.legal', [
            'page' => Page::findOrFail($slug, 'legal'),
            'legalPages' => Page::legal(),
        ]);
    }

    private function render(Page $page): View
    {
        $related = Page::ofKind($page->kind)
            ->reject(fn (Page $p) => $p->slug === $page->slug)
            ->sortBy('sort_order')
            ->values();

        return view('pages.content', compact('page', 'related'));
    }
}
