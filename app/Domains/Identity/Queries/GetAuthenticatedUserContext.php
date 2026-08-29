<?php

namespace App\Domains\Identity\Queries;

use App\Domains\Identity\Models\User;
use Illuminate\Support\Facades\Auth;

class GetAuthenticatedUserContext
{
    private static ?array $cache = null;

    public static function fetch(): ?User
    {
        if (self::$cache !== null) {
            return self::$cache['user'];
        }

        $user = Auth::user();
        $user?->loadMissing(['avatar', 'profile']);
        self::$cache['user'] = $user;

        return self::$cache['user'];
    }

    public static function refresh(): void
    {
        self::$cache = null;
    }
}
