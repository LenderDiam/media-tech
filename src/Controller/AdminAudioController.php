<?php

namespace App\Controller;

use App\Entity\Audio;
use App\Form\AudioType;
use App\Repository\AudioRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/document/audio')]
final class AdminAudioController extends AbstractController
{
    #[Route(name: 'app_admin_document_audio_index', methods: ['GET'])]
    public function index(AudioRepository $audioRepository): Response
    {
        return $this->render('admin/document/audio/index.html.twig', [
            'audio' => $audioRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_document_audio_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $audio = new Audio();
        $form = $this->createForm(AudioType::class, $audio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($audio);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_document_audio_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/document/audio/new.html.twig', [
            'audio' => $audio,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_document_audio_show', methods: ['GET'])]
    public function show(Audio $audio): Response
    {
        return $this->render('admin/document/audio/show.html.twig', [
            'audio' => $audio,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_document_audio_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Audio $audio, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AudioType::class, $audio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_document_audio_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/document/audio/edit.html.twig', [
            'audio' => $audio,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_document_audio_delete', methods: ['POST'])]
    public function delete(Request $request, Audio $audio, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$audio->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($audio);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_document_audio_index', [], Response::HTTP_SEE_OTHER);
    }
}
