<?php

namespace App\Http\Enums;

enum RoleEnum : string
{
    case SuperAdmin = 'super admin';
    case Admin = 'admin';
    case Guess = 'guess';

    public static function values(): array
    {
        return [
            self::Guess->value,
            self::Admin->value,
            self::SuperAdmin->value,
        ];
    }
}
