<?php

namespace App\Controller;

use App\Entity\BonAchat;
use App\Form\BonAchatType;
use App\Repository\BonAchatRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/bon/achat')]
class BonAchatController extends AbstractController
{
    #[Route('/', name: 'app_bon_achat_indexfront', methods: ['GET'])]
    public function indexfront(BonAchatRepository $bonAchatRepository): Response
    {
        return $this->render('bon_achat/indexfront.html.twig', [
            'bon_achats' => $bonAchatRepository->findAll(),
        ]);
    }
    #[Route('/back', name: 'app_bon_achat_index', methods: ['GET'])]
    public function index(BonAchatRepository $bonAchatRepository): Response
    {
        return $this->render('bon_achat/index.html.twig', [
            'bon_achats' => $bonAchatRepository->findAll(),
        ]);
    }
    #[Route('/new', name: 'app_bon_achat_new', methods: ['GET', 'POST'])]
    public function new(Request $request, BonAchatRepository $bonAchatRepository): Response
    {
        $bonAchat = new BonAchat();
        $form = $this->createForm(BonAchatType::class, $bonAchat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bonAchatRepository->save($bonAchat, true);

            return $this->redirectToRoute('app_bon_achat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('bon_achat/new.html.twig', [
            'bon_achat' => $bonAchat,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_bon_achat_show', methods: ['GET'])]
    public function show(BonAchat $bonAchat): Response
    {
        return $this->render('bon_achat/show.html.twig', [
            'bon_achat' => $bonAchat,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_bon_achat_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, BonAchat $bonAchat, BonAchatRepository $bonAchatRepository): Response
    {
        $form = $this->createForm(BonAchatType::class, $bonAchat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bonAchatRepository->save($bonAchat, true);

            return $this->redirectToRoute('app_bon_achat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('bon_achat/edit.html.twig', [
            'bon_achat' => $bonAchat,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_bon_achat_delete', methods: ['POST'])]
    public function delete(Request $request, BonAchat $bonAchat, BonAchatRepository $bonAchatRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$bonAchat->getId(), $request->request->get('_token'))) {
            $bonAchatRepository->remove($bonAchat, true);
        }

        return $this->redirectToRoute('app_bon_achat_index', [], Response::HTTP_SEE_OTHER);
    }
}
