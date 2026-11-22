<?php

namespace App\Auth;

use RuntimeException;

/**
 * Admin accounts for the /admin lead desk, kept in a JSON file
 * (default storage/app/private/admins.json). Passwords are stored as bcrypt hashes.
 * Manage accounts with `php artisan admin:create`.
 */
class AdminAccounts
{
    public function __construct(private readonly string $path) {}

    /** @return array<int, array> */
    public function all(): array
    {
        if (! is_file($this->path)) {
            return [];
        }

        $data = json_decode((string) file_get_contents($this->path), true);

        return is_array($data['accounts'] ?? null) ? array_values($data['accounts']) : [];
    }

    public function find(mixed $id): ?array
    {
        foreach ($this->all() as $account) {
            if ((string) $account['id'] === (string) $id) {
                return $account;
            }
        }

        return null;
    }

    public function findByEmail(string $email): ?array
    {
        $email = strtolower(trim($email));

        foreach ($this->all() as $account) {
            if (hash_equals(strtolower($account['email']), $email)) {
                return $account;
            }
        }

        return null;
    }

    /**
     * Insert or update the account with this email address.
     *
     * @return array{0: array, 1: bool} the saved account and whether it was newly created
     */
    public function save(array $attributes): array
    {
        return $this->locked(function (array $accounts) use ($attributes) {
            $email = strtolower(trim($attributes['email']));
            $created = true;

            foreach ($accounts as $i => $account) {
                if (strtolower($account['email']) === $email) {
                    $accounts[$i] = $saved = array_merge($account, $attributes, ['email' => $email]);
                    $created = false;
                }
            }

            if ($created) {
                $nextId = $accounts ? max(array_column($accounts, 'id')) + 1 : 1;
                $accounts[] = $saved = array_merge(['id' => $nextId, 'remember_token' => null], $attributes, ['email' => $email]);
            }

            return [$accounts, [$saved, $created]];
        });
    }

    /** Update fields of an existing account (e.g. the remember-me token). */
    public function update(mixed $id, array $attributes): void
    {
        $this->locked(function (array $accounts) use ($id, $attributes) {
            foreach ($accounts as $i => $account) {
                if ((string) $account['id'] === (string) $id) {
                    $accounts[$i] = array_merge($account, $attributes);
                }
            }

            return [$accounts, null];
        });
    }

    /** Read-modify-write under an exclusive lock. */
    private function locked(callable $change): mixed
    {
        $dir = dirname($this->path);

        if (! is_dir($dir) && ! @mkdir($dir, 0775, true) && ! is_dir($dir)) {
            throw new RuntimeException("Cannot create {$dir} – check folder permissions.");
        }

        $handle = fopen($this->path, 'c+');

        if ($handle === false || ! flock($handle, LOCK_EX)) {
            throw new RuntimeException("Cannot open {$this->path} for writing – check folder permissions.");
        }

        try {
            $data = json_decode((string) stream_get_contents($handle), true);
            $accounts = is_array($data['accounts'] ?? null) ? array_values($data['accounts']) : [];

            [$accounts, $result] = $change($accounts);

            ftruncate($handle, 0);
            rewind($handle);
            fwrite($handle, json_encode(['accounts' => array_values($accounts)], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR)."\n");
            fflush($handle);
        } finally {
            flock($handle, LOCK_UN);
            fclose($handle);
        }

        @chmod($this->path, 0660);

        return $result;
    }
}
