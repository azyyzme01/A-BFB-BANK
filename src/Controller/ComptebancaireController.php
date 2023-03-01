<?php

namespace App\Controller;
use Endroid\QrCode\QrCode;
use Endroid\QrCodeBundle\Response\QrCodeResponse;
use Endroid\QrCode\Writer\PngWriter;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

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

#[Route('/comptebancaire')]
class ComptebancaireController extends AbstractController
{
    #[Route('/', name: 'app_comptebancaire_index', methods: ['GET'])]
    public function index(ComptebancaireRepository $comptebancaireRepository): Response
    {
        return $this->render('comptebancaire/index.html.twig', [
            'comptebancaires' => $comptebancaireRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_comptebancaire_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ComptebancaireRepository $comptebancaireRepository): Response
    {
        $comptebancaire = new Comptebancaire();
        $form = $this->createForm(ComptebancaireType::class, $comptebancaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comptebancaireRepository->save($comptebancaire, true);

            return $this->redirectToRoute('app_comptebancaireback_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('comptebancaire/new.html.twig', [
            'comptebancaire' => $comptebancaire,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_comptebancaire_show', methods: ['GET'])]
    public function show(Comptebancaire $comptebancaire): Response
    {
        return $this->render('comptebancaire/show.html.twig', [
            'comptebancaire' => $comptebancaire,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_comptebancaire_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Comptebancaire $comptebancaire, ComptebancaireRepository $comptebancaireRepository): Response
    {
        $form = $this->createForm(ComptebancaireType::class, $comptebancaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comptebancaireRepository->save($comptebancaire, true);

            return $this->redirectToRoute('app_comptebancaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('comptebancaire/edit.html.twig', [
            'comptebancaire' => $comptebancaire,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_comptebancaire_delete', methods: ['POST'])]
    public function delete(Request $request, Comptebancaire $comptebancaire, ComptebancaireRepository $comptebancaireRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$comptebancaire->getId(), $request->request->get('_token'))) {
            $comptebancaireRepository->remove($comptebancaire, true);
        }

        return $this->redirectToRoute('app_comptebancaireback_index', [], Response::HTTP_SEE_OTHER);
    }

    public function getQrCodeForAccount(int $Id): Response
    {
        // Récupérer les informations du compte bancaire à partir de la base de données
        $bankAccount = $this->getDoctrine()->getRepository(Comptebancaire::class)->find($Id);

        if (!$bankAccount) {
            throw $this->createNotFoundException('Le compte bancaire n\'existe pas');
        }

        // Générer le code QR à partir des informations du compte bancaire
        $qrCode = new QrCode($bankAccount->getNom());
        $qrCode->setSize(300);
        $qrCode->setMargin(10);

        $pngWriter = new PngWriter();
        $qrCodeResult = $pngWriter->write($qrCode);

         // Générer la réponse HTTP contenant le code QR
         $response = new QrCodeResponse($qrCodeResult);
         $response->headers->set('Content-Disposition', $response->headers->makeDisposition(
             ResponseHeaderBag::DISPOSITION_ATTACHMENT,
             'qr_code.png'
         ));
 

        return $response;
    }
}
