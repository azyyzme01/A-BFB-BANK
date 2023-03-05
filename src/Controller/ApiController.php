<?php

namespace App\Controller;

use App\Entity\Rdv;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    /**
     * @Route("/api", name="api")
     */
    public function index()
    {
        return $this->render('api/index.html.twig', [
            'controller_name' => 'ApiController',
        ]);
    }

    /**
     * @Route("/{id}/edit", name="api_event_edit", methods={"POST"})
     */
    public function majEvent(?Rdv $calendar, Request $request)
    {
        // On récupère les données
        $donnees = json_decode($request->getContent());

        if(
            isset($donnees->raison) && !empty($donnees->raison) &&
            isset($donnees->start) && !empty($donnees->start) &&
            isset($donnees->heure) && !empty($donnees->heure) &&
            isset($donnees->services) && !empty($donnees->services) 
         
        ){
            // Les données sont complètes
            // On initialise un code
            $code = 200;

            // On vérifie si l'id existe
            if(!$calendar){
                // On instancie un rendez-vous
                $calendar = new Rdv;

                // On change le code
                $code = 201;
            }

            // On hydrate l'objet avec les données
            $calendar->setRaison($donnees->raison);
            $calendar->setHeure($donnees->heure);
            $calendar->setStart(new DateTime($donnees->start));
            
                $calendar->setEnd(new DateTime($donnees->end));
            
           
            $calendar->setServices($donnees->services);
            $calendar->setDate($donnees->date);
           

            $em = $this->getDoctrine()->getManager();
            $em->persist($calendar);
            $em->flush();

            // On retourne le code
            return new Response('Ok', $code);
        }else{
            // Les données sont incomplètes
            return new Response('Données incomplètes', 404);
        }


        return $this->render('api/index.html.twig', [
            'controller_name' => 'ApiController',
        ]);
    }
}