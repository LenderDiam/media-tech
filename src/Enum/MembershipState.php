<?php

namespace App\Enum;

enum MembershipState: int
{
    case Pending = 1;
    case Approved = 2;
    case Rejected = 3;

    public function label(): string
    {
        return match($this) {
            self::Pending => 'en attente',
            self::Approved => 'approuvé',
            self::Rejected => 'rejeté',
        };
    }
}
