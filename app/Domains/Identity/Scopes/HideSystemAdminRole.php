<?php

namespace App\Domains\Identity\Scopes;

use App\Domains\Identity\Enums\RoleType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class HideSystemAdminRole implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        // Spatie roles use a unique 'name' field string pattern
        $builder->where('name', '!=', RoleType::SYSTEM_ADMIN);
    }
}
