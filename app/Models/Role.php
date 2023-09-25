<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use ReflectionClass;

class Role extends \Spatie\Permission\Models\Role
{
    use HasFactory;

    const ADMIN = "administrator";
    const GUEST = "guest";

    public static function getRoles()
    {
        $constant = new ReflectionClass(__CLASS__);
        return $constant->getConstants();
    }
}
