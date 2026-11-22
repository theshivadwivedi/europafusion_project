<?php

namespace App\Auth;

use Illuminate\Auth\GenericUser;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Hashing\Hasher;

/**
 * Laravel user provider backed by AdminAccounts (a JSON file instead of a users table).
 * Registered as the "file" driver in AppServiceProvider and used by config/auth.php.
 */
class FileUserProvider implements UserProvider
{
    public function __construct(
        private readonly AdminAccounts $accounts,
        private readonly Hasher $hasher,
    ) {}

    public function retrieveById($identifier): ?Authenticatable
    {
        return $this->user($this->accounts->find($identifier));
    }

    public function retrieveByToken($identifier, #[\SensitiveParameter] $token): ?Authenticatable
    {
        $user = $this->retrieveById($identifier);

        return $user && $user->getRememberToken() && hash_equals((string) $user->getRememberToken(), (string) $token)
            ? $user
            : null;
    }

    public function updateRememberToken(Authenticatable $user, #[\SensitiveParameter] $token): void
    {
        $this->accounts->update($user->getAuthIdentifier(), ['remember_token' => $token]);
        $user->setRememberToken($token);
    }

    public function retrieveByCredentials(#[\SensitiveParameter] array $credentials): ?Authenticatable
    {
        $email = $credentials['email'] ?? null;

        return is_string($email) && $email !== '' ? $this->user($this->accounts->findByEmail($email)) : null;
    }

    public function validateCredentials(Authenticatable $user, #[\SensitiveParameter] array $credentials): bool
    {
        $plain = $credentials['password'] ?? null;
        $hash = $user->getAuthPassword();

        return is_string($plain) && is_string($hash) && $hash !== '' && $this->hasher->check($plain, $hash);
    }

    public function rehashPasswordIfRequired(Authenticatable $user, #[\SensitiveParameter] array $credentials, bool $force = false): void
    {
        if (! $this->hasher->needsRehash($user->getAuthPassword()) && ! $force) {
            return;
        }

        $this->accounts->update($user->getAuthIdentifier(), ['password' => $this->hasher->make($credentials['password'])]);
    }

    private function user(?array $account): ?GenericUser
    {
        return $account === null ? null : new GenericUser($account + ['remember_token' => null]);
    }
}
