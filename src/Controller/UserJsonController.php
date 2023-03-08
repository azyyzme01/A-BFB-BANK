<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Component\Serializer\Serializer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\User;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class UserJsonController extends AbstractController
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    #[Route("UserJSON/signin", name: "app_login")]
    public function verifierUser(Request $request): Response
    {
        $email = $request->get('email');
        $password = $request->get('password');

        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy([
            'email' => $email
        ]);

        if ($user) {
            if (password_verify($password, $user->getPassword())) {
               $serializer =new Serializer([new ObjectNormalizer()]);
               $formatted = $serializer->normalize($user);
               return new JsonResponse($formatted);

            }
         else {
            return new Response ("password not found");
        }
    }
        else{
        return new Response("user not found");}
    }


    #[Route("UserJSON/signup", name: "app_signup")]
    public function signup(Request $request)
    {
        $email =$request->query->get("email");
        $name =$request->query->get("name");
        $password=$request->query->get("password");
        $city=$request->query->get("city");
        $prenomc=$request->query->get("prenomc");
        $num_tel=$request->query->get("num_tel");

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            return new Response("Email invalide");
        }
        $user = new User();
        $user->setName($name);
        $user->setEmail($email);
        $user->setPassword($password);
        $user->setIsVerified(true);
        $user->setCity($city);
        $user->setPrenomc($prenomc);
        $user->setNumTel($num_tel);
        try{
            $em =$this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return new JsonResponse("account is created",status: 200);
        }catch (\Exception $ex){

            return new Response("execption ".$ex->getMessage());

        }

    }


    #[Route("UserJSON/signup", name: "updateUserJSON")]
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



}
