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

#[Route('/rdv')]
class RdvController extends AbstractController
{
    #[Route('/', name: 'app_rdv_index', methods: ['GET'])]
    public function index(RdvRepository $rdvRepository): Response
    {
        return $this->render('rdv/index.html.twig', [
            'rdvs' => $rdvRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_rdv_new', methods: ['GET', 'POST'])]
    public function new(Request $request, RdvRepository $rdvRepository, FlashyNotifier $flashy): Response
    {
        $rdv = new Rdv();
        $form = $this->createForm(Rdv1Type::class, $rdv);
        $form->handleRequest($request);//tb3th requuet
    
        if ($form->isSubmitted() && $form->isValid()) {
            $rdvRepository->save($rdv, true);
            $flashy->primaryDark('Thanks for signing up!', 'http://your-awesome-link.com');
    
          //  return $this->redirectToRoute('home');
           // return $this->redirectToRoute('app_rdv_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('rdv/new.html.twig', [
            'rdv' => $rdv,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_rdv_show', methods: ['GET'])]
    public function show(Rdv $rdv): Response
    {
        return $this->render('rdv/show.html.twig', [
            'rdv' => $rdv,
        ]);
        $flashy->primaryDark('Welcome', 'http://your-awesome-link.com');
    }

    #[Route('/{id}/edit', name: 'app_rdv_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Rdv $rdv, RdvRepository $rdvRepository, FlashyNotifier $flashy): Response
    {
        $form = $this->createForm(Rdv1Type::class, $rdv);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rdvRepository->save($rdv, true);
           

            return $this->redirectToRoute('app_rdv_index', [], Response::HTTP_SEE_OTHER);
            $flashy->primaryDark('Succees', 'http://your-awesome-link.com');
        }

        return $this->renderForm('rdv/edit.html.twig', [
            'rdv' => $rdv,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_rdv_delete', methods: ['POST'])]
    public function delete(Request $request, Rdv $rdv, RdvRepository $rdvRepository, FlashyNotifier $flashy): Response
    {
        if ($this->isCsrfTokenValid('delete'.$rdv->getId(), $request->request->get('_token'))) {
            $rdvRepository->remove($rdv, true);
            $flashy->primaryDark('delated avec succes', 'http://your-awesome-link.com');

        }

        return $this->redirectToRoute('app_rdv_index', [], Response::HTTP_SEE_OTHER);
    }
   
   
   
    #[Route('/rdv/tri/{type}', name: 'app_tri', methods:['GET', 'POST'])]
    public function trierRdvParDate(string $type): Response
    {
        if ($type !== 'asc' && $type !== 'desc') {
            throw $this->createNotFoundException('Le type de tri doit être "asc" ou "desc".');
        }

        $repository = $this->getDoctrine()->getRepository(Rdv::class);
        $rdvs = $repository->findBy([], ['date' => $type]);

        return $this->render('rdv/trie.html.twig', [
            'rdvs' => $rdvs,
            'type' => $type,
        ]);
    }
  
}
