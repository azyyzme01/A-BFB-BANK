<?php

namespace App\Controller;

use App\Entity\Convention;
use App\Form\ConventionType;
use App\Repository\ConventionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/convention')]
class ConventionController extends AbstractController
{
    #[Route('/', name: 'app_convention_indexfront', methods: ['GET'])]
    public function index(ConventionRepository $conventionRepository): Response
    {
        return $this->render('convention/indexfront.html.twig', [
            'conventions' => $conventionRepository->findAll(),
        ]);
    }
    #[Route('/back', name: 'app_convention_index', methods: ['GET'])]
    public function indexback(ConventionRepository $conventionRepository): Response
    {
        return $this->render('convention/index.html.twig', [
            'conventions' => $conventionRepository->findAll(),
        ]);
    }
   
    #[Route('/new', name: 'app_convention_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ConventionRepository $conventionRepository): Response
    {
        $convention = new Convention();
        $form = $this->createForm(ConventionType::class, $convention);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $conventionRepository->save($convention, true);
           

            return $this->redirectToRoute('app_convention_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('convention/new.html.twig', [
            'convention' => $convention,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_convention_show', methods: ['GET'])]
    public function show(Convention $convention): Response
    {
        return $this->render('convention/show.html.twig', [
            'convention' => $convention,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_convention_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Convention $convention, ConventionRepository $conventionRepository): Response
    {
        $form = $this->createForm(ConventionType::class, $convention);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $conventionRepository->save($convention, true);

            return $this->redirectToRoute('app_convention_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('convention/edit.html.twig', [
            'convention' => $convention,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_convention_delete', methods: ['POST'])]
    public function delete(Request $request, Convention $convention, ConventionRepository $conventionRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$convention->getId(), $request->request->get('_token'))) {
            $conventionRepository->remove($convention, true);
        }

        return $this->redirectToRoute('app_convention_index', [], Response::HTTP_SEE_OTHER);
    }
}
