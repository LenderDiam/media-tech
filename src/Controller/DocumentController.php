<?php

namespace App\Controller;

use App\Entity\Copy;
use App\Entity\Document;
use App\Entity\User;
use App\Enum\CopyState;
use App\Repository\DocumentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/document')]
final class DocumentController extends AbstractController
{
    #[Route(name: 'app_document_index', methods: ['GET'])]
    public function index(DocumentRepository $documentRepository): Response
    {
        return $this->render('document/index.html.twig', [
            'documents' => $documentRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_document_show', methods: ['GET'])]
    public function show(Document $document, EntityManagerInterface $entityManager): Response
    #[Route('/{id}/bookmark', name: 'app_document_bookmark', methods: ['POST'])]
    public function bookmark(
        Document $document,
        EntityManagerInterface $entityManager,
        #[CurrentUser(User::class)] User $user,
    ): RedirectResponse
    {
        if ($document->getUsers()->contains($user)) {
            $this->addFlash('warning', 'Ce document est déjà dans vos favoris.');
        } else {
            $document->addUser($user);
            $entityManager->flush();

            $this->addFlash('success', 'Le document a été ajouté à vos favoris.');
        }

        return $this->redirectToRoute('app_document_show', ['id' => $document->getId()]);
    }

        $copies = $entityManager->getRepository(Copy::class)->findBy(['document' => $document, 'state' => CopyState::Available]);
        $isBookmarked = $document->getUsers()->contains($user);

        return $this->render('document/show.html.twig', [
            'document' => $document,
            'nbCopies' => count($copies),
            'isBookmarked' => $isBookmarked,
        ]);
    }
}
