<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\UserService;

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
        $this->service->register($request->request->all());

        return $this->redirect('/');
    }
}
