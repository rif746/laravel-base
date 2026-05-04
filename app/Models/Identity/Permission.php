<?php

namespace App\Models\Identity;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Spatie\Permission\Models\Permission as SpatiePermission;

#[Fillable(['name', 'description', 'group', 'guard_name'])]
class Permission extends SpatiePermission
{
    //
}
