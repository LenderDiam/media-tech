<?php

namespace App\Service;

use App\Entity\Subscription;
use App\Entity\Transaction;
use App\Entity\User;

final class TransactionManager
{
    public function create(User $user, Transaction $transaction, Subscription $subscription): void
    {
        $transaction
            ->setSubscription($subscription)
            ->setUser($user)
            ->setCreatedAt(new \DateTimeImmutable())
        ;
    }
}
