<?php

namespace App\Support;

use Illuminate\Support\Facades\Log;
use RuntimeException;

/**
 * File storage for form submissions: one pretty-printed JSON file per record
 * (e.g. storage/app/private/leads/enquiries/000012.json).
 *
 * - IDs come from a ".sequence" counter guarded by an exclusive file lock, so two
 *   simultaneous submissions never get the same number and deleted IDs are never reused.
 * - Files are written to a temporary name and renamed into place, so a reader
 *   never sees a half-written record.
 */
class LeadStore
{
    public function __construct(private readonly string $directory) {}

    public function insert(array $data): array
    {
        $this->ensureDirectory();

        $lock = fopen($this->directory.'/.sequence', 'c+');

        if ($lock === false || ! flock($lock, LOCK_EX)) {
            throw new RuntimeException("Cannot lock the lead sequence in {$this->directory}");
        }

        try {
            $last = (int) trim((string) stream_get_contents($lock));
            $id = max($last, $this->highestId()) + 1;

            $record = ['id' => $id] + $data;
            $this->write($id, $record);

            ftruncate($lock, 0);
            rewind($lock);
            fwrite($lock, (string) $id);
            fflush($lock);
        } finally {
            flock($lock, LOCK_UN);
            fclose($lock);
        }

        return $record;
    }

    public function find(int $id): ?array
    {
        return $this->read($this->file($id));
    }

    /** @return array<int, array> newest first */
    public function all(): array
    {
        $records = [];

        foreach (glob($this->directory.'/[0-9]*.json') ?: [] as $file) {
            if (($record = $this->read($file)) !== null) {
                $records[] = $record;
            }
        }

        usort($records, fn (array $a, array $b) => $b['id'] <=> $a['id']);

        return $records;
    }

    public function update(int $id, array $record): void
    {
        if (! is_file($this->file($id))) {
            throw new RuntimeException("Lead #{$id} no longer exists.");
        }

        $this->write($id, ['id' => $id] + $record);
    }

    public function delete(int $id): bool
    {
        $file = $this->file($id);

        return is_file($file) && unlink($file);
    }

    private function write(int $id, array $record): void
    {
        $json = json_encode($record, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);
        $tmp = $this->directory.'/.tmp-'.bin2hex(random_bytes(6));

        if (file_put_contents($tmp, $json."\n", LOCK_EX) === false || ! rename($tmp, $this->file($id))) {
            @unlink($tmp);

            throw new RuntimeException("Cannot write lead #{$id} to {$this->directory} – check folder permissions.");
        }
    }

    private function read(string $file): ?array
    {
        if (! is_file($file)) {
            return null;
        }

        $data = json_decode((string) file_get_contents($file), true);

        if (! is_array($data) || ! isset($data['id'])) {
            Log::warning('Skipping unreadable lead file', ['file' => $file]);

            return null;
        }

        return $data;
    }

    private function highestId(): int
    {
        $ids = array_map(fn ($f) => (int) basename($f, '.json'), glob($this->directory.'/[0-9]*.json') ?: []);

        return $ids ? max($ids) : 0;
    }

    private function file(int $id): string
    {
        return $this->directory.'/'.str_pad((string) $id, 6, '0', STR_PAD_LEFT).'.json';
    }

    private function ensureDirectory(): void
    {
        if (! is_dir($this->directory) && ! @mkdir($this->directory, 0775, true) && ! is_dir($this->directory)) {
            throw new RuntimeException("Cannot create {$this->directory} – check folder permissions.");
        }
    }
}
