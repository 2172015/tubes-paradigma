<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case CUSTOMER = 'customer';

    public function label(): string
    {
        return match($this) {
            self::ADMIN => 'Administrator',
            self::CUSTOMER => 'Pelanggan',
        };
    }

    public function isAdmin(): bool
    {
        return $this === self::ADMIN;
    }
}