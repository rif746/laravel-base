<?php

namespace App\Domains\Identity\Queries;

use App\Domains\Identity\Models\User;
use Illuminate\Support\Facades\Auth;

class GetAuthenticatedUserContext
{
    private ?array $cache = null;

    public function fetch(): ?User
    {
        if ($this->cache !== null) {
            return $this->cache['user'];
        }

        $user = Auth::user();
        $user?->loadMissing(['avatar', 'profile']);
        $this->cache['user'] = $user;

        return $this->cache['user'];
    }

    public function refresh(): void
    {
        $this->cache = null;
    }
}
