<?php

namespace App\Controller;

use App\Entity\Subscription;
use App\Entity\Transaction;
use App\Entity\User;
use App\Repository\SubscriptionRepository;
use App\Repository\TransactionRepository;
use App\Repository\UserRepository;
use App\Service\TransactionManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Bundle\SecurityBundle\Security;


#[Route('/subscription')]
final class SubscriptionController extends AbstractController
{
    #[Route(name: 'app_subscription_index', methods: ['GET'])]
    public function index(
        SubscriptionRepository $subscriptionRepository,
        UserRepository $userRepository,
        TransactionRepository $transactionRepository,
        Request $request,
        #[CurrentUser()] User $currentUser,
    ): Response {
        $expirationDate = null;
        $lastTransaction = $transactionRepository->findLatestTransactionForUser($currentUser);
        if ($lastTransaction) {
            $expirationDate = $transactionRepository->getSubscriptionExpirationDateFromTransaction($lastTransaction);
        }

        $amount = $request->query->get('amount');

        return $this->render('subscription/index.html.twig', [
            'subscriptions' => $subscriptionRepository->findAll(),
            'currentSubscription' => $userRepository->findCurrentSubscriptionForUser($currentUser),
            'hasReduction' => $currentUser->getApprovedAt() ? true : false,
            'expirationDate' => $expirationDate,
            'amount' => $amount,
        ]);
    }

    #[Route('/{id}/payement', name: 'app_subscription_payement', methods: ['POST'])]
    public function payement(
        EntityManagerInterface $entityManager,
        #[CurrentUser()] User $currentUser,
        Subscription $subscription,
        TransactionManager $transactionManager,
        Security $security,
    ): Response {
        $transaction = new Transaction();
    
        $basePrice = $subscription->getPrice();
        $hasReduction = $currentUser->getApprovedAt() !== null;
        $finalAmount = $hasReduction ? round($basePrice * 0.7, 2) : $basePrice;
    
        $transaction->setAmount($finalAmount);
        $transactionManager->create($currentUser, $transaction, $subscription);
    
        $currentUser->addRole(User::ROLE_MEMBER);
    
        $entityManager->persist($transaction);
        $entityManager->persist($currentUser);
        $entityManager->flush();
    
        $security->login($currentUser);
    
        return $this->redirectToRoute('app_subscription_index', ['amount' => $finalAmount], Response::HTTP_SEE_OTHER);
    }
}
