<?php

namespace App\Enum;

enum CopyState: int
{
    case Available = 1;
    case Reserved = 2;
    case Borrowed = 3;
    case Lost = 4;
}