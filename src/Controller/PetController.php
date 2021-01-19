<?php

namespace App\Controller;

use App\Service\PetService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PetController extends AbstractController
{
    protected PetService $service;

    public function __construct(PetService $petService)
    {
        $this->service = $petService;
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
    public function addPet(Request $request)
    {
        return $this->service->addPet($request)
            ? $this->redirectToRoute('pets')
            : $this->render('error.html.twig', ['message' => 'You might have chosen wrong breed for the animal type']);
    }
}
