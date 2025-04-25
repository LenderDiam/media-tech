<?php

namespace App\Repository;

use App\Entity\Subscription;
use App\Entity\Transaction;
use App\Entity\User;
use App\Enum\SubscriptionPeriodicity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Transaction>
 */
class TransactionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Transaction::class);
    }

    public function findLatestTransactionForUser(User $user): ?Transaction
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT t
             FROM App\Entity\Transaction t
             WHERE t.user = :user
             ORDER BY t.createdAt DESC'
            )
            ->setParameter('user', $user)
            ->setMaxResults(1)
            ->getOneOrNullResult();
    }

    public function getSubscriptionExpirationDateFromTransaction(Transaction $transaction): \DateTimeImmutable
    {
        $validityMap = [
            SubscriptionPeriodicity::Monthly->value => '+1 month',
            SubscriptionPeriodicity::Yearly->value => '+1 year',
        ];

        $periodicity = $transaction->getSubscription()->getPeriodicity()->value;

        return $transaction->getCreatedAt()->modify($validityMap[$periodicity]);
    }

    //    /**
    //     * @return Transaction[] Returns an array of Transaction objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Transaction
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
