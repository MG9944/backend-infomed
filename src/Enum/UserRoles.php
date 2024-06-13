<?php

namespace App\Enum;

class UserRoles
{
    public const ROLE_ADMIN = 'ROLE_ADMIN';
    public const ROLE_DOCTOR = 'ROLE_DOCTOR';

    public static $availableRoles = [
        self::ROLE_ADMIN => 'Admin',
        self::ROLE_DOCTOR => 'Doctor',
    ];
}
