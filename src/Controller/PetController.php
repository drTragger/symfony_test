<?php

namespace App\Controller;

use App\Service\PetService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Annotation\Route;

class PetController extends AbstractController
{
    public function __construct(protected PetService $service)
    {
    }

    #[Route('/pets', name: 'pets')]
    public function index(): Response
    {
        return $this->render('pet/index.html.twig', [
            'types' => $this->service->getTypes(),
            'breeds' => $this->service->getBreeds(),
            'userId' => $this->getUser()->getId(),
        ]);
    }

    #[Route('/pets/add', name: 'add_pet')]
    public function addPet(Request $request): RedirectResponse|Response
    {
        return $this->service->addPet($request)
            ? $this->redirectToRoute('pets')
            : $this->render('error.html.twig', ['message' => 'You might have chosen wrong breed for the animal type']);
    }

    #[Route('/pets/services', name: 'pets_services')]
    public function services(): Response
    {
        return $this->render('pet/service.html.twig', [
            'services' => $this->service->getServices(),
            'animals' => $this->service->getPets($this->getUser()->getId()),
            'user' => $this->getUser()->getId(),
        ]);
    }

    #[Route('/pets/services/add', name: 'add_service')]
    public function addService(Request $request): RedirectResponse
    {
        $this->service->registerForService($request);
        return $this->redirect('/welcome');
    }
}
