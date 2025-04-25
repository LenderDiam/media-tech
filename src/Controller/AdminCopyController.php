<?php

namespace App\Controller;

use App\Entity\Copy;
use App\Form\CopyType;
use App\Repository\CopyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/copy')]
final class AdminCopyController extends AbstractController
{
    #[Route(name: 'app_admin_copy_index', methods: ['GET'])]
    public function index(CopyRepository $copyRepository): Response
    {
        return $this->render('admin/copy/index.html.twig', [
            'copies' => $copyRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_copy_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $copy = new Copy();
        $form = $this->createForm(CopyType::class, $copy);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($copy);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_copy_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/copy/new.html.twig', [
            'copy' => $copy,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_copy_show', methods: ['GET'])]
    public function show(Copy $copy): Response
    {
        return $this->render('admin/copy/show.html.twig', [
            'copy' => $copy,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_copy_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Copy $copy, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CopyType::class, $copy);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_copy_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/copy/edit.html.twig', [
            'copy' => $copy,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_copy_delete', methods: ['POST'])]
    public function delete(Request $request, Copy $copy, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$copy->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($copy);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_copy_index', [], Response::HTTP_SEE_OTHER);
    }
}
