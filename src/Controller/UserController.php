<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Service\UserService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserController extends AbstractController
{
    private $service;

    public function __construct(UserService $userService)
    {
        $this->service = $userService;
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

        $user = $this->service->register($request->request->all());

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirect('/');
    }
}
