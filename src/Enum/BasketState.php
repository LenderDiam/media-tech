<?php

namespace App\Enum;

enum BasketState: int
{
    case Pending = 1;
    case Validated = 2;

    public function label(): string
    {
        return match($this) {
            self::Pending => 'en attente',
            self::Validated => 'validé',
        };
    }
}