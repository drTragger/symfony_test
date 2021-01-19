<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\UserService;

class UserController extends AbstractController
{
    private UserService $service;

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

    #[Route('/password', name: 'reset_page')]
    public function getEmailFromUser()
    {
        return $this->render('security/password-reset.html.twig');
    }

    #[Route('/password/url', name: 'send_url')]
    public function sendURL(Request $request)
    {
        $this->service->sendURL($request->request->get('email'));
        return $this->render('security/success.html.twig', ['email' => $request->request->get('email')]);
    }

    #[Route('/password/new_password/{token}', name: 'new_password_page')]
    public function newPassword(string $token)
    {
        return $this->render('security/new_password.html.twig', ['token' => $token]);
    }

    #[Route('/password/reset', name: 'reset_password')]
    public function resetPassword(Request $request)
    {
        if ($this->service->resetPassword($request->request)) {
            return $this->redirect('/');
        }
        echo 'Error occurred';
    }
}
