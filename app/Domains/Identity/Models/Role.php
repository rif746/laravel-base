<?php

namespace App\Domains\Identity\Models;

use App\Domains\Identity\Policies\RolePolicy;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Permission\Contracts\Role as RoleContract;
use Spatie\Permission\Models\Role as SpatieRole;

#[Fillable(['name', 'guard_name'])]
#[UsePolicy(RolePolicy::class)]
class Role extends SpatieRole implements RoleContract, Auditable
{
    use \OwenIt\Auditing\Auditable;
}
