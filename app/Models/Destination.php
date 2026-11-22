<?php

namespace App\Models;

use App\Support\Content;
use Illuminate\Support\Collection;

/** A study destination, read from resources/content/destinations.json. */
class Destination extends Record
{
    /** Flag stripe colours, used for the accent band on country heroes. */
    public const FLAG_COLORS = [
        'de' => ['#000000', '#DD0000', '#FFCE00'],
        'at' => ['#C8102E', '#FFFFFF', '#C8102E'],
        'it' => ['#009246', '#FFFFFF', '#CE2B37'],
        'fi' => ['#FFFFFF', '#002F6C', '#FFFFFF'],
        'es' => ['#AA151B', '#F1BF00', '#AA151B'],
        'nl' => ['#AE1C28', '#FFFFFF', '#21468B'],
        'pl' => ['#FFFFFF', '#DC143C'],
        'lv' => ['#9E3039', '#FFFFFF', '#9E3039'],
        'lt' => ['#FDB913', '#006A44', '#C1272D'],
    ];

    /** Active destinations in display order. */
    public static function all(): Collection
    {
        return Content::remember('destinations', fn () => collect(Content::json('destinations.json'))
            ->map(fn (array $row) => new static($row + [
                'is_primary' => false,
                'is_active' => true,
                'sort_order' => 0,
                'popular_study_areas' => [],
                'highlights' => [],
                'top_universities' => [],
            ]))
            ->filter(fn (self $d) => (bool) $d->is_active)
            ->sortBy('sort_order')
            ->values());
    }

    public static function find(?string $slug): ?static
    {
        return $slug === null ? null : static::all()->firstWhere('slug', $slug);
    }

    public static function findOrFail(string $slug): static
    {
        return static::find($slug) ?? abort(404);
    }

    /** Public URL of the destination's landing page (Germany has a hub page for its pathways). */
    public function url(): string
    {
        return url('study-in-'.$this->slug);
    }

    public function flagColors(): array
    {
        return self::FLAG_COLORS[$this->code] ?? ['#15294d'];
    }

    /** Short work-rights text for cards (text before the first ";"). */
    public function shortWorkRights(): string
    {
        return trim(explode(';', (string) $this->work_rights)[0]);
    }

    /** Short living-cost text for cards (text before the first "("). */
    public function shortLivingCost(): string
    {
        return trim(explode('(', (string) $this->living_cost)[0]);
    }

    /** Country names accepted by the enquiry forms, e.g. "Germany". */
    public static function formOptions(): array
    {
        return static::all()->pluck('name')->all();
    }
}
