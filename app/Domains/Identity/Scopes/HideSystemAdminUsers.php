<?php

namespace App\Domains\Identity\Scopes;

use App\Domains\Identity\Enums\RoleType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class HideSystemAdminUsers implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        // 💡 Checks Spatie's relation structure to exclude users possessing the hidden role name
        $builder->whereHas('roles', function (Builder $query) {
            // We use withoutGlobalScope here to ensure the nested query can see
            // the system-admin role record long enough to filter it out!
            $query->withoutGlobalScope(HideSystemAdminRole::class)
                ->where('name', '!=', RoleType::SYSTEM_ADMIN);
        });
    }
}
