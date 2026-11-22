<?php

namespace App\Models;

use App\Models\Concerns\HasLeadStatus;
use App\Support\LeadStore;
use Illuminate\Contracts\Routing\UrlRoutable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * A form submission stored as a JSON file (see App\Support\LeadStore).
 * Implements UrlRoutable so admin routes like /admin/enquiries/{enquiry}
 * resolve the record automatically and return 404 when it does not exist.
 */
abstract class Lead extends Record implements UrlRoutable
{
    use HasLeadStatus;

    private const DATES = ['created_at', 'updated_at'];

    /** Folder below config('site.leads_path'). */
    abstract protected static function folder(): string;

    /** Fields searched by the admin "Search" box. */
    abstract protected static function searchable(): array;

    public function __construct(array $attributes = [])
    {
        foreach (self::DATES as $key) {
            if (isset($attributes[$key]) && ! $attributes[$key] instanceof Carbon) {
                $attributes[$key] = Carbon::parse($attributes[$key])->setTimezone(config('app.timezone'));
            }
        }

        parent::__construct($attributes);
    }

    protected static function store(): LeadStore
    {
        return new LeadStore(rtrim((string) config('site.leads_path'), '/\\').DIRECTORY_SEPARATOR.static::folder());
    }

    public static function create(array $attributes): static
    {
        $now = Carbon::now();

        return new static(static::store()->insert(static::serialise(array_merge(
            ['status' => 'new', 'admin_notes' => null],
            $attributes,
            ['created_at' => $now, 'updated_at' => $now],
        ))));
    }

    /** All records, newest first. */
    public static function all(): Collection
    {
        return collect(static::store()->all())->map(fn (array $row) => new static($row));
    }

    public static function find(int|string|null $id): ?static
    {
        if ($id === null || ! ctype_digit((string) $id)) {
            return null;
        }

        $row = static::store()->find((int) $id);

        return $row === null ? null : new static($row);
    }

    /** Records matching the admin filters (status, free-text search). */
    public static function filtered(?string $status = null, ?string $term = null): Collection
    {
        $term = Str::lower(trim((string) $term));

        return static::all()
            ->when($status && array_key_exists($status, self::STATUSES), fn ($c) => $c->where('status', $status))
            ->when($term !== '', fn ($c) => $c->filter(fn (self $lead) => collect(static::searchable())
                ->contains(fn ($field) => Str::contains(Str::lower((string) $lead->{$field}), $term))))
            ->values();
    }

    public static function countWithStatus(string $status): int
    {
        return static::all()->where('status', $status)->count();
    }

    public function update(array $attributes): bool
    {
        $this->attributes = array_merge($this->attributes, $attributes, ['updated_at' => Carbon::now()]);
        static::store()->update((int) $this->id, static::serialise($this->attributes));

        return true;
    }

    public function delete(): bool
    {
        return static::store()->delete((int) $this->id);
    }

    public function fresh(): ?static
    {
        return static::find($this->id);
    }

    private static function serialise(array $attributes): array
    {
        foreach (self::DATES as $key) {
            if (($attributes[$key] ?? null) instanceof Carbon) {
                $attributes[$key] = $attributes[$key]->toIso8601String();
            }
        }

        unset($attributes['id']);

        return $attributes;
    }

    public function getRouteKey(): mixed
    {
        return $this->id;
    }

    public function getRouteKeyName(): string
    {
        return 'id';
    }

    public function resolveRouteBinding($value, $field = null): ?static
    {
        return static::find($value);
    }

    public function resolveChildRouteBinding($childType, $value, $field): mixed
    {
        return null;
    }
}
