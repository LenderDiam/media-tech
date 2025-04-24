<?php

namespace App\Service;

use App\Entity\Membership;
use App\Entity\User;
use App\Enum\MembershipState;

final class MembershipManager
{
    public function create(User $user, Membership $membership): void
    {
        $membership
            ->setUser($user)
            ->setCreatedAt(new \DateTimeImmutable())
        ;
    }

    public function update(Membership $membership): void
    {
        $membership
            ->setUpdatedAt(new \DateTimeImmutable())
        ;
    }

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
