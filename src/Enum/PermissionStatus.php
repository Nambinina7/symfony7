<?php

namespace App\Enum;

enum PermissionStatus
{
    public const APPROVED = 'Approuvé';
    public const PENDING = 'En attente';

    public static function getValues(): array
    {
        return [
            self::PENDING,
            self::APPROVED,
        ];
    }
}
