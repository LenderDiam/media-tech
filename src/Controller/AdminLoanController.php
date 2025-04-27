<?php

namespace App\Controller;

use App\Entity\Loan;
use App\Repository\LoanRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/loan')]
final class AdminLoanController extends AbstractController
{
    #[Route(name: 'app_admin_loan_index', methods: ['GET'])]
    public function index(LoanRepository $loanRepository): Response
    {
        return $this->render('admin/loan/index.html.twig', [
            'loans' => $loanRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_admin_loan_show', methods: ['GET'])]
    public function show(Loan $loan): Response
    {
        return $this->render('admin/loan/show.html.twig', [
            'loan' => $loan,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_loan_delete', methods: ['POST'])]
    public function delete(Request $request, Loan $loan, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$loan->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($loan);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_loan_index', [], Response::HTTP_SEE_OTHER);
    }
}
