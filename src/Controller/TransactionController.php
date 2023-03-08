<?php

namespace App\Controller;

use App\Entity\Transaction;
use App\Entity\Comptebancaire;
use App\Form\TransactionType;
use App\Repository\TransactionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/transaction')]
class TransactionController extends AbstractController
{
    #[Route('/', name: 'app_transaction_index', methods: ['GET'])]
    public function index(TransactionRepository $transactionRepository): Response
    {
        return $this->render('transaction/index.html.twig', [
            'transactions' => $transactionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_transaction_new', methods: ['GET', 'POST'])]
    public function effectuerTransaction(Request $request, TransactionRepository $transactionRepository, EntityManagerInterface $entityManager): Response
    {
        $transaction = new Transaction();
        $form = $this->createForm(TransactionType::class, $transaction);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
    
            // Récupérer l'objet Transaction rempli avec les données du formulaire
            $transaction = $form->getData();
    
            // Récupérer l'objet Comptebancaire correspondant au compte source
            $compteSource = $transaction->getCompteSource();
    
            // Vérifier si le compte source existe dans la base de données
            $compteSourceId = $compteSource->getId();
            $existingCompteSource = $entityManager->find(Comptebancaire::class, $compteSourceId);
            if (!$existingCompteSource) {
                throw $this->createNotFoundException('Le compte source n\'existe pas');
            }
    
            // Récupérer l'objet Comptebancaire correspondant au compte destination
            $compteDestinationId = $transaction->getCompteDestination();
            $existingCompteDestination = $entityManager->find(Comptebancaire::class, $compteDestinationId);
            if (!$existingCompteDestination) {
                throw $this->createNotFoundException('Le compte destination n\'existe pas');

                

            }
    
            // Mettre à jour les soldes des comptes source et destination
            $montant = $transaction->getMontant();
            $compteSource->setSoldeInitial($compteSource->getSoldeInitial() - $montant);
            $existingCompteDestination->setSoldeInitial($existingCompteDestination->getSoldeInitial() + $montant);
    
            // Enregistrer la transaction et les comptes mis à jour dans la base de données
            $transactionRepository->save($transaction, true);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_transaction_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('transaction/new.html.twig', [
            'transaction' => $transaction,
            'form' => $form,
        ]);
    }
    



    #[Route('/{id}', name: 'app_transaction_show', methods: ['GET'])]
    public function show(Transaction $transaction): Response
    {
        return $this->render('transaction/show.html.twig', [
            'transaction' => $transaction,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_transaction_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Transaction $transaction, TransactionRepository $transactionRepository): Response
    {
        $form = $this->createForm(TransactionType::class, $transaction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $transactionRepository->save($transaction, true);

            return $this->redirectToRoute('app_transaction_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('transaction/edit.html.twig', [
            'transaction' => $transaction,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_transaction_delete', methods: ['POST'])]
    public function delete(Request $request, Transaction $transaction, TransactionRepository $transactionRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$transaction->getId(), $request->request->get('_token'))) {
            $transactionRepository->remove($transaction, true);
        }

        return $this->redirectToRoute('app_transactionback_index', [], Response::HTTP_SEE_OTHER);
    }
}
