<?php

namespace App\Enums;

enum TransactionStatus: string
{
    case PENDING = 'pending';
    case SHIPPING = 'shipping';
    case COMPLETED = 'completed';
    case CANCELED = 'canceled';

    // Method untuk label manusiawi (digunakan di View)
    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Menunggu Pembayaran',
            self::SHIPPING => 'Sedang Dikirim',
            self::COMPLETED => 'Selesai',
            self::CANCELED => 'Dibatalkan',
        };
    }

    // Method untuk warna Badge Bootstrap (digunakan di View)
    public function color(): string
    {
        return match($this) {
            self::PENDING => 'warning',   // Kuning
            self::SHIPPING => 'primary',  // Biru
            self::COMPLETED => 'success', // Hijau
            self::CANCELED => 'danger',   // Merah
        };
    }
}
