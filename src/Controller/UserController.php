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
            $offreRepository->save($offre, true);

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
            $offreRepository->save($offre, true);

            return $this->redirectToRoute('app_offreback_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offre/editback.html.twig', [
            'offre' => $offre,
            'form' => $form,
        ]);
    }
}


