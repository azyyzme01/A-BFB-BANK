<?php

namespace App\Controller;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Comptebancaire;
use App\Form\ComptebancaireType;
use App\Repository\ComptebancaireRepository;
use App\Entity\Transaction;
use App\Form\TransactionType;
use App\Repository\TransactionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;


class BackController extends AbstractController
{
    #[Route('/back', name: 'app_back')]
    public function index(): Response
    {
        return $this->render('back/index.html.twig', [
            'controller_name' => 'BackController',
        ]);
    }
////////////////////////compte/////////////////////////
    #[Route('/compteback', name: 'app_comptebancaireback_index', methods: ['GET'])]
    public function indexcompteback(ComptebancaireRepository $comptebancaireRepository): Response
    {
        return $this->render('comptebancaire/indexback.html.twig', [
            'comptebancaires' => $comptebancaireRepository->findAll(),
        ]);

    }

    #[Route('/{id}/back', name: 'app_comptebancaireback_show', methods: ['GET'])]
    public function showcompteback(Comptebancaire $comptebancaire): Response
    {
        return $this->render('comptebancaire/showback.html.twig', [
            'comptebancaire' => $comptebancaire,
        ]);
    }

    #[Route('/{id}/editback', name: 'app_comptebancaireback_edit', methods: ['GET', 'POST'])]
    public function editcompteback(Request $request, Comptebancaire $comptebancaire, ComptebancaireRepository $comptebancaireRepository): Response
    {
        $form = $this->createForm(ComptebancaireType::class, $comptebancaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comptebancaireRepository->save($comptebancaire, true);

            return $this->redirectToRoute('app_comptebancaireback_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('comptebancaire/edit.html.twig', [
            'comptebancaire' => $comptebancaire,
            'form' => $form,
        ]);
    }
///////////////////////transaction ////////////////////
    
    #[Route('/transactionback', name: 'app_transactionback_index', methods: ['GET'])]
    public function indextransactionback(TransactionRepository $transactionRepository): Response
    {
        return $this->render('transaction/indexback.html.twig', [
            'transactions' => $transactionRepository->findAll(),
        ]);
    }

     #[Route('/newtransactionback', name: 'app_transactionback_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TransactionRepository $transactionRepository): Response
    {
        $transaction = new Transaction();
        $form = $this->createForm(TransactionType::class, $transaction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $transactionRepository->save($transaction, true);

            return $this->redirectToRoute('app_transactionback_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('transaction/newback.html.twig', [
            'transaction' => $transaction,
            'form' => $form,
        ]);
    }
    #[Route('/{id}/transactionback', name: 'app_transactionback_show', methods: ['GET'])]
    public function showtransactionback(Transaction $transaction): Response
    {
        return $this->render('transaction/showback.html.twig', [
            'transaction' => $transaction,
        ]);
    }
    #[Route('/new', name: 'app_transactionback_new', methods: ['GET', 'POST'])]
    public function effectuerTransaction(Request $request, TransactionRepository $transactionRepository, EntityManagerInterface $entityManager,MailerInterface $mailer): Response
    {
        $transaction = new Transaction();
        $form = $this->createForm(TransactionType::class, $transaction);
        $form->handleRequest($request);
        
        $email = (new Email())
        ->from('admin@admin.com')
        ->to('client@gmail.com')
        ->subject('bienvenue dans notre espace client!')
        ->html('<p>transaction effectuée avec succes!</p>');

    $mailer->send($email );
    $this->addFlash(
        'success',
        'Votre demande a été envoyé avec succès'
       
    );
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
    
            return $this->redirectToRoute('app_transactionback_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('transaction/newback.html.twig', [
            'transaction' => $transaction,
            'form' => $form,
        ]);
    }
    

    #[Route('/{id}/edittransaction', name: 'app_transactionback_edit', methods: ['GET', 'POST'])]
    public function edittransactionback(Request $request, Transaction $transaction, TransactionRepository $transactionRepository): Response
    {
        $form = $this->createForm(TransactionType::class, $transaction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $transactionRepository->save($transaction, true);

            return $this->redirectToRoute('app_transactionback_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('transaction/edit.html.twig', [
            'transaction' => $transaction,
            'form' => $form,
        ]);
    }


        /**
     * @Route("/sort", name="bank_account_sort")
     */
    public function sort(ComptebancaireRepository $repository, EntityManagerInterface $entityManager)
    {
        // Créer une instance de QueryBuilder
        $qb = $repository->createQueryBuilder('ba');

        // Ajouter l'ordre de tri
        $qb->orderBy('ba.solde_initial', 'DESC');

        // Exécuter la requête
        $comptebancaire = $qb->getQuery()->getResult();
        $entityManager->flush();

        // Afficher la liste triée des comptes bancaires
        return $this->renderForm('comptebancaire/indexback.html.twig', [
            'comptebancaires' => $comptebancaire,
            //'form' => $form,
        ]);
            
        
    }
    

    
}



