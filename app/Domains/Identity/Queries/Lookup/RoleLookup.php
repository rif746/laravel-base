<?php

namespace App\Domains\Identity\Queries\Lookup;

use App\Domains\Identity\Models\Role;
use Illuminate\Database\Eloquent\Collection;

class RoleLookup
{
    public function fetch(?string $search): Collection
    {
        return Role::query()
            ->select('name')
            ->where('name', 'like', "%{$search}%")
            ->get();
    }
}
