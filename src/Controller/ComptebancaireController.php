<?php

namespace App\Controller;
use Endroid\QrCode\QrCode;
use Endroid\QrCodeBundle\Response\QrCodeResponse;
use Endroid\QrCode\Writer\PngWriter;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;


use Dompdf\Dompdf;
use Dompdf\Options;

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
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

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

    #[Route("/Allcompte", name: "list")]
    //* Dans cette fonction, nous utilisons les services NormlizeInterface et StudentRepository, 
    //* avec la méthode d'injection de dépendances.
    public function getCompte(ComptebancaireRepository $repo, SerializerInterface $serializer)
    {
        $comptes = $repo->findAll();
        //* Nous utilisons la fonction normalize qui transforme le tableau d'objets 
        //* students en  tableau associatif simple.
        // $studentsNormalises = $normalizer->normalize($students, 'json', ['groups' => "students"]);

        // //* Nous utilisons la fonction json_encode pour transformer un tableau associatif en format JSON
        // $json = json_encode($studentsNormalises);

        $json = $serializer->serialize($comptes, 'json', ['groups' => "comptes"]);

        //* Nous renvoyons une réponse Http qui prend en paramètre un tableau en format JSON
        return new Response($json);
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

    #[Route("/comptes/{id}", name: "comptes")]
    public function comptesId($id, NormalizerInterface $normalizer, ComptebancaireRepository $repo)
    {
        $comptes = $repo->find($id);
        $comptesNormalises = $normalizer->normalize($comptes, 'json', ['groups' => "comptes"]);
        return new Response(json_encode($comptesNormalises));
    }

    #[Route("/addcomptesJSON", name: "addScomptesJSON")]
    public function addcomotesJSON(Request $req,   NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $comptes = new Comptebancaire();
        $comptes->setNom($req->get('nom'));
        $comptes->setPrenom($req->get('prenom'));
        $comptes->setEmail($req->get('email'));
        $comptes->setNumTlfn($req->get('num_tlfn'));
        $comptes->setSoldeInitial($req->get('solde_initial'));
        $em->persist($comptes);
        $em->flush();

        $jsonContent = $Normalizer->normalize($comptes, 'json', ['groups' => 'comptes']);
        return new Response(json_encode($jsonContent));
    }

    #[Route('/{id}', name: 'app_comptebancaire_show', methods: ['GET'])]
    public function show(Comptebancaire $comptebancaire): Response
    {
        return $this->render('comptebancaire/show.html.twig', [
            'comptebancaire' => $comptebancaire,
        ]);
    }

    #[Route('/{id}/editt', name: 'app_comptebancaire_edit', methods: ['GET', 'POST'])]
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

    #[Route("updatecomptesJSON/{id}", name: "updatecomptesJSON")]
    public function updatecomptesJSON(Request $req, $id, NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $comptes = $em->getRepository(Comptebancaire::class)->find($id);
        $comptes->setNom($req->get('nom'));
        $comptes->setPrenom($req->get('prenom'));
        $comptes->setEmail($req->get('email'));
        $comptes->setNumTlfn($req->get('num_tlfn'));
        $comptes->setSoldeInitial($req->get('solde_initial'));

        $em->flush();

        $jsonContent = $Normalizer->normalize($comptes, 'json', ['groups' => 'comptes']);
        return new Response("compte updated successfully " . json_encode($jsonContent));
    }

    #[Route("deletecomptesJSON/{id}", name: "deletecomptesJSON")]
    public function deletecomptesJSON(Request $req, $id, NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $comptes = $em->getRepository(Comptebancaire::class)->find($id);
        $em->remove($comptes);
        $em->flush();
        $jsonContent = $Normalizer->normalize($comptes, 'json', ['groups' => 'comptes']);
        return new Response("compte deleted successfully " . json_encode($jsonContent));
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
         // Concaténer les informations du compte bancaire pour générer le texte du code QR
    $qrText = sprintf(
        "Nom : %s\nPrenom :  %s\num_tlfn: %s\nSolde initial : %.2f Dinars",
        $bankAccount->getNom(),
        $bankAccount->getPrenom(),
        $bankAccount->getNumTlfn(),
        $bankAccount->getSoldeInitial()
    );


        // Générer le code QR à partir des informations du compte bancaire
        $qrCode = new QrCode($qrText);
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

    public function exporterPdf($id)
    {
        // Récupérez les informations du propriétaire à partir de la base de données
        $proprietaire = $this->getDoctrine()->getRepository(Comptebancaire::class)->find($id);

        // Générez la vue Twig avec les informations du propriétaire
        $html = $this->renderView('comptebancaire/proprietaire_pdf.html.twig', [
            'proprietaire' => $proprietaire,
        ]);

        // Configurez les options de Dompdf
        $options = new Options();
        $options->set('defaultFont', 'Arial');

        // Générez le PDF
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->render();

        // Renvoyez le PDF en tant que réponse HTTP
        $response = new Response($dompdf->output());
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment; filename="proprietaire.pdf"');

        return $response;
    }
}
