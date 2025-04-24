<?php

namespace App\Enum;

enum MembershipState: int
{
    case Pending = 1;
    case Approved = 2;
    case Rejected = 3;
}
