<?php

namespace App\Enum;

enum PeriodicalFrequency: int
{
    case Undefined = 0;
    case Daily = 1;
    case Weekly = 2;
    case Monthly = 3;
    case Yearly = 4;
    case Other = 5;
}
