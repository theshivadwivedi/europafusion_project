<?php

namespace App\Models;

use App\Support\Content;
use Illuminate\Support\Collection;

/** A service, read from resources/content/services.json. */
class Service extends Record
{
    public const GROUPS = [
        'academic' => 'Academic Guidance',
        'beyond' => 'Beyond Admission',
    ];

    public static function all(): Collection
    {
        return Content::remember('services', fn () => collect(Content::json('services.json'))
            ->map(fn (array $row) => new static($row + ['body' => [], 'sort_order' => 0]))
            ->sortBy('sort_order')
            ->values());
    }

    /** Services keyed by group (academic / beyond). */
    public static function grouped(): Collection
    {
        return static::all()->groupBy('group');
    }

    public function url(): string
    {
        return route('services').'#'.$this->slug;
    }
}
