<?php

namespace App\Models\Identity;

use App\Policies\Identity\RolePolicy;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Spatie\Permission\Models\Role as SpatieRole;

#[Fillable(['name', 'guard_name'])]
#[UsePolicy(RolePolicy::class)]
class Role extends SpatieRole
{
    //
}
