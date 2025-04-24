<?php

namespace App\Repository;

use App\Entity\Subscription;
use App\Entity\User;
use App\Enum\SubscriptionPeriodicity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function findLatestSubscriptionForUser(User $user): ?Subscription
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT s
             FROM App\Entity\Subscription s
             JOIN s.transactions t
             WHERE t.user = :user
             ORDER BY t.createdAt DESC'
            )
            ->setParameter('user', $user)
            ->setMaxResults(1)
            ->getOneOrNullResult();
    }

    public function findLatestTransactionForUser(User $user): ?\App\Entity\Transaction
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

    public function findCurrentSubscriptionForUser(User $user): ?Subscription
    {
        $lastTransaction = $this->findLatestTransactionForUser($user);
        if (!$lastTransaction) {
            return null;
        }

        $lastSubscription = $this->findLatestSubscriptionForUser($user);
        if (!$lastSubscription) {
            return null;
        }

        $validityMap = [
            SubscriptionPeriodicity::Monthly->value => '-1 month',
            SubscriptionPeriodicity::Yearly->value => '-1 year',
        ];

        $periodicity = $lastSubscription->getPeriodicity();
        if ($periodicity instanceof \App\Enum\SubscriptionPeriodicity) {
            $periodicity = $periodicity->value;
        }

        if (isset($validityMap[$periodicity])) {
            $validityDate = (new \DateTimeImmutable())->modify($validityMap[$periodicity]);
            if ($lastTransaction->getCreatedAt() > $validityDate) {
                return $lastSubscription;
            }
        }

        return null;
    }

    //    /**
    //     * @return User[] Returns an array of User objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?User
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
