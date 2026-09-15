<?php

namespace App\Domains\Identity\Models;

use App\Attributes\Model\Audit;
use App\Attributes\Model\Lookupable;
use App\Domains\Identity\Policies\RolePolicy;
use App\Domains\System\Concerns\Model\HasPublicUlid;
use App\Domains\System\Concerns\Model\IsLookupable;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Spatie\Permission\Contracts\Role as RoleContract;
use Spatie\Permission\Models\Role as SpatieRole;

#[Fillable(['name', 'guard_name'])]
#[Audit(
    label: 'role',
    events: ['create', 'update', 'delete']
)]
#[UsePolicy(RolePolicy::class)]
#[Lookupable(id: 'name', text: 'name')]
class Role extends SpatieRole implements RoleContract
{
    use IsLookupable;
    use HasPublicUlid;
}
