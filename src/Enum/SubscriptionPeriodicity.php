<?php

namespace App\Enum;

enum SubscriptionPeriodicity: int
{
    case Monthly = 1;
    case Yearly = 2;

    public function label(): string
    {
        return match($this) {
            self::Monthly => 'mensuel',
            self::Yearly => 'annuel',
        };
    }
}