<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Offre;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\OffreRepository;
use Symfony\Component\HttpFoundation\Request;




class JsonController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/alluser', name: 'list')]

    public function all( OffreRepository $repo, SerializerInterface $serializeInterface)
    {
        $Offre=$repo->findAll();
        $json=$serializeInterface->serialize($Offre,'json',['groups'=>'offre']);
        dump($Offre);
        die();

        // $userNormalises = $normalizer->normalize($user,'json');
        // $json=json_encode($userNormalises);
        // return new Response($json);


    }


    #[Route('addOffreJSON/new', name: 'addOffreJSON')]
    public function addOffreJSON(Request $req,   NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $Offre = new Offre();
        $Offre->setNom($req->get('nom'));
        $Offre->setDescription($req->get('desc'));
        $Offre->setDateOuverture($req->get('dateouv'));
        $Offre->setDateExpiration($req->get('dateexp'));
        $Offre->setTarif($req->get('tarif'));
        $Offre->setTypes($req->get('type'));

        $em->persist($Offre);
        $em->flush();

        $jsonContent = $Normalizer->normalize($Offre, 'json', ['groups' => 'Offre']);
        return new Response(json_encode($jsonContent));
    }


    #[Route("updateOffreJSON/{id}", name: "updateOffreJSON")]
    public function updateOffreJSON(Request $req, $id, NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $Offre = $em->getRepository(Offre::class)->find($id);
        $Offre->setNom($req->get('nom'));
        $Offre->setDescription($req->get('desc'));
        $Offre->setDateOuverture($req->get('dateouv'));
        $Offre->setDateExpiration($req->get('dateexp'));
        $Offre->setTarif($req->get('tarif'));
        $Offre->setTypes($req->get('type'));
        $em->flush();

        $jsonContent = $Normalizer->normalize($user, 'json', ['groups' => 'user']);
        return new Response("Student updated successfully " . json_encode($jsonContent));
    }


    #[Route("deleteOffreJSON/{id}", name: "deleteOffreJSON")]
    public function deleteOffreJSON(Request $req, $id, NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $Offre = $em->getRepository(Offre::class)->find($id);
        $em->remove($Offre);
        $em->flush();
        $jsonContent = $Normalizer->normalize($Offre, 'json', ['groups' => 'Offre']);
        return new Response("Student deleted successfully " . json_encode($jsonContent));
    }


}
