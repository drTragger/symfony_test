<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\{RedirectResponse, Request};
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\UserService;

class UserController extends AbstractController
{
    public function __construct(protected UserService $service)
    {
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
        return $this->service->register($request->request->all())
            ? $this->redirect('/welcome')
            : $this->render('error.html.twig', ['message' => 'Passwords are not similar']);
    }

    #[Route('/password', name: 'reset_page')]
    public function getEmailFromUser(): Response
    {
        return $this->render('security/password-reset.html.twig');
    }

    #[Route('/password/send', name: 'send_url')]
    public function sendURL(Request $request): Response
    {
        return $this->service->sendURL($request->request->get('email'))
            ? $this->render('security/success.html.twig', ['email' => $request->request->get('email')])
            : $this->render('error.html.twig', ['message' => 'User not found']);
    }

    #[Route('/password/new_password/{token}', name: 'new_password_page')]
    public function newPassword(string $token): Response
    {
        return $this->render('security/new_password.html.twig', ['token' => $token]);
    }

    #[Route('/password/reset', name: 'reset_password')]
    public function resetPassword(Request $request): RedirectResponse|Response
    {
        return $this->service->resetPassword($request->request)
            ? $this->redirect('/')
            : $this->render('error.html.twig', ['message' => 'Passwords are not similar']);
    }
}
