<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Rdv;
use App\Form\Rdv1Type;
use App\Repository\RdvRepository;


use App\Entity\Type;
use App\Entity\Offre;
use App\Form\OffreType;
use Twilio\Rest\Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\OffreRepository;
use Symfony\Component\HttpFoundation\Request;

class UserController extends AbstractController
{
    #[Route('/afficher', name: 'afficher_front', methods: ['GET'])]
    public function profile(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    ////////////////////////////////////////
    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #[Route('/affichageb', name: 'app_rdvb_index', methods: ['GET'])]
    public function indexb(RdvRepository $rdvRepository): Response
    {
        return $this->render('rdv/affichageb.html.twig', [
            'rdvs' => $rdvRepository->findAll(),
        ]);
    }
    #[Route('/newb', name: 'app_rdv_newb', methods: ['GET', 'POST'])]
    public function new(Request $request, RdvRepository $rdvRepository): Response
    {
        $rdv = new Rdv();
        $form = $this->createForm(Rdv1Type::class, $rdv);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rdvRepository->save($rdv, true);

            return $this->redirectToRoute('app_rdv_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('rdv/new.html.twig', [
            'rdv' => $rdv,
            'form' => $form,
        ]);
    }
    ////////////////////////////////////////////////////////////////////////////////////////////
    #[Route('/affichageback', name: 'app_offreback_index', methods: ['GET'])]
    public function indexback(OffreRepository $offreRepository): Response
    {
        return $this->render('offre/affichageback.html.twig', [
            'offres' => $offreRepository->findAll(),
        ]);
    }


    #[Route('/newbo', name: 'app_offreb_new', methods: ['GET', 'POST'])]
    public function newback(Request $request, OffreRepository $offreRepository): Response
    {
        $offre = new Offre();
        $form = $this->createForm(OffreType::class, $offre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           
            $this->addFlash(
                'info',
              'votre offre  est ajouter avec succees  !!',  
          );
          $offreRepository->save($offre, true);
          /** @var UploadedFile $uploadedFile */
$uploadedFile = $form['image']->getData();
$destination = $this->getParameter('kernel.project_dir').'/public/uploads';
$originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
$newFile = $originalFilename.'-'.uniqid().'.'.$uploadedFile->guessExtension();
$uploadedFile->move(
    $destination,
    $newFile
   );
   $offre->setImage($newFile);
   $entityManager = $this->getDoctrine()->getManager();
   $entityManager->persist($offre);
   $entityManager->flush();
          $accountSid = 'ACb69b8a3f3829d83872a7d7ac8f871aa7';
            $authToken = 'b71a44b30d3588bbeced8743ecd1fb7e';
            $client = new Client($accountSid, $authToken);
    
            $message = $client->messages->create(
                '+21652837711', // replace with admin's phone number
                [
                    'from' => '+15673717791', // replace with your Twilio phone number
                    'body' => 'offre ajouté : ' . $form->get('nom')->getData(),
                ]
            );




            return $this->redirectToRoute('app_offreback_index', [], Response::HTTP_SEE_OTHER);
           
        }
       

        return $this->renderForm('offre/ajoutback.html.twig', [
            'offre' => $offre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/backdali', name: 'app_offreback_edittt', methods: ['GET', 'POST'])]
    public function editback(Request $request, Offre $offre, OffreRepository $offreRepository): Response
    {
        $form = $this->createForm(OffreType::class, $offre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash(
                'info',
                'votre offre  est supprimer avec succees  !!',
            );
            $offreRepository->save($offre, true);

           // return $this->redirectToRoute('app_offreback_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offre/editback.html.twig', [
            'offre' => $offre,
            'form' => $form,
        ]);
    }
    #[Route('back/{id}', name: 'app_offreback_show', methods: ['GET'])]
    public function show(Offre $offre): Response
    {
        return $this->render('offre/showback.html.twig', [
            'offre' => $offre,
        ]);
    }

    /**
     * @Route("/offers", name="offer_list")
     */
    public function offerList(OffreRepository $offre , EntityManagerInterface $entityManager )
    {
        $entityManager = $this->getDoctrine()->getManager();
        $query = $entityManager->createQuery(
            'SELECT o
            FROM App\Entity\Offre o
            ORDER BY o.date_expiration ASC'
        );

        $offre = $query->getResult();

        return $this->render('offre/affichageback.html.twig', [
            'offres' => $offre,
        ]);
    }

    /**
     * @Route("/offres/stats/type", name="offre_stats_par_type")
     */
    public function statsParType(EntityManagerInterface $entityManager): Response
    {
        // Récupération des statistiques des offres par type
        $stats = $entityManager->createQuery(
            'SELECT t.nom AS type, COUNT(o.id) AS nbOffres
             FROM App\Entity\Offre o
             JOIN o.types t
             GROUP BY t.nom'
        )->getResult();

        // Retourne une vue Twig avec les statistiques des offres par type
        return $this->render('offre/stat.html.twig', [
            'stats' => $stats

        ]);
    }

    public function searchOffres(Request $request, OffreRepository $offreRepository): JsonResponse
    {
        $searchTerm = $request->query->get('searchTerm');
        $offres = $offreRepository->searchByTerm($searchTerm);

        $response = [];

        foreach ($offres as $offre) {
            $response[] = [
                'id' => $offre->getId(),
                'nom' => $offre->getNom(),
                'description' => $offre->getDescription(),
                'dateOuverture' => $offre->getDateOuverture()->format('Y-m-d'),
                'dateExpiration' => $offre->getDateExpiration()->format('Y-m-d'),
                'tarif' => $offre->getTarif()
            ];
        }

        return new JsonResponse($response);
    }
}
