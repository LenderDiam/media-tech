<?php

namespace App\Repository;

use App\Entity\Subscription;
use App\Entity\User;
use App\Repository\TransactionRepository;
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
    private TransactionRepository $transactionRepository;

    public function __construct(ManagerRegistry $registry, TransactionRepository $transactionRepository)
    {
        parent::__construct($registry, User::class);
        $this->transactionRepository = $transactionRepository;
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

    public function findCurrentSubscriptionForUser(User $user): ?Subscription
    {
        $lastSubscription = $this->findLatestSubscriptionForUser($user);
        if (!$lastSubscription) {
            return null;
        }

        $lastTransaction = $this->transactionRepository->findLatestTransactionForUser($user);
        if (!$lastTransaction || $lastTransaction->getSubscription() !== $lastSubscription) {
            return null;
        }

        $expirationDate = $this->transactionRepository->getSubscriptionExpirationDateFromTransaction($lastTransaction);
        if ($expirationDate && new \DateTimeImmutable() <= $expirationDate) {
            return $lastSubscription;
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
