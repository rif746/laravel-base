<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends \Spatie\Permission\Models\Role
{
    use HasFactory;

    const ADMIN = 'Administrator';

    const GUEST = 'Guest';

    public static function getDefaultRoles()
    {
        return [
            self::ADMIN,
            self::GUEST,
        ];
    }

    public function scopeSearch($query, $search)
    {
        return $query->orWhere('name', 'like', "%{$search}%");
    }
}
