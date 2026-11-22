<?php

namespace App\Models;

use ArrayAccess;
use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;

/**
 * A database-free model: a bag of attributes readable as properties ($page->title)
 * or array keys ($page['title']), so Blade views and collection helpers
 * (where, pluck, firstWhere, sortBy…) work exactly as they do with Eloquent.
 *
 * Website content is read from JSON files in resources/content and leads are
 * stored as JSON files in storage/app/private/leads – no database is used.
 */
abstract class Record implements Arrayable, ArrayAccess, JsonSerializable
{
    public function __construct(protected array $attributes = []) {}

    public function __get(string $key): mixed
    {
        return $this->attributes[$key] ?? null;
    }

    public function __set(string $key, mixed $value): void
    {
        $this->attributes[$key] = $value;
    }

    public function __isset(string $key): bool
    {
        return isset($this->attributes[$key]);
    }

    public function __unset(string $key): void
    {
        unset($this->attributes[$key]);
    }

    public function offsetExists(mixed $offset): bool
    {
        return array_key_exists($offset, $this->attributes);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->attributes[$offset] ?? null;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->attributes[$offset] = $value;
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->attributes[$offset]);
    }

    public function toArray(): array
    {
        return $this->attributes;
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
