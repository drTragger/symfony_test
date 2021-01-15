<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserController extends AbstractController
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->encoder = $passwordEncoder;
    }

    #[Route('/welcome', name: 'welcome')]
    public function index(): Response
    {
        return $this->render('index.html.twig');
    }

    #[Route('/registration', name: 'registration_page')]
    public function registration(): Response
    {
        return $this->render('user/registration.html.twig');
    }

    #[Route('/users', name: 'register')]
    public function register(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $userData = $request->request->get('form');

        if ($userData['password'] === $userData['confirm_password']) {
            $user = new User;
            $user->setName($userData['name']);
            $user->setEmail($userData['email']);
            $user->setPassword($this->encoder->encodePassword($user, $userData['password']));
            $user->setRoles(['admin']);
            $entityManager->persist($user);
            $entityManager->flush();

            return new Response('User ' . $user->getName() . ' successfully registered');
        }
    }
}
