<?php

namespace App\Enums;

enum OrderStatus: string
{
    case DRAFT            = 'draft';
    case PENDING_APPROVAL = 'pending_approval';
    case APPROVED         = 'approved';
    case WAITING_PAYMENT  = 'waiting_payment';
    case PAID             = 'paid';
    case CANCELLED        = 'cancelled';
    case EXPIRED          = 'expired';

    public function label(): string
    {
        return match($this) {
            self::DRAFT            => 'Draft',
            self::PENDING_APPROVAL => 'Menunggu Persetujuan',
            self::APPROVED         => 'Disetujui',
            self::WAITING_PAYMENT  => 'Menunggu Pembayaran',
            self::PAID             => 'Lunas',
            self::CANCELLED        => 'Dibatalkan',
            self::EXPIRED          => 'Kedaluwarsa',
        };
    }

    public function badgeColor(): string
    {
        return match($this) {
            self::DRAFT            => 'gray',
            self::PENDING_APPROVAL => 'yellow',
            self::APPROVED         => 'blue',
            self::WAITING_PAYMENT  => 'orange',
            self::PAID             => 'green',
            self::CANCELLED        => 'red',
            self::EXPIRED          => 'red',
        };
    }
}
