<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Permission extends \Spatie\Permission\Models\Permission
{
    use HasFactory;

    public static function getDefaultPermissions()
    {
        return [
            'user' => [
                'index' => [Role::ADMIN],
                'save' => [Role::ADMIN],
            ]
        ];
    }
}
