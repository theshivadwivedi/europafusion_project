<?php

namespace App\Models;

use App\Support\Content;
use Illuminate\Support\Collection;

/** A language or standardized test, read from resources/content/exam-prep.json. */
class Exam extends Record
{
    public static function all(): Collection
    {
        return Content::remember('exams', fn () => collect(Content::json('exam-prep.json')['exams'] ?? [])
            ->map(fn (array $row) => new static($row + ['tags' => [], 'sort_order' => 0]))
            ->sortBy('sort_order')
            ->values());
    }
}
