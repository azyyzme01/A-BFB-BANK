<?php
namespace App\Controller;
use MercurySeries\FlashyBundle\FlashyNotifier;
use App\Entity\Rdv;
use App\Form\Rdv1Type;
use App\Repository\RdvRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Vonage\Client\Credentials\Basic;
use Vonage\Client;
use Vonage\SMS\Message\SMS;

class AjoutController extends AbstractController
{ #[Route('/saisie-vocale', name: 'saisie_vocale', methods: ['GET'])]
    public function saisieVocale(): Response
    {
        return $this->render('rdv/saisie_vocale.html.twig');
    }
    #[Route('/new', name: 'app_rdv', methods: ['GET', 'POST'])]
    public function new(Request $request, RdvRepository $rdvRepository, FlashyNotifier $flashy): Response
    {
        
        $rdv = new Rdv();
        $form = $this->createForm(Rdv1Type::class, $rdv);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){ 
            $rdvRepository->save($rdv, true);
           

             $basic = new Basic('f3bef53f', 'K9Z6loi4zrxu49BV');
             $client = new Client($basic);
             $message = new SMS('+21626411812', 'Bank_BFB', 'Rendez-vous pris en succes ');
             $response = $client->sms()->send($message);
     
            
            
            $d = $form->get('date')->getData();
            $repository = $this->getDoctrine()->getRepository(Rdv::class);
            $rdvs = $repository->findBy([
                'date' => $d
            ]);
    
            // Calcul du nombre de rendez-vous précédents
            $nombrePrecedents = -1;
            foreach ($rdvs as $rd) {
                if ($rd->getDate() == $d) {
                    $nombrePrecedents++;
                }
            }
    
          
          
            $flashy->primaryDark('Succees', 'http://your-awesome-link.com');
            return $this->render('rdv/nbr.html.twig', [
                'dateSaisie' => $d,
                'nombrePrecedents' => $nombrePrecedents
            ]);
           // return $this->redirectToRoute('app_rdv_index', [], Response::HTTP_SEE_OTHER);
        
        }

        return $this->renderForm('ajout/index.html.twig', [
            'rdv' => $rdv,
            'form' => $form,
        ]);
    }

   

  
    
}
