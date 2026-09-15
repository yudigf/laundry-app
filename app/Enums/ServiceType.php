<?php

declare(strict_types=1);

namespace App\Enums;

enum ServiceType: string
{
    case Standar = 'standar';
    case Express = 'express';

    public function label(): string
    {
        return match ($this) {
            self::Standar => 'Standar',
            self::Express => 'Express',
        };
    }
}
