<?php

namespace App\Controller;

use App\Entity\Membership;
use App\Entity\User;
use App\Form\MembershipType;
use App\Repository\MembershipRepository;
use App\Service\MembershipManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/membership')]
final class MembershipController extends AbstractController
{
    #[Route(name: 'app_membership_index', methods: ['GET'])]

    public function index(MembershipRepository $membershipRepository, #[CurrentUser()] User $currentUser,): Response
    {
        return $this->render('membership/index.html.twig', [
            'memberships' => $membershipRepository->findBy(
                ['user' => $currentUser],
                ['createdAt' => 'DESC']
            ),
        ]);
    }

    #[Route('/new', name: 'app_membership_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        MembershipManager $membershipManager,
        #[CurrentUser()] User $currentUser,
    ): Response
    {
        $membership = new Membership();
        $form = $this->createForm(MembershipType::class, $membership);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $membershipManager->create($currentUser, $membership);
            $entityManager->persist($membership);
            $entityManager->flush();

            return $this->redirectToRoute('app_membership_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('membership/new.html.twig', [
            'membership' => $membership,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_membership_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Membership $membership,
        EntityManagerInterface $entityManager,
        MembershipManager $membershipManager,
    ): Response
    {
        $form = $this->createForm(MembershipType::class, $membership);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $membershipManager->update($membership);
            $entityManager->flush();

            return $this->redirectToRoute('app_membership_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('membership/edit.html.twig', [
            'membership' => $membership,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_membership_delete', methods: ['POST'])]
    public function delete(Request $request, Membership $membership, EntityManagerInterface $entityManager): Response
    {
        if ($membership->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Vous n\'avez pas la permission de supprimer cette demande.');
        }

        if ($this->isCsrfTokenValid('delete' . $membership->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($membership);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_membership_index', [], Response::HTTP_SEE_OTHER);
    }
}
