<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $urls = collect(['/', '/destinations', '/study-in-germany', '/services', '/exam-prep', '/contact', '/b2b'])
            ->map(fn ($path) => ['loc' => url($path), 'lastmod' => null])
            ->merge(Page::all()->concat(Page::legal())->map(fn (Page $p) => [
                'loc' => url($p->slug),
                'lastmod' => $p->updated_at?->toAtomString(),
            ]));

        return response()
            ->view('sitemap', ['urls' => $urls])
            ->header('Content-Type', 'application/xml');
    }
}
