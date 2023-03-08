<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\RdvRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

use Symfony\Component\Serializer\Exception\ExceptionInterface;

use App\Entity\Rdv;


class JsonController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    #[Route('/alluser', name: 'list')]

    public function all( RdvRepository $repo, SerializerInterface $serializeInterface)
    {
        $rdv=$repo->findAll();
        $json=$serializeInterface->serialize($rdv,'json',['groups'=>'rdv']);
        dump($rdv);
        die();

        // $userNormalises = $normalizer->normalize($user,'json');
        // $json=json_encode($userNormalises);
        // return new Response($json);


    }
    #[Route('addUserJSON/new', name: 'addUserJSON')]
    public function addUserJSON(Request $req,   NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $rdv = new Rdv();
        
        $rdv->setDate($req->get('date'));
        $rdv->setHeure($req->get('heure'));
        $rdv->setStart($req->get('start'));
        $rdv->setEnd($req->get('end'));
        $rdv->setRaison($req->get('raison'));
        $rdv->setServices($req->get('services'));
        $em->persist($rdv);
        $em->flush();

        $jsonContent = $Normalizer->normalize($rdv, 'json', ['groups' => 'rdv']);
        return new Response(json_encode($jsonContent));
    }

    #[Route("deleteUserJSON/{id}", name: "deleteUserJSON")]
    public function deleteUserJSON(Request $req, $id, NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $rdv = $em->getRepository(Rdv::class)->find($id);
        $em->remove($rdv);
        $em->flush();
        $jsonContent = $Normalizer->normalize($rdv, 'json', ['groups' => 'rdv']);
        return new Response("Rdv deleted successfully " . json_encode($jsonContent));
    }
    #[Route("updateUserJSON/{id}", name: "updateUserJSON")]
    public function updateUserJSON(Request $req, $id, NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(Rdv::class)->find($id);
        $user-> setEnd($req->get('end'));
        $user->setStart($req->get('start'));
        $user->setServices($req->get('services'));
        $user->setHeure($req->get('heure'));
        $user->setRaison($req->get('raison'));
        

        $em->flush();

        $jsonContent = $Normalizer->normalize($user, 'json', ['groups' => 'user']);
        return new Response("Rdv updated successfully " . json_encode($jsonContent));
    }


    
}




