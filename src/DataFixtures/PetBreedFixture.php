<?php

namespace App\DataFixtures;

use App\Entity\PetBreed;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PetBreedFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $dogBreeds = array('American Bulldog', 'Foxhound', 'Shepherd', 'Spaniel', 'Spitz', 'Retriever');
        $catBreeds = array('Abyssinian', 'Bengal', 'Bobtail', 'Maine Coon');
        $rabbitBreeds = array('Alaska', 'American', 'Big Silver Marten', 'Satin Angora');
        $monkeyBreeds = array('Baboon', 'Capuchin', 'Chimpanzee', 'Gorilla', 'Marmoset');
        $elephantBreeds = array('African', 'Asian', 'Indian');
        $birdBreeds = array('Budgerigar', 'Cockatiel', 'Cockatoo', 'Dove', 'Parrotlet', 'Green-Cheeked Conure');
        $insectBreeds = array('Bee', 'Beetle', 'Bug', 'Butterfly', 'Cricket', 'Dragonfly', 'Fly');
        $horseBreeds = array('Boerperd', 'Konik', 'Lokai', 'Novokirghiz');
        $rodentBreeds = array('Rat', 'Mouse', 'Hamster', 'Squirrel', 'Chinchilla', 'Beaver', 'Gopher', 'Degu');
        $fishBreeds = array('Betta', 'Goldfish', 'Angelfish', 'Catfish', 'Guppy');

        $animals = array($dogBreeds, $catBreeds, $rabbitBreeds, $monkeyBreeds, $elephantBreeds, $birdBreeds, $insectBreeds, $horseBreeds, $rodentBreeds, $fishBreeds);

        foreach ($animals as $key => $breeds) {
            foreach ($breeds as $breed) {
                $petBreed = new PetBreed();
                $petBreed->setBreed($breed);
                $petBreed->setType($key);
                $manager->persist($petBreed);
            }
        }

        $manager->flush();
    }
}
