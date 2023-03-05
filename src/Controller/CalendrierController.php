<?php

namespace App\Controller;

use App\Entity\Rdv;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\RdvRepository;
class CalendrierController extends AbstractController
{
    #[Route('/calendrier', name: 'app_calendrier')]
    public function index(RdvRepository $calendar)
    {
        $events = $calendar->findAll();

        $r = [];
        

        foreach($events as $event){
            $r[] = [
                'id' => $event->getId(),
                'date' => $event->getDate()->format('Y-m-d'),
               
                'title' => $event-> getRaison(),
                'heure' => $event->getHeure(),
                'start' => $event->getStart()->format('Y-m-d H:i:s'),
                'end' => $event->getEnd()->format('Y-m-d H:i:s'),
                'services' => $event->getServices(),
            ];
        }

        $data = json_encode($r);

        return $this->render('calendrier/index.html.twig', compact('data'));
    }


  }
