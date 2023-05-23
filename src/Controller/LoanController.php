<?php

namespace App\Controller;

use App\Form\LoanFormType;
use App\Service\FeeCalculatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LoanController extends AbstractController
{
    public function __construct(
        protected FeeCalculatorInterface $feeCalculator,
    ) {
    }

    #[Route('/', name: 'calculate_fee', methods: ['GET','POST'])]
    public function calculateFee(Request $request): Response
    {
        $form = $this->createForm(LoanFormType::class);
        $form->handleRequest($request);

        $fee = null;
        $totalPayment = null;
        $loanTerm = null;
        $monthlyInstallment = null;

        if ($form->isSubmitted() && $form->isValid()) {
            $loanAmount = $form->get('loanAmount')->getData();
            $loanTerm = $form->get('loanTerm')->getData();
            
            // Calculate fee
            $fee = $this->feeCalculator->calculateFee($loanAmount);

            // Calculate total payment
            $totalPayment = $loanAmount + $fee;

            // Calculate the monthly installment
            $monthlyInstallment = ($loanAmount + $fee) / $loanTerm;
        }

        return $this->render('loan/index.html.twig', [
            'fee' => $fee,
            'totalPayment' => $totalPayment,
            'loanTerm' => $loanTerm,
            'monthlyInstallment' => $monthlyInstallment,
            'form' => $form->createView(),
        ]);
    }
}