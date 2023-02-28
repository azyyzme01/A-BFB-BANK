<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\User;
use Symfony\Component\Serializer\SerializerInterface;

class UserJsonController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/alluser', name: 'list')]

    public function all( UserRepository $repo, SerializerInterface $serializeInterface)
    {
        $user=$repo->findAll();
        $json=$serializeInterface->serialize($user,'json',['groups'=>'user']);
        dump($user);
        die();

        // $userNormalises = $normalizer->normalize($user,'json');
        // $json=json_encode($userNormalises);
        // return new Response($json);


    }


    #[Route('addUserJSON/new', name: 'addUserJSON')]
    public function addUserJSON(Request $req,   NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $user = new User();
        $user->setName($req->get('name'));
        $user->setNumTel($req->get('num'));
        $user->setPrenomc($req->get('prenom'));
        $user->setCity($req->get('ville'));
        $user->setEmail($req->get('email'));
        $user->setPassword($req->get('password'));

        $em->persist($user);
        $em->flush();

        $jsonContent = $Normalizer->normalize($user, 'json', ['groups' => 'user']);
        return new Response(json_encode($jsonContent));
    }



    #[Route("updateUserJSON/{id}", name: "updateUserJSON")]
    public function updateUserJSON(Request $req, $id, NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($id);
        $user->setName($req->get('name'));
        $user->setNumTel($req->get('num'));
        $user->setPrenomc($req->get('prenom'));
        $user->setCity($req->get('ville'));
        $user->setEmail($req->get('email'));
        $user->setPassword($req->get('password'));

        $em->flush();

        $jsonContent = $Normalizer->normalize($user, 'json', ['groups' => 'user']);
        return new Response("Student updated successfully " . json_encode($jsonContent));
    }



    #[Route("deleteUserJSON/{id}", name: "deleteUserJSON")]
    public function deleteUserJSON(Request $req, $id, NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($id);
        $em->remove($user);
        $em->flush();
        $jsonContent = $Normalizer->normalize($user, 'json', ['groups' => 'user']);
        return new Response("Student deleted successfully " . json_encode($jsonContent));
    }

}
