<?php

namespace App\Service;

use App\Entity\Membership;
use App\Entity\User;
use App\Enum\MembershipState;

final class MembershipManager
{
    public function approve(User $who, User $by, Membership $membership): void
    {
        $who
            ->setRejectedBy(null)
            ->setRejectedAt(null)
            ->setApprovedBy($by)
            ->setApprovedAt(new \DateTimeImmutable())
        ;

        $membership->setState(MembershipState::Approved);
    }

    public function reject(User $who, User $by, Membership $membership): void
    {
        $who
            ->setApprovedBy(null)
            ->setApprovedAt(null)
            ->setRejectedBy($by)
            ->setRejectedAt(new \DateTimeImmutable())
        ;

        $membership->setState(MembershipState::Rejected);
    }
}
