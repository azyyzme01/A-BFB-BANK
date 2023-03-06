<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizableInterface;

#[Route('/front')]
class FrontController extends AbstractController
{
    public function index(Request $request, UserRepository $userRepository, PaginatorInterface $paginator): Response
    {
        $sort = $request->query->get('sort', 'id'); // default to sorting by id if no sort parameter is provided
        $dir = $request->query->get('dir', 'asc'); // default to ascending order if no dir parameter is provided

        // Make sure that the sort direction is either 'asc' or 'desc'
        $dir = in_array(strtolower($dir), ['asc', 'desc']) ? strtolower($dir) : 'asc';

        // Use the QueryBuilder to get the users and sort them based on the provided parameters
        $qb = $userRepository->createQueryBuilder('u')
            ->orderBy("u.$sort", $dir);
        $users = $qb->getQuery()->getResult();

        // Paginate the results
        $users = $paginator->paginate(
            $users,
            $request->query->getInt('page', 1),
            limit: 6
        );

        // Pass the sort and dir parameters to the Twig template
        return $this->render('front/index.html.twig', [
            'users' => $users,
            'sort' => $sort,
            'dir' => $dir,
        ]);
    }





    #[Route('/new', name: 'app_front_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserRepository $userRepository): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($user, true);

            return $this->redirectToRoute('app_front_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('front/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_front_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('front/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_front_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($user, true);

            return $this->redirectToRoute('app_front_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('front/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_front_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);
        }

        return $this->redirectToRoute('app_front_index', [], Response::HTTP_SEE_OTHER);
    }


}
