<?php

namespace App\Enum;

enum AudioFormat: int
{
    case Undefined = 0;
    case Mp3 = 1;
    case Wav = 2;
    case Flac = 3;
    case Other = 4;
    
    public function label(): string
    {
        return match($this) {
            self::Undefined => 'non défini',
            self::Mp3 => 'mp3',
            self::Wav => 'wav',
            self::Flac => 'flac',
            self::Other => 'autre',
        };
    }
}
