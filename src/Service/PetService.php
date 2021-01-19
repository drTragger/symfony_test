<?php


namespace App\Service;

use App\Entity\{Pet, UserService};
use App\Repository\{PetBreedRepository, PetRepository, PetTypeRepository, ServiceRepository, UserServiceRepository};
use DateTime;
use Symfony\Component\HttpFoundation\Request;

class PetService
{
    public function __construct(
        protected PetTypeRepository $petTypeRepository,
        protected PetBreedRepository $petBreedRepository,
        protected PetRepository $petRepository,
        protected ServiceRepository $serviceRepository,
        protected UserServiceRepository $userServiceRepository)
    {
    }

    public function getTypes(): array
    {
        return $this->petTypeRepository->findAll();
    }

    public function getBreeds(): array
    {
        return $this->petBreedRepository->findAll();
    }

    public function addPet(Request $request): bool
    {
        $type = $this->petTypeRepository->findOneBy(['type' => $request->request->get('type')]);
        $breed = $this->petBreedRepository->findOneBy(['breed' => $request->request->get('breed')]);

        if ($breed->getType() === $type->getId()) {
            $pet = new Pet();
            $pet->setTypeId($type->getId());
            $pet->setBreedId($breed->getId());
            $pet->setOwnerId($request->request->get('userId'));

            $this->petRepository->savePet($pet);

            return true;
        }
        return false;
    }

    public function getServices(): array
    {
        return $this->serviceRepository->findAll();
    }

    public function getPets($userId): array
    {
        $pets = $this->petRepository->findBy(['owner_id' => $userId]);

        $animals = [];
        foreach ($pets as $key => $pet) {
            $animals[$key]['id'] = $pet->getId();
            $animals[$key]['type'] = $this->petTypeRepository->findOneBy(['id' => $pet->getTypeId()]);
            $animals[$key]['breed'] = $this->petBreedRepository->findOneBy(['id' => $pet->getBreedId()]);
        }

        return $animals;
    }

    public function registerForService(Request $request)
    {
        $date = date('Y-m-d H:i:s', strtotime($request->get('date')));
        $date = new DateTime($date);
        $userService = new UserService();
        $userService->setPetId($request->get('pet'));
        $userService->setUserId($request->get('user'));
        $userService->setServiceId($request->get('service'));
        $userService->setDate($date);
        $this->userServiceRepository->saveUserService($userService);
    }
}