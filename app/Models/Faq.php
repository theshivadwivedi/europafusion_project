<?php

namespace App\Models;

use App\Support\Content;
use Illuminate\Support\Collection;

/** A frequently asked question, read from resources/content/exam-prep.json. */
class Faq extends Record
{
    public static function group(string $group): Collection
    {
        return Content::remember("faqs:{$group}", fn () => collect(Content::json('exam-prep.json')['faqs'] ?? [])
            ->values()
            ->map(fn (array $row, int $i) => new static($row + ['sort_order' => $i + 1]))
            ->where('group', $group)
            ->values());
    }
}
