<?php

namespace App\Controller;

use App\Entity\Periodical;
use App\Form\PeriodicalType;
use App\Repository\PeriodicalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/document/periodical')]
final class AdminPeriodicalController extends AbstractController
{
    #[Route(name: 'app_admin_document_periodical_index', methods: ['GET'])]
    public function index(PeriodicalRepository $periodicalRepository): Response
    {
        return $this->render('admin/document/periodical/index.html.twig', [
            'periodicals' => $periodicalRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_document_periodical_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $periodical = new Periodical();
        $form = $this->createForm(PeriodicalType::class, $periodical);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($periodical);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_document_periodical_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/document/periodical/new.html.twig', [
            'periodical' => $periodical,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_document_periodical_show', methods: ['GET'])]
    public function show(Periodical $periodical): Response
    {
        return $this->render('admin/document/periodical/show.html.twig', [
            'periodical' => $periodical,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_document_periodical_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Periodical $periodical, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PeriodicalType::class, $periodical);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_document_periodical_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/document/periodical/edit.html.twig', [
            'periodical' => $periodical,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_document_periodical_delete', methods: ['POST'])]
    public function delete(Request $request, Periodical $periodical, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$periodical->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($periodical);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_document_periodical_index', [], Response::HTTP_SEE_OTHER);
    }
}
