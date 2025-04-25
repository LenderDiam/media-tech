<?php

namespace App\Controller;

use App\Entity\Basket;
use App\Entity\Copy;
use App\Entity\Document;
use App\Entity\Loan;
use App\Entity\User;
use App\Enum\BasketState;
use App\Enum\CopyState;
use App\Service\BasketManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/basket')]
final class BasketController extends AbstractController
{
    #[Route(name: 'app_basket')]
    public function index(EntityManagerInterface $entityManager, #[CurrentUser()] User $user): Response
    {
        $basket = $entityManager->getRepository(Basket::class)->findOneBy(['user' => $user, 'state' => BasketState::Pending]);

        return $this->render('basket/index.html.twig', [
            'basket' => $basket,
        ]);
    }

    #[Route('/add/{id}', name: 'app_basket_add', methods: ['POST'])]
    public function add(
        Document $document,
        EntityManagerInterface $entityManager,
        #[CurrentUser()] User $user,
        BasketManager $basketManager,
    ): RedirectResponse
    {
        // Chercher un exemplaire disponible
        $copy = $entityManager->getRepository(Copy::class)->findOneBy(['document' => $document, 'state' => CopyState::Available]);

        if (!$copy) {
            $this->addFlash('danger', 'Ce document n\'a plus d\'exemplaire disponible.');
            return $this->redirectToRoute('app_document_show', ['id' => $document->getId()]);
        }

        // Vérifier si l'utilisateur a déjà un panier
        $basket = $entityManager->getRepository(Basket::class)->findOneBy(['user' => $user, 'state' => BasketState::Pending]);
        if (!$basket) {
            $basket = new Basket();
            $basketManager->create($user, $basket);
            $entityManager->persist($basket);
        }

        // Vérifier que le panier ne dépasse pas 5 exemplaires
        if ($basket->getCopies()->count() >= 5) {
            $this->addFlash('danger', 'Vous ne pouvez pas ajouter plus de 5 exemplaires à votre panier.');
            return $this->redirectToRoute('app_document_show', ['id' => $document->getId()]);
        }

        $basket->addCopy($copy);
        $entityManager->flush();

        $this->addFlash('success', 'Exemplaire ajouté au panier.');
        return $this->redirectToRoute('app_document_show', ['id' => $document->getId()]);
    }

    #[Route('/remove/{id}', name: 'app_basket_remove', methods: ['POST'])]
    public function remove(
        Copy $copy,
        #[CurrentUser()] User $user,
        EntityManagerInterface $entityManager,
    ): RedirectResponse
    {
        $basket = $entityManager->getRepository(Basket::class)->findOneBy(['user' => $user, 'state' => BasketState::Pending]);

        if (!$basket || !$basket->getCopies()->contains($copy)) {
            $this->addFlash('warning', 'Cette copie n\'est pas dans votre panier.');
            return $this->redirectToRoute('app_basket');
        }

        $basket->removeCopy($copy);
        $entityManager->persist($basket);
        $entityManager->flush();

        $this->addFlash('success', 'Exemplaire retirée du panier.');
        return $this->redirectToRoute('app_basket');
    }

    #[Route('/validate', name: 'app_basket_validate', methods: ['POST'])]
    public function validate(
        #[CurrentUser()] User $user,
        EntityManagerInterface $entityManager,
    ): RedirectResponse
    {
        $basket = $entityManager->getRepository(Basket::class)->findOneBy(['user' => $user, 'state' => BasketState::Pending]);

        if (!$basket || $basket->getCopies()->isEmpty()) {
            $this->addFlash('danger', 'Votre panier est vide. Impossible de valider.');
            return $this->redirectToRoute('app_basket');
        }

        foreach ($basket->getCopies() as $copy) {
            if ($copy->getState() !== CopyState::Available) {
                $this->addFlash('danger', sprintf(
                    'Le document "%s" n\'est plus disponible.',
                    $copy->getDocument()->getTitle()
                ));
                return $this->redirectToRoute('app_basket');
            }
        }

        $loan = new Loan();
        $loan
            ->setUser($user)
            ->setStartAt(new \DateTimeImmutable())
        ;
        $entityManager->persist($loan);

        // Associer les copies au prêt et changer leur état
        foreach ($basket->getCopies() as $copy) {
            $loan->addCopy($copy);
            $copy->setState(CopyState::Reserved);
        }

        // Marquer le panier comme validé
        $basket->setState(BasketState::Validated);
        $entityManager->flush();

        $this->addFlash('success', 'Votre panier a été validé et les copies ont été empruntées.');
        return $this->redirectToRoute('app_basket');
    }
}
