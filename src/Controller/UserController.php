<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Type;
use App\Entity\Offre;
use App\Form\OffreType;
#use App\Form\FiltreOffreType;
use App\Repository\OffreRepository;
#use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
#use Symfony\Component\HttpFoundation\Response;
#use Symfony\Component\Routing\Annotation\Route;
use Twilio\Rest\Client;
use Doctrine\ORM\EntityManagerInterface;


class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #[Route('/affichageback', name: 'app_offreback_index', methods: ['GET'])]
    public function indexback(OffreRepository $offreRepository): Response
    {
        return $this->render('offre/affichageback.html.twig', [
            'offres' => $offreRepository->findAll(),
        ]);
    }


    #[Route('/newback', name: 'app_offreback_new', methods: ['GET', 'POST'])]
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
          $accountSid = 'ACb69b8a3f3829d83872a7d7ac8f871aa7';
            $authToken = '827acb98a105eb528c5b7dd20921f450';
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
    #[Route('back/{id}', name: 'app_offreback_show', methods: ['GET'])]
    public function show(Offre $offre): Response
    {
        return $this->render('offre/showback.html.twig', [
            'offre' => $offre,
        ]);
    }

    #[Route('/{id}/back', name: 'app_offreback_edit', methods: ['GET', 'POST'])]
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

            return $this->redirectToRoute('app_offreback_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offre/editback.html.twig', [
            'offre' => $offre,
            'form' => $form,
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

    
    

    
}


 



     
    

  



