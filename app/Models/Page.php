<?php

namespace App\Models;

use App\Support\Content;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * A content page.
 *  - country / pathway pages: JSON block documents in resources/content/pages/*.json
 *    (schema in docs/CONTENT_SCHEMA.md)
 *  - legal pages: resources/content/legal.json + resources/content/legal/{slug}.html
 */
class Page extends Record
{
    /** Country and pathway pages, in file-name order. */
    public static function all(): Collection
    {
        return Content::remember('pages', function () {
            $files = glob(Content::path('pages/*.json')) ?: [];
            sort($files);

            return collect($files)->values()->map(function (string $file, int $i) {
                $doc = Content::decode($file);

                return new static([
                    'slug' => $doc['slug'],
                    'kind' => $doc['kind'],
                    'destination' => Destination::find($doc['destination'] ?? null),
                    'title' => $doc['title'],
                    'nav_label' => $doc['nav_label'] ?? null,
                    'meta_description' => $doc['meta_description'] ?? null,
                    'sections' => $doc['sections'] ?? [],
                    'sort_order' => $i,
                    'updated_at' => Carbon::createFromTimestamp(filemtime($file)),
                ]);
            });
        });
    }

    /** The four legal pages, in footer order. */
    public static function legal(): Collection
    {
        return Content::remember('legal', fn () => collect(Content::json('legal.json'))
            ->values()
            ->map(fn (array $row, int $i) => new static([
                'slug' => $row['slug'],
                'kind' => 'legal',
                'title' => $row['title'],
                'nav_label' => $row['title'],
                'meta_description' => $row['meta_description'] ?? null,
                'body' => trim(Content::text("legal/{$row['slug']}.html")),
                'last_updated' => isset($row['last_updated']) ? Carbon::parse($row['last_updated']) : null,
                'updated_at' => isset($row['last_updated']) ? Carbon::parse($row['last_updated']) : null,
                'sort_order' => 100 + $i,
            ])));
    }

    public static function ofKind(string $kind): Collection
    {
        return ($kind === 'legal' ? static::legal() : static::all()->where('kind', $kind))->values();
    }

    public static function findOrFail(string $slug, string $kind): static
    {
        return static::ofKind($kind)->firstWhere('slug', $slug) ?? abort(404);
    }

    public function url(): string
    {
        return url($this->slug);
    }

    /** Sections that carry an anchor id and a title, used for the in-page navigation. */
    public function anchors(): array
    {
        return collect($this->sections ?? [])
            ->filter(fn ($s) => ! empty($s['id']) && ! empty($s['title']) && ! in_array($s['type'], ['hero', 'cta'], true))
            ->map(fn ($s) => ['id' => $s['id'], 'label' => $s['eyebrow'] ?? $s['title']])
            ->values()
            ->all();
    }
}
