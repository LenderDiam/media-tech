<?php

namespace App\Service;

use App\Entity\Basket;
use App\Entity\Loan;
use App\Entity\User;
use App\Enum\BasketState;

final class BasketManager
{
    public function create(User $user, Basket $basket): void
    {
        $basket
            ->setUser($user)
            ->setState(BasketState::Pending)
            ->setCreatedAt(new \DateTimeImmutable())
        ;
    }
}
