<?php

namespace App\Models;

use App\Support\Content;
use Illuminate\Support\Collection;

/** A partner / featured university, read from resources/content/universities.json. */
class University extends Record
{
    public static function all(): Collection
    {
        return Content::remember('universities', fn () => collect(Content::json('universities.json'))
            ->values()
            ->map(fn (array $row, int $i) => new static($row + ['sort_order' => $i + 1, 'show_on_home' => true])));
    }

    public static function onHome(): Collection
    {
        return static::all()->where('show_on_home', true)->values();
    }
}
