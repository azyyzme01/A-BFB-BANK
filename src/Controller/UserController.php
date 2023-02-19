<?php

namespace App\Controller;

use App\Entity\Rdv;
use App\Form\Rdv1Type;
use App\Repository\RdvRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class UserController extends AbstractController
{
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

}
