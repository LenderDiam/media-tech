<?php

namespace App\Enum;

enum VideoFormat: int
{
    case Undefined = 0;
    case Mp4 = 1;
    case Avi = 2;
    case Mkv = 3;
    case Other = 4;
}
