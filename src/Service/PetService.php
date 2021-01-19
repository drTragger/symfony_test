<?php


namespace App\Service;

use App\Entity\Pet;
use App\Repository\PetBreedRepository;
use App\Repository\PetRepository;
use App\Repository\PetTypeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use App\Entity\User;

class PetService
{
    protected PetTypeRepository $petTypeRepository;
    protected PetBreedRepository $petBreedRepository;
    protected $petRepository;

    public function __construct(PetTypeRepository $petTypeRepository, PetBreedRepository $petBreedRepository, PetRepository $petRepository)
    {
        $this->petTypeRepository = $petTypeRepository;
        $this->petBreedRepository = $petBreedRepository;
        $this->petRepository = $petRepository;
    }

    public function getTypes(): array
    {
        return $this->petTypeRepository->findAll();
    }

    public function getBreeds(): array
    {
        return $breeds = $this->petBreedRepository->findAll();
    }

    public function addPet(Request $request)
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
}