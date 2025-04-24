<?php

namespace App\Controller;

use App\Entity\Membership;
use App\Entity\User;
use App\Form\MembershipType;
use App\Repository\MembershipRepository;
use App\Service\MembershipManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/admin/membership')]
final class AdminMembershipController extends AbstractController
{
    #[Route(name: 'app_admin_membership_index', methods: ['GET'])]
    public function index(MembershipRepository $membershipRepository): Response
    {
        return $this->render('admin/membership/index.html.twig', [
            'memberships' => $membershipRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_admin_membership_show', methods: ['GET'])]
    public function show(Membership $membership): Response
    {
        return $this->render('admin/membership/show.html.twig', [
            'membership' => $membership,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_membership_delete', methods: ['POST'])]
    public function delete(Request $request, Membership $membership, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $membership->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($membership);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_membership_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/approve/{user}', name: 'app_admin_membership_approve', methods: ['GET'])]
    public function approve(
        EntityManagerInterface $entityManager,
        #[CurrentUser()] User $currentUser,
        Membership $membership, 
        #[MapEntity(id: 'user')] User $user,
        MembershipManager $membershipManager,
    ): Response
    {
        $membershipManager->approve(who: $user, by: $currentUser, membership: $membership);
        $entityManager->flush();

        return $this->redirectToRoute('app_admin_membership_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/reject/{user}', name: 'app_admin_membership_reject', methods: ['GET'])]
    public function reject(
        EntityManagerInterface $entityManager,
        #[CurrentUser()] User $currentUser,
        Membership $membership, 
        #[MapEntity(id: 'user')] User $user,
        MembershipManager $membershipManager,
    ): Response
    {
        $membershipManager->reject(who: $user, by: $currentUser, membership: $membership);
        $entityManager->flush();

        return $this->redirectToRoute('app_admin_membership_index', [], Response::HTTP_SEE_OTHER);
    }
}
