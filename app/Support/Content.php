<?php

namespace App\Support;

use Closure;
use RuntimeException;

/**
 * Reads the website content (resources/content/*.json and *.html).
 * Files are decoded once per PHP process and kept in memory.
 */
class Content
{
    /** @var array<string, mixed> */
    private static array $memo = [];

    public static function path(string $file = ''): string
    {
        return resource_path('content'.($file === '' ? '' : '/'.ltrim($file, '/')));
    }

    /** Decoded JSON file, relative to resources/content. */
    public static function json(string $file): array
    {
        return self::remember("json:{$file}", fn () => self::decode(self::path($file)));
    }

    /** Raw text file (e.g. legal HTML), relative to resources/content. */
    public static function text(string $file): string
    {
        $path = self::path($file);

        if (! is_file($path)) {
            throw new RuntimeException("Content file not found: {$path}");
        }

        return (string) file_get_contents($path);
    }

    public static function decode(string $path): array
    {
        if (! is_file($path)) {
            throw new RuntimeException("Content file not found: {$path}");
        }

        try {
            $data = json_decode((string) file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw new RuntimeException("Invalid JSON in {$path}: {$e->getMessage()}", previous: $e);
        }

        if (! is_array($data)) {
            throw new RuntimeException("Invalid JSON in {$path}: expected an object or list");
        }

        return $data;
    }

    public static function remember(string $key, Closure $build): mixed
    {
        if (! array_key_exists($key, self::$memo)) {
            self::$memo[$key] = $build();
        }

        return self::$memo[$key];
    }

    public static function flush(): void
    {
        self::$memo = [];
    }
}
