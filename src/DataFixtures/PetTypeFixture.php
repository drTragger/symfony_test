<?php

namespace App\DataFixtures;

use App\Entity\PetType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PetTypeFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $petTypes = array('Dog', 'Cat', 'Rabbit', 'Monkey', 'Elephant', 'Bird', 'Insect', 'Horse', 'Rodent', 'Fish');

        foreach ($petTypes as $petType) {
            $type = new PetType();
            $type->setType($petType);
            $manager->persist($type);
        }

        $manager->flush();
    }
}
