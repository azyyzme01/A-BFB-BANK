<?php

namespace App\Controller;

use App\Entity\Service;
use App\Entity\Rdv;
use App\Form\Rdv1Type;
use App\Form\Service1Type;
use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\RdvRepository;
#[Route('/service')]
class ServiceController extends AbstractController
{
    #[Route('/', name: 'app_service_index', methods: ['GET'])]
    public function index(ServiceRepository $serviceRepository): Response
    {
        return $this->render('service/index.html.twig', [
            'services' => $serviceRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_service_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ServiceRepository $serviceRepository): Response
    {
        $service = new Service();
        $form = $this->createForm(Service1Type::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $serviceRepository->save($service, true);
            $flashy->primaryDark('succes', 'http://your-awesome-link.com');
            return $this->redirectToRoute('app_service_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('service/new.html.twig', [
            'service' => $service,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_service_show', methods: ['GET'])]
    public function show(Service $service): Response
    {
        return $this->render('service/show.html.twig', [
            'service' => $service,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_service_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Service $service, ServiceRepository $serviceRepository): Response
    {
        $form = $this->createForm(Service1Type::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $serviceRepository->save($service, true);
            $flashy->primaryDark('succes', 'http://your-awesome-link.com');
            return $this->redirectToRoute('app_service_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('service/edit.html.twig', [
            'service' => $service,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_service_delete', methods: ['POST'])]
    public function delete(Request $request, Service $service, ServiceRepository $serviceRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$service->getId(), $request->request->get('_token'))) {
            $serviceRepository->remove($service, true);
            $flashy->primaryDark('succes', 'http://your-awesome-link.com');
        }

        return $this->redirectToRoute('app_service_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/stat', name: 'app_stat', methods: ['GET', 'POST'])]
    public function stats(ServiceRepository $serviceRepository, RdvRepository $appointmentRepository): Response
    {
        $services = $serviceRepository->findAll();
        $appointments = $appointmentRepository->findAll();

        // Calculer le nombre de rendez-vous pour chaque service
        $appointmentsByService = array();
        foreach ($services as $service) {
            $appointmentsByService[$service->getType()] = 0;
            foreach ($appointments as $appointment) {
                if ($appointment->getServices() === $service) {
                    $appointmentsByService[$service->getType()]++;
                }
            }
        }

        return $this->render('st.html.twig', [
            'services' => $services,
            'appointmentsByService' => $appointmentsByService,
            'appointmentsCount' => count($appointments),
        ]);
    }
}
