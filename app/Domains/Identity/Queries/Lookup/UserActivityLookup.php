<?php

namespace App\Domains\Identity\Queries\Lookup;

use App\Domains\Identity\Models\UserActivity;
use Illuminate\Support\Collection;

class UserActivityLookup
{
    public static function fetch(null|int|string $userId): Collection
    {
        if(!$userId) {
            return new Collection();
        }

        return UserActivity::where('user_id', $userId)->latest('event_time')->limit(5)->get();
    }
}
