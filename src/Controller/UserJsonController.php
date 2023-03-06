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
}
