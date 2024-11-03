<?php

namespace App\Enum;

enum RoleType: String
{
    case ADMINISTRATOR = "Administrator";
    case GUEST = "Guest";

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function getDefaultPermissions()
    {
        return [
            "dashboard" => [
                "index" => [
                    "description" => "Dapat mengakses dashboard",
                    "role" => [self::ADMINISTRATOR],
                ],
            ],
            "user" => [
                "index" => [
                    "description" => "Dapat mengakses data user",
                    "role" => [self::ADMINISTRATOR],
                ],
                "create" => [
                    "description" => "Dapat menambah data user",
                    "role" => [self::ADMINISTRATOR],
                ],
                "edit" => [
                    "description" => "Dapat mengubah data user",
                    "role" => [self::ADMINISTRATOR],
                ],
                "delete" => [
                    "description" => "Dapat menghapus data user",
                    "role" => [self::ADMINISTRATOR],
                ],
            ],
            "role" => [
                "index" => [
                    "description" => "Dapat mengakses data role",
                    "role" => [self::ADMINISTRATOR],
                ],
                "create" => [
                    "description" => "Dapat membuat data role",
                    "role" => [self::ADMINISTRATOR],
                ],
                "edit" => [
                    "description" => "Dapat mengubah data role",
                    "role" => [self::ADMINISTRATOR],
                ],
                "delete" => [
                    "description" => "Dapat menghapus data role",
                    "role" => [self::ADMINISTRATOR],
                ],
            ],
        ];
    }
}
