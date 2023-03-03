<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Offre;
use App\Form\OffreType;
use App\Repository\OffreRepository;
#use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
#use Symfony\Component\HttpFoundation\Response;
#use Symfony\Component\Routing\Annotation\Route;
<<<<<<< HEAD
use Twilio\Rest\Client;

=======
<<<<<<< HEAD
use Mediumart\Orange\SMS\SMS;
use Mediumart\Orange\SMS\Http\SMSClient;

=======
>>>>>>> ba85304107831b3acd7bbc56141d72ddff22ce70
>>>>>>> 6e16bf4008d740d51f92a8d8664babcdb5e1be4b

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
<<<<<<< HEAD
=======
<<<<<<< HEAD
>>>>>>> 6e16bf4008d740d51f92a8d8664babcdb5e1be4b
           
            $this->addFlash(
                'info',
              'votre offre  est ajouter avec succees  !!',  
          );
          $offreRepository->save($offre, true);
<<<<<<< HEAD
          $accountSid = 'AC2fd50f1e9b4af8b5f5863144ff9df1a8';
            $authToken = '9fc6fa0cea07192b09928b7abaa62497';
            $client = new Client($accountSid, $authToken);
    
            $message = $client->messages->create(
                '+21624888400', // replace with admin's phone number
                [
                    'from' => '+12706790757', // replace with your Twilio phone number
                    'body' => 'offre ajouté : ' . $form->get('nom')->getData(),
                ]
            );


=======
>>>>>>> 6e16bf4008d740d51f92a8d8664babcdb5e1be4b


            return $this->redirectToRoute('app_offreback_index', [], Response::HTTP_SEE_OTHER);
           
        }
       
<<<<<<< HEAD
=======
=======
            $offreRepository->save($offre, true);

            return $this->redirectToRoute('app_offreback_index', [], Response::HTTP_SEE_OTHER);
        }
>>>>>>> ba85304107831b3acd7bbc56141d72ddff22ce70
>>>>>>> 6e16bf4008d740d51f92a8d8664babcdb5e1be4b

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
<<<<<<< HEAD
            $this->addFlash(
                'info',
              'votre offre  est supprimer avec succees  !!',  
          );
=======
>>>>>>> 6e16bf4008d740d51f92a8d8664babcdb5e1be4b
            $offreRepository->save($offre, true);

            return $this->redirectToRoute('app_offreback_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offre/editback.html.twig', [
            'offre' => $offre,
            'form' => $form,
        ]);
    }
<<<<<<< HEAD

  
=======
<<<<<<< HEAD

    /**
 * @Route("/sms",name="sms")
 */
public function sms()
{

    $client = SMSClient::getInstance('2Yf3CBy0mWhiS0TcVCWonAOkEUXs6cLF', 'Bgflgfsi6lEN1e2V');
    $sms = new SMS($client);
    $sms->message('Salut '.',
puisque vous etes l administrateur  nous vous informons que qu un post s est ajoute ')
->from('+21651464577')
->to('+21651464577')
->send();
return $this->redirectToRoute('event');
}
=======
>>>>>>> ba85304107831b3acd7bbc56141d72ddff22ce70
>>>>>>> 6e16bf4008d740d51f92a8d8664babcdb5e1be4b
}


