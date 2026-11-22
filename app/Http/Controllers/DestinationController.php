<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Page;
use Illuminate\View\View;

class DestinationController extends Controller
{
    public function index(): View
    {
        return view('pages.destinations', [
            'destinations' => Destination::all(),
        ]);
    }

    /** Germany hub: the reference site has no single Germany page, so this links its four pathway pages. */
    public function germany(): View
    {
        $germany = Destination::findOrFail('germany');

        return view('pages.germany', [
            'destination' => $germany,
            'pathways' => Page::ofKind('pathway')
                ->filter(fn (Page $p) => $p->destination?->slug === $germany->slug)
                ->sortBy('sort_order')
                ->values(),
        ]);
    }
}
