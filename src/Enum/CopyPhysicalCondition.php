<?php

namespace App\Enum;

enum CopyPhysicalCondition: int
{
    case New = 1;
    case Good = 2;
    case Worn = 3;
    case Damaged = 4;
}