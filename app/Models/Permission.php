<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Permission extends \Spatie\Permission\Models\Permission
{
    use HasFactory;

    public static function getDefaultPermissions()
    {
        return [
            "dashboard" => [
                "index" => [
                    "description" => "Dapat mengakses dashboard",
                    "role" => [Role::ADMIN],
                ],
            ],
            "user" => [
                "index" => [
                    "description" => "Dapat mengakses data user",
                    "role" => [Role::ADMIN],
                ],
                "create" => [
                    "description" => "Dapat mengakses data role",
                    "role" => [Role::ADMIN],
                ],
                "edit" => [
                    "description" => "Dapat mengakses data role",
                    "role" => [Role::ADMIN],
                ],
                "delete" => [
                    "description" => "Dapat mengakses data role",
                    "role" => [Role::ADMIN],
                ],
            ],
            "role" => [
                "index" => [
                    "description" => "Dapat mengakses data role",
                    "role" => [Role::ADMIN],
                ],
                "create" => [
                    "description" => "Dapat mengakses data role",
                    "role" => [Role::ADMIN],
                ],
                "edit" => [
                    "description" => "Dapat mengakses data role",
                    "role" => [Role::ADMIN],
                ],
                "delete" => [
                    "description" => "Dapat mengakses data role",
                    "role" => [Role::ADMIN],
                ],
            ],
        ];
    }
}
