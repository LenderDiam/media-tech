<?php

namespace App\Enum;

enum CopyState: int
{
    case Available = 1;
    case Reserved = 2;
    case Borrowed = 3;
    case Lost = 4;

    public function label(): string
    {
        return match($this) {
            self::Available => 'disponible',
            self::Reserved => 'réservé',
            self::Borrowed => 'emprunté',
            self::Lost => 'perdu',
        };
    }
}