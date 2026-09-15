<?php

declare(strict_types=1);

namespace App\Enums;

enum OrderStatus: string
{
    case Pending = 'pending';
    case InProgress = 'in_progress';
    case ReadyForPickup = 'ready_for_pickup';
    case Completed = 'completed';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Menunggu Antrean',
            self::InProgress => 'Sedang Dicuci / Diproses',
            self::ReadyForPickup => 'Siap Diambil',
            self::Completed => 'Selesai Diambil',
            self::Cancelled => 'Dibatalkan',
        };
    }

    public function canTransitionTo(self $target): bool
    {
        return match ($this) {
            self::Pending => in_array($target, [self::InProgress, self::Cancelled], true),
            self::InProgress => in_array($target, [self::ReadyForPickup, self::Cancelled], true),
            self::ReadyForPickup => in_array($target, [self::Completed], true),
            self::Completed, self::Cancelled => false,
        };
    }
}
