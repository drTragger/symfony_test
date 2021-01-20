<?php

namespace App\Controller;

use App\Service\PetService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response, RedirectResponse};
use Symfony\Component\Routing\Annotation\Route;

class PetController extends AbstractController
{
    public function __construct(protected PetService $service)
    {
    }

    #[Route('/pets', name: 'pets')]
    public function index(): Response
    {
        return $this->getUser()
            ? $this->render('pet/index.html.twig', [
                'types' => $this->service->getTypes(),
                'breeds' => $this->service->getBreeds(),
                'userId' => $this->getUser()->getId(),
            ])
            : $this->render('error.html.twig', ['message' => 'You need to log in to see this page']);
    }

    #[Route('/pets/add', name: 'add_pet')]
    public function addPet(Request $request): RedirectResponse|Response
    {
        return $this->service->addPet($request)
            ? $this->redirect('/welcome')
            : $this->render('error.html.twig', ['message' => 'You might have chosen wrong breed for the animal type']);
    }

    #[Route('/pets/services/creation', name: 'pets_services')]
    public function services(): Response
    {
        return $this->getUser()
            ? $this->render('pet/service.html.twig', [
                'services' => $this->service->getServices(),
                'animals' => $this->service->getPets($this->getUser()->getId()),
                'user' => $this->getUser()->getId(),
            ])
            : $this->render('error.html.twig', ['message' => 'You need to log in to see this page']);
    }

    #[Route('/pets/services/add', name: 'add_service')]
    public function addService(Request $request): RedirectResponse|Response
    {
        return $this->service->registerForService($request)
            ? $this->redirect('/welcome')
            : $this->render('error.html.twig', ['message' => 'The date you have chosen already expired']);
    }

    #[Route('/pets/services', name: 'show_services')]
    public function showServices(): Response
    {
        $services = $this->service->showServices($this->getUser());

        $total = 0;
        foreach ($services as $service) {
            $total += $service['price'];
        }

        return match ($services) {
            1 => $this->render('pet/services.html.twig', ['message' => 'You have no registered services']),
            2 => $this->render('error.html.twig', ['message' => 'You need to log in to see this page']),
            default => $this->render('pet/services.html.twig', ['services' => $services, 'total' => $total]),
        };
    }
}
