<?php

namespace App\Enums;

enum InvoiceStatus: string
{
    case ISSUED    = 'issued';
    case PAID      = 'paid';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match($this) {
            self::ISSUED    => 'Terbit',
            self::PAID      => 'Lunas',
            self::CANCELLED => 'Dibatalkan',
        };
    }

    public function badgeClass(): string
    {
        return match($this) {
            self::ISSUED    => 'bg-yellow-100 text-yellow-800',
            self::PAID      => 'bg-green-100 text-green-800',
            self::CANCELLED => 'bg-red-100 text-red-800',
        };
    }
}
