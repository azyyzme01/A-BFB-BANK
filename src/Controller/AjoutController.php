<?php
namespace App\Controller;

use App\Entity\Rdv;
use App\Form\Rdv1Type;
use App\Repository\RdvRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AjoutController extends AbstractController
{
    #[Route('/new', name: 'app_rdv', methods: ['GET', 'POST'])]
    public function new(Request $request, RdvRepository $rdvRepository): Response
    {
        $rdv = new Rdv();
        $form = $this->createForm(Rdv1Type::class, $rdv);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rdvRepository->save($rdv, true);

           // return $this->redirectToRoute('app_rdv_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ajout/index.html.twig', [
            'rdv' => $rdv,
            'form' => $form,
        ]);
    }
}
