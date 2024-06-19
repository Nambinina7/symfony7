<?php

namespace App\Enum;

enum Status
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
