<?php

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

class Pagination
{
    /** Paginate an in-memory collection, keeping the current query string in the links. */
    public static function of(Collection $items, Request $request, int $perPage = 15): LengthAwarePaginator
    {
        $page = max(1, Paginator::resolveCurrentPage());

        return new LengthAwarePaginator(
            $items->forPage($page, $perPage)->values(),
            $items->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()],
        );
    }
}
