<?php

namespace App\Enum;

enum VideoFormat: int
{
    case Undefined = 0;
    case Mp4 = 1;
    case Avi = 2;
    case Mkv = 3;
    case Other = 4;

    public function label(): string
    {
        return match($this) {
            self::Undefined => 'non défini',
            self::Mp4 => 'mp4',
            self::Avi => 'avi',
            self::Mkv => 'mkv',
            self::Other => 'autre',
        };
    }
}
