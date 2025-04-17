<?php

namespace App\Enum;

enum AudioFormat: int
{
    case Undefined = 0;
    case Mp3 = 1;
    case Wav = 2;
    case Flac = 3;
    case Other = 4;
}
